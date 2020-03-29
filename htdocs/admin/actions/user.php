<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_id($_POST['id']);
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'edit') {

        validate_email($_POST['email']);

        db_update(
          'users',
          array(
             'email'=>$_POST['email'],
             'team_name'=>$_POST['team_name'],
             'enabled'=>$_POST['enabled'],
             'competing'=>$_POST['competing'],
             'country_id'=>$_POST['country']
          ),
          array(
             'id'=>$_POST['id']
          )
        );

        invalidate_cache(CONST_CACHE_NAME_USER . $_POST['id']);

        redirectBack ("generic_success");
    }

    else if ($_POST['action'] == 'delete') {

        if (!$_POST['delete_confirmation']) {
            message_error('Please confirm delete');
        }

        $correct_submissions = db_select_all(
            'submissions',
            array ('challenge'),
            array(
                'user_id'=>$_POST['id'],
                'correct'=>1
            )
        );

        foreach ($correct_submissions as $submission) {
            echo $submission['challenge'];
            challengeUnsolve ($submission['challenge']);
        }

        db_delete(
            'users',
            array(
                'id'=>$_POST['id']
            )
        );

        db_delete(
            'submissions',
            array(
                'user_id'=>$_POST['id']
            )
        );

        db_delete(
            'ip_log',
            array(
                'user_id'=>$_POST['id']
            )
        );

        db_delete(
            'cookie_tokens',
            array(
                'user_id'=>$_POST['id']
            )
        );

        invalidate_cache(CONST_CACHE_NAME_USER . $_POST['id']);

        redirect('/admin/users.php?generic_success=1');
    }

    else if ($_POST['action'] == 'reset_password') {

        if (!$_POST['reset_confirmation']) {
            message_error('Please confirm password reset');
        }

        $new_password = generate_random_string(8);
        $new_passhash = make_passhash($new_password);

        db_update(
            'users',
            array(
                'passhash'=>$new_passhash
            ),
            array(
                'id'=>$_POST['id']
            )
        );

        message_generic('Success', 'Users new password is: ' . $new_password);
    }
}