<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

	if ($_POST['action'] === 'new') {

		validate_id($_POST['challenge']);

		if (isset ($_POST['url']) && !empty ($_POST['url'])) {

        	require_fields(array('filename'), $_POST);

	        $file_id = db_insert(
	            'files',
	            array(
	                'added'=>time(),
	                'added_by'=>$_SESSION['id'],
	                'title'=>$_POST['filename'],
	                'url'=>$_POST['url'],
	                'challenge'=>$_POST['challenge'],
            		'download_key'=>hash('sha256', generate_random_string(128))
	            )
	        );
	    } else 
			store_file($_FILES['file'], $_POST['challenge'], $_POST['filename']);

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['challenge']);

		redirect('/admin/challenge.php?id='.$_POST['challenge'].'&generic_success=1');
	}

	else if ($_POST['action'] === 'edit') {
		validate_id($_POST['id']);

        require_fields(array('filename'), $_POST);

		db_update(
           'files',
           array(
              'title'=>$_POST['filename'],
              'url'=>$_POST['url'],
              'challenge'=>$_POST['challenge']
           ),
           array(
              'id'=>$_POST['id']
           )
        );

		if (!$_FILES['file']['error']) {
			change_file ($_POST['id'], $_FILES['file']);
		}

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['challenge']);

		redirect('/admin/file.php?id='.htmlspecialchars($_POST['id']).'&generic_success=1');
	}

	else if ($_POST['action'] === 'delete') {
		validate_id($_POST['id']);

		if (!$_POST['delete_confirmation']) {
            message_error('Please confirm delete');
        }

		delete_file($_POST['id']);

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['challenge']);

		redirect('/admin/challenge.php?id='.$_POST['challenge'].'&generic_success=1');
	}
}