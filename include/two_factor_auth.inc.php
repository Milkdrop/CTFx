<?php

function get_two_factor_auth_qr_url() {
    require_once(CONST_PATH_THIRDPARTY.'/Google2FA/Google2FA.php');

    $user = db_query_fetch_one(
        'SELECT
            u.id,
            u.team_name,
            t.secret
        FROM users AS u
        JOIN two_factor_auth AS t ON t.user_id = u.id
        WHERE
          u.id = :user_id',
        array(
            'user_id'=>$_SESSION['id']
        )
    );

    if (empty($user['id']) || empty($user['secret'])) {
        die_with_message_error('No two-factor authentication tokens found.');
    }

    return Google2FA::get_qr_code_url($user['team_name'], $user['secret']);
}

function validate_two_factor_auth_code($for_user, $code) {
    require_once(CONST_PATH_THIRDPARTY.'/Google2FA/Google2FA.php');

    $valid = false;

    $secret = db_select_one(
        'two_factor_auth',
        array(
            'secret'
        ),
        array(
            'user_id'=>$for_user
        )
    );

    try {
        $valid = Google2FA::verify_key($secret['secret'], $code);
    } catch (Exception $e) {
        die_with_message_error('Error while validating 2FA code');
    }

    return $valid;
}

function generate_two_factor_auth_secret($length) {
    return generate_random_string($length, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567');
}