<?php

/**
 *
 * This file contains default configuration.
 *
 *        DO NOT MAKE CHANGES HERE
 *
 * Copy this file and name it "config.inc.php"
 * before making any changes. Any changes in
 * config.inc.php will override the default
 * config. It is also possible to override
 * configuration options using environment
 * variables. Environment variables override
 * both the default settings and the hard-coded
 * user defined settings.
 *
 */

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

/**
 * By default all configuration options may be overridden using environment variables.
 * Add specific options to this list to allow only those to be overridden.
 * Comment out or add '*' to the list to allow overriding of all config variables.
 */
//const RESTRICT_ENV_CONFIG_OVERRIDE = [
//    ''
//];

// general site settings
Config::set('SITE_NAME', 'CTFx');
Config::set('SITE_SLOGAN', 'Fresh install');
Config::set('SITE_DESCRIPTION', 'Blazing fast CTF Platform from the future');

Config::set('URL_BASE_PATH', '/');
Config::set('URL_STATIC_RESOURCES', '/static');

// Challenges
Config::set('CHALLENGE_INITIAL_POINTS', 500);
Config::set('CHALLENGE_MINIMUM_POINTS', 50);
Config::set('CHALLENGE_SOLVES_UNTIL_MINIMUM', 100);

// CTF default start and end times, in unix timestamp
Config::set('CTF_START_TIME', 1632796517);
Config::set('CTF_END_TIME', 1831991513);

Config::set('SUBMISSION_COOLDOWN', 5);

// ID of category that players would see when they click on the Challenges page
Config::set('DEFAULT_CATEGORY_ON_CHALLENGES_PAGE', 1);

// redirects
Config::set('REDIRECT_INDEX_TO', 'home');

// captcha
Config::set('ENABLE_CAPTCHA', false);
Config::set('HCAPTCHA_SITE_KEY', 'site_key');
Config::set('HCAPTCHA_SECRET', 'secret');

// account limitations
Config::set('MIN_TEAM_NAME_LENGTH', 2);
Config::set('MAX_TEAM_NAME_LENGTH', 30);

// is site SSL compatible? if true, cookies will be sent using only SSL
Config::set('SSL_COMPAT', false);

// session & cookie expiry time in seconds
// 0 until browser is closed
Config::set('SESSION_TIMEOUT', 604800);
Config::set('COOKIE_TIMEOUT', 604800);

// logging options
Config::set('MELLIVORA_CONFIG_LOG_VALIDATION_FAILURE_ID', true);

// maximum file upload size
Config::set('MELLIVORA_CONFIG_MAX_FILE_UPLOAD_SIZE', 5242880);
Config::set('MELLIVORA_CONFIG_APPEND_MD5_TO_DOWNLOADS', false);

// email stuff
Config::set('MELLIVORA_CONFIG_EMAIL_USE_SMTP', true);
Config::set('MELLIVORA_CONFIG_EMAIL_FROM_EMAIL', 'yourmail@gmail.com');
Config::set('MELLIVORA_CONFIG_EMAIL_FROM_NAME', 'X-MAS CTF Team');
// blank for same as "FROM"
Config::set('MELLIVORA_CONFIG_EMAIL_REPLYTO_EMAIL', '');
Config::set('MELLIVORA_CONFIG_EMAIL_REPLYTO_NAME', '');
// options:
// 0 off (for production use)
// 1 client messages
// 2 client and server messages
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_DEBUG_LEVEL', 0);
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_HOST', 'smtp.gmail.com');
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_PORT', 587);
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_SECURITY', 'tls');
// require SMTP authentication?
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_AUTH', true);
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_USER', 'yourmail@gmail.com');
Config::set('MELLIVORA_CONFIG_EMAIL_SMTP_PASSWORD', 'mail_password_here');

// only trust x-forwarded-for ip address if you're running
// some sort of reverse proxy, like Cloudflare. when set
// to true, the latest added forwarded-for ip will be used
// for logging and housekeeping
Config::set('TRUST_HTTP_X_FORWARDED_FOR', true);

// when this is set to true, an IP address
// will be resolved when it is listed. set
// this to false if DNS resolution is too
// slow when listing a users IPs
Config::set('MELLIVORA_CONFIG_get_client_ip_HOST_BY_ADDRESS', false);

// cache times
Config::set('MELLIVORA_CONFIG_CACHE_TIME_SCORES', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_HOME', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_USER', 5);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_CHALLENGE', 0);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_HINTS', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_FILES', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_COUNTRIES', 60);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_DYNAMIC', 60);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_REGISTER', 10);

Config::set('MELLIVORA_CONFIG_SHOW_ACHIEVEMENTS', true);
