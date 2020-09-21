
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblDiscountCode', 'backend', 'Label / Discount code', 'script', '2016-03-11 05:13:55');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Discount code', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnApply', 'backend', 'Button / Apply', 'script', '2016-03-11 05:20:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Apply', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnRemove', 'backend', 'Button / Remove', 'script', '2016-03-11 05:20:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Remove', 'script');

COMMIT;