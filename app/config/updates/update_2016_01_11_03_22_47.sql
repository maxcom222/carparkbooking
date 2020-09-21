
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblPricesSavedTitle', 'backend', 'Label / Prices saved', 'script', '2016-01-11 03:17:24');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Prices saved', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblPricesSavedDesc', 'backend', 'Label / Prices saved', 'script', '2016-01-11 03:17:53');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All prices have been deleted.', 'script');

COMMIT;