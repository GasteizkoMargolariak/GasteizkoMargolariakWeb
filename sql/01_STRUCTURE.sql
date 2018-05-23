CREATE DATABASE gm CHARACTER SET utf8 COLLATE utf8_bin;

SET NAMES utf8;

CONNECT gm;

DROP TABLE IF EXISTS user;
CREATE TABLE user (
  id       INT          AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64)  NOT NULL,
  email    VARCHAR(128) NOT NULL,
  password CHAR(128)    NOT NULL,
  salt     CHAR(128)    NOT NULL,
  fname    VARCHAR(64),
  lname    VARCHAR(64),
  picture  VARCHAR(100),
  phone    VARCHAR(16),
  cp       VARCHAR(6),
  admin    BOOLEAN      NOT NULL DEFAULT 0,
  active   BOOLEAN      NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
  user  INT         NOT NULL references user.id,
  dtime VARCHAR(30) NOT NULL
);

DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions(
  user     INT     PRIMARY KEY references user.id,
  access   BOOLEAN NOT NULL DEFAULT 1,
  post     BOOLEAN NOT NULL DEFAULT 1,
  settings BOOLEAN NOT NULL DEFAULT 1,
  money_r  BOOLEAN NOT NULL DEFAULT 0,
  money_w  BOOLEAN NOT NULL DEFAULT 0,
  manager  BOOLEAN NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS post;
CREATE TABLE post (
  id        INT           AUTO_INCREMENT PRIMARY KEY,
  permalink VARCHAR(300)  NOT NULL,
  title_es  VARCHAR(300)  NOT NULL,
  title_en  VARCHAR(300),
  title_eu  VARCHAR(300),
  text_es   VARCHAR(5000) NOT NULL,
  text_en   VARCHAR(5000),
  text_eu   VARCHAR(5000),
  user      INT           NOT NULL DEFAULT 1 REFERENCES user.id,
  dtime     TIMESTAMP     NOT NULL DEFAULT now(),
  visible   BOOLEAN       NOT NULL DEFAULT 1,
  comments  BOOLEAN       NOT NULL DEFAULT 1
);

DROP TABLE IF EXISTS post_tag;
CREATE TABLE post_tag (
  post INT         NOT NULL REFERENCES post.id,
  tag  VARCHAR(64) NOT NULL,
  PRIMARY KEY (post, tag)
);

DROP TABLE IF EXISTS post_image;
CREATE TABLE post_image (
  id    INT          AUTO_INCREMENT PRIMARY KEY,
  post  INT          NOT NULL REFERENCES post.id,
  image VARCHAR(300) NOT NULL,
  idx   INT          NOT NULL
);

DROP TABLE IF EXISTS post_comment;
CREATE TABLE post_comment (
  id       INT           AUTO_INCREMENT PRIMARY KEY,
  post     INT           NOT NULL REFERENCES post(id),
  text     VARCHAR(5000) NOT NULL,
  dtime    TIMESTAMP     NOT NULL DEFAULT now(),
  user     INT           REFERENCES user.id,
  username VARCHAR(200),
  lang     VARCHAR(10),
  approved BOOLEAN       NOT NULL DEFAULT 1,
  visit    INT           REFERENCES stat_visit.id,
  app      VARCHAR(24),
  app_user VARCHAR(64)
);

DROP TABLE IF EXISTS place;
CREATE TABLE place (
  id         INT          AUTO_INCREMENT PRIMARY KEY,
  name_es    VARCHAR(100) NOT NULL,
  name_en    VARCHAR(100),
  name_eu    VARCHAR(100),
  address_es VARCHAR(200) NOT NULL,
  address_en VARCHAR(200),
  address_eu VARCHAR(200),
  cp         VARCHAR(10),
  lat        DOUBLE,
  lon        DOUBLE
);

DROP TABLE IF EXISTS route;
CREATE TABLE route (
  id    INT         AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(32),
  mins  INT,
  c_lat DOUBLE      NOT NULL,
  c_lon DOUBLE      NOT NULL,
  zoom  INT         DEFAULT 16
);

DROP TABLE IF EXISTS route_point;
CREATE TABLE route_point (
  id      INT     AUTO_INCREMENT PRIMARY KEY,
  route   INT     NOT NULL DEFAULT 0,
  part    INT     NOT NULL,
  lat_o   DOUBLE,
  lon_o   DOUBLE,
  lat_d   DOUBLE,
  lon_d   DOUBLE,
  mins    INT,
  visible BOOLEAN NOT NULL DEFAULT 1
);

DROP TABLE IF EXISTS sponsor;
CREATE TABLE sponsor (
  id           INT           NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name_es      VARCHAR(100)  NOT NULL,
  name_en      VARCHAR(100),
  name_eu      VARCHAR(100),
  text_es      VARCHAR(1000),
  text_en      VARCHAR(1000),
  text_eu      VARCHAR(1000),
  image        VARCHAR(100),
  local        BOOLEAN       NOT NULL DEFAULT 1,
  address_es   VARCHAR(200),
  address_en   VARCHAR(200),
  address_eu   VARCHAR(200),
  link         VARCHAR(300),
  lat          DOUBLE,
  lon          DOUBLE,
  ammount      INT,
  print        INT           NOT NULL DEFAULT 0,
  print_static INT           NOT NULL DEFAULT 0,
  click        INT           NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS stat_visit;
CREATE TABLE stat_visit (
  id      INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ip      VARCHAR(50),
  uagent  VARCHAR(400),
  os      VARCHAR(150),
  browser VARCHAR(150)
);

DROP TABLE IF EXISTS stat_view;
CREATE TABLE stat_view (
  id       INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
  visit   INT          NOT NULL REFERENCES stat_visit.id,
  section VARCHAR(80)  NOT NULL,
  entry   VARCHAR(100) NOT NULL,
  dtime   TIMESTAMP    NOT NULL DEFAULT now()
);
CREATE INDEX visit_dtime ON stat_view(visit, dtime);

DROP TABLE IF EXISTS sync;
CREATE TABLE sync (
  id     INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
  dtime  TIMESTAMP   NOT NULL DEFAULT now(),
  client VARCHAR(64),
  user   VARCHAR(64),
  fg     INT         NOT NULL DEFAULT 0,
  synced BOOLEAN     NOT NULL DEFAULT 0,
  error  VARCHAR(256),
  ip     VARCHAR(128),
  os     VARCHAR(128),
  uagent VARCHAR(256)
);

DROP TABLE IF EXISTS stat_sync;
CREATE TABLE stat_sync (
  id     INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
  dtime  TIMESTAMP   NOT NULL DEFAULT now(),
  user   VARCHAR(64) NOT NULL,
  fg     INT         NOT NULL DEFAULT 1,
  os     VARCHAR(64) NOT NULL,
  ip     VARCHAR(50) NOT NULL,
  synced INT         NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS photo;
CREATE TABLE photo (
  id             INT           NOT NULL AUTO_INCREMENT PRIMARY KEY,
  file           VARCHAR(100)  NOT NULL UNIQUE,
  permalink      VARCHAR(100)  NOT NULL UNIQUE,
  title_es       VARCHAR(100),
  title_en       VARCHAR(100),
  title_eu       VARCHAR(100),
  description_es VARCHAR(1000),
  description_en VARCHAR(1000),
  description_eu VARCHAR(1000),
  dtime          DATETIME,
  uploaded       TIMESTAMP     NOT NULL DEFAULT now(),
  place          INT           REFERENCES place.id,
  width          INT,
  height         INT,
  size           BIGINT,
  approved       BOOLEAN       NOT NULL DEFAULT 1,
  user           INT           NOT NULL DEFAULT 1 REFERENCES user.id,
  username       VARCHAR(64)
);

DROP TABLE IF EXISTS album;
CREATE TABLE album (
  id             INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  permalink      VARCHAR(100) NOT NULL,
  title_es       VARCHAR(100) NOT NULL,
  title_en       VARCHAR(100),
  title_eu       VARCHAR(100),
  description_es VARCHAR(1000),
  description_en VARCHAR(1000),
  description_eu VARCHAR(1000),
  user           INT          NOT NULL DEFAULT 1 REFERENCES user.id,
  open           BOOLEAN      NOT NULL DEFAULT 1,
  dtime          TIMESTAMP    NOT NULL DEFAULT now()
);

DROP TABLE IF EXISTS photo_album;
CREATE TABLE photo_album (
  photo INT NOT NULL REFERENCES photo.id,
  album INT NOT NULL REFERENCES album.id,
  PRIMARY KEY (photo, album)
);

DROP TABLE IF EXISTS photo_comment;
CREATE TABLE photo_comment (
  id       INT           AUTO_INCREMENT PRIMARY KEY,
  photo    INT           NOT NULL  REFERENCES photo(id),
  text     VARCHAR(5000) NOT NULL,
  dtime    TIMESTAMP     NOT NULL DEFAULT now(),
  user     INT           REFERENCES user.id,
  username VARCHAR(200),
  lang     VARCHAR(10),
  approved BOOLEAN       NOT NULL DEFAULT 1,
  visit    INT           REFERENCES stat_visit.id,
  app      VARCHAR(24),
  app_user VARCHAR(64)
);

DROP TABLE IF EXISTS activity;
CREATE TABLE activity (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  permalink   VARCHAR(100) NOT NULL,
  date        DATE         NOT NULL,
  city        VARCHAR(100) NOT NULL DEFAULT "Vitoria-Gasteiz",
  title_es    VARCHAR(300) NOT NULL,
  title_en    VARCHAR(300),
  title_eu    VARCHAR(300),
  text_es     TEXT         NOT NULL,
  text_en     TEXT,
  text_eu     TEXT,
  after_es    VARCHAR(2000),
  after_en    VARCHAR(2000),
  after_eu    VARCHAR(2000),
  price       FLOAT         NOT NULL,
  inscription BOOLEAN       NOT NULL  DEFAULT 1,
  people      INT           DEFAULT 0,
  max_people  INT,
  album       INT           REFERENCES album.id,
  user        INT           NOT NULL DEFAULT 1 REFERENCES user.id,
  dtime       TIMESTAMP     NOT NULL DEFAULT now(),
  visible     BOOLEAN       NOT NULL DEFAULT 1,
  comments    BOOLEAN       NOT NULL DEFAULT 1
);

DROP TABLE IF EXISTS activity_tag;
CREATE TABLE activity_tag (
  activity INT         NOT NULL REFERENCES activity.id,
  tag      VARCHAR(64) NOT NULL,
  PRIMARY KEY (activity, tag)
);

DROP TABLE IF EXISTS activity_image;
CREATE TABLE activity_image (
  id       INT         AUTO_INCREMENT PRIMARY KEY,
  activity INT         NOT NULL REFERENCES activity.id,
  image    VARCHAR(64) NOT NULL,
  idx      INT         NOT NULL
);

DROP TABLE IF EXISTS activity_itinerary;
CREATE TABLE activity_itinerary (
  id             INT           AUTO_INCREMENT PRIMARY KEY,
  activity       INT           NOT NULL REFERENCES activity.id,
  name_es        VARCHAR(64)   NOT NULL,
  name_en        VARCHAR(64),
  name_eu        VARCHAR(64),
  description_es VARCHAR(1000),
  description_en VARCHAR(1000),
  description_eu VARCHAR(1000),
  start          DATETIME      NOT NULL,
  end            DATETIME,
  place          INT            REFERENCES place.id,
  route          INT            REFERENCES route.id
);

DROP TABLE IF EXISTS activity_comment;
CREATE TABLE activity_comment (
  id        INT           AUTO_INCREMENT PRIMARY KEY,
  activity  INT           NOT NULL  REFERENCES post(id),
  text      VARCHAR(5000) NOT NULL,
  dtime     TIMESTAMP     NOT NULL DEFAULT now(),
  user      INT           REFERENCES user.id,
  username  VARCHAR(200),
  lang      VARCHAR(10),
  approved  BOOLEAN        NOT NULL DEFAULT 1,
  visit     INT            REFERENCES stat_visit.id,
  app       VARCHAR(24),
  app_user  VARCHAR(64)
);

DROP TABLE IF EXISTS festival;
CREATE TABLE festival (
  id         INT           AUTO_INCREMENT PRIMARY KEY,
  year       INT           UNIQUE,
  text_es    VARCHAR(3000) NOT NULL,
  text_en    VARCHAR(3000),
  text_eu    VARCHAR(3000),
  summary_es VARCHAR(3000),
  summary_en VARCHAR(3000),
  summary_eu VARCHAR(3000),
  img        VARCHAR(2000)
);

DROP TABLE IF EXISTS festival_day;
CREATE TABLE festival_day (
  id         INT         AUTO_INCREMENT PRIMARY KEY,
  date       DATE        NOT NULL,
  name_es    VARCHAR(60) NOT NULL,
  name_en    VARCHAR(60),
  name_eu    VARCHAR(60),
  price      INT         NOT NULL,
  people     INT         NOT NULL DEFAULT 0,
  max_people INT         NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS festival_offer;
CREATE TABLE festival_offer (
  id             INT          AUTO_INCREMENT PRIMARY KEY,
  year           INT,
  name_es        VARCHAR(60)  NOT NULL,
  name_en        VARCHAR(60),
  name_eu        VARCHAR(60),
  description_es VARCHAR(500),
  description_en VARCHAR(500),
  description_eu VARCHAR(500),
  days           INT          NOT NULL,
  price          INT          NOT NULL
);

DROP TABLE IF EXISTS location;
CREATE TABLE location (
  id     INT        AUTO_INCREMENT PRIMARY KEY,
  action VARCHAR(1) NOT NULL,
  dtime  TIMESTAMP  NOT NULL  DEFAULT now(),
  lat    DOUBLE,
  lon    DOUBLE,
  start  INT,
  user   INT        NOT NULL REFERENCES user.id
);

DROP TABLE IF EXISTS notification;
CREATE TABLE notification (
  id       INT           AUTO_INCREMENT PRIMARY KEY,
  user     INT           NOT NULL REFERENCES user.id,
  dtime    TIMESTAMP     NOT NULL DEFAULT now(),
  duration INT           NOT NULL DEFAULT 60,
  action   VARCHAR(20)   NOT NULL DEFAULT 'message',
  title_es VARCHAR(100)  NOT NULL,
  title_en VARCHAR(100),
  title_eu VARCHAR(100),
  text_es  VARCHAR(1000),
  text_en  VARCHAR(1000),
  text_eu  VARCHAR(1000),
  internal BOOLEAN       NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS people;
CREATE TABLE people (
  id      INT          AUTO_INCREMENT PRIMARY KEY,
  name_es VARCHAR(100) NOT NULL,
  name_en VARCHAR(100),
  name_eu VARCHAR(100),
  link    VARCHAR(300)
);

DROP TABLE IF EXISTS festival_event;
CREATE TABLE festival_event (
  id             INT           AUTO_INCREMENT PRIMARY KEY,
  gm             BOOLEAN       DEFAULT 0,
  title_es       VARCHAR(200)  NOT NULL,
  title_en       VARCHAR(200),
  title_eu       VARCHAR(200),
  description_es VARCHAR(5000),
  description_en VARCHAR(5000),
  description_eu VARCHAR(5000),
  host           INT           REFERENCES people.id,
  sponsor        INT           REFERENCES people.id,
  place          INT           REFERENCES place.id,
  route          INT           REFERENCES route.id,
  start          DATETIME      NOT NULL,
  end            DATETIME,
  interest       INT           NOT NULL DEFAULT 2
);

DROP TABLE IF EXISTS festival_event_image;
CREATE TABLE festival_event_image (
  id    INT         AUTO_INCREMENT PRIMARY KEY,
  event INT         NOT NULL REFERENCES activity.id,
  image VARCHAR(64) NOT NULL,
  idx   INT         NOT NULL
);

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  name    VARCHAR(64) PRIMARY KEY,
  value   VARCHAR(64) NOT NULL,
  user    INT         REFERENCES user.id,
  changed TIMESTAMP   NOT NULL  DEFAULT now()
);

DROP TABLE IF EXISTS version;
CREATE TABLE version (
  section VARCHAR(128) PRIMARY KEY,
  version INT          NOT NULL DEFAULT 1
);

DROP TABLE IF EXISTS member;
CREATE TABLE member (
  id         INT           AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(64)   NOT NULL,
  lname      VARCHAR(128)  NOT NULL,
  lname2     VARCHAR(128),
  alias      VARCHAR(32),
  dni        VARCHAR(16),
  bdate      DATE,
  phone      VARCHAR(32),
  mail       VARCHAR(128),
  address    VARCHAR(512),
  jdate      DATE,
  joined     VARCHAR(16),
  board      BOOLEAN       NOT NULL DEFAULT 0,
  facebook   VARCHAR(1024),
  twitter    VARCHAR(1024),
  googleplus VARCHAR(1024)
);

DROP TABLE IF EXISTS member_activity;
CREATE TABLE member_activity(
  member   INT     REFERENCES member.id,
  activity INT     REFERENCES activity.id,
  paid     BOOLEAN NOT NULL DEFAULT 1,
  PRIMARY KEY(member, activity)
);

DROP TABLE IF EXISTS intolerance;
CREATE TABLE intolerance(
  member  INT          REFERENCES member.id,
  element VARCHAR(128),
  PRIMARY KEY(member, element)
);

DROP TABLE IF EXISTS member_day;
CREATE TABLE member_day(
  member INT     REFERENCES member.id,
  day    INT     REFERENCES festival_day.id,
  paid   BOOLEAN NOT NULL DEFAULT 1,
  PRIMARY KEY (member, day)
);

DROP TABLE IF EXISTS transac_category;
CREATE TABLE transac_category(
  id                   INT          AUTO_INCREMENT PRIMARY KEY,
  title_internal       VARCHAR(510) NOT NULL,
  title_es             VARCHAR(510) NOT NULL,
  title_en             VARCHAR(510),
  title_eu             VARCHAR(510),
  description_internal VARCHAR(510) NOT NULL,
  description_es       VARCHAR(510) NOT NULL,
  description_en       VARCHAR(510),
  description_eu       VARCHAR(510)
);

DROP TABLE IF EXISTS transact;
CREATE TABLE transact(
  id                INT                AUTO_INCREMENT PRIMARY KEY,
  dtime            TIMESTAMP        NOT NULL DEFAULT now(),
  ammount            FLOAT            NOT NULL,
  user            INT                NOT NULL REFERENCES user.id,
  concept            VARCHAR(1024),
  category        INT                NOT NULL REFERENCES transac_category.id
);

DROP TABLE IF EXISTS translation;
CREATE TABLE translation(
  id       INT           AUTO_INCREMENT PRIMARY KEY,
  dtime    TIMESTAMP     NOT NULL DEFAULT now(),
  username VARCHAR(255)  NOT NULL DEFAULT 'unknown',
  tab      VARCHAR(255)  NOT NULL,
  field    VARCHAR(255)  NOT NULL,
  eid      INT           NOT NULL,
  lang     VARCHAR(2)    NOT NULL,
  text     VARCHAR(9000) NOT NULL,
  applied  DATETIME
);


# Create views for festival_event
CREATE OR REPLACE VIEW festival_event_gm AS SELECT id, title_es, title_en, title_eu, description_es, description_en, description_eu, host, sponsor, place, route, start, end, interest FROM festival_event WHERE gm = 1;
CREATE OR REPLACE VIEW festival_event_city AS SELECT id, title_es, title_en, title_eu, description_es, description_en, description_eu, host, sponsor, place, route, start, end, interest FROM festival_event WHERE gm = 0;


# Triggers to update versions
DELIMITER //

CREATE OR REPLACE PROCEDURE version_up (IN section_name VARCHAR(128))
  BEGIN
  UPDATE version SET version = version + 1 WHERE section = section_name;
  END//

CREATE OR REPLACE TRIGGER version_i_activity AFTER INSERT ON activity FOR EACH ROW
  BEGIN
  CALL version_up('activity');
  END//
CREATE OR REPLACE TRIGGER version_d_activity AFTER DELETE ON activity FOR EACH ROW
  BEGIN
  CALL version_up('activity');
  END//
CREATE OR REPLACE TRIGGER version_u_activity AFTER UPDATE ON activity FOR EACH ROW
  BEGIN
  CALL version_up('activity');
  END//
CREATE OR REPLACE TRIGGER version_i_activity_comment AFTER INSERT ON activity_comment FOR EACH ROW
  BEGIN
  CALL version_up('activity_comment');
  END//
CREATE OR REPLACE TRIGGER version_d_activity_comment AFTER DELETE ON activity_comment FOR EACH ROW
  BEGIN
  CALL version_up('activity_comment');
  END//
CREATE OR REPLACE TRIGGER version_u_activity_comment AFTER UPDATE ON activity_comment FOR EACH ROW
  BEGIN
  CALL version_up('activity_comment');
  END//
CREATE OR REPLACE TRIGGER version_i_activity_image AFTER INSERT ON activity_image FOR EACH ROW
  BEGIN
  CALL version_up('activity_image');
  END//
CREATE OR REPLACE TRIGGER version_d_activity_image AFTER DELETE ON activity_image FOR EACH ROW
  BEGIN
  CALL version_up('activity_image');
  END//
CREATE OR REPLACE TRIGGER version_u_activity_image AFTER UPDATE ON activity_image FOR EACH ROW
  BEGIN
  CALL version_up('activity_image');
  END//
CREATE OR REPLACE TRIGGER version_i_activity_itinerary AFTER INSERT ON activity_itinerary FOR EACH ROW
  BEGIN
  CALL version_up('activity_itinerary');
  END//
CREATE OR REPLACE TRIGGER version_d_activity_itinerary AFTER DELETE ON activity_itinerary FOR EACH ROW
  BEGIN
  CALL version_up('activity_itinerary');
  END//
CREATE OR REPLACE TRIGGER version_u_activity_itinerary AFTER UPDATE ON activity_itinerary FOR EACH ROW
  BEGIN
  CALL version_up('activity_itinerary');
  END//
CREATE OR REPLACE TRIGGER version_i_activity_tag AFTER INSERT ON activity_tag FOR EACH ROW
  BEGIN
  CALL version_up('activity_tag');
  END//
CREATE OR REPLACE TRIGGER version_d_activity_tag AFTER DELETE ON activity_tag FOR EACH ROW
  BEGIN
  CALL version_up('activity_tag');
  END//
CREATE OR REPLACE TRIGGER version_u_activity_tag AFTER UPDATE ON activity_tag FOR EACH ROW
  BEGIN
  CALL version_up('activity_tag');
  END//
CREATE OR REPLACE TRIGGER version_i_album AFTER INSERT ON album FOR EACH ROW
  BEGIN
  CALL version_up('album');
  END//
CREATE OR REPLACE TRIGGER version_d_album AFTER DELETE ON album FOR EACH ROW
  BEGIN
  CALL version_up('album');
  END//
CREATE OR REPLACE TRIGGER version_u_album AFTER UPDATE ON album FOR EACH ROW
  BEGIN
  CALL version_up('album');
  END//
CREATE OR REPLACE TRIGGER version_i_festival AFTER INSERT ON festival FOR EACH ROW
  BEGIN
  CALL version_up('festival');
  END//
CREATE OR REPLACE TRIGGER version_d_festival AFTER DELETE ON festival FOR EACH ROW
  BEGIN
  CALL version_up('festival');
  END//
CREATE OR REPLACE TRIGGER version_u_festival AFTER UPDATE ON festival FOR EACH ROW
  BEGIN
  CALL version_up('festival');
  END//
CREATE OR REPLACE TRIGGER version_i_festival_day AFTER INSERT ON festival_day FOR EACH ROW
  BEGIN
  CALL version_up('festival_day');
  END//
CREATE OR REPLACE TRIGGER version_d_festival_day AFTER DELETE ON festival_day FOR EACH ROW
  BEGIN
  CALL version_up('festival_day');
  END//
CREATE OR REPLACE TRIGGER version_u_festival_day AFTER UPDATE ON festival_day FOR EACH ROW
  BEGIN
  CALL version_up('festival_day');
  END//
CREATE OR REPLACE TRIGGER version_i_festival_event AFTER INSERT ON festival_event FOR EACH ROW
  BEGIN
  IF NEW.gm = 0 THEN
    CALL version_up('festival_event_city');
  ELSE
    CALL version_up('festival_event_gm');
  END IF;
  END//
CREATE OR REPLACE TRIGGER version_d_festival_event AFTER DELETE ON festival_event FOR EACH ROW
  BEGIN
  IF OLD.gm = 0 THEN
    CALL version_up('festival_event_city');
  ELSE
    CALL version_up('festival_event_gm');
  END IF;
  END//
CREATE OR REPLACE TRIGGER version_u_festival_event AFTER UPDATE ON festival_event FOR EACH ROW
  BEGIN
  IF NEW.gm = 0 THEN
    CALL version_up('festival_event_city');
  ELSE
    CALL version_up('festival_event_gm');
  END IF;
  END//
CREATE OR REPLACE TRIGGER version_i_festival_event_image AFTER INSERT ON festival_event_image FOR EACH ROW
  BEGIN
  CALL version_up('festival_event_image');
  END//
CREATE OR REPLACE TRIGGER version_d_festival_event_image AFTER DELETE ON festival_event_image FOR EACH ROW
  BEGIN
  CALL version_up('festival_event_image');
  END//
CREATE OR REPLACE TRIGGER version_u_festival_event_image AFTER UPDATE ON festival_event_image FOR EACH ROW
  BEGIN
  CALL version_up('festival_event_image');
  END//
CREATE OR REPLACE TRIGGER version_i_festival_offer AFTER INSERT ON festival_offer FOR EACH ROW
  BEGIN
  CALL version_up('festival_offer');
  END//
CREATE OR REPLACE TRIGGER version_d_festival_offer AFTER DELETE ON festival_offer FOR EACH ROW
  BEGIN
  CALL version_up('festival_offer');
  END//
CREATE OR REPLACE TRIGGER version_u_festival_offer AFTER UPDATE ON festival_offer FOR EACH ROW
  BEGIN
  CALL version_up('festival_offer');
  END//
CREATE OR REPLACE TRIGGER version_i_people AFTER INSERT ON people FOR EACH ROW
  BEGIN
  CALL version_up('people');
  END//
CREATE OR REPLACE TRIGGER version_d_people AFTER DELETE ON people FOR EACH ROW
  BEGIN
  CALL version_up('people');
  END//
CREATE OR REPLACE TRIGGER version_u_people AFTER UPDATE ON people FOR EACH ROW
  BEGIN
  CALL version_up('people');
  END//
CREATE OR REPLACE TRIGGER version_i_photo AFTER INSERT ON photo FOR EACH ROW
  BEGIN
  CALL version_up('photo');
  END//
CREATE OR REPLACE TRIGGER version_d_photo AFTER DELETE ON photo FOR EACH ROW
  BEGIN
  CALL version_up('photo');
  END//
CREATE OR REPLACE TRIGGER version_u_photo AFTER UPDATE ON photo FOR EACH ROW
  BEGIN
  CALL version_up('photo');
  END//
CREATE OR REPLACE TRIGGER version_i_photo_album AFTER INSERT ON photo_album FOR EACH ROW
  BEGIN
  CALL version_up('photo_album');
  END//
CREATE OR REPLACE TRIGGER version_d_photo_album AFTER DELETE ON photo_album FOR EACH ROW
  BEGIN
  CALL version_up('photo_album');
  END//
CREATE OR REPLACE TRIGGER version_u_photo_album AFTER UPDATE ON photo_album FOR EACH ROW
  BEGIN
  CALL version_up('photo_album');
  END//
CREATE OR REPLACE TRIGGER version_i_photo_comment AFTER INSERT ON photo_comment FOR EACH ROW
  BEGIN
  CALL version_up('photo_comment');
  END//
CREATE OR REPLACE TRIGGER version_d_photo_comment AFTER DELETE ON photo_comment FOR EACH ROW
  BEGIN
  CALL version_up('photo_comment');
  END//
CREATE OR REPLACE TRIGGER version_u_photo_comment AFTER UPDATE ON photo_comment FOR EACH ROW
  BEGIN
  CALL version_up('photo_comment');
  END//
CREATE OR REPLACE TRIGGER version_i_place AFTER INSERT ON place FOR EACH ROW
  BEGIN
  CALL version_up('place');
  END//
CREATE OR REPLACE TRIGGER version_d_place AFTER DELETE ON place FOR EACH ROW
  BEGIN
  CALL version_up('place');
  END//
CREATE OR REPLACE TRIGGER version_u_place AFTER UPDATE ON place FOR EACH ROW
  BEGIN
  CALL version_up('place');
  END//
CREATE OR REPLACE TRIGGER version_i_post AFTER INSERT ON post FOR EACH ROW
  BEGIN
  CALL version_up('post');
  END//
CREATE OR REPLACE TRIGGER version_d_post AFTER DELETE ON post FOR EACH ROW
  BEGIN
  CALL version_up('post');
  END//
CREATE OR REPLACE TRIGGER version_u_post AFTER UPDATE ON post FOR EACH ROW
  BEGIN
  CALL version_up('post');
  END//
CREATE OR REPLACE TRIGGER version_i_post_comment AFTER INSERT ON post_comment FOR EACH ROW
  BEGIN
  CALL version_up('post_comment');
  END//
CREATE OR REPLACE TRIGGER version_d_post_comment AFTER DELETE ON post_comment FOR EACH ROW
  BEGIN
  CALL version_up('post_comment');
  END//
CREATE OR REPLACE TRIGGER version_u_post_comment AFTER UPDATE ON post_comment FOR EACH ROW
  BEGIN
  CALL version_up('post_comment');
  END//
CREATE OR REPLACE TRIGGER version_i_post_image AFTER INSERT ON post_image FOR EACH ROW
  BEGIN
  CALL version_up('post_image');
  END//
CREATE OR REPLACE TRIGGER version_d_post_image AFTER DELETE ON post_image FOR EACH ROW
  BEGIN
  CALL version_up('post_image');
  END//
CREATE OR REPLACE TRIGGER version_u_post_image AFTER UPDATE ON post_image FOR EACH ROW
  BEGIN
  CALL version_up('post_image');
  END//
CREATE OR REPLACE TRIGGER version_i_post_tag AFTER INSERT ON post_tag FOR EACH ROW
  BEGIN
  CALL version_up('post_tag');
  END//
CREATE OR REPLACE TRIGGER version_d_post_tag AFTER DELETE ON post_tag FOR EACH ROW
  BEGIN
  CALL version_up('post_tag');
  END//
CREATE OR REPLACE TRIGGER version_u_post_tag AFTER UPDATE ON post_tag FOR EACH ROW
  BEGIN
  CALL version_up('post_tag');
  END//
CREATE OR REPLACE TRIGGER version_i_settings AFTER INSERT ON settings FOR EACH ROW
  BEGIN
  CALL version_up('settings');
  END//
CREATE OR REPLACE TRIGGER version_d_settings AFTER DELETE ON settings FOR EACH ROW
  BEGIN
  CALL version_up('settings');
  END//
CREATE OR REPLACE TRIGGER version_u_settings AFTER UPDATE ON settings FOR EACH ROW
  BEGIN
  CALL version_up('settings');
  END//
CREATE OR REPLACE TRIGGER version_i_sponsor AFTER INSERT ON sponsor FOR EACH ROW
  BEGIN
  CALL version_up('sponsor');
  END//
CREATE OR REPLACE TRIGGER version_d_sponsor AFTER DELETE ON sponsor FOR EACH ROW
  BEGIN
  CALL version_up('sponsor');
  END//
CREATE OR REPLACE TRIGGER version_u_sponsor AFTER UPDATE ON sponsor FOR EACH ROW
  BEGIN
  IF (NEW.print = OLD.print AND NEW.print_static = OLD.print_static AND NEW.click = OLD.click AND NEW.ammount = OLD.ammount) THEN
    CALL version_up('sponsor');
  END IF;
  END//


DELIMITER ;
