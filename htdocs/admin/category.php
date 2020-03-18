<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if (isset ($_GET['id'])) {
    validate_id($_GET['id']);

    $category = db_select_one(
	    'categories',
	    array('*'),
	    array('id' => $_GET['id'])
	);

    if ($category === false)
        unset ($category);
}

head('Site management');
menu_management();

section_title (isset ($category)?'Edit category: ' . $category['title']:"New category");
form_start('/admin/actions/category');
form_input_text('Title', $category['title']);
form_textarea('Description', $category['description']);
form_input_checkbox('Exposed', $category['exposed']);
form_hidden('action', isset ($category)?'edit':'new');
form_hidden('id', $_GET['id']);
form_button_submit_bbcode('Save changes');
form_end ();

if (!isset ($category)) {
    die (foot ());
}

section_subhead('Delete category: ' . $category['title']);
form_start('/admin/actions/category');
message_inline('Warning! This will delete all challenges under this category, as well as all submissions, files, and hints related those challenges!', "red");
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
form_button_submit('Delete category', 'danger');
form_end();

foot();