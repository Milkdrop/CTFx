mysqld_safe &
while ! mysql -e "show databases;"; do sleep 1; done
cd /var/www/ctfx/install/sql
mysql < 000-db.sql
mysql < 001-ctfx.sql
mysql < 002-countries.sql

cd /var/www/ctfx/install/docker
mysql < 003-custom-db.sql
/etc/init.d/$(ls /etc/init.d | grep php) start
nginx

while true; do sleep 1000; done