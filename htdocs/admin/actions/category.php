<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] === 'new') {
        require_fields(array('title'), $_POST);

        $id = db_insert(
          'categories',
          array(
             'added'=>time(),
             'title'=>$_POST['title'],
             'description'=>$_POST['description'],
             'exposed'=>$_POST['exposed']
          )
       );

        if ($id) {
            redirect('/admin/category.php?id='.$id);
        } else {
            message_error('Could not insert new category.');
        }

    } else {
        validate_id($_POST['id']);

        if ($_POST['action'] === 'edit') {

           db_update(
              'categories',
              array(
                 'title'=>$_POST['title'],
                 'description'=>$_POST['description'],
                 'exposed'=>$_POST['exposed']
              ),
              array(
                 'id'=>$_POST['id']
              )
           );

            redirect('/admin/category.php?id='.$_POST['id'].'&generic_success=1');
        } else if ($_POST['action'] === 'delete') {

            if (!$_POST['delete_confirmation']) {
                message_error('Please confirm delete');
            }

            db_delete(
                'categories',
                array(
                    'id'=>$_POST['id']
                )
            );

            $challenges = db_select_all(
                'challenges',
                array('id'),
                array('category' => $_POST['id'])
            );

            foreach ($challenges as $challenge) {
                delete_challenge_cascading($challenge['id']);
            }

            redirect('/admin/index.php?generic_success=1');
        }
    }
}