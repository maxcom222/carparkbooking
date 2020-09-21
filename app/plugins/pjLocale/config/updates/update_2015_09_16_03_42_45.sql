
START TRANSACTION;

UPDATE `plugin_locale_languages` SET `file`='vn.png' WHERE `iso`='vi';

UPDATE `plugin_locale_languages` SET `file`='sh.png' WHERE `iso`='si';

UPDATE `plugin_locale_languages` SET `file`='si.png' WHERE `iso`='sl';

COMMIT;