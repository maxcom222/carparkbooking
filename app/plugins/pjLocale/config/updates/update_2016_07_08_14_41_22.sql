
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_error_line', 'backend', 'Label / Error found at line', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The error was found at line: %s', 'plugin');

COMMIT;