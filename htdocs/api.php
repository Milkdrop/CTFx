<?php

require('../include/mellivora.inc.php');

// TODO: Forbid people from seeing things when ctf is not started

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'submit_flag') {
        enforce_authentication(CONST_USER_CLASS_USER, true);
        
        if (!ctf_started()) {
            die_with_message_error('CTF has not started yet');
        }

        validate_id($_POST['challenge']);

        $submission_data = db_query_fetch_one(
            'SELECT MAX(added) AS added, MAX(correct) AS correct
            FROM submissions
            WHERE
              challenge = :challenge AND
              user_id = :user_id',
            array(
                'challenge' => $_POST['challenge'],
                'user_id' => $_SESSION['id']
            )
        );
        
        if ($submission_data['correct'] === 1) {
            die_with_message_error('You already solved this challenge');
        } else {
            if (time() - $submission_data['added'] < Config::get('SUBMISSION_COOLDOWN')) {
                $time_left = Config::get('SUBMISSION_COOLDOWN') - (time() - $submission_data['added']);
                die_with_message_error('You need to wait ' . $time_left . ' more seconds to submit another flag');
            }
            
            $challenge = db_select_one(
                'challenges',
                array(
                    'id',
                    'category',
                    'flag',
                    'case_insensitive_flag',
                    'flaggable',
                    'initial_points',
                    'minimum_points',
                    'solves_until_minimum'
                ),
                array(
                    'id' => $_POST['challenge'],
                    'exposed' => 1
                )
            );
    
            if (empty($challenge)) {
                die_with_message_error('Challenge does not exist');
            }
            
            if (!$challenge['flaggable']) {
                die_with_message_error('Challenge is not flaggable');
            }

            if (!is_string($_POST['flag'])) {
                redirect('challenges?category=' . $challenge['category']);
            }

            $correct = false;

            $_POST['flag'] = trim($_POST['flag']);
            $challenge['flag'] = trim($challenge['flag']);
    
            if ($challenge['case_insensitive_flag']) {
                if (strcasecmp($_POST['flag'], $challenge['flag']) === 0) {
                    $correct = true;
                }
            } else {
                if (strcmp($_POST['flag'], $challenge['flag']) === 0) {
                    $correct = true;
                }
            }

            db_insert(
                'submissions',
                array(
                    'added'=>time(),
                    'challenge'=>$_POST['challenge'],
                    'user_id'=>$_SESSION['id'],
                    'flag'=>$_POST['flag'],
                    'correct'=>($correct ? '1' : '0')
                )
            );

            if ($correct) {
                update_challenge_points($challenge);
                die_with_message('Challenge solved!', '<a class="btn-solid" href="challenges?category=' . $challenge['category'] . '">Go back</a>', false, 'flag.png');
            } else {
                die_with_message('Incorrect flag.', '<a class="btn-solid btn-solid-danger" href="challenges?category=' . $challenge['category'] . '">Try again</a>', false, 'unflag.png', "#E06552");
            }
        }
    }
}