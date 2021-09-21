<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'edit') {
        validate_id($_POST['id']);

        switch ($_POST['what']) {
            case 'category': {
                db_update('categories',
                    array(
                    'title'=>$_POST['title'],
                    'description'=>$_POST['description']
                    ),  array('id'=>$_POST['id'])
                );
    
                redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
            } break;

            default: die_with_message_error('Unknown thing to edit');
        }
    } else if ($_POST['action'] == 'delete') {
        validate_id($_POST['id']);
        
        if ($_POST['delete_confirmation'] === 'yes') {

            switch($_POST['what']) {
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

                default: die_with_message_error('Unknown thing to delete');
            }

            redirect(Config::get('URL_BASE_PATH') . 'admin/challenges');
        } else {
            switch('what') {
                case 'category': $message = 'Deleting the category will delete all of its challenges, submissions, flags and hints'; break;
                default: $message = ''; break;
            }

            admin_delete_confirmation($message);
        }
    }
}