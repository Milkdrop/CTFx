<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if (isset ($_GET['id'])) {
    validate_id($_GET['id']);

    $hint = db_select_one(
        'hints',
        array('*'),
        array('id' => $_GET['id'])
    );

    if ($hint === false)
        unset ($hint);
}

head('Site management');
menu_management();
section_title (isset ($hint)?'Edit hint':'New hint');

$opts = db_query_fetch_all(
    'SELECT
         ch.id,
         ch.title,
         ca.title AS category
    FROM challenges AS ch
    LEFT JOIN categories AS ca ON ca.id = ch.category
    ORDER BY ca.title, ch.title'
);

form_start('/admin/actions/hint.php');
form_textarea('Body', $hint['body']);

form_select($opts, 'Challenge', 'id', isset ($hint)?$hint['challenge']:array_get($_GET, 'challenge', 0), 'title', 'category');
form_input_checkbox('Visible', $hint['visible']);
form_hidden('action', isset ($hint)?'edit':'new');
form_hidden('id', $_GET['id']);
form_button_submit_bbcode('Save changes');
form_end();

if (!isset ($hint))
    die (foot ());

section_subhead('Delete hint');
form_start('/admin/actions/hint.php');
form_input_checkbox('Delete confirmation', false, 'red');
form_hidden('action', 'delete');
form_hidden('challenge', $hint['challenge']);
form_hidden('id', $_GET['id']);
form_button_submit('Delete hint', '3');
form_end();

foot();