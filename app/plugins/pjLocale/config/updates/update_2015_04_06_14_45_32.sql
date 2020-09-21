
START TRANSACTION;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "plugin_locale_titles");
UPDATE `multi_lang` SET `content` = 'Translate' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_lbl_id', 'backend', 'Label / ID:', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'ID:', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_lbl_show_id', 'backend', 'Label / Show ID in all titles to easily locate them', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Show IDs', 'plugin');

COMMIT;