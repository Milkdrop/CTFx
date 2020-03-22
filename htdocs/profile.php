<?php

require('../include/mellivora.inc.php');

enforce_authentication();

$user = db_select_one(
    'users',
    array(
        'team_name',
        'email',
        'enabled',
        'competing',
        'country_id',
        '2fa_status'
    ),
    array('id' => $_SESSION['id'])
);

head(lang_get('profile'));

section_subhead(
    lang_get('profile_settings'),
    '| <a href="user?id='.htmlspecialchars($_SESSION['id']).'">'.lang_get('view_public_profile').'</a>',
    false
);

form_start('actions/profile');
form_input_text('Email', $user['email'], array('disabled'=>true));
form_input_text('Team name', $user['team_name'], array('disabled'=>true));

$opts = db_query_fetch_all('SELECT * FROM countries ORDER BY country_name ASC');
form_select($opts, 'Country', 'id', $user['country_id'], 'country_name');
message_inline ("In order to add a profile picture, register yourself on <a href='https://en.gravatar.com/'>Gravatar</a>", "blue", false);

form_hidden('action', 'edit');
form_button_submit(lang_get('save_changes'));
form_end();

section_subhead(lang_get('two_factor_auth'), "using Google Authenticator (TOTP)");
form_start('actions/profile');
if ($user['2fa_status'] == 'generated') {
    form_generic('QR', '<img src="'.get_two_factor_auth_qr_url().'" alt="QR" title="'.lang_get('scan_with_totp_app').'" />');
    message_inline ("Scan the above QR Code using a 2FA TOTP App such as Google Authenticator to obtain the code");
    form_input_text('Code');
    form_hidden('action', '2fa_enable');
    form_button_submit("Enable 2FA");
}

else if ($user['2fa_status'] == 'disabled') {
    form_hidden('action', '2fa_generate');
    form_button_submit("Generate QR Code");
}

else if ($user['2fa_status'] == 'enabled') {
    form_generic('QR', '<img src="'.get_two_factor_auth_qr_url().'" alt="QR" title="'.lang_get('scan_with_totp_app').'" />');
    form_hidden('action', '2fa_disable');
    form_button_submit("Disable 2FA", '3');
}
form_end();

section_subhead(lang_get('reset_password'));
form_start('actions/profile');
form_input_password('Current password');
form_input_password('New password');
form_input_password('New password again');
form_hidden('action', 'reset_password');
form_input_captcha();
form_button_submit(lang_get('reset_password'), '2');
form_end();

foot();