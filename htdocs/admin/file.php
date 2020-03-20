<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if (isset ($_GET['id'])) {
    validate_id($_GET['id']);

    $file = db_select_one(
      'files',
      array('*'),
      array('id' => $_GET['id'])
  );

    if ($file === false)
        unset ($file);
}

head('Site management');
menu_management();
section_title (isset ($file)?'Edit file':'New file');

$opts = db_query_fetch_all(
    'SELECT
       ch.id,
       ch.title,
       ca.title AS category
     FROM challenges AS ch
     LEFT JOIN categories AS ca ON ca.id = ch.category
     ORDER BY ca.title, ch.title'
);

form_start('/admin/actions/file','','multipart/form-data');
form_input_text('Filename', $file['title']);
form_select($opts, 'Challenge', 'id', isset ($file)?$file['challenge']:array_get($_GET, 'challenge', 0), 'title', 'category');
form_input_text('URL', $file['url']);
form_file('file');
form_hidden('action', isset ($file)?'edit':'new');
form_hidden('id', $_GET['id']);
if (!isset ($file))
	message_inline('You can provide an external download URL, or upload a file (max ' . bytes_to_pretty_size(max_file_upload_size()) . ') directly on the platform');

form_button_submit('Save changes');
form_end();

if (!isset ($file))
  die (foot ());

section_subhead('Delete file');
form_start('/admin/actions/file');
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
form_hidden('challenge', $file['challenge']);
form_button_submit('Delete file', '3');
form_end();

foot();