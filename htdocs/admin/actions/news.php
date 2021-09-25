<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] === 'new') {
       $id = db_insert(
          'news',
          array(
             'added'=>time(),
             'added_by'=>$_SESSION['id'],
             'title'=>$_POST['title'],
             'body'=>$_POST['body']
          )
       );

       if ($id) {
          invalidate_cache(CONST_CACHE_NAME_HOME);
          redirect('/admin/news.php?id='.$id);
       } else {
          message_error('Could not insert new news item.');
       }
    } else {

      validate_id($_POST['id']);
      if ($_POST['action'] === 'edit') {

         db_update(
            'news',
            array(
               'title'=>$_POST['title'],
               'body'=>$_POST['body']
            ),
            array(
               'id'=>$_POST['id']
            )
         );

          invalidate_cache(CONST_CACHE_NAME_HOME);

          redirect('/admin/news.php?id='.$_POST['id'].'&generic_success=1');
      }

      else if ($_POST['action'] === 'delete') {

          if (!$_POST['delete_confirmation']) {
              message_error('Please confirm delete');
          }

          db_delete(
              'news',
              array(
                  'id'=>$_POST['id']
              )
          );

          invalidate_cache(CONST_CACHE_NAME_HOME);
          
          redirect('/admin/?generic_success=1');
      }
    }
}