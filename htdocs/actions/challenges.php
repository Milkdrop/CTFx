<?php

require('../../include/mellivora.inc.php');

enforce_authentication(
    CONST_USER_CLASS_USER,
    true
);

$time = time();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
        validate_captcha();
    }

    if ($_POST['action'] == 'submit_flag') {

        validate_id($_POST['challenge']);

        $submissions = db_query_fetch_all(
            'SELECT
              s.added,
              s.marked,
              s.correct,
              c.automark
            FROM
              submissions AS s JOIN challenges AS c ON c.id = s.challenge
            WHERE
              s.challenge = :challenge AND
              s.user_id = :user_id',
            array(
                'user_id' => $_SESSION['id'],
                'challenge' => $_POST['challenge']
            )
        );

        $latest_submission_attempt = 0;
        $num_attempts = 0;
        foreach ($submissions as $submission) {
            $latest_submission_attempt = max($submission['added'], $latest_submission_attempt);

            // make sure user isn't "accidentally" submitting a correct flag twice
            if ($submission['correct']) {
                message_error('You may only submit a correct flag once.');
            }

            // don't allow multiple unmarked submissions to manually marked challenges
            if (!$submission['automark'] && !$submission['marked']) {
                message_error('You already have an unmarked submission for this challenge.');
            }

            $num_attempts++;
        }

        // ACHIEVEMENT-CODE
        // A bit imprecise in the implementation but it gets the job done
        if ($num_attempts >= 10) {
            add_achievement(11);
        }
        // ACHIEVEMENT-CODE

        // get challenge information
        $challenge = db_select_one(
            'challenges',
            array(
                'flag',
                'category',
                'case_insensitive',
                'automark',
                'available_from',
                'available_until',
                'num_attempts_allowed',
                'min_seconds_between_submissions',
                'solve_decay',
                'solves'
            ),
            array(
                'id' => $_POST['challenge'],
                'exposed' => 1
            )
        );

        if (empty($challenge)) {
            message_generic('Sorry','No such challenge.');
        }

        $seconds_since_submission = $time-$latest_submission_attempt;
        if ($seconds_since_submission < $challenge['min_seconds_between_submissions']) {
            message_generic('Sorry','You may not submit another solution for this challenge for another ' . seconds_to_pretty_time($challenge['min_seconds_between_submissions']-$seconds_since_submission));
        }

        if ($challenge['num_attempts_allowed'] && $num_attempts >= $challenge['num_attempts_allowed']) {
            message_generic('Sorry','You\'ve already tried '.$challenge['num_attempts_allowed'].' times. Sorry!');
        }

        if (!user_is_staff ()) {
            if ($challenge['available_from'] && $time < $challenge['available_from']) {
                message_generic('Sorry','This challenge hasn\'t started yet.');
            }

            if ($challenge['available_until'] && $time > $challenge['available_until']) {
                message_generic('Sorry','This challenge has expired.');
            }
        }
    
        if (empty($_POST['flag'])) {
            redirect('challenges?category='.$challenge['category'].'&status=empty');
        }

        $correct = false;

        // automark the submission
        if ($challenge['automark']) {

            // lots of people submit with trailing whitespace..
            // we probably never want automarked keys with whitespace
            // at beginning or end, so trimming is probably fine.

            $_POST['flag'] = trim($_POST['flag']);
            $challenge['flag'] = trim($challenge['flag']);

            if ($challenge['case_insensitive']) {
                if (strcasecmp($_POST['flag'], $challenge['flag']) === 0) {
                    $correct = true;
                }
            } else {
                if (strcmp($_POST['flag'], $challenge['flag']) === 0) {
                    $correct = true;
                }
            }
        }
            
        db_insert(
            'submissions',
            array(
                'added'=>$time,
                'challenge'=>$_POST['challenge'],
                'user_id'=>$_SESSION['id'],
                'flag'=>$_POST['flag'],
                'correct'=>($correct ? '1' : '0'),
                'marked'=>($challenge['automark'] ? '1' : '0')
            )
        );

        if ($correct) {
            challengeSolve($_POST['challenge']);

            // ACHIEVEMENT-CODE
            $totalChalls = db_count_num('challenges', array('category' => $challenge['category']));
            $solvedChalls = db_query_fetch_one('
                SELECT
                   COUNT(*) AS count
                FROM challenges AS ch JOIN submissions AS s ON s.challenge = ch.id AND s.user_id=:user_id AND s.correct = 1
                WHERE
                  ch.category = :category',
                array(
                    'user_id'=>$_SESSION['id'],
                    'category'=>$challenge['category']
                )
            )["count"];

            if ($solvedChalls == $totalChalls) {
                // Custom order for X-MAS stuff, you can change this however you want
                if ($challenge['category'] <= 9) {
                    add_achievement($challenge['category'] - 1);
                }
            }

            $solvedInThePast5Mins = db_query_fetch_one('
                SELECT
                   COUNT(*) AS count
                FROM submissions
                WHERE
                  user_id=:user_id AND
                  correct=1 AND
                  added>=:min_time',
                array(
                    'user_id' => $_SESSION['id'],
                    'min_time' => time() - 5 * 60
                )
            )["count"];

            if ($solvedInThePast5Mins >= 5) {
                add_achievement(10);
            }

            if ($challenge['solves'] >= $challenge['solve_decay'] && $challenge['solve_decay'] > 0 && $challenge['category'] != 10) {
                add_achievement(12);
            }

            // ACHIEVEMENT-CODE
        }

        if (!$challenge['automark']) {
            redirect('challenges?category='.$challenge['category'].'&status=manual');
        }

        redirect('challenges?category='.$challenge['category'].'&status=' . ($correct ? 'correct' : 'incorrect'));
    }
}
