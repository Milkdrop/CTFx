<?php

function user_is_logged_in() {
    return isset($_SESSION['id']);
}

function user_is_enabled() {
    if ($_SESSION['enabled']) {
        return true;
    }

    return false;
}

function user_is_staff () {
    if (user_is_logged_in() && $_SESSION['class'] >= CONST_USER_CLASS_MODERATOR) {
        return true;
    }

    return false;
}

function login_session_refresh($force_user_data_reload = false) {
    // force a database reload of user data
    if (user_is_logged_in()) {

        update_user_last_active_time($_SESSION['id']);

        if ($force_user_data_reload) {

            $user = db_select_one(
                'users',
                array(
                    'id',
                    'class',
                    'enabled',
                    '2fa_status',
                    'download_key'
                ),
                array(
                    'id' => $_SESSION['id']
                )
            );

            if ($_SESSION['2fa_status'] == 'authenticated') {
                $user['2fa_status'] = $_SESSION['2fa_status'];
            }

            login_session_create($user);
        }
    }

    // if users session has expired, but they have the "remember me" cookie
    if (!user_is_logged_in() && login_cookie_isset()) {
        login_session_create_from_login_cookie();
    }

    if (user_is_logged_in() && !user_is_enabled()) {
        logout();
    }
}

function login_create($email, $password, $remember_me) {

    if(empty($email) || empty($password)) {
        message_error('Please enter your email and password.');
    }

    $user = db_select_one(
        'users',
        array(
            'id',
            'passhash',
            'download_key',
            'class',
            'enabled',
            '2fa_status'
        ),
        array(
            'email'=>$email
        )
    );

    if (!check_passhash($password, $user['passhash'])) {
        message_error('Login failed');
    }

    if (!$user['enabled']) {
        message_generic('Ooops!', 'Your account is not enabled.
        If you have just registered, this is normal - an email with instructions will be sent out closer to the event start date!
        In all other cases, please contact the system administrator with any questions.');
    }

    login_session_create($user);
    regenerate_tokens();

    if ($remember_me) {
        login_cookie_create($user);
    }

    log_user_ip($user['id']);

    return true;
}

function login_session_create($user) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['class'] = $user['class'];
    $_SESSION['enabled'] = $user['enabled'];
    $_SESSION['2fa_status'] = $user['2fa_status'];
    $_SESSION['download_key'] = $user['download_key'];
    $_SESSION['fingerprint'] = get_fingerprint();

    update_user_last_active_time($user['id']);
}

function regenerate_tokens() {
    regenerate_xsrf_token();
    regenerate_submission_token();
}

function login_cookie_create($user, $token_series = false) {

    $time = time();
    $ip = get_client_ip(true);

    if (!$token_series) {
        $token_series = generate_random_string(16);
    }
    $token = generate_random_string(64);

    db_insert(
        'cookie_tokens',
        array(
            'added'=>$time,
            'ip_created'=>$ip,
            'ip_last'=>$ip,
            'user_id'=>$user['id'],
            'token_series'=>$token_series,
            'token'=>$token
        )
    );

    $cookie_content = array (
        't'=>$token,
        'ts'=>$token_series
    );

    setcookie(
        CONST_COOKIE_NAME, // name
        json_encode($cookie_content), // content
        $time+Config::get('MELLIVORA_COOKIE_TIMEOUT'), // expiry
        '/', // path
        null, // domain
        Config::get('MELLIVORA_SSL_COMPAT'), // serve over SSL only
        true // httpOnly
    );
}

function login_cookie_destroy() {

    if (!login_cookie_isset()) {
        return;
    }

    $cookie = login_cookie_decode();

    db_delete(
        'cookie_tokens',
        array(
            'token'=>$cookie['t'],
            'token_series'=>$cookie['ts']
        )
    );

    destroy_cookie(CONST_COOKIE_NAME);
}

function destroy_cookie($name) {
    unset($_COOKIE[$name]);

    setcookie(
        $name,
        '',
        time() - 3600,
        '/'
    );
}

function login_cookie_isset() {
    return isset($_COOKIE[CONST_COOKIE_NAME]);
}

function login_cookie_decode() {

    if (!login_cookie_isset()) {
        log_exception(new Exception('Tried to decode nonexistent login cookie'));
        logout();
    }

    $cookieObj = json_decode($_COOKIE[CONST_COOKIE_NAME]);

    return array('t'=>$cookieObj->{'t'}, 'ts'=>$cookieObj->{'ts'});
}

function login_session_create_from_login_cookie() {

    if (!login_cookie_isset()) {
        log_exception(new Exception('Tried to create session from nonexistent login cookie'));
        logout();
    }

    $cookie = login_cookie_decode();

    $cookie_token_entry = db_select_one(
        'cookie_tokens',
        array(
            'user_id'
        ),
        array(
            'token'=>$cookie['t'],
            'token_series'=>$cookie['ts']
        )
    );

    if (!$cookie_token_entry['user_id']) {

        /*
         * TODO, here we could check:
         *    - if the token_series matches but
         *    - the token does not match
         * this probably means someone has already
         * used this cookie to re-authenticate.
         * This probably mean the cookie has been stolen.
         */

        log_exception(new Exception('An invalid cookie token was used. Cookie likely stolen. TS: ' . $cookie['ts']));
        logout();

        // explicitly exit here, even
        // though we do in redirect()
        exit;
    }

    // get the user whom this token
    // was issued for
    $user = db_select_one(
        'users',
        array(
            'id',
            'class',
            'enabled',
            '2fa_status',
            'download_key'
        ),
        array(
            'id'=>$cookie_token_entry['user_id']
        )
    );

    // remove the cookie token from the db
    // as it is used, and we don't want it
    // to every be used again
    db_delete(
        'cookie_tokens',
        array(
            'token'=>$cookie['t'],
            'token_series'=>$cookie['ts']
        )
    );

    // issue a new login cookie for the user
    // using the same token series identifier
    login_cookie_create($user, $cookie['ts']);

    login_session_create($user);
    regenerate_tokens();
}

function update_user_last_active_time($user_id) {

    validate_id($user_id);

    $now = time();

    if (!array_get($_SESSION, 'last_active') || $now - $_SESSION['last_active'] > CONST_USER_MIN_SECONDS_BETWEEN_ACTIVITY_LOG) {

        db_update(
            'users',
            array('last_active' => $now),
            array('id' => $user_id)
        );

        $_SESSION['last_active'] = $now;
    }
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

function check_passhash($password, $hash) {
    return password_verify($password, $hash);
}

function get_fingerprint() {
    return md5(get_client_ip());
}

function login($user_id, $admin) {
    log_user_ip($user_id);
    
    $_SESSION['id'] = $user_id;
    $_SESSION['admin'] = ($admin === true);
    $_SESSION['ip'] = get_client_ip();
}

function login_session_destroy () {
    session_unset();
    session_destroy();
}

function enforce_authentication($admin = false) {
    login_session_refresh($force_user_data_reload);

    if (!user_is_logged_in()
    || $_SESSION['ip'] != get_client_ip()
    || ($admin && $_SESSION['admin'] == false)
    ) {
        logout();
    }
    
    //enforce_2fa();
}

function enforce_2fa() {
    if ($_SESSION['2fa_status'] == 'enabled') {
        redirect('two_factor_auth');
    }
}

function session_set_2fa_authenticated() {
    $_SESSION['2fa_status'] = 'authenticated';
}

function logout() {
    login_session_destroy();
    login_cookie_destroy();
    redirect(Config::get('REDIRECT_INDEX_TO'));
}
