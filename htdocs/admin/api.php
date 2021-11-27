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
                    $fields = array('title', 'description', 'flag', 'case_insensitive_flag', 'category', 'relies_on',
                        'exposed', 'flaggable', 'initial_points', 'minimum_points', 'solves_until_minimum', 'authors');
                    
                    foreach ($fields as $entry) {
                        if (isset($_POST[$entry])) {
                            $challenge[$entry] = trim($_POST[$entry]);
                        }
                    }

                    if ($challenge['relies_on'] == '0') {
                        $challenge['relies_on'] = NULL;
                    }

                    if (ctf_started()) {
                        if ($challenge['exposed'] && $challenge['release_time'] == 0) {
                            $challenge['release_time'] = time();
                        }
                    } else {
                        if ($challenge['exposed']) {
                            $challenge['release_time'] = Config::get('CTF_START_TIME');
                        } else {
                            $challenge['release_time'] = 0;
                        }
                    }

                    db_update('challenges',
                        array(
                            'category'=>$challenge['category'],
                            'title'=>$challenge['title'],
                            'description'=>$challenge['description'],
                            'authors'=>$challenge['authors'],
                            'flag'=>$challenge['flag'],
                            'case_insensitive_flag'=>$challenge['case_insensitive_flag'],
                            'initial_points'=>$challenge['initial_points'],
                            'minimum_points'=>$challenge['minimum_points'],
                            'solves_until_minimum'=>$challenge['solves_until_minimum'],
                            'exposed'=>$challenge['exposed'],
                            'release_time'=>$challenge['release_time'],
                            'flaggable'=>$challenge['flaggable'],
                            'relies_on'=>$challenge['relies_on']
                        ),  array('id'=>$challenge['id'])
                    );

                    update_challenge_points($challenge);
                    
                    // Edit current targets
                    $targets = api_get_targets_for_challenge($challenge['id']);
                    
                    foreach ($targets as $target) {
                        $edited_target_url = $_POST['target_url_' . $target['id']];
                        $delete_target = $_POST['delete_target_' . $target['id']];

                        if (isset($edited_target_url) && strcmp($edited_target_url, $target['url']) !== 0) {
                            db_update('targets', array('url'=>$edited_target_url), array('id'=>$target['id']));
                        }

                        if ($delete_target == 1) {
                            db_delete('targets', array('id'=>$target['id']));
                        }
                    }

                    // Create new target
                    if (!empty($_POST['new_target_url'])) {
                        db_insert('targets', array(
                            'added'=>time(),
                            'challenge'=>$challenge['id'],
                            'url'=>$_POST['new_target_url']
                        ));
                    }

                    // Edit current files
                    $files = api_get_files_for_challenge($challenge['id']);
                    
                    foreach($files as $file) {
                        $edited_file_name = $_POST['file_name_' . $file['id']];
                        $edited_file_url = $_POST['file_url_' . $file['id']];
                        $delete_file = $_POST['delete_file_' . $file['id']];

                        if (isset($edited_file_name) && strcmp($edited_file_name, $file['name']) !== 0) {
                            db_update('files', array('name'=>$edited_file_name), array('id'=>$file['id']));
                        }

                        if (isset($edited_file_url) && strcmp($edited_file_url, $file['url']) !== 0) {
                            db_update('files', array('url'=>$edited_file_url), array('id'=>$file['id']));
                        }

                        if ($delete_file == 1) {
                            db_delete('files', array('id'=>$file['id']));
                        }
                    }

                    // Create new file
                    if (!empty($_POST['new_file_url'])) {
                        db_insert('files', array(
                            'added'=>time(),
                            'challenge'=>$challenge['id'],
                            'name'=>'files',
                            'url'=>$_POST['new_file_url']
                        ));
                    }

                    // Edit current hints
                    $hints = api_get_hints_for_challenge($challenge['id']);
                    
                    foreach($hints as $hint) {
                        $edited_hint_value = $_POST['hint_content_' . $hint['id']];
                        $delete_hint = $_POST['delete_hint_' . $hint['id']];

                        if (isset($edited_hint_value) && strcmp($edited_hint_value, $hint['content']) !== 0) {
                            db_update('hints', array('content'=>$edited_hint_value), array('id'=>$hint['id']));
                        }

                        if ($delete_hint == 1) {
                            db_delete('hints', array('id'=>$hint['id']));
                        }
                    }

                    // Create new hint
                    if (!empty($_POST['new_hint_content'])) {
                        db_insert('hints', array(
                            'added'=>time(),
                            'challenge'=>$challenge['id'],
                            'content'=>$_POST['new_hint_content']
                        ));
                    }
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