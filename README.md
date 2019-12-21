CTFx
=========

CTFx is a CTF Platform forked from [mellivora](https://github.com/Nakiami/mellivora), that focuses on low memory footprint and low server CPU usage. It has a futuristic interface that's optimized for slower hardware, meaning that there is no bulky Javascript running in the background, nor length CSS stylesheets. CTFx improves on the mellivora CTF engine by the UI redesign and the addition of new features.

<p align="center">
  <img src="readme-img/home.png" width="640" alt="CTFx home"/>
</p>

## Features
- Unlimited categories and challenges with configurable dynamic/static scoring
- Challenge hints
- Set custom start and end times for any challenge or category
- Unlockable challenges (In order to see them requires you to solve another challenge (from any category you choose))
- Local or [Amazon S3](https://aws.amazon.com/s3/) challenge file upload
- Admin Panel with competition overview, IP logging, user/email search, exception log (that includes the users that caused them)
- Create/edit front page news
- Arbitrary menu items and internal pages
- BBCode Support for challenge and category descriptions, news, etc ...
- Optional solve count limit per challenge
- [reCAPTCHA](https://www.google.com/recaptcha/) support
- User-defined or auto-generated passwords on signup
- Configurable caching
- Caching proxy (like [Cloudflare](https://www.cloudflare.com/)) aware (optional x-forwarded-for trust)
- [Segment](https://segment.com/) analytics support
- SMTP email support. Bulk or single email composition
- TOTP two factor auth support
- [CTF Time](https://ctftime.org/) compatible JSON scoreboard
- And more ...

## Looks
CTFx has a slick modern interface. See the [gallery](gallery.md).

## Performance
CTFx is extremely lightweight and fast. See the [benchmarks](benchmarks.md).

## Installation
**- Install the following dependencies**
  - `nginx php-fpm php-xml php-curl php-mysql php-mbstring php-pear composer mysql-server`

**- Secure mysql server**
  - Run the command `mysql_secure_installation` and remove anonymous users, disable root login and remove the test database

**- Copy repo contents to /var/www/ctfx/**
  - Run `composer install --no-dev --optimize-autoloader` under /var/www/ctfx
  - Make the folder `writable` writable

**- Setup nginx**
  - Copy the recommended nginx config `install/recommended_nginx_config` to `/etc/nginx/nginx.conf`

**- Setup MySQL**
  - sudo into `mysql`, then run the following queries:
  - `CREATE DATABASE mellivora CHARACTER SET utf8 COLLATE utf8_general_ci;`
  - `GRANT ALL PRIVILEGES ON mellivora.* TO 'mellivora'@'%' IDENTIFIED BY 'mellivora_pass';`
  - exit `mysql`
  - `sudo mysql < install/sql/001-mellivora.sql`
  - `sudo mysql < install/sql/002-countries.sql`

**- Create Admin User**
  - Register your admin account on the website (and enable 2FA Authentication preferably)
  - Logout of your account
  - sudo into `mysql` and run the query `USE mellivora; UPDATE users SET class=100 WHERE id=1;`

## Installation Tips:
- You can change the /var/www/ctfx path, but if you do so, you must update the `MELLIVORA_CONFIG_PATH_BASE` variable in `include/config/config.inc.php`
- If you have issues with executing PHP you should check if the php-fpm .sock file present in the nginx config exists.
- It is **recommended** that you change the default database password, and if you do so you must also change it in `include/config/db.inc.php`
- You can change the homepage to your liking by modifying `htdocs/home.php`. Make sure to also change the css rules in `htdocs/css/mellivora.min.css` for #ctfx-main-logo and .main-intro-text, if you want a different overall style.
- You might want to change the `MELLIVORA_CONFIG_CTF_START_TIME` and `MELLIVORA_CONFIG_CTF_END_TIME` variables in `include/config/config.inc.php`, so that each new challenge you create will have these times set as default.

## License
This software is licenced under the [GNU General Public License v3 (GPL-3)](http://www.tldrlegal.com/license/gnu-general-public-license-v3-%28gpl-3%29). The "include/thirdparty/" directory contains third party code. Please read their LICENSE files for information on the software availability and distribution.