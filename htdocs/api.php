<?php

require('../include/mellivora.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
        validate_captcha();
    }

    if ($_POST['action'] == 'submit_flag') {
        enforce_authentication(CONST_USER_CLASS_USER, true);
        
        validate_id($_POST['challenge']);

        $submission_data = db_query_fetch_one(
            'SELECT MAX(added) AS added, MAX(correct) AS correct
            FROM submissions
            WHERE
              challenge = :challenge AND
              user_id = :user_id',
            array(
                'user_id' => $_SESSION['id'],
                'challenge' => $_POST['challenge']
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
                    'flag',
                    'category',
                    'case_insensitive_flag',
                    'solve_decay',
                    'solves'
                ),
                array(
                    'id' => $_POST['challenge'],
                    'exposed' => 1
                )
            );
    
            if (empty($challenge)) {
                die_with_message_error('Challenge does not exist');
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

            redirect('challenges?category=' . $challenge['category'] . '&status=' . ($correct ? 'correct' : 'incorrect'));
        }
    }
}