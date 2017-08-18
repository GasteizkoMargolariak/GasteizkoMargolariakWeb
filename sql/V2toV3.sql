DROP TABLE IF EXISTS route;
CREATE TABLE route (
  id       INT           AUTO_INCREMENT  PRIMARY KEY,
  name     VARCHAR(32),
  mins     INT
);

DROP TABLE IF EXISTS route_point;
CREATE TABLE route_point (
  id       INT      AUTO_INCREMENT        PRIMARY KEY,
  route    INT      NOT NULL              DEFAULT 0,
  part     INT      NOT NULL,
  place_o  INT      REFERENCES place.id,
  lat_o    DOUBLE,
  lon_o    DOUBLE,
  place_d  INT      REFERENCES place.id,
  lat_d    DOUBLE,
  lon_d    DOUBLE,
  mins     INT,
  visible  BOOLEAN  NOT NULL              DEFAULT 1
);

ALTER TABLE sponsor ADD local BOOLEAN NOT NULL DEFAULT 1;
UPDATE sponsor SET local = 0 WHERE address_es IS NULL OR address_es = '';

ALTER TABLE activity_itinerary ADD route INT
ALTER TABLE activity_itinerary MODIFY place INT;

ALTER TABLE festival_day ADD people INT NOT NULL DEFAULT 0;
ALTER TABLE festival_day ADD max_people INT NOT NULL DEFAULT 0;

CREATE OR REPLACE VIEW festival_event_gm AS SELECT id, title_es, title_en, title_eu, description_es, description_en, description_eu, host, sponsor, place, route, start, end, interest FROM festival_event WHERE gm = 1;
CREATE OR REPLACE VIEW festival_event_city AS SELECT id, title_es, title_en, title_eu, description_es, description_en, description_eu, host, sponsor, place, route, start, end, interest FROM festival_event WHERE gm = 0;

ALTER TABLE festival_event ADD sponsor INT;
ALTER TABLE festival_event ADD route INT;
ALTER TABLE festival_event MODIFY place INT;
ALTER TABLE festival_event ADD interest INT NOT NULL DEFAULT 2;

ALTER TABLE version MODIFY section VARCHAR(128);

ALTER TABLE sync DROP action;
ALTER TABLE sync DROP section;
ALTER TABLE sync DROP version_from;
ALTER TABLE sync DROP version_to;
ALTER TABLE sync DROP format;
ALTER TABLE sync ADD synced BOOLEAN NOT NULL DEFAULT 0;


# Populate table version
DELETE FROM version;
INSERT INTO version VALUES ('activity', 1);
INSERT INTO version VALUES ('activity_comment', 1);
INSERT INTO version VALUES ('activity_image', 1);
INSERT INTO version VALUES ('activity_itinerary', 1);
INSERT INTO version VALUES ('activity_tag', 1);
INSERT INTO version VALUES ('album', 1);
INSERT INTO version VALUES ('festival', 1);
INSERT INTO version VALUES ('festival_day', 1);
INSERT INTO version VALUES ('festival_event_city', 1);
INSERT INTO version VALUES ('festival_event_gm', 1);
INSERT INTO version VALUES ('festival_event_image', 1);
INSERT INTO version VALUES ('festival_offer', 1);
INSERT INTO version VALUES ('people', 1);
INSERT INTO version VALUES ('photo', 1);
INSERT INTO version VALUES ('photo_album', 1);
INSERT INTO version VALUES ('photo_comment', 1);
INSERT INTO version VALUES ('place', 1);
INSERT INTO version VALUES ('post', 1);
INSERT INTO version VALUES ('post_comment', 1);
INSERT INTO version VALUES ('post_image', 1);
INSERT INTO version VALUES ('post_tag', 1);
INSERT INTO version VALUES ('settings', 1);
INSERT INTO version VALUES ('sponsor', 1);


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
    CALL version_up('sponsor');
  END//


DELIMITER ;

