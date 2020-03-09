<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

validate_id($_GET['id']);

head('Site management');
menu_management();
section_title ('Edit file');

$file = db_select_one(
    'files',
    array('*'),
    array('id' => $_GET['id'])
);

form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_file');
form_input_text('Title', $file['title']);

$opts = db_query_fetch_all(
    'SELECT
       ch.id,
       ch.title,
       ca.title AS category
     FROM challenges AS ch
     LEFT JOIN categories AS ca ON ca.id = ch.category
     ORDER BY ca.title, ch.title'
);

form_select($opts, 'Challenge', 'id', $file['challenge'], 'title', 'category');
form_hidden('action', 'edit');
form_hidden('challenge', $file['challenge']);
form_hidden('id', $_GET['id']);
form_button_submit('Save changes');
form_end();

section_subhead('Delete file');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_file');
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
form_button_submit('Delete file', 'danger');
form_end();

foot();