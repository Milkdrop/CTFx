<?php

require('config_loader.inc.php');
require(dirname(__FILE__) . '/constants.inc.php');
require(CONST_PATH_THIRDPARTY_COMPOSER . '/autoload.php');
require(CONST_PATH_INCLUDE . '/language/language.inc.php');
require(CONST_PATH_INCLUDE . '/session.inc.php');
require(CONST_PATH_INCLUDE . '/raceconditions.inc.php');
require(CONST_PATH_INCLUDE . '/xsrf.inc.php');
require(CONST_PATH_INCLUDE . '/achievements.inc.php');
require(CONST_PATH_INCLUDE . '/api.inc.php');
require(CONST_PATH_INCLUDE . '/general.inc.php');
require(CONST_PATH_INCLUDE . '/db.inc.php');
require(CONST_PATH_INCLUDE . '/cache.inc.php');
require(CONST_PATH_INCLUDE . '/json.inc.php');
require(CONST_PATH_INCLUDE . '/email.inc.php');
require(CONST_PATH_INCLUDE . '/files.inc.php');
require(CONST_PATH_INCLUDE . '/captcha.inc.php');
require(CONST_PATH_INCLUDE . '/two_factor_auth.inc.php');
require(CONST_PATH_LAYOUT . '/layout.inc.php');
require(CONST_PATH_THIRDPARTY . '/Parsedown.php');

set_exception_handler('log_exception');

session_set_cookie_params(
    Config::get('MELLIVORA_CONFIG_SESSION_TIMEOUT'),
    '/',
    null,
    Config::get('MELLIVORA_CONFIG_SSL_COMPAT'),
    true
);

ini_set('session.gc_maxlifetime', Config::get('MELLIVORA_CONFIG_SESSION_TIMEOUT'));
session_start();