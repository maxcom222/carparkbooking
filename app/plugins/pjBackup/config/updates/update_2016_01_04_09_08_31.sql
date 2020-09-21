
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'plugin_backup_size', 'backend', 'Plugin / Size', 'script', '2016-01-04 08:58:41');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Size', 'script');

COMMIT;