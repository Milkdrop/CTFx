<?php

const CONST_DYNAMIC_VISIBILITY_BOTH = 'both';
const CONST_DYNAMIC_VISIBILITY_PRIVATE = 'private';
const CONST_DYNAMIC_VISIBILITY_PUBLIC = 'public';

const CONST_CACHE_DYNAMIC_MENU_GROUP = 'dynamic_menu';
const CONST_CACHE_DYNAMIC_PAGES_GROUP = 'dynamic_pages';
const CONST_CACHE_GROUP_NAME_DYNAMIC_MENU = 'dynamic_menu';

const CONST_CACHE_NAME_HINTS = 'hints';
const CONST_CACHE_NAME_HOME = 'home';
const CONST_CACHE_NAME_SCORES_JSON = 'scores_json';
const CONST_CACHE_NAME_SCORES = 'scores';
const CONST_CACHE_NAME_REGISTER = 'register';
const CONST_CACHE_NAME_FILES = 'files_';
const CONST_CACHE_NAME_CHALLENGE_HINTS = 'hints_challenge_';
const CONST_CACHE_NAME_COUNTRY = 'country_';
const CONST_CACHE_NAME_CHALLENGE = 'challenge_';
const CONST_CACHE_NAME_USER = 'user_';

const CONST_MIN_REQUIRED_PHP_VERSION = '5.6';

const CONST_NUM_EXCEPTIONS_PER_PAGE = 30;

define('CONST_PATH_INCLUDE', dirname(__FILE__));
define('CONST_PATH_LAYOUT', CONST_PATH_INCLUDE . '/layout');
define('CONST_PATH_THIRDPARTY', CONST_PATH_INCLUDE . '/thirdparty');
define('CONST_PATH_CONFIG', CONST_PATH_INCLUDE . '/config');
define('CONST_PATH_FILE_WRITABLE', dirname(__FILE__) . '/..' . '/writable');
define('CONST_PATH_FILE_UPLOAD', CONST_PATH_FILE_WRITABLE . '/upload');
define('CONST_PATH_CACHE', CONST_PATH_FILE_WRITABLE . '/cache');