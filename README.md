Mellivora
=========

Mellivora is a CTF engine written in PHP. Want a quick overview? Check out a [screenshot gallery on imgur](http://mellivora.imgur.com/). Want a quick start? Use [Mellivora with Docker](install/docker/README.md).

<p align="center">
  <img src="https://cdn.rawgit.com/Nakiami/mellivora/e1a47ff10ab55e67d8cf6bda001491d5c7867fc8/htdocs/img/mellivora.svg" width="250" alt="Mellivora logo"/>
</p>

### Features
- Arbitrary categories and challenges.
- Scoreboard with optional multiple team types.
- Manual or automatic free-text submission marking.
- Challenge hints.
- Team progress page.
- Challenge overview page.
- Limit category and challenge exposure to certain times.
- Challenge reveal on parent challenge solve (by any team).
- Optional signup restrictions based on email regex.
- Local or [Amazon S3](https://aws.amazon.com/s3/) challenge file upload.
- Optional automatic MD5 append to files.
- Admin management console with competition overview.
- Create/edit front page news.
- Arbitrary menu items and internal pages.
- Optional total number and time-based submission throttling.
- User management with IP correlation.
- Internal log for catching exceptions.
- [reCAPTCHA](https://www.google.com/recaptcha/) support.
- User-defined or auto-generated passwords on signup.
- User/Email/IP search.
- Configurable caching.
- Caching proxy (like [Cloudflare](https://www.cloudflare.com/)) aware (optional x-forwarded-for trust).
- Optional separate domain for static files.
- [Segment](https://segment.com/) analytics support.
- SMTP email support. Bulk or single email composition.
- TOTP two factor auth support.
- [CTF Time](https://ctftime.org/) compatible JSON scoreboard.
- Self-serve and admin password reset.
- and more ...

### Scaling
Mellivora scales well on Amazon Elastic Beanstalk and has support for S3 file storage.

### Performance
Mellivora is lightweight. And fast. Very fast. Want to run a large competition on an EC2 micro instance? No problem!? See [benchmarks.md](benchmarks.md) for some possibly unhelpful benchmarks.

### Installation
- Install the following packages:
- nginx-light
- php-fpm
- php-xml
- php-curl
- php-mysql
- php-mbstring
- composer
- mysql-server

- Setup mysql server with `mysql_secure_installation`
- remove anonymous users
- disable root login
- remove test database

- Copy repo contents to /var/www/ctfx/
- Run `composer install --no-dev --optimize-autoloader` under /var/www/ctfx
- Make the folder `writable` writable

- Setup website config at `/etc/nginx/sites-enabled/default`
- Remember to change the php-fpm socket to your current .sock file

- sudo mysql:
- CREATE DATABASE mellivora CHARACTER SET utf8 COLLATE utf8_general_ci;
- GRANT ALL PRIVILEGES ON mellivora.* TO 'mellivora'@'%' IDENTIFIED BY 'mellivora_pass'; # if you change the pass, make sure to also change it in `include/config/db.inc.php`
- sudo mysql < install/sql/001-mellivora.sql
- sudo mysql < install/sql/002-countries.sql

- Change the variables `MELLIVORA_CONFIG_SITE_URL` and `MELLIVORA_CONFIG_SITE_URL_STATIC_RESOURCES` under `include/config/config.inc.php` to your current server's accessible address

- Create your admin account on the website (and enable 2FA Authentication preferably)
- Logout of your account
- sudo mysql
- USE mellivora; UPDATE users SET class=100 WHERE id=1;

### License
This software is licenced under the [GNU General Public License v3 (GPL-3)](http://www.tldrlegal.com/license/gnu-general-public-license-v3-%28gpl-3%29). The "include/thirdparty/" directory contains third party code. Please read their LICENSE files for information on the software availability and distribution.