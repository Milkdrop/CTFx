<?php

function php_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function generate_random_int($min = 0, $max = PHP_INT_MAX) {
    $factory = new RandomLib\Factory;
    $generator = $factory->getMediumStrengthGenerator();

    return $generator->generateInt($min, $max);
}

function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
	
    if (Config::get('TRUST_HTTP_X_FORWARDED_FOR') && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // in almost all cases, there will only be one IP in this header
        if (is_valid_ip($_SERVER['HTTP_X_FORWARDED_FOR'], true)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // in the rare case where several IPs are listed
        else {
            $forwarded_for_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($forwarded_for_list as $forwarded_for) {
                $forwarded_for = trim($forwarded_for);
                if (is_valid_ip($forwarded_for, true)) {
                    $ip = $forwarded_for;
                }
            }
        }
    }

    return $ip;
}

function is_valid_ip($ip, $public_only = false) {
    // we only want public, non-reserved IPs
    if ($public_only) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return true;
        } else {
            return false;
        }
    }

    // allow non-public and reserved IPs
    else {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }
}

function is_valid_id($id) {
    if (isset($id) && is_integer_value($id) && $id > 0) {
        return true;
    }

    return false;
}

function validate_id($id) {
    if (!is_valid_id($id)) {

        if (Config::get('LOG_VALIDATION_FAILURE_ID')) {
            log_exception(new Exception('Invalid ID'));
        }
        
        die_with_message_error('Invalid ID provided');
    }

    return true;
}

function validate_url($url) {
    $valid = false;

    if (filter_var ($url, FILTER_VALIDATE_URL)) {
        $valid = true;
    } else {
        $url = "http://test.com" . $url;
        if (filter_var ($url, FILTER_VALIDATE_URL))
            $valid = true;
    }

    if (!$valid) {
        log_exception(new Exception('Invalid URL in redirect: ' . $url));
        die_with_message_error('Invalid redirect URL. This has been reported.');
    }
}

function is_integer_value($val) {
    return is_int($val) ? true : ctype_digit($val);
}

function log_exception($exception, $showStackTrace = true, $customMessage = "") {

    // write exception to php's default error handler
    // in case we fail to insert it into the db

    error_log($exception);

    try {
        db_insert(
            'exceptions',
            array(
                'added'=>time(),
                'added_by'=>array_get($_SESSION, 'id', 0),
                'message'=>$exception->getMessage(),
                'code'=>$exception->getCode(),
                'trace'=>$showStackTrace? $exception->getTraceAsString() : $customMessage,
                'file'=>$exception->getFile(),
                'line'=>$exception->getLine(),
                'user_ip'=>get_client_ip(),
                'user_agent'=>$_SERVER['HTTP_USER_AGENT']
            )
        );
    } catch (Exception $inception_exception) {
        error_log($inception_exception);
        exit;
    }
}

function formatted_date($timestamp) {
    return date('d/m/Y - H:i:s', $timestamp) . " UTC+0";
}

function ctf_started() {
    return time() >= Config::get('CTF_START_TIME');
}

function get_system_memory_usage () {
    $output = trim (shell_exec ("free -b"));
    $output = explode ("\n", $output)[1];
    $mem = array_merge (array_filter (explode (" ", $output)))[2];

    return $mem;
}

function starts_with($haystack, $needle) {
    return $needle === '' || strpos($haystack, $needle) === 0;
}

function redirect($url, $absolute = false) {
    if (strpos($url, '/actions/') !== false) {
        $url = Config::get('REDIRECT_INDEX_TO');
    }

    if (!$absolute) {
        $url = Config::get('URL_BASE_PATH') . trim($url, '/');
    }

    validate_url($url);

    header('location: ' . $url);
    exit();
}

function check_server_configuration() {
    check_server_and_db_time();
    check_server_writable_dirs();
    check_server_php_version();
}

function check_server_php_version() {
    if (version_compare(PHP_VERSION, CONST_MIN_REQUIRED_PHP_VERSION, '<')) {
        echo message_inline('Your version of PHP is too old. You need at least ' . CONST_MIN_REQUIRED_PHP_VERSION . '. You are running: ' . PHP_VERSION, true, "#EF3E36");
    }
}

function check_server_writable_dirs() {
    // check that our writable dirs are writable
    foreach (array_diff(scandir(CONST_PATH_FILE_WRITABLE), array('.', '..')) as $dir) {
        $dir = CONST_PATH_FILE_WRITABLE . '/' . $dir;
        if (!is_writable($dir)) {
            echo message_inline('Directory (' . $dir . ') must be writable.', true, "#EF3E36");
        }
    }
}

function check_server_and_db_time() {
    // check for DB and PHP time mismatch
    $dbInfo = db_query_fetch_one('SELECT UNIX_TIMESTAMP() AS timestamp, TIMESTAMPDIFF(SECOND, UTC_TIMESTAMP(), NOW()) AS timezone_offzet_seconds');
    $error = abs(time() - $dbInfo['timestamp']);
    if ($error >= 5) {
        echo message_inline('Database and PHP times are out of sync. (' . $error . ' seconds off)', true, "#EF3E36");
    }

    if (date('Z') != $dbInfo['timezone_offzet_seconds']) {
        echo message_inline('Database and PHP timezones are different.', true, "#EF3E36");
    }
}

function array_get ($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

function calculate_points_using_formula($init_pts, $min_pts, $solves_until_min, $solves) {
    if ($solves_until_min == 0 || $solves >= $solves_until_min)
        return $min_pts; // Avoid divide by 0 exception and clamp at min solves
    else
        return $init_pts - ($init_pts - $min_pts) * ($solves * $solves) / ($solves_until_min * $solves_until_min);
}

function update_challenge_points($challenge) {
    $solves = db_query_fetch_one('SELECT COUNT(*) AS count FROM submissions WHERE challenge=:challenge AND correct=1', array('challenge' => $challenge['id']))['count'];

    db_update('challenges',
        array(
            'solves'=>$solves,
            'points'=>calculate_points_using_formula($challenge['initial_points'], $challenge['minimum_points'], $challenge['solves_until_minimum'], $solves)
        ),
        array('id' => $challenge['id'])
    );
}
