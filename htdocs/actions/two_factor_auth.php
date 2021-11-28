<?php

require('../../include/ctfx.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'authenticate') {
        if (validate_two_factor_auth_code($_POST['code'])) {
            session_set_2fa_authenticated();
            redirect(Config::get('MELLIVORA_CONFIG_LOGIN_REDIRECT_TO'));
        } else {
            redirect('two_factor_auth?generic_failure=1');
        }
    }
}