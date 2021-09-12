<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if (isset ($_GET['id'])) {
    validate_id($_GET['id']);

    $news = db_select_one(
	    'news',
	    array('*'),
	    array('id' => $_GET['id'])
	);

    if ($news === false)
        unset ($news);
}

head('Site management');
menu_management();
section_header(isset ($news)?'Edit news item: ' . $news['title']:'New news item');

form_start('/admin/actions/news');
form_input_text('Title', $news['title']);
form_textarea('Body', $news['body']);
form_hidden('action', isset ($news)?'edit':'new');
form_hidden('id', $_GET['id']);
form_button_submit_bbcode ('Save changes');
form_end();

if (!isset ($news))
    die (foot ());

section_subhead('Delete news item');
form_start('/admin/actions/news');
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
form_button_submit ('Delete news item', '3');
form_end();

foot();