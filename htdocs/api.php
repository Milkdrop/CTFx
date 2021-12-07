<?php

require('../include/ctfx.inc.php');

// TODO: Forbid people from seeing things when ctf is not started

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['get'])) {
        if ($_GET['get'] == 'xsrf_token') {
            echo get_xsrf_token();
        } else if ($_GET['get'] == 'my_user_id') {
            echo $_SESSION['id'];
        } else if ($_GET['get'] == 'user') {
            validate_id($_GET['id']);
            if (cache_start('api_user', Config::get('CACHE_TIME_USER'), $_GET['id'])) {

                $user = db_select_one(
                    'users',
                    array(
                        'team_name',
                        'email',
                        'country_id'
                    ),
                    array('id' => $_GET['id'])
                );
                
                $country = db_select_one(
                    'countries',
                    array('country_name','country_code'),
                    array('id' => $user['country_id'])
                );

                if (ctf_started()) {
                    $challenges = db_query_fetch_all('
                        SELECT c.id, c.title
                        FROM challenges AS c
                        LEFT JOIN submissions AS s ON s.challenge = c.id AND s.user_id = :user_id
                        WHERE c.exposed = 1 AND s.correct = 1
                        ORDER BY c.category ASC, c.id ASC',
                        array(
                            'user_id' => $_GET['id']
                        )
                    );
                } else {
                    $challenges = array();
                }
                
                $output = [
                    'team_name' => $user['team_name'],
                    'avatar_url' => "https://www.gravatar.com/avatar/" . md5($user["email"]) . "?s=256&d=mp",
                    'country_name' => $country['country_name'],
                    'country_code' => $country['country_code'],
                    'challenges_solved' => $challenges
                ];

                echo json_encode($output);

                cache_end();
            }
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    validate_xsrf_token();

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

        if (empty($email) || empty($password) || empty($team_name) || empty($country)) {
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
    
        if (isset($user['id'])) {
            die_with_message_error('User already exists.');
        }
        
        $user_id = db_insert(
            'users',
            array(
                'added'=>time(),
                'team_name'=>$team_name,
                'email'=>$email,
                'passhash'=>password_hash($password, PASSWORD_DEFAULT),
                'country_id'=>$country,
                'last_active'=>time()
            )
        );
        
        if ($user_id) {
            login($user_id, false);
        } else {
            die_with_message_error('Could not register');
        }
    } else if ($_POST['action'] == 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (!is_string($email) || !is_string($password)) {
            die_with_message_error('Form data is invalid');
        }

        if (empty($email) || empty($password)) {
            die_with_message_error('Form data is empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die_with_message_error('Invalid email');
        }

        $user = db_query_fetch_one(
            'SELECT id, email, passhash, admin, 2fa_status FROM users WHERE email = :email',
            array('email' => $email)
        );
        
        if (is_valid_id($user['id']) && password_verify($password, $user['passhash'])) {
            if ($user['2fa_status'] == 'enabled') {
                $_SESSION['id_before_2fa'] = $user['id'];
                $_SESSION['admin_before_2fa'] = ($user['admin'] == 1);
                redirect('two_factor_auth');
            } else {
                login($user['id'], $user['admin'] == 1);
            }
        } else {
            die_with_message_error("Wrong email or password.");
        }
    } else if ($_POST['action'] == 'login_2fa') {
        if (!validate_two_factor_auth_code($_SESSION['id_before_2fa'], $_POST['code'])) {
            die_with_message_error('Incorrect 2FA Code');
        }

        login($_SESSION['id_before_2fa'], $_SESSION['admin_before_2fa']);
    }
    
    enforce_authentication();
    
    if ($_POST['action'] == 'logout') {
        logout();
    } else if ($_POST['action'] == 'update_profile') {
        $team_name = trim($_POST['team_name']);
        $email = trim($_POST['email']);
        $country = trim($_POST['country']);

        if (!is_string($team_name) || !is_string($email) || !is_string($country)) {
            die_with_message_error('Form data is invalid');
        }

        if (empty($team_name) || empty($email) || empty($country)) {
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

        $user = db_query_fetch_one(
            'SELECT id FROM users WHERE (team_name = :team_name OR email = :email) AND id != :id',
            array('team_name' => $team_name, 'email' => $email, 'id' => $_SESSION['id'])
        );
        
        if (empty($user)) {
            db_update('users',
                array(
                'team_name'=>$team_name,
                'email'=>$email,
                'country_id'=>$country
                ), array('id'=>$_SESSION['id'])
            );
            
            redirect('profile');
        } else {
            die_with_message_error('There\'s already a user with this team name or e-mail.');
        }

    } else if ($_POST['action'] == 'change_password') {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $new_password_repeat = trim($_POST['new_password_repeat']);

        if (!is_string($current_password) || !is_string($new_password) || !is_string($new_password_repeat)) {
            die_with_message_error('Form data is invalid');
        }

        if (empty($current_password) || empty($new_password) || empty($new_password_repeat)) {
            die_with_message_error('Form data is empty');
        }

        $user = db_query_fetch_one(
            'SELECT passhash FROM users WHERE id = :id',
            array('id' => $_SESSION['id'])
        );

        if (password_verify($current_password, $user['passhash'])) {
            if (strcmp($new_password, $new_password_repeat) === 0) {
                db_update('users',
                    array(
                        'passhash'=>password_hash($new_password, PASSWORD_DEFAULT)
                    ), array('id'=>$_SESSION['id'])
                );

                redirect('profile');
            } else {
                die_with_message_error('New password doesn\'t match its repeat.');
            }
        } else {
            die_with_message_error('Wrong password.');
        }

    } else if ($_POST['action'] == 'generate_2fa') {
        db_delete('two_factor_auth',
            array('user_id'=>$_SESSION['id'])
        );

        // TODO - Please...
        $secret_key = '';
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

        for ($i = 0; $i < 32; $i++) {
            $secret_key .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        db_insert('two_factor_auth',
            array(
                'user_id'=>$_SESSION['id'],
                'secret'=>$secret_key
            )
        );

        db_update('users',
            array('2fa_status'=>'generated'),
            array('id'=>$_SESSION['id'])
        );

        redirect('profile');
    
    } else if ($_POST['action'] == 'enable_2fa') {
        if (!validate_two_factor_auth_code($_SESSION['id'], $_POST['code'])) {
            die_with_message_error('Incorrect 2FA Code');
        }

        db_update('users',
            array('2fa_status'=>'enabled'),
            array('id'=>$_SESSION['id'])
        );

        redirect('profile');

    } else if ($_POST['action'] == 'disable_2fa') {

        db_update('users',
            array('2fa_status'=>'disabled'),
            array('id'=>$_SESSION['id'])
        );

        db_delete('two_factor_auth',
            array('user_id'=>$_SESSION['id'])
        );

        redirect('profile');

    } else if ($_POST['action'] == 'submit_flag') {
        if (!ctf_started()) {
            die_with_message_error('CTF has not started yet');
        }

        if (user_is_staff()) {
            die_with_message_error('Temporary: Admins can\'t solve challenges');
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

            // TODO - Non-competing users make the API sad
            $solve_position = 0;
            if ($correct) {
                $solve_position = db_query_fetch_one(
                    'SELECT COUNT(id) AS num FROM submissions WHERE challenge = :challenge AND correct = 1',
                    array('challenge' => $_POST['challenge'])
                )["num"] + 1;
            }
            
            db_insert(
                'submissions',
                array(
                    'added'=>time(),
                    'challenge'=>$_POST['challenge'],
                    'user_id'=>$_SESSION['id'],
                    'flag'=>$_POST['flag'],
                    'correct'=>($correct ? '1' : '0'),
                    'solve_position'=>$solve_position
                )
            );

            if ($correct) {
                update_challenge_points($challenge);
                die_with_message('Challenge solved!', '<a class="btn-solid" href="challenges?category=' . $challenge['category'] . '">Go back</a>', false, 'flag.png');
            } else {
                die_with_message('Incorrect flag.', '<a class="btn-solid btn-solid-danger" href="challenges?category=' . $challenge['category'] . '">Try again</a>', false, 'unflag.png', "#EF3E36");
            }
        }
    }
}