<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	validate_id($_POST['challenge']);
	validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

	if ($_POST['action'] == 'upload') {

		store_file($_POST['challenge'], $_FILES['file']);

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['challenge']);

		redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_challenge.php?id='.$_POST['challenge'].'&generic_success=1');
	}

	else if ($_POST['action'] == 'edit') {
		validate_id($_POST['id']);

		db_update(
           'files',
           array(
              'title'=>$_POST['title'],
              'challenge'=>$_POST['challenge']
           ),
           array(
              'id'=>$_POST['id']
           )
        );

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['id']);

		redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_file.php?id='.htmlspecialchars($_POST['id']).'&generic_success=1');
	}

	else if ($_POST['action'] == 'delete') {
		validate_id($_POST['id']);

		delete_file($_POST['id']);

		invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['id']);

		redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_challenge.php?id='.$_POST['challenge'].'&generic_success=1');
	}
}