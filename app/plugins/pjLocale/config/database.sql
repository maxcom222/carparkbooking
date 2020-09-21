DROP TABLE IF EXISTS `plugin_locale_languages`;
CREATE TABLE IF NOT EXISTS `plugin_locale_languages` (
  `iso` varchar(2) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `plugin_locale_languages` (`iso`, `title`, `file`) VALUES
('aa', 'Afar', NULL),
('ab', 'Abkhazian', NULL),
('ae', 'Avestan', 'ae.png'),
('af', 'Afrikaans', 'af.png'),
('ak', 'Akan', NULL),
('am', 'Amharic', 'am.png'),
('an', 'Aragonese', 'an.png'),
('as', 'Assamese', 'as.png'),
('av', 'Avaric', NULL),
('ay', 'Aymara', NULL),
('ba', 'Bashkir', 'ba.png'),
('be', 'Belarusian', 'be.png'),
('bg', 'Bulgarian', 'bg.png'),
('bh', 'Bihari', 'bh.png'),
('bi', 'Bislama', 'bi.png'),
('bm', 'Bambara', 'bm.png'),
('bn', 'Bengali', 'bn.png'),
('bo', 'Tibetan', 'bo.png'),
('br', 'Breton', 'br.png'),
('bs', 'Bosnian', 'bs.png'),
('ca', 'Catalan', 'catalonia.png'),
('ce', 'Chechen', NULL),
('ch', 'Chamorro', 'ch.png'),
('co', 'Corsican', 'co.png'),
('cr', 'Cree', 'cr.png'),
('cs', 'Czech', 'cz.png'),
('cu', 'Church Slavic', 'cu.png'),
('cv', 'Chuvash', 'cv.png'),
('cy', 'Welsh', 'cy.png'),
('da', 'Danish', 'dk.png'),
('de', 'German', 'de.png'),
('dz', 'Dzongkha', 'dz.png'),
('ee', 'Ewe', 'ee.png'),
('el', 'Greek', 'gr.png'),
('eo', 'Esperanto', NULL),
('es', 'Spanish', 'es.png'),
('et', 'Estonian', 'et.png'),
('eu', 'Basque', 'eu.png'),
('ff', 'Fulah', NULL),
('fi', 'Finnish', 'fi.png'),
('fj', 'Fijian', 'fj.png'),
('fo', 'Faroese', 'fo.png'),
('fr', 'French', 'fr.png'),
('fy', 'Western Frisian', NULL),
('ga', 'Irish', 'ga.png'),
('gb', 'English - UK', 'gb.png'),
('gd', 'Scottish Gaelic', 'gd.png'),
('gl', 'Galician', 'gl.png'),
('gn', 'Guarani', 'gn.png'),
('gu', 'Gujarati', 'gu.png'),
('gv', 'Manx', NULL),
('ha', 'Hausa', NULL),
('hi', 'Hindi', NULL),
('ho', 'Hiri Motu', NULL),
('hr', 'Croatian', 'hr.png'),
('ht', 'Haitian', 'ht.png'),
('hu', 'Hungarian', 'hu.png'),
('hy', 'Armenian', NULL),
('hz', 'Herero', NULL),
('ia', 'Interlingua (International Auxiliary Language Association)', NULL),
('id', 'Indonesian', 'id.png'),
('ie', 'Interlingue', 'ie.png'),
('ig', 'Igbo', NULL),
('ii', 'Sichuan Yi', NULL),
('ik', 'Inupiaq', NULL),
('io', 'Ido', 'io.png'),
('is', 'Icelandic', 'is.png'),
('it', 'Italian', 'it.png'),
('iu', 'Inuktitut', NULL),
('ka', 'Georgian', NULL),
('kg', 'Kongo', 'kg.png'),
('ki', 'Kikuyu', 'ki.png'),
('kj', 'Kwanyama', NULL),
('kl', 'Kalaallisut', NULL),
('km', 'Khmer', 'km.png'),
('kn', 'Kannada', 'kn.png'),
('ko', 'Korean', NULL),
('kr', 'Kanuri', 'kr.png'),
('kv', 'Komi', NULL),
('kw', 'Cornish', 'kw.png'),
('ky', 'Kirghiz', 'ky.png'),
('la', 'Latin', 'la.png'),
('lb', 'Luxembourgish', 'lb.png'),
('lg', 'Ganda', NULL),
('li', 'Limburgish', 'li.png'),
('ln', 'Lingala', NULL),
('lo', 'Lao', NULL),
('lt', 'Lithuanian', 'lt.png'),
('lu', 'Luba-Katanga', 'lu.png'),
('lv', 'Latvian', 'lv.png'),
('mg', 'Malagasy', 'mg.png'),
('mh', 'Marshallese', 'mh.png'),
('mi', 'Maori', NULL),
('mk', 'Macedonian', 'mk.png'),
('mn', 'Mongolian', 'mn.png'),
('mr', 'Marathi', 'mr.png'),
('mt', 'Maltese', 'mt.png'),
('my', 'Burmese', 'my.png'),
('na', 'Nauru', 'na.png'),
('nb', 'Norwegian Bokmal', NULL),
('nd', 'North Ndebele', NULL),
('ne', 'Nepali', 'ne.png'),
('ng', 'English - Nigeria', 'ng.png'),
('nl', 'Dutch', 'nl.png'),
('nn', 'Norwegian Nynorsk', NULL),
('no', 'Norwegian', 'no.png'),
('nr', 'South Ndebele', 'nr.png'),
('nv', 'Navajo', NULL),
('ny', 'Chichewa', NULL),
('oc', 'Occitan', NULL),
('oj', 'Ojibwa', NULL),
('om', 'Oromo', 'om.png'),
('or', 'Oriya', NULL),
('os', 'Ossetian', NULL),
('pi', 'Pali', NULL),
('pl', 'Polish', 'pl.png'),
('pt', 'Portuguese', 'pt.png'),
('qu', 'Quechua', NULL),
('rm', 'Raeto-Romance', NULL),
('rn', 'Kirundi', NULL),
('ro', 'Romanian', 'ro.png'),
('ru', 'Russian', 'ru.png'),
('rw', 'Kinyarwanda', 'rw.png'),
('sa', 'Sanskrit', 'sa.png'),
('sc', 'Sardinian', 'sc.png'),
('se', 'Northern Sami', 'se.png'),
('sg', 'Sango', 'sg.png'),
('si', 'Sinhala', 'si.png'),
('sk', 'Slovak', 'sk.png'),
('sl', 'Slovenian', 'sl.png'),
('sm', 'Samoan', 'sm.png'),
('sn', 'Shona', 'sn.png'),
('sq', 'Albanian', 'al.png'),
('sr', 'Serbian', 'sr.png'),
('ss', 'Swati', NULL),
('st', 'Southern Sotho', 'st.png'),
('su', 'Sundanese', NULL),
('sv', 'Swedish', 'se.png'),
('sw', 'Swahili', NULL),
('ta', 'Tamil', NULL),
('te', 'Telugu', NULL),
('tg', 'Tajik', 'tg.png'),
('th', 'Thai', 'th.png'),
('ti', 'Tigrinya', NULL),
('tl', 'Tagalog', 'tl.png'),
('tn', 'Tswana', 'tn.png'),
('to', 'Tonga', 'to.png'),
('tr', 'Turkish', 'tr.png'),
('ts', 'Tsonga', NULL),
('tt', 'Tatar', 'tt.png'),
('tw', 'Twi', 'tw.png'),
('ty', 'Tahitian', NULL),
('uk', 'Ukrainian', NULL),
('us', 'English - USA', 'us.png'),
('uz', 'Uzbek', 'uz.png'),
('ve', 'Venda', 've.png'),
('vi', 'Vietnamese', 'vi.png'),
('vo', 'Volapuk', NULL),
('wa', 'Walloon', 'wa.png'),
('wo', 'Wolof', NULL),
('xh', 'Xhosa', NULL),
('yo', 'Yoruba', NULL),
('za', 'Zhuang', 'za.png'),
('zu', 'Zulu', NULL);

DROP TABLE IF EXISTS `plugin_locale`;
CREATE TABLE IF NOT EXISTS `plugin_locale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_iso` varchar(2) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_iso` (`language_iso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `plugin_locale` (`id`, `language_iso`, `sort`, `is_default`) VALUES
(1, 'gb', 1, 1);

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_languages', 'backend', 'Locale plugin / Languages', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_titles', 'backend', 'Locale plugin / Title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Translate and change titles', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_index_title', 'backend', 'Locale plugin / Languages info title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Languages', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_index_body', 'backend', 'Locale plugin / Languages info body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add as many languages as you need to your script. For each of the languages added you need to translate all the text titles.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_titles_title', 'backend', 'Locale plugin / Titles info title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Titles', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_titles_body', 'backend', 'Locale plugin / Titles info body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Using the form below you can edit all the text in the software.<br /><br />Each piece of text used in the software is saved in the database and has its own unique ID. In the first column below you can see the ID for each piece of text. To show these IDs in the script itself check the "Show IDs" checkbox and click Save button next to it. This will show the corresponding :ID: for each text message. Please, note that ONLY you will see these IDs. Now you can search for any ID and easily change and/or translate the text. Have in the mind that you shoud use : before and after the ID when you search for it.  <br /><br />Check our <a target="_blank" href="http://www.phpjabbers.com/knowledgebase/other">knowledgebase</a> and watch video tutorial how to change and/or translate the text.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_title', 'backend', 'Locale plugin / Title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_flag', 'backend', 'Locale plugin / Flag', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Flag', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_is_default', 'backend', 'Locale plugin / Is default', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Is default', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_order', 'backend', 'Locale plugin / Order', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Order', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_add_lang', 'backend', 'Locale plugin / Add Language', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add Language', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_field', 'backend', 'Locale plugin / Field', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Field', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_value', 'backend', 'Locale plugin / Value', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Value', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_type_backend', 'backend', 'Locale plugin / Back-end title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Back-end title', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_type_frontend', 'backend', 'Locale plugin / Front-end title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Front-end title', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_type_arrays', 'backend', 'Locale plugin / Special title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Special title', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PAL01', 'arrays', 'Locale plugin / Status title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Titles Updated', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PAL01', 'arrays', 'Locale plugin / Status body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'All the changes made to titles have been saved.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_lbl_rows', 'backend', 'Locale plugin / Per page', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Per page', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PAL02', 'arrays', 'Locale plugin / Status title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import error', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PAL02', 'arrays', 'Locale plugin / Status body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed due missing parameters.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PAL03', 'arrays', 'Locale plugin / Status title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import complete', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PAL03', 'arrays', 'Locale plugin / Status body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'The import was performed successfully.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PAL04', 'arrays', 'Locale plugin / Status title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import error', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PAL04', 'arrays', 'Locale plugin / Status body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed due empty data.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PAL05', 'arrays', 'Locale plugin / Status title', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import error', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PAL05', 'arrays', 'Locale plugin / Status body', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed because file cannot be open.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_import_export', 'backend', 'Locale plugin / Import Export menu', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import / Export', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_import', 'backend', 'Locale plugin / Import', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_export', 'backend', 'Locale plugin / Export', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Export', 'plugin');


INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_browse', 'backend', 'Locale plugin / Browse your computer', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Browse your computer', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_ie_title', 'backend', 'Locale plugin / Import Export (title)', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import / Export', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_locale_ie_body', 'backend', 'Locale plugin / Import Export (body)', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use form below to Import or Export CSV with all titles. Please, do not change first row and first and second column in the CSV file.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_separator', 'backend', 'Locale plugin / Delimiter', 'plugin', '2014-07-16 14:02:18');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delimiter', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_separators_ARRAY_comma', 'arrays', 'Locale plugin / Delimiter: comma', 'plugin', '2014-07-16 14:02:36');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Comma', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_separators_ARRAY_semicolon', 'arrays', 'Locale plugin / Delimiter: semicolon', 'plugin', '2014-07-16 14:02:52');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Semicolon', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_separators_ARRAY_tab', 'arrays', 'Locale plugin / Delimiter: tab', 'plugin', '2014-07-16 14:03:09');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Tab', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL20', 'arrays', 'error_bodies_ARRAY_PAL20', 'plugin', '2014-07-21 07:54:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The following languages have been found. Select those you want to import.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL20', 'arrays', 'error_titles_ARRAY_PAL20', 'plugin', '2014-07-21 07:55:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import confirmation', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL11', 'arrays', 'error_titles_ARRAY_PAL11', 'plugin', '2014-07-21 07:58:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL11', 'arrays', 'error_bodies_ARRAY_PAL11', 'plugin', '2014-07-21 07:58:37');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Missing, empty or invalid parameters.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL12', 'arrays', 'error_titles_ARRAY_PAL12', 'plugin', '2014-07-21 07:59:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL12', 'arrays', 'error_bodies_ARRAY_PAL12', 'plugin', '2014-07-21 07:59:46');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'File have not been uploaded.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL13', 'arrays', 'error_titles_ARRAY_PAL13', 'plugin', '2014-07-21 08:00:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL13', 'arrays', 'error_bodies_ARRAY_PAL13', 'plugin', '2014-07-21 08:01:02');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Uploaded file cannot open for reading.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL14', 'arrays', 'error_titles_ARRAY_PAL14', 'plugin', '2014-07-21 08:01:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL14', 'arrays', 'error_bodies_ARRAY_PAL14', 'plugin', '2014-07-21 08:01:37');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'New line(s) have been found.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL15', 'arrays', 'error_titles_ARRAY_PAL15', 'plugin', '2014-07-21 08:01:51');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL15', 'arrays', 'error_bodies_ARRAY_PAL15', 'plugin', '2014-07-21 08:04:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Uploaded file doesn''t contain the necessary columns.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL16', 'arrays', 'error_titles_ARRAY_PAL16', 'plugin', '2014-07-21 08:04:13');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL16', 'arrays', 'error_bodies_ARRAY_PAL16', 'plugin', '2014-07-21 08:05:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of columns are not equal on every row.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL17', 'arrays', 'error_titles_ARRAY_PAL17', 'plugin', '2014-07-21 08:06:10');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL17', 'arrays', 'error_bodies_ARRAY_PAL17', 'plugin', '2014-07-21 08:06:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Invalid data found.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL18', 'arrays', 'error_titles_ARRAY_PAL18', 'plugin', '2014-07-21 08:26:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL18', 'arrays', 'error_bodies_ARRAY_PAL18', 'plugin', '2014-07-21 08:27:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Missing columns.', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_PAL19', 'arrays', 'error_titles_ARRAY_PAL19', 'plugin', '2014-07-21 08:27:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Import failed', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_PAL19', 'arrays', 'error_bodies_ARRAY_PAL19', 'plugin', '2014-07-21 08:27:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Invalid data found.', 'plugin');
