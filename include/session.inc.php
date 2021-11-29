<?php

function user_is_logged_in() {
    return isset($_SESSION['id']);
}

function user_is_staff() {
    return (user_is_logged_in() && $_SESSION['admin'] === true);
}

function log_user_ip($user_id) {

    validate_id($user_id);

    $now = time();
    $ip = get_client_ip();

    $entry = db_select_one('ip_log', array('id', 'times_used'), array('user_id'=>$user_id, 'ip'=>$ip));

    // if the user has logged in with this IP previously
    if ($entry['id']) {
        db_update('ip_log',
            array(
                'last_used'=>time(),
                'times_used'=>$entry['times_used'] + 1
            ), array('id'=>$entry['id'])
        );
    }
    // if this is a new IP
    else {
        db_insert(
            'ip_log',
            array(
                'added'=>time(),
                'user_id'=>$user_id,
                'last_used'=>$now,
                'ip'=>$ip
            )
        );
    }
}

// TODO - When an user gets deleted, its session token should be deleted too
function login($user_id, $admin) {
    session_unset();
    
    log_user_ip($user_id);
    $_SESSION['id'] = $user_id;
    $_SESSION['admin'] = ($admin === true);
    $_SESSION['ip'] = get_client_ip();
    $_SESSION['last_active'] = time();
    redirect(Config::get('REDIRECT_INDEX_TO'));
}

function enforce_authentication($admin = false) {
    if (!user_is_logged_in()) {
        logout();
    }

    if ($_SESSION['ip'] != get_client_ip()) {
        log_exception(new Exception('Tried to use a different IP from their session.'));
        logout();
    }

    if ($admin && $_SESSION['admin'] == false) {
        log_exception(new Exception('Tried to access an admin page,'));
        logout();
    }

    // Update user activity
    if (empty($_SESSION['last_active']) || time() - $_SESSION['last_active'] > Config::get('ACTIVITY_LOG_FREQUENCY')) {
        db_update(
            'users',
            array('last_active' => time()),
            array('id' => $_SESSION['id'])
        );

        $_SESSION['last_active'] = time();
    }
}

function logout() {
    session_unset();
    session_destroy();
    redirect(Config::get('REDIRECT_INDEX_TO'));
}
