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

// paths below must end in a "/" !
Config::set('MELLIVORA_CONFIG_PATH_BASE', '/var/www/ctfx/');

// language
Config::set('MELLIVORA_CONFIG_SITE_LANGUAGE', 'en');

// general site settings
Config::set('MELLIVORA_CONFIG_SITE_NAME', 'X-MAS CTF');
Config::set('MELLIVORA_CONFIG_SITE_SLOGAN', 'X-MAS CTF 2019');
Config::set('MELLIVORA_CONFIG_SITE_DESCRIPTION', 'X-MAS Capture The Flag 2019 platform - Merry Christmas!');

Config::set('MELLIVORA_CONFIG_SITE_URL', '/');
Config::set('MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES', '/');

Config::set('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH', 'admin/');
Config::set('MELLIVORA_CONFIG_SITE_ADMIN_URL', Config::get('MELLIVORA_CONFIG_SITE_URL') . Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH'));

// CTF default start and end times, in unix timestamp
Config::set('MELLIVORA_CONFIG_CTF_START_TIME', 1576263600);
Config::set('MELLIVORA_CONFIG_CTF_END_TIME', 1576868400);

// redirects
Config::set('MELLIVORA_CONFIG_INDEX_REDIRECT_TO', 'home');
Config::set('MELLIVORA_CONFIG_LOGIN_REDIRECT_TO', 'home');
Config::set('MELLIVORA_CONFIG_REGISTER_REDIRECT_TO', 'home');

// account limitations
Config::set('MELLIVORA_CONFIG_MIN_TEAM_NAME_LENGTH', 2);
Config::set('MELLIVORA_CONFIG_MAX_TEAM_NAME_LENGTH', 30);
Config::set('MELLIVORA_CONFIG_ACCOUNTS_SIGNUP_ALLOWED', true);
Config::set('MELLIVORA_CONFIG_ACCOUNTS_DEFAULT_ENABLED', true);

// if set to true, a random password will be generated
// on signup and sent out by email to the user
Config::set('MELLIVORA_CONFIG_ACCOUNTS_EMAIL_PASSWORD_ON_SIGNUP', false);

// is site SSL compatible? if true, cookies will be sent using only SSL
Config::set('MELLIVORA_CONFIG_SSL_COMPAT', false);

// session & cookie expiry time in seconds
// 0 until browser is closed
Config::set('MELLIVORA_CONFIG_SESSION_TIMEOUT', 604800);
Config::set('MELLIVORA_CONFIG_COOKIE_TIMEOUT', 604800);

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

// enable re-captcha on signup and various public forms
Config::set('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PUBLIC', false);
// enabled captcha also on private forms for logged in users
Config::set('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE', false);
// re-captcha keys must be set to function
Config::set('MELLIVORA_CONFIG_RECAPTCHA_PUBLIC_KEY', 'public_v2_captcha_key');
Config::set('MELLIVORA_CONFIG_RECAPTCHA_PRIVATE_KEY', 'private_v2_captcha_key');

// only trust x-forwarded-for ip address if you're running
// some sort of reverse proxy, like Cloudflare. when set
// to true, the latest added forwarded-for ip will be used
// for logging and housekeeping
Config::set('MELLIVORA_CONFIG_TRUST_HTTP_X_FORWARDED_FOR_IP', true);

// when this is set to true, an IP address
// will be resolved when it is listed. set
// this to false if DNS resolution is too
// slow when listing a users IPs
Config::set('MELLIVORA_CONFIG_GET_IP_HOST_BY_ADDRESS', false);

// cache times
Config::set('MELLIVORA_CONFIG_CACHE_TIME_SCORES', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_HOME', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_USER', 5);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_CHALLENGE', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_HINTS', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_FILES', 10);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_COUNTRIES', 60);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_DYNAMIC', 60);
Config::set('MELLIVORA_CONFIG_CACHE_TIME_REGISTER', 10);

// user tracking and statistics
Config::set('MELLIVORA_CONFIG_SEGMENT_IO_KEY', '');

// Amazon S3 credentials, for storing files in S3.
// Leave blank to store files locally.
Config::set('MELLIVORA_CONFIG_AWS_S3_KEY_ID', '');
Config::set('MELLIVORA_CONFIG_AWS_S3_SECRET', '');
Config::set('MELLIVORA_CONFIG_AWS_S3_BUCKET', '');
