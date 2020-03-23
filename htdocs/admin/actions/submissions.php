<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_id($_POST['id']);
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    $submission = db_select_one(
        'submissions',
        array(
            'user_id',
            'challenge',
            'correct'
        ),
        array('id'=>$_POST['id'])
    );

    if ($_POST['action'] === 'delete') {
        if ($submission['correct'] === 1)
            challengeUnsolve ($submission['challenge']);

        db_delete(
            'submissions',
            array(
                'id'=>$_POST['id']
            )
        );

        redirectBack ('generic_success');
    }

    else if ($_POST['action'] === 'mark_incorrect') {
        if ($submission['correct'] === 1)
            challengeUnsolve ($submission['challenge']);

        db_update('submissions', array('correct'=>0, 'marked'=>1), array('id'=>$_POST['id']));
        redirectBack ('generic_success');
    }

    else if ($_POST['action'] === 'mark_correct') {
        $num_correct_submissions = db_count_num(
            'submissions',
            array(
                'user_id'=>$submission['user_id'],
                'challenge'=>$submission['challenge'],
                'correct'=>1
            )
        );

        if ($num_correct_submissions > 0) {
            redirectBack ('generic_failure');
        }

        db_update('submissions', array('correct'=>1, 'marked'=>1), array('id'=>$_POST['id']));
        challengeSolve ($submission['challenge']);

        redirectBack ('generic_success');
    }
}