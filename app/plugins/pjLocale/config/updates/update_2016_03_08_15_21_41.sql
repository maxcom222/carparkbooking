
START TRANSACTION;

UPDATE `fields` SET `label` = 'Locale plugin / Front-end title' WHERE `key` = 'plugin_locale_lbl_fend' LIMIT 1;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'plugin_locale_lbl_fend');
UPDATE `multi_lang` SET `content` = 'Front-end title' WHERE `foreign_id` = @id AND `model` = 'pjField' AND `field` = 'title';

COMMIT;