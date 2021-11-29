<?php

$captcha_script_sent = false;

function display_captcha() {
    global $captcha_script_sent;

    $return = '';
    if (!$captcha_script_sent) {
        $return .= "<script src='https://www.hCaptcha.com/1/api.js' async defer></script>";
        $captcha_script_sent = true;
    }

    $return .= '<div class="h-captcha" data-sitekey="' . htmlspecialchars(Config::get('HCAPTCHA_SITE_KEY')) . '"></div>';
    return $return;
}

function validate_captcha() {
    if(!isset($_POST['h-captcha-response']) || empty($_POST['h-captcha-response'])) {
        die_with_message_error("Captcha fail");
        return false;
    }
    
    $data = array(
        'secret' => Config::get('HCAPTCHA_SECRET'),
        'response' => $_POST['h-captcha-response']
    );

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);
    
    if ($responseData->success) {
        return true;
    } else {
        die_with_message_error("Captcha fail");
    }
}