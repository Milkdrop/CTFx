CREATE DATABASE ctfx CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ctfx'@'%' IDENTIFIED BY 'ctfx_pass';
GRANT ALL PRIVILEGES ON ctfx.* TO 'ctfx'@'%';