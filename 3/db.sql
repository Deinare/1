CREATE TABLE application (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(128) NOT NULL DEFAULT '',
  year int(10) NOT NULL DEFAULT 0,
  ability_god int(1) NOT NULL DEFAULT 0,
  ability_fly int(1) NOT NULL DEFAULT 0,
  ability_idclip int(1) NOT NULL DEFAULT 0,
  ability_fireball int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE form_data (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    fio varchar(255) NOT NULL DEFAULT '',
    number varchar(11) NOT NULL DEFAULT 0,
    email varchar(255) NOT NULL DEFAULT '',
	date varchar(20) NOT NULL,
	radio varchar(12) NOT NULL DEFAULT '',
	bio text NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE languages(
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(128) NOT NULL DEFAULT '',
	PRIMARY KEY (id)
);

INSERT INTO languages SET name = 'C';

CREATE TABLE form_data_lang(
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	id_form int(10) NOT NULL,
	id_lang int(10) NOT NULL,
	PRIMARY KEY (id)
);