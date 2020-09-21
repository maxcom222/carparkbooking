
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'plugin_authorize_currency_supported', 'backend', 'Authorize plugin / Currency is supported', 'plugin', '2016-05-03 17:12:32');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please, make sure that currency you have in your Authorize.net account is %s', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_authorize_currency_not_supported', 'backend', 'Authorize plugin / Currency is not supported', 'plugin', '2016-05-03 17:12:32');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '%s currency you have selected is not supported by Authorize.net', 'plugin');

COMMIT;