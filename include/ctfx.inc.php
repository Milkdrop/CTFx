<?php

require('config_loader.inc.php');
require(dirname(__FILE__) . '/constants.inc.php');
require(CONST_PATH_INCLUDE . '/session.inc.php');
require(CONST_PATH_INCLUDE . '/xsrf.inc.php');
require(CONST_PATH_INCLUDE . '/api.inc.php');
require(CONST_PATH_INCLUDE . '/general.inc.php');
require(CONST_PATH_INCLUDE . '/db.inc.php');
require(CONST_PATH_INCLUDE . '/cache.inc.php');
require(CONST_PATH_INCLUDE . '/captcha.inc.php');
require(CONST_PATH_INCLUDE . '/two_factor_auth.inc.php');
require(CONST_PATH_LAYOUT . '/layout.inc.php');
require(CONST_PATH_THIRDPARTY . '/Parsedown.php');
require(CONST_PATH_THIRDPARTY . '/Google2FA.php');

set_exception_handler('log_exception');

session_set_cookie_params(
    Config::get('SESSION_TIMEOUT'),
    '/',
    null,
    Config::get('SSL_COMPAT'),
    true
);

ini_set('session.gc_maxlifetime', Config::get('SESSION_TIMEOUT'));
session_start();