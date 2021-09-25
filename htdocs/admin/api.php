<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'create') {
        switch ($_POST['what']) {
            case 'category': {
                if (empty($_POST['title'])) die_with_message_error('Category cannot have an empty title');

                db_insert('categories',
                    array(
                        'added'=>time(),
                        'title'=>trim($_POST['title']),
                        'description'=>trim($_POST['description'])
                    )
                );

                redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
            } break;

            case 'challenge': {
                if (empty($_POST['title'])) die_with_message_error('Challenge cannot have an empty title');

                db_insert('challenges',
                    array(
                        'added'=>time(),
                        'category'=>trim($_POST['category']),
                        'title'=>trim($_POST['title']),
                        'description'=>trim($_POST['description']),
                        'flag'=>trim($_POST['flag']),
                        'points'=>Config::get('CHALLENGE_INITIAL_POINTS'),
                        'initial_points'=>Config::get('CHALLENGE_INITIAL_POINTS'),
                        'minimum_points'=>Config::get('CHALLENGE_MINIMUM_POINTS'),
                        'solves_until_minimum'=>Config::get('CHALLENGE_SOLVES_UNTIL_MINIMUM')
                    )
                );

                redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
            } break;

            default: die_with_message_error('Unknown thing to create');
        }

    } else if ($_POST['action'] == 'update') {
        validate_id($_POST['id']);

        switch ($_POST['what']) {
            // Must supply both params for every request
            case 'category': {
                if (empty($_POST['title'])) die_with_message_error('Category cannot have an empty title');

                db_update('categories',
                    array(
                    'title'=>trim($_POST['title']),
                    'description'=>trim($_POST['description'])
                    ),  array('id'=>$_POST['id'])
                );
    
                redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
            } break;

            // Can selectively supply only the needed params to update
            case 'challenge': {
                if (isset($_POST['title']) && empty($_POST['title'])) die_with_message_error('Challenge cannot have an empty title');

                $challenge = db_query_fetch_one('SELECT * FROM challenges WHERE id=:id', array('id' => $_POST['id']));

                if (!empty($challenge)) {
                    // Normally we would check if the category is valid, but you can't get it wrong unless you intend to
                    foreach (array('title', 'description', 'flag', 'category', 'relies_on', 'exposed', 'flaggable') as $entry) {
                        if (isset($_POST[$entry])) {
                            $challenge[$entry] = trim($_POST[$entry]);
                        }
                    }

                    if ($challenge['relies_on'] == '0') $challenge['relies_on'] = NULL;

                    db_update('challenges',
                        array(
                            'title'=>$challenge['title'],
                            'description'=>$challenge['description'],
                            'flag'=>$challenge['flag'],
                            'category'=>$challenge['category'],
                            'relies_on'=>$challenge['relies_on'],
                            'exposed'=>$challenge['exposed'],
                            'flaggable'=>$challenge['flaggable']
                        ),  array('id'=>$_POST['id'])
                    );
                } else {
                    die_with_message_error('No such challenge');
                }

                redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
            } break;

            default: die_with_message_error('Unknown thing to update');
        }
    } else if ($_POST['action'] == 'delete') {
        validate_id($_POST['id']);
        
        if ($_POST['delete_confirmation'] === 'yes') {
            switch ($_POST['what']) {
                case 'category': {
                    $challenges = db_select_all('challenges', array('id'), array('category' => $_POST['id']));

                    foreach ($challenges as $challenge) {
                        db_delete('submissions', array('challenge'=>$challenge['id']));
                        db_delete('files', array('challenge'=>$challenge['id']));
                        db_delete('hints', array('challenge'=>$challenge['id']));
                        db_delete('challenges', array('id'=>$challenge['id']));
                    }

                    db_delete('categories', array('id'=>$_POST['id']));
                } break;

                case 'challenge': {
                    db_delete('submissions', array('challenge'=>$_POST['id']));
                    db_delete('files', array('challenge'=>$_POST['id']));
                    db_delete('challenges', array('id'=>$_POST['id']));
                } break;

                default: die_with_message_error('Unknown thing to delete');
            }

            redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
        } else {
            switch($_POST['what']) {
                case 'category': $message = 'Deleting the category will delete ALL of its challenges, submissions, files and hints'; break;
                default: $message = ''; break;
            }

            admin_delete_confirmation($message);
        }
    }
}