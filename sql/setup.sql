CREATE DATABASE gm CHARACTER SET utf8 COLLATE utf8_bin;

SET NAMES utf8;

CONNECT gm;

DROP TABLE IF EXISTS user;
CREATE TABLE user (
	id			INT				AUTO_INCREMENT	PRIMARY KEY,
	username	VARCHAR(64)		NOT NULL,
	email		VARCHAR(128)	NOT NULL,
	password	CHAR(128)		NOT NULL,
	salt		CHAR(128)		NOT NULL, 
	fname		VARCHAR(64),
	lname		VARCHAR(64),
	picture		VARCHAR(100),
	phone		VARCHAR(16),
	cp			VARCHAR(6),
	admin		BOOLEAN			NOT NULL		DEFAULT 0,
	active		BOOLEAN			NOT NULL		DEFAULT 0
);

DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
	user	INT			NOT NULL	references user.id,
	dtime	VARCHAR(30)	NOT NULL
);

DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions(
	user		INT			PRIMARY KEY references user.id,
	access		BOOLEAN		NOT NULL	DEFAULT 1,
	post		BOOLEAN		NOT NULL	DEFAULT 1,
	settings	BOOLEAN		NOT NULL	DEFAULT 1,
	money_r		BOOLEAN		NOT NULL	DEFAULT 0,
	money_w		BOOLEAN		NOT NULL	DEFAULT 0,
	manager		BOOLEAN		NOT NULL	DEFAULT 0
);

DROP TABLE IF EXISTS post;
CREATE TABLE post (
	id			INT				AUTO_INCREMENT	PRIMARY KEY,
	permalink	VARCHAR(300)	NOT NULL,
	title_es	VARCHAR(300)	NOT NULL,
	title_en	VARCHAR(300),
	title_eu	VARCHAR(300),
	text_es		VARCHAR(5000)	NOT NULL,
	text_en		VARCHAR(5000),
	text_eu		VARCHAR(5000),
	user		INT				NOT NULL		DEFAULT 1	REFERENCES user.id,
	dtime		TIMESTAMP		NOT NULL		DEFAULT now(),
	visible		BOOLEAN			NOT NULL		DEFAULT	1,
	comments	BOOLEAN			NOT NULL		DEFAULT 1
);

DROP TABLE IF EXISTS post_tag;
CREATE TABLE post_tag (
	post	INT			NOT NULL	REFERENCES post.id,
	tag		VARCHAR(64)	NOT NULL,
	PRIMARY KEY (post, tag)
);

DROP TABLE IF EXISTS post_image;
CREATE TABLE post_image (
	id		INT				AUTO_INCREMENT	PRIMARY KEY,
	post	INT				NOT NULL	REFERENCES post.id,
	image	VARCHAR(300)	NOT NULL,
	idx		INT				NOT NULL
);

DROP TABLE IF EXISTS post_comment;
CREATE TABLE post_comment (
	id			INT				AUTO_INCREMENT			PRIMARY KEY,
	post		INT				NOT NULL				REFERENCES post(id),
	text		VARCHAR(5000)	NOT NULL,
	dtime		TIMESTAMP		NOT NULL				DEFAULT now(),
	user		INT				REFERENCES user.id,
	username	VARCHAR(200),
	lang		VARCHAR(10),
	approved	BOOLEAN			NOT NULL				DEFAULT 1,
	visit		INT				REFERENCES stat_visit.id,
	app			VARCHAR(24)
);

DROP TABLE IF EXISTS place;
CREATE TABLE place (
	id			INT				AUTO_INCREMENT  PRIMARY KEY,
	name_es		VARCHAR(100)	NOT NULL,
	name_en		VARCHAR(100),
	name_eu		VARCHAR(100),
	address_es	VARCHAR(200)	NOT NULL,
	address_en	VARCHAR(200),
	address_eu	VARCHAR(200),
	cp			VARCHAR(10),
	lat			DOUBLE,
	lon			DOUBLE
);

DROP TABLE IF EXISTS sponsor;
CREATE TABLE sponsor (
	id				INT				NOT NULL	AUTO_INCREMENT  PRIMARY KEY,
	name_es			VARCHAR(100)    NOT NULL,
	name_en			VARCHAR(100),
	name_eu			VARCHAR(100),
	text_es			VARCHAR(1000),
	text_en			VARCHAR(1000),
	text_eu			VARCHAR(1000),
	image			VARCHAR(100),
	address_es		VARCHAR(200),
	address_en		VARCHAR(200),
	address_eu		VARCHAR(200),
	link			VARCHAR(300),
	lat				DOUBLE,
	lon				DOUBLE,
	ammount			INT,
	print 			INT				DEFAULT 0,
	print_static	INT				DEFAULT 0,
	click			INT				DEFAULT 0
);

DROP TABLE IF EXISTS stat_visit;
CREATE TABLE stat_visit (
	id		INT				NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	ip		VARCHAR(50),
	uagent	VARCHAR(400),
	os		VARCHAR(150),
	browser	VARCHAR(150)
);

DROP TABLE IF EXISTS stat_view;
CREATE TABLE stat_view (
	id		INT				NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	visit	INT				NOT NULL	REFERENCES stat_visit.id,
	section	VARCHAR(80)		NOT NULL,
	entry	VARCHAR(100)	NOT NULL,
	dtime	TIMESTAMP		NOT NULL	DEFAULT now()
);
CREATE INDEX visit_dtime ON stat_view(visit, dtime);

DROP TABLE IF EXISTS sync;
CREATE TABLE sync (
	id				INT			NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	dtime			TIMESTAMP	NOT NULL	DEFAULT now(),
	client			VARCHAR(64),
	user			VARCHAR(64),
	action			VARCHAR(12),
	section			VARCHAR(32),
	version_from	INT			NOT NULL	DEFAULT 0,
	version_to		INT			NOT NULL	DEFAULT -1,
	fg				INT			NOT NULL	DEFAULT 1,
	format			VARCHAR(24),
	error			VARCHAR(256),
	ip				VARCHAR(128),
	os				VARCHAR(128),
	uagent			VARCHAR(256)
);

DROP TABLE IF EXISTS stat_sync;
CREATE TABLE stat_sync (
	id		INT			NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	dtime	TIMESTAMP	NOT NULL	DEFAULT now(),
	user	VARCHAR(64)	NOT NULL,
	fg		INT			NOT NULL	DEFAULT 1,
	os		VARCHAR(64)	NOT NULL,
	ip		VARCHAR(50)	NOT NULL,
	synced	INT			NOT NULL	DEFAULT 0
);

DROP TABLE IF EXISTS photo;
CREATE TABLE photo (
	id				INT				NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	file			VARCHAR(100)	NOT NULL	UNIQUE,
	permalink		VARCHAR(100)	NOT NULL	UNIQUE,
	title_es		VARCHAR(100),
	title_en		VARCHAR(100),
	title_eu		VARCHAR(100),
	description_es	VARCHAR(1000),
	description_en	VARCHAR(1000),
	description_eu	VARCHAR(1000),
	dtime			DATETIME,
	uploaded		TIMESTAMP		NOT NULL	DEFAULT now(),
	place			INT				REFERENCES place.id,
	width			INT,
	height			INT,
	size			BIGINT,
	approved		BOOLEAN			NOT NULL	DEFAULT 1,
	user			INT				NOT NULL	DEFAULT 1	REFERENCES user.id,
	username		VARCHAR(64)
);

DROP TABLE IF EXISTS album;
CREATE TABLE album (
	id				INT				NOT NULL	AUTO_INCREMENT	PRIMARY KEY,
	permalink		VARCHAR(100)	NOT NULL,
	title_es		VARCHAR(100)	NOT NULL,
	title_en		VARCHAR(100),
	title_eu		VARCHAR(100),
	description_es	VARCHAR(1000),
	description_en	VARCHAR(1000),
	description_eu	VARCHAR(1000),
	user			INT				NOT NULL	DEFAULT 1	REFERENCES user.id,
	open			BOOLEAN			NOT NULL	DEFAULT 1,
	dtime			TIMESTAMP		NOT NULL	DEFAULT now()
);

DROP TABLE IF EXISTS photo_album;
CREATE TABLE photo_album (
	photo	INT	NOT NULL	REFERENCES photo.id,
	album	INT	NOT NULL	REFERENCES album.id,
	PRIMARY KEY (photo, album)
);

DROP TABLE IF EXISTS photo_comment;
CREATE TABLE photo_comment (
	id			INT				AUTO_INCREMENT			PRIMARY KEY,
	photo		INT				NOT NULL				REFERENCES photo(id),
	text		VARCHAR(3000)	NOT NULL,
	dtime		TIMESTAMP		NOT NULL				DEFAULT now(),
	user		INT				REFERENCES user.id,
	username	VARCHAR(200),
	lang		VARCHAR(10),
	approved	BOOLEAN			NOT NULL				DEFAULT 1,
	visit		INT				REFERENCES stat_visit.id,
	app			VARCHAR(24)
);

DROP TABLE IF EXISTS activity;
CREATE TABLE activity (
	id			INT				AUTO_INCREMENT	PRIMARY KEY,
	permalink	VARCHAR(100)	NOT NULL,
	date		DATE			NOT NULL,
	city		VARCHAR(100)	NOT NULL	DEFAULT "Vitoria-Gasteiz",
	title_es	VARCHAR(300)	NOT NULL,
	title_en	VARCHAR(300),
	title_eu	VARCHAR(300),
	text_es		TEXT			NOT NULL,
	text_en		TEXT,
	text_eu		TEXT,
	after_es	VARCHAR(2000),
	after_en	VARCHAR(2000),
	after_eu	VARCHAR(2000),
	price		FLOAT			NOT NULL,
	inscription	BOOLEAN			NOT NULL		DEFAULT 1,
	people		INT				DEFAULT 0,
	max_people	INT,
	album		INT				REFERENCES album.id,
	user		INT				NOT NULL		DEFAULT 1	REFERENCES user.id,
	dtime		TIMESTAMP		NOT NULL		DEFAULT now(),
	visible		BOOLEAN			NOT NULL		DEFAULT	1,
	comments	BOOLEAN			NOT NULL		DEFAULT 1
);

DROP TABLE IF EXISTS activity_tag;
CREATE TABLE activity_tag (
	activity	INT			NOT NULL	REFERENCES activity.id,
	tag			VARCHAR(64)	NOT NULL,
	PRIMARY KEY (activity, tag)
);

DROP TABLE IF EXISTS activity_image;
CREATE TABLE activity_image (
	id			INT			AUTO_INCREMENT	PRIMARY KEY,
	activity	INT			NOT NULL	REFERENCES activity.id,
	image		VARCHAR(64)	NOT NULL,
	idx			INT			NOT NULL
);

DROP TABLE IF EXISTS activity_itinerary;
CREATE TABLE activity_itinerary (
	id				INT				AUTO_INCREMENT	PRIMARY KEY,
	activity		INT				NOT NULL	REFERENCES activity.id,
	name_es			VARCHAR(64)		NOT NULL,
	name_en			VARCHAR(64),
	name_eu			VARCHAR(64),
	description_es	VARCHAR(1000),
	description_en	VARCHAR(1000),
	description_eu	VARCHAR(1000),
	start			DATETIME		NOT NULL,
	end				DATETIME,
	place			INT				NOT NULL	REFERENCES place.id
);

DROP TABLE IF EXISTS activity_comment;
CREATE TABLE activity_comment (
	id			INT				AUTO_INCREMENT			PRIMARY KEY,
	activity	INT				NOT NULL				REFERENCES post(id),
	text		VARCHAR(5000)	NOT NULL,
	dtime		TIMESTAMP		NOT NULL				DEFAULT now(),
	user		INT				REFERENCES user.id,
	username	VARCHAR(200),
	lang		VARCHAR(10),
	approved	BOOLEAN			NOT NULL				DEFAULT 1,
	visit		INT				REFERENCES stat_visit.id,
	app			VARCHAR(24)
);

DROP TABLE IF EXISTS festival;
CREATE TABLE festival (
	id			INT				AUTO_INCREMENT	PRIMARY KEY,
	year		INT				UNIQUE,
	text_es		VARCHAR(3000)	NOT NULL,
	text_en		VARCHAR(3000),
	text_eu		VARCHAR(3000),
	summary_es	VARCHAR(3000),
	summary_en	VARCHAR(3000),
	summary_eu	VARCHAR(3000),
	img			VARCHAR(2000)
);

DROP TABLE IF EXISTS festival_day;
CREATE TABLE festival_day (
	id		INT			AUTO_INCREMENT	PRIMARY KEY,
	date	DATE		NOT NULL,
	name_es	VARCHAR(60)	NOT NULL,
	name_en	VARCHAR(60),
	name_eu	VARCHAR(60),
	price	INT			NOT NULL
);

DROP TABLE IF EXISTS festival_offer;
CREATE TABLE festival_offer (
	id				INT			AUTO_INCREMENT	PRIMARY KEY,
	year			INT,
	name_es			VARCHAR(60)	NOT NULL,
	name_en			VARCHAR(60),
	name_eu			VARCHAR(60),
	description_es	VARCHAR(500),
	description_en	VARCHAR(500),
	description_eu	VARCHAR(500),
	days			INT			NOT NULL,
	price			INT			NOT NULL
);

DROP TABLE IF EXISTS location;
CREATE TABLE location (
	id		INT			AUTO_INCREMENT 	PRIMARY KEY,
	action	VARCHAR(1)	NOT NULL,
	dtime	TIMESTAMP	NOT NULL		DEFAULT now(),
	lat		DOUBLE,
	lon		DOUBLE,
	start	INT,
	user	INT			NOT NULL		REFERENCES user.id
);

DROP TABLE IF EXISTS notification;
CREATE TABLE notification (
	id			INT				AUTO_INCREMENT 	PRIMARY KEY,
	user		INT				NOT NULL		REFERENCES user.id,
	dtime		TIMESTAMP		NOT NULL		DEFAULT now(),
	duration	INT				NOT NULL		DEFAULT 60,
	action		VARCHAR(20)		NOT NULL		DEFAULT 'message',
	title_es	VARCHAR(100)	NOT NULL,
	title_en	VARCHAR(100),
	title_eu	VARCHAR(100),
	text_es		VARCHAR(1000),
	text_en		VARCHAR(1000),
	text_eu		VARCHAR(1000),
	internal	BOOLEAN			NOT NULL		DEFAULT 0
);

DROP TABLE IF EXISTS people;
CREATE TABLE people (
	id		INT				AUTO_INCREMENT	PRIMARY KEY,
	name_es	VARCHAR(100)	NOT NULL,
	name_en	VARCHAR(100),
	name_eu	VARCHAR(100),
	link	VARCHAR(300)
);

DROP TABLE IF EXISTS festival_event;
CREATE TABLE festival_event (
	id				INT				AUTO_INCREMENT	PRIMARY KEY,
	gm				BOOLEAN			DEFAULT 0,
	title_es		VARCHAR(200)	NOT NULL,
	title_en		VARCHAR(200),
	title_eu		VARCHAR(200),
	description_es	VARCHAR(5000),
	description_en	VARCHAR(5000),
	description_eu	VARCHAR(5000),
	host			INT				REFERENCES people.id,
	place			INT				REFERENCES place.id,
	start			DATETIME,
	end				DATETIME
);

DROP TABLE IF EXISTS festival_event_image;
CREATE TABLE festival_event_image (
	id			INT			AUTO_INCREMENT	PRIMARY KEY,
	event		INT			NOT NULL	REFERENCES activity.id,
	image		VARCHAR(64)	NOT NULL,
	idx			INT			NOT NULL
);

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
	name		VARCHAR(64)	PRIMARY KEY,
	value		VARCHAR(64)	NOT NULL,
	user		INT			REFERENCES user.id,
	changed		TIMESTAMP	NOT NULL		DEFAULT now()
);

DROP TABLE IF EXISTS version;
CREATE TABLE version (
	section		VARCHAR(12)	PRIMARY KEY,
	version		INT			DEFAULT 1
);

DROP TABLE IF EXISTS member;
CREATE TABLE member (
	id			INT				AUTO_INCREMENT	PRIMARY KEY,
	name		VARCHAR(64)		NOT NULL,
	lname		VARCHAR(128)	NOT NULL,
	lname2		VARCHAR(128),
	alias		VARCHAR(32),
	dni			VARCHAR(16),
	bdate		DATE,
	phone		VARCHAR(32),
	mail		VARCHAR(128),
	address		VARCHAR(512),
	jdate		DATE,
	joined		VARCHAR(16),
	board		BOOLEAN			NOT NULL	DEFAULT 0,
	facebook	VARCHAR(1024),
	twitter		VARCHAR(1024),
	googleplus	VARCHAR(1024)
);

DROP TABLE IF EXISTS member_activity;
CREATE TABLE member_activity(
	member		INT		REFERENCES member.id,
	activity	INT		REFERENCES activity.id,
	paid		BOOLEAN	NOT NULL	DEFAULT 1,
	PRIMARY KEY(member, activity)
);

DROP TABLE IF EXISTS intolerance;
CREATE TABLE intolerance(
	member	INT				REFERENCES member.id,
	element	VARCHAR(128),
	PRIMARY KEY(member, element)
);

DROP TABLE IF EXISTS member_day;
CREATE TABLE member_day(
	member	INT		REFERENCES member.id,
	day		INT		REFERENCES festival_day.id,
	paid	BOOLEAN	NOT NULL	DEFAULT 1,
	PRIMARY KEY (member, day)
);

DROP TABLE IF EXISTS transac_category;
CREATE TABLE transac_category(
	id						INT				AUTO_INCREMENT PRIMARY KEY,
	title_internal			VARCHAR(510)	NOT NULL,
	title_es				VARCHAR(510)	NOT NULL,
	title_en				VARCHAR(510),
	title_eu				VARCHAR(510),
	description_internal	VARCHAR(510)	NOT NULL,
	description_es			VARCHAR(510)	NOT NULL,
	description_en			VARCHAR(510),
	description_eu			VARCHAR(510)
);

DROP TABLE IF EXISTS transact;
CREATE TABLE transact(
	id				INT				AUTO_INCREMENT PRIMARY KEY,
	dtime			TIMESTAMP		NOT NULL	DEFAULT now(),
	ammount			FLOAT			NOT NULL,
	user			INT				NOT NULL	REFERENCES user.id,
	concept			VARCHAR(1024),
	category		INT				NOT NULL	REFERENCES transac_category.id
);

DROP TABLE IF EXISTS translation;
CREATE TABLE translation(
	id				INT				AUTO_INCREMENT PRIMARY KEY,
	dtime			TIMESTAMP		NOT NULL	DEFAULT now(),
	username		VARCHAR(255)	NOT NULL	DEFAULT 'unknown',
	tab				VARCHAR(255)	NOT NULL,
	field			VARCHAR(255)	NOT NULL,
	eid				INT				NOT NULL,
	lang			VARCHAR(2)		NOT NULL,
	text			VARCHAR(9000)	NOT NULL,
	applied			DATETIME
);

#Set up two users for database. File dbusers.sql contains:
/*
GRANT SELECT ON gm.* TO 'XXXX'@'XXXX' IDENTIFIED BY 'XXXX';
GRANT SELECT, INSERT, DELETE, UPDATE ON gm.* TO 'XXXX'@'XXXX' IDENTIFIED BY 'XXXX';
*/
SOURCE dbusers.sql;

