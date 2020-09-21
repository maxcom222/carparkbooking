
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_btn_close', 'backend', 'Locale plugin / Button: Close', 'plugin', '2016-03-07 13:10:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_flag_info_title', 'backend', 'Locale plugin / Info message', 'plugin', '2016-03-07 13:13:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Info message', 'plugin');

COMMIT;