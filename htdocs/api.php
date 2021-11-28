<?php

require('../include/ctfx.inc.php');

// TODO: Forbid people from seeing things when ctf is not started

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if ($_POST['action'] == 'register') {
        if (Config::get('ENABLE_CAPTCHA')) {
            validate_captcha();
        }

        $team_name = trim($_POST['team_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $country = trim($_POST['country']);

        if (!is_string($team_name) || !is_string($email)
        || !is_string($password) || !is_string($country)) {
            die_with_message_error('Form data is invalid');
        }

        if (empty($email) || empty($password) || empty($team_name)) {
            die_with_message_error('Form data is empty');
        }

        if (strlen($team_name) > Config::get('MAX_TEAM_NAME_LENGTH')
        || strlen($team_name) < Config::get('MIN_TEAM_NAME_LENGTH')) {
            die_with_message_error('Team name is too long or too short');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die_with_message_error('Invalid email');
        }

        $num_countries = db_select_one(
            'countries',
            array('COUNT(*) AS num')
        )['num'];

        if (!is_valid_id($country) || $country > $num_countries) {
            die_with_message_error('Invalid country ID');
        }
        
        $user = db_select_one('users', array('id'),
            array(
                'team_name' => $team_name,
                'email' => $email
            ),
            null,
            'OR'
        );
    
        if ($user['id']) {
            die_with_message_error('User already exists.');
        }
        
        $user_id = db_insert(
            'users',
            array(
                'added'=>time(),
                'team_name'=>$team_name,
                'email'=>$email,
                'passhash'=>password_hash($password),
                'country_id'=>$country,
                'last_active'=>time()
            )
        );
        
        if ($user_id) {
            login($user_id, false);
            redirect(Config::get('REDIRECT_INDEX_TO'));
        } else {
            die_with_message_error('Could not register');
        }
    }

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'submit_flag') {
        enforce_authentication();
        
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