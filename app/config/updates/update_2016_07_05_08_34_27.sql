
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_field_required', 'frontend', 'Label / This field is required.', 'script', '2016-07-05 08:24:08');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This field is required.', 'script');

COMMIT;