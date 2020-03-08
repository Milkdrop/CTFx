<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_id($_POST['challenge_id']);
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'upload_file') {

        store_file($_POST['challenge_id'], $_FILES['file']);

        invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['challenge_id']);

        redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_challenge.php?id='.$_POST['challenge_id'].'&generic_success=1');
    }

    else if ($_POST['action'] == 'delete_file') {
    	validate_id($_POST['id']);

        delete_file($_POST['id']);

        invalidate_cache(CONST_CACHE_NAME_FILES . $_POST['id']);

        redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_challenge.php?id='.$_POST['challenge_id'].'&generic_success=1');
    }
}