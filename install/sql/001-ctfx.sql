USE ctfx;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE categories (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  description text NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE challenges (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  category smallint(5) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  description text NOT NULL DEFAULT '',
  authors varchar(255) NOT NULL DEFAULT '',
  flag text NOT NULL,
  case_insensitive_flag tinyint(1) NOT NULL DEFAULT '0',
  points int(10) signed NOT NULL,
  initial_points int(10) signed NOT NULL,
  minimum_points int(10) signed NOT NULL,
  solves_until_minimum int(10) unsigned NOT NULL,
  solves int(10) unsigned NOT NULL DEFAULT '0',
  exposed tinyint(1) NOT NULL DEFAULT '0',
  release_time int(10) unsigned NOT NULL DEFAULT '0',
  flaggable tinyint(1) NOT NULL DEFAULT '1',
  relies_on int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id),
  KEY category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE hints (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  challenge int(10) unsigned NOT NULL,
  content text NOT NULL,
  PRIMARY KEY (id),
  KEY challenge (challenge)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE files (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  challenge int(10) unsigned NOT NULL,
  name varchar(255) NOT NULL,
  url text NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY challenge (challenge)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE targets (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  challenge int(10) unsigned NOT NULL,
  url text NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY challenge (challenge)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  team_name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  passhash varchar(255) NOT NULL,
  country_id smallint(5) unsigned NOT NULL,
  competing tinyint(1) NOT NULL DEFAULT '1',
  admin tinyint(1) NOT NULL DEFAULT '0',
  last_active int(10) unsigned NOT NULL DEFAULT '0',
  extra_points int(10) signed NOT NULL DEFAULT '0',
  2fa_status enum('disabled','generated','enabled') NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  UNIQUE KEY team_name (team_name)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE countries (
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  country_name varchar(50) NOT NULL DEFAULT '',
  country_code char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  UNIQUE KEY short (country_code)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE exceptions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  added_by int(10) unsigned NOT NULL,
  message varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  trace text NOT NULL,
  `file` varchar(255) NOT NULL,
  line int(10) unsigned NOT NULL,
  user_ip int(10) unsigned NOT NULL,
  user_agent text NOT NULL,
  unread BOOLEAN NOT NULL DEFAULT TRUE,
PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE ip_log (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  user_id int(10) unsigned NOT NULL,
  last_used int(10) unsigned NOT NULL,
  ip int(10) unsigned NOT NULL,
  times_used int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY user_ip (user_id,ip)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE news (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  body text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE submissions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(10) unsigned NOT NULL,
  challenge int(10) unsigned NOT NULL,
  user_id int(10) unsigned NOT NULL,
  flag text NOT NULL,
  correct tinyint(1) NOT NULL DEFAULT '0',
  solve_position int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY challenge (challenge),
  KEY user_id (user_id),
  KEY challenge_user_id (challenge,user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE two_factor_auth (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(10) unsigned NOT NULL,
  secret char(32) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY user_id (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

