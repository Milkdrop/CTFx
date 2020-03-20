<?php

require('../include/mellivora.inc.php');

// get auth data
if (isset($_GET['auth_key']) && is_valid_id($_GET['id'])) {

    $auth = db_select_one(
        'reset_password',
        array(
            'id',
            'user_id',
            'auth_key'
        ),
        array(
            'auth_key' => $_GET['auth_key'],
            'user_id' => $_GET['id']
        )
    );

    if (!$auth['user_id']) {
        message_error(lang_get('no_reset_data'));
    }
}

// start here
if (!isset($_GET['action'])) {

    head (lang_get('reset_password'));
    section_title ("Reset Password:");

    echo '
    <form method="post" class="form-signin" action="actions/reset_password">
        <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'EMAIL'),'" type="text" class="form-control form-group" placeholder="',lang_get('email_address'),'" required autofocus />
        <input type="hidden" name="action" value="reset_password" />
        ';

    if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PUBLIC')) {
        display_captcha();
    }

        echo '
        <button class="btn btn-lg btn-1" type="submit">',lang_get('reset_password'),'</button>
    </form>
    ';

    foot();
}

// return from password reset email here
else if ($_GET['action']=='choose_password' && is_valid_id($auth['user_id'])) {

    head (lang_get('choose_password'));
    section_title ("Choose Password:");

    echo '
    <form method="post" class="form-signin" action="actions/reset_password">
        <input name="',md5(Config::get('MELLIVORA_CONFIG_SITE_NAME').'PWD'),'" type="password" class="form-control form-group" placeholder="',lang_get('password'),'" required autofocus />
        <input type="hidden" name="action" value="choose_password" />
        <input type="hidden" name="auth_key" value="',htmlspecialchars($_GET['auth_key']),'" />
        <input type="hidden" name="id" value="',htmlspecialchars($_GET['id']),'" />
        <button class="btn btn-lg btn-1" type="submit">',lang_get('reset_password'),'</button>
    </form>
    ';

    foot();
}
