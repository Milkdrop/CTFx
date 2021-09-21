<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('Site management');
admin_menu();

if (array_get($_GET, 'bcc') == 'all') {
    $users = db_select_all(
        'users',
        array('email')
    );

    $bcc = '';
    foreach ($users as $user) {
        $bcc .= $user['email'].",\n";
    }
    $bcc = trim($bcc);
}

echo section_header('New email');

message_inline('Separate receiver emails with a comma and optional whitespace. You can use BBCode. If you do, you must send as HTML email.');

form_start('/admin/actions/new_email');

if(isset($bcc)) {
    form_input_text('To', Config::get('MELLIVORA_CONFIG_EMAIL_FROM_EMAIL'));
    form_input_text('CC');
    form_textarea('BCC', $bcc);
} else {
    form_input_text('To', isset($_GET['to']) ? $_GET['to'] : '');
    form_input_text('CC');
    form_input_text('BCC');
}

form_input_text('Subject');
form_textarea('Body');

form_input_checkbox('HTML email');

form_hidden('action', 'new');

message_inline('Important email? Remember to Ctrl+C before attempting to send!', "green");

form_button_submit('Send email');
form_end();

foot();