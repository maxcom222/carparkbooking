
START TRANSACTION;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "plugin_locale_titles_body");
UPDATE `multi_lang` SET `content` = 'Using the form below you can edit all the text in the software.<br /><br />Each piece of text used in the software is saved in the database and has its own unique ID. In the first column below you can see the ID for each piece of text. To show these IDs in the script itself check the "Show IDs" checkbox and click Save button next to it. This will show the corresponding :ID: for each text message. Please, note that ONLY you will see these IDs. Now you can search for any ID and easily change and/or translate the text. Have in the mind that you should use : before and after the ID when you search for it.  <br /><br />Check our <a target="_blank" href="http://www.phpjabbers.com/knowledgebase/other">knowledgebase</a> and watch video tutorial how to change and/or translate the text.' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

COMMIT;