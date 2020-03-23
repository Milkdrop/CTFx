FROM debian:stable-slim
RUN apt-get update
RUN apt-get install nano apt-utils procps -y
RUN apt-get upgrade -y
RUN apt-get install nginx php-fpm mariadb-server -y
RUN apt-get install composer php-fpm php-xml php-curl php-mysql php-mbstring php-pear -y

RUN rm -rf /var/www/*
RUN mkdir /var/www/ctfx

COPY include /var/www/ctfx/include
COPY install /var/www/ctfx/install
COPY composer.* /var/www/ctfx/
COPY writable /var/www/ctfx/writable
RUN chmod -R 777 /var/www/ctfx/writable

WORKDIR /var/www/ctfx
RUN composer install --no-dev --optimize-autoloader

RUN cp install/recommended_nginx_config /etc/nginx/nginx.conf
RUN sed -i 's/phpVERSION.HERE-fpm/'$(ls /etc/init.d/ | grep php)'/g' /etc/nginx/nginx.conf

RUN chmod 777 install/docker/entrypoint.sh

COPY htdocs /var/www/ctfx/htdocs
ENTRYPOINT install/docker/entrypoint.sh