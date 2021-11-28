<?php

function form_xsrf_token() {
    if(empty($_SESSION['xsrf_token'])) {
        $_SESSION['xsrf_token'] = bin2hex(random_bytes(32));
    }

    return '<input type="hidden" name="xsrf_token" value="' . htmlspecialchars($_SESSION['xsrf_token']) . '" />';
}

function validate_xsrf_token() {
    if ($_SESSION['xsrf_token'] != $_POST['xsrf_token']) {
        log_exception(new Exception('Invalid XSRF token. Was: "' . $token.'". Wanted: "' . $_SESSION['xsrf_token'].'"'));
        die_with_message_error('Invalid XSRF token');
    }
}
