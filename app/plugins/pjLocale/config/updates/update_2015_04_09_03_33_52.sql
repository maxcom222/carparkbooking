
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_showid_dialog_title', 'backend', 'Label / Show IDs', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Show IDs', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_showid_dialog_desc', 'backend', 'Label / Show IDs', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'ID will be displayed next to each text found in the software. You can then search for an ID to easily change or translate the text.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_button_confirm', 'backend', 'Button / Confirm', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirm', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_button_cancel', 'backend', 'Button / Cancel', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_default', 'backend', 'Label / default', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'default', 'plugin');

COMMIT;