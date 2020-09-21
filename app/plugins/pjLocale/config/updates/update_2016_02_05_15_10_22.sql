
START TRANSACTION;

ALTER TABLE `plugin_locale` 
	CHANGE `language_iso` `language_iso` varchar(20) COLLATE utf8_general_ci NULL after `id`, 
	ADD COLUMN `name` varchar(255) COLLATE utf8_general_ci NULL after `language_iso`, 
	ADD COLUMN `flag` varchar(255) COLLATE utf8_general_ci NULL after `name`, 
	ADD COLUMN `dir` enum('ltr','rtl') COLLATE utf8_general_ci NULL DEFAULT 'ltr' after `flag`;

DROP TABLE IF EXISTS `plugin_locale_languages`;
CREATE TABLE IF NOT EXISTS `plugin_locale_languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iso` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `native` varchar(255) DEFAULT NULL,
  `dir` enum('ltr','rtl') DEFAULT 'ltr',
  `country_abbr` varchar(3) DEFAULT NULL,
  `language_abbr` varchar(3) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iso` (`iso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `plugin_locale_languages` (`id`, `iso`, `title`, `region`, `native`, `dir`, `country_abbr`, `language_abbr`, `file`) VALUES
(1, 'af-ZA', 'Afrikaans', 'South Africa', 'Afrikaans (Suid Afrika)', 'ltr', 'ZAF', 'AFK', 'za.png'),
(2, 'sq-AL', 'Albanian', 'Albania', 'shqipe (Shqipëria)', 'ltr', 'ALB', 'SQI', 'al.png'),
(3, 'gsw-FR', 'Alsatian', 'France', 'Elsässisch (Frànkrisch)', 'ltr', 'FRA', 'GSW', 'fr.png'),
(4, 'am-ET', 'Amharic', 'Ethiopia', 'አማርኛ (ኢትዮጵያ)', 'ltr', 'ETH', 'AMH', 'et.png'),
(5, 'ar', 'Arabic‎', NULL, 'العربية‏', 'rtl', 'SAU', 'ARA', 'empty.png'),
(6, 'ar-DZ', 'Arabic', 'Algeria', 'العربية (الجزائر)‏', 'rtl', 'DZA', 'ARG', 'dz.png'),
(7, 'ar-BH', 'Arabic', 'Bahrain', 'العربية (البحرين)‏', 'rtl', 'BHR', 'ARH', 'bh.png'),
(8, 'ar-EG', 'Arabic', 'Egypt', 'العربية (مصر)‏', 'rtl', 'EGY', 'ARE', 'eg.png'),
(9, 'ar-IQ', 'Arabic', 'Iraq', 'العربية (العراق)‏', 'rtl', 'IRQ', 'ARI', 'iq.png'),
(10, 'ar-JO', 'Arabic', 'Jordan', 'العربية (الأردن)‏', 'rtl', 'JOR', 'ARJ', 'jo.png'),
(11, 'ar-KW', 'Arabic', 'Kuwait', 'العربية (الكويت)‏', 'rtl', 'KWT', 'ARK', 'kw.png'),
(12, 'ar-LB', 'Arabic', 'Lebanon', 'العربية (لبنان)‏', 'rtl', 'LBN', 'ARB', 'lb.png'),
(13, 'ar-LY', 'Arabic', 'Libya', 'العربية (ليبيا)‏', 'rtl', 'LBY', 'ARL', 'ly.png'),
(14, 'ar-MA', 'Arabic', 'Morocco', 'العربية (المملكة المغربية)‏', 'rtl', 'MAR', 'ARM', 'ma.png'),
(15, 'ar-OM', 'Arabic', 'Oman', 'العربية (عمان)‏', 'rtl', 'OMN', 'ARO', 'om.png'),
(16, 'ar-QA', 'Arabic', 'Qatar', 'العربية (قطر)‏', 'rtl', 'QAT', 'ARQ', 'qa.png'),
(17, 'ar-SA', 'Arabic', 'Saudi Arabia', 'العربية (المملكة العربية السعودية)‏', 'rtl', 'SAU', 'ARA', 'sa.png'),
(18, 'ar-SY', 'Arabic', 'Syria', 'العربية (سوريا)‏', 'rtl', 'SYR', 'ARS', 'sy.png'),
(19, 'ar-TN', 'Arabic', 'Tunisia', 'العربية (تونس)‏', 'rtl', 'TUN', 'ART', 'tn.png'),
(20, 'ar-AE', 'Arabic', 'U.A.E.', 'العربية (الإمارات العربية المتحدة)‏', 'rtl', 'ARE', 'ARU', 'ae.png'),
(21, 'ar-YE', 'Arabic', 'Yemen', 'العربية (اليمن)‏', 'rtl', 'YEM', 'ARY', 'ye.png'),
(22, 'hy-AM', 'Armenian', 'Armenia', 'Հայերեն (Հայաստան)', 'ltr', 'ARM', 'HYE', 'am.png'),
(23, 'as-IN', 'Assamese', 'India', 'অসমীয়া (ভাৰত)', 'ltr', 'IND', 'ASM', 'in.png'),
(24, 'az', 'Azeri', NULL, 'Azərbaycan­ılı', 'rtl', 'AZE', 'AZE', 'empty.png'),
(25, 'az-Cyrl', 'Azeri', 'Cyrillic', 'Азәрбајҹан дили', 'rtl', 'AZE', 'AZC', 'az.png'),
(26, 'az-Cyrl-AZ', 'Azeri', 'Cyrillic, Azerbaijan', 'Азәрбајҹан (Азәрбајҹан)', 'rtl', 'AZE', 'AZC', 'az.png'),
(27, 'az-Latn', 'Azeri', 'Latin', 'Azərbaycan­ılı', 'rtl', 'AZE', 'AZE', 'az.png'),
(28, 'az-Latn-AZ', 'Azeri', 'Latin, Azerbaijan', 'Azərbaycan­ılı (Azərbaycan)', 'rtl', 'AZE', 'AZE', 'az.png'),
(29, 'ba-RU', 'Bashkir', 'Russia', 'Башҡорт (Россия)', 'ltr', 'RUS', 'BAS', 'ru.png'),
(30, 'eu-ES', 'Basque', 'Basque', 'euskara (euskara)', 'ltr', 'ESP', 'EUQ', 'es.png'),
(31, 'be-BY', 'Belarusian', 'Belarus', 'Беларускі (Беларусь)', 'ltr', 'BLR', 'BEL', 'by.png'),
(32, 'bn', 'Bengali', NULL, 'বাংলা', 'ltr', 'IND', 'BNG', 'empty.png'),
(33, 'bn-BD', 'Bengali', 'Bangladesh', 'বাংলা (বাংলাদেশ)', 'ltr', 'BGD', 'BNB', 'bd.png'),
(34, 'bn-IN', 'Bengali', 'India', 'বাংলা (ভারত)', 'ltr', 'IND', 'BNG', 'in.png'),
(35, 'bs', 'Bosnian', NULL, 'bosanski', 'ltr', 'BIH', 'BSB', 'empty.png'),
(36, 'bs-Cyrl', 'Bosnian', 'Cyrillic', 'босански (Ћирилица)', 'ltr', 'BIH', 'BSC', 'ba.png'),
(37, 'bs-Cyrl-BA', 'Bosnian', 'Cyrillic, Bosnia and Herzegovina', 'босански (Босна и Херцеговина)', 'ltr', 'BIH', 'BSC', 'ba.png'),
(38, 'bs-Latn', 'Bosnian', 'Latin', 'bosanski (Latinica)', 'ltr', 'BIH', 'BSB', 'ba.png'),
(39, 'bs-Latn-BA', 'Bosnian', 'Latin, Bosnia and Herzegovina', 'bosanski (Bosna i Hercegovina)', 'ltr', 'BIH', 'BSB', 'ba.png'),
(40, 'br-FR', 'Breton', 'France', 'brezhoneg (Frañs)', 'ltr', 'FRA', 'BRE', 'fr.png'),
(41, 'bg-BG', 'Bulgarian', 'Bulgaria', 'български (България)', 'ltr', 'BGR', 'BGR', 'bg.png'),
(42, 'ca-ES', 'Catalan', 'Catalan', 'català (català)', 'ltr', 'ESP', 'CAT', 'es.png'),
(43, 'zh', 'Chinese', NULL, '中文', 'ltr', 'CHN', 'CHS', 'empty.png'),
(44, 'zh-Hans', 'Chinese', 'Simplified', '中文(简体)', 'ltr', 'CHN', 'CHS', 'cn.png'),
(45, 'zh-CN', 'Chinese', 'Simplified, PRC', '中文(中华人民共和国)', 'ltr', 'CHN', 'CHS', 'cn.png'),
(46, 'zh-SG', 'Chinese', 'Simplified, Singapore', '中文(新加坡)', 'ltr', 'SGP', 'ZHI', 'sg.png'),
(47, 'zh-Hant', 'Chinese', 'Traditional', '中文(繁體)', 'ltr', 'HKG', 'ZHH', 'hk.png'),
(48, 'zh-HK', 'Chinese', 'Traditional, Hong Kong S.A.R.', '中文(香港特別行政區)', 'ltr', 'HKG', 'ZHH', 'hk.png'),
(49, 'zh-MO', 'Chinese', 'Traditional, Macao S.A.R.', '中文(澳門特別行政區)', 'ltr', 'MCO', 'ZHM', 'mc.png'),
(50, 'zh-TW', 'Chinese', 'Traditional, Taiwan', '中文(台灣)', 'ltr', 'TWN', 'CHT', 'tw.png'),
(51, 'co-FR', 'Corsican', 'France', 'Corsu (France)', 'ltr', 'FRA', 'COS', 'fr.png'),
(52, 'hr', 'Croatian', NULL, 'hrvatski', 'ltr', 'HRV', 'HRV', 'empty.png'),
(53, 'hr-HR', 'Croatian', 'Croatia', 'hrvatski (Hrvatska)', 'ltr', 'HRV', 'HRV', 'hr.png'),
(54, 'hr-BA', 'Croatian', 'Latin, Bosnia and Herzegovina', 'hrvatski (Bosna i Hercegovina)', 'ltr', 'BIH', 'HRB', 'ba.png'),
(55, 'cs-CZ', 'Czech', 'Czech Republic', 'čeština (Česká republika)', 'ltr', 'CZE', 'CSY', 'cz.png'),
(56, 'da-DK', 'Danish', 'Denmark', 'dansk (Danmark)', 'ltr', 'DNK', 'DAN', 'dk.png'),
(57, 'prs-AF', 'Dari', 'Afghanistan', 'درى (افغانستان)‏', 'ltr', 'AFG', 'PRS', 'af.png'),
(58, 'dv-MV', 'Divehi', 'Maldives', 'ދިވެހިބަސް (ދިވެހި ރާއްޖެ)‏', 'rtl', 'MDV', 'DIV', 'mv.png'),
(59, 'nl', 'Dutch', NULL, 'Nederlands', 'ltr', 'NLD', 'NLD', 'empty.png'),
(60, 'nl-BE', 'Dutch', 'Belgium', 'Nederlands (België)', 'ltr', 'BEL', 'NLB', 'be.png'),
(61, 'nl-NL', 'Dutch', 'Netherlands', 'Nederlands (Nederland)', 'ltr', 'NLD', 'NLD', 'nl.png'),
(62, 'en', 'English', NULL, 'English', 'ltr', 'USA', 'ENU', 'empty.png'),
(63, 'en-AU', 'English', 'Australia', 'English (Australia)', 'ltr', 'AUS', 'ENA', 'au.png'),
(64, 'en-BZ', 'English', 'Belize', 'English (Belize)', 'ltr', 'BLZ', 'ENL', 'bz.png'),
(65, 'en-CA', 'English', 'Canada', 'English (Canada)', 'ltr', 'CAN', 'ENC', 'ca.png'),
(66, 'en-029', 'English', 'Caribbean', 'English (Caribbean)', 'ltr', 'CAR', 'ENB', 'en.png'),
(67, 'en-IN', 'English', 'India', 'English (India)', 'ltr', 'IND', 'ENN', 'in.png'),
(68, 'en-IE', 'English', 'Ireland', 'English (Ireland)', 'ltr', 'IRL', 'ENI', 'ie.png'),
(69, 'en-JM', 'English', 'Jamaica', 'English (Jamaica)', 'ltr', 'JAM', 'ENJ', 'jm.png'),
(70, 'en-MY', 'English', 'Malaysia', 'English (Malaysia)', 'ltr', 'MYS', 'ENM', 'my.png'),
(71, 'en-NZ', 'English', 'New Zealand', 'English (New Zealand)', 'ltr', 'NZL', 'ENZ', 'nz.png'),
(72, 'en-PH', 'English', 'Republic of the Philippines', 'English (Philippines)', 'ltr', 'PHL', 'ENP', 'ph.png'),
(73, 'en-SG', 'English', 'Singapore', 'English (Singapore)', 'ltr', 'SGP', 'ENE', 'sg.png'),
(74, 'en-ZA', 'English', 'South Africa', 'English (South Africa)', 'ltr', 'ZAF', 'ENS', 'za.png'),
(75, 'en-TT', 'English', 'Trinidad and Tobago', 'English (Trinidad y Tobago)', 'ltr', 'TTO', 'ENT', 'tt.png'),
(76, 'en-GB', 'English', 'United Kingdom', 'English (United Kingdom)', 'ltr', 'GBR', 'ENG', 'gb.png'),
(77, 'en-US', 'English', 'United States', 'English (United States)', 'ltr', 'USA', 'ENU', 'us.png'),
(78, 'en-ZW', 'English', 'Zimbabwe', 'English (Zimbabwe)', 'ltr', 'ZWE', 'ENW', 'zw.png'),
(79, 'et-EE', 'Estonian', 'Estonia', 'eesti (Eesti)', 'ltr', 'EST', 'ETI', 'ee.png'),
(80, 'fo-FO', 'Faroese', 'Faroe Islands', 'føroyskt (Føroyar)', 'ltr', 'FRO', 'FOS', 'fo.png'),
(81, 'fil-PH', 'Filipino', 'Philippines', 'Filipino (Pilipinas)', 'ltr', 'PHL', 'FPO', 'ph.png'),
(82, 'fi-FI', 'Finnish', 'Finland', 'suomi (Suomi)', 'ltr', 'FIN', 'FIN', 'fi.png'),
(83, 'fr', 'French', NULL, 'français', 'ltr', 'FRA', 'FRA', 'empty.png'),
(84, 'fr-BE', 'French', 'Belgium', 'français (Belgique)', 'ltr', 'BEL', 'FRB', 'be.png'),
(85, 'fr-CA', 'French', 'Canada', 'français (Canada)', 'ltr', 'CAN', 'FRC', 'ca.png'),
(86, 'fr-FR', 'French', 'France', 'français (France)', 'ltr', 'FRA', 'FRA', 'fr.png'),
(87, 'fr-LU', 'French', 'Luxembourg', 'français (Luxembourg)', 'ltr', 'LUX', 'FRL', 'lu.png'),
(88, 'fr-MC', 'French', 'Monaco', 'français (Principauté de Monaco)', 'ltr', 'MCO', 'FRM', 'mc.png'),
(89, 'fr-CH', 'French', 'Switzerland', 'français (Suisse)', 'ltr', 'CHE', 'FRS', 'ch.png'),
(90, 'fy-NL', 'Frisian', 'Netherlands', 'Frysk (Nederlân)', 'ltr', 'NLD', 'FYN', 'nl.png'),
(91, 'gl-ES', 'Galician', 'Galician', 'galego (galego)', 'ltr', 'ESP', 'GLC', 'es.png'),
(92, 'ka-GE', 'Georgian', 'Georgia', 'ქართული (საქართველო)', 'ltr', 'GEO', 'KAT', 'ge.png'),
(93, 'de', 'German', NULL, 'Deutsch', 'ltr', 'DEU', 'DEU', 'empty.png'),
(94, 'de-AT', 'German', 'Austria', 'Deutsch (Österreich)', 'ltr', 'AUT', 'DEA', 'at.png'),
(95, 'de-DE', 'German', 'Germany', 'Deutsch (Deutschland)', 'ltr', 'DEU', 'DEU', 'de.png'),
(96, 'de-LI', 'German', 'Liechtenstein', 'Deutsch (Liechtenstein)', 'ltr', 'LIE', 'DEC', 'li.png'),
(97, 'de-LU', 'German', 'Luxembourg', 'Deutsch (Luxemburg)', 'ltr', 'LUX', 'DEL', 'lu.png'),
(98, 'de-CH', 'German', 'Switzerland', 'Deutsch (Schweiz)', 'ltr', 'CHE', 'DES', 'ch.png'),
(99, 'el-GR', 'Greek', 'Greece', 'Ελληνικά (Ελλάδα)', 'ltr', 'GRC', 'ELL', 'gr.png'),
(100, 'kl-GL', 'Greenlandic', 'Greenland', 'kalaallisut (Kalaallit Nunaat)', 'ltr', 'GRL', 'KAL', 'gl.png'),
(101, 'gu-IN', 'Gujarati', 'India', 'ગુજરાતી (ભારત)', 'ltr', 'IND', 'GUJ', 'in.png'),
(102, 'ha', 'Hausa', NULL, 'Hausa', 'ltr', 'NGA', 'HAU', 'empty.png'),
(103, 'ha-Latn', 'Hausa', 'Latin', 'Hausa (Latin)', 'ltr', 'NGA', 'HAU', 'ng.png'),
(104, 'ha-Latn-NG', 'Hausa', 'Latin, Nigeria', 'Hausa (Nigeria)', 'ltr', 'NGA', 'HAU', 'ng.png'),
(105, 'he-IL', 'Hebrew', 'Israel', 'עברית (ישראל)‏', 'rtl', 'ISR', 'HEB', 'il.png'),
(106, 'hi-IN', 'Hindi', 'India', 'हिंदी (भारत)', 'ltr', 'IND', 'HIN', 'in.png'),
(107, 'hu-HU', 'Hungarian', 'Hungary', 'magyar (Magyarország)', 'ltr', 'HUN', 'HUN', 'hu.png'),
(108, 'is-IS', 'Icelandic', 'Iceland', 'íslenska (Ísland)', 'ltr', 'ISL', 'ISL', 'is.png'),
(109, 'ig-NG', 'Igbo', 'Nigeria', 'Igbo (Nigeria)', 'ltr', 'NGA', 'IBO', 'ng.png'),
(110, 'id-ID', 'Indonesian', 'Indonesia', 'Bahasa Indonesia (Indonesia)', 'ltr', 'IDN', 'IND', 'id.png'),
(111, 'iu', 'Inuktitut', NULL, 'Inuktitut', 'ltr', 'CAN', 'IUK', 'empty.png'),
(112, 'iu-Latn', 'Inuktitut', 'Latin', 'Inuktitut (Qaliujaaqpait)', 'ltr', 'CAN', 'IUK', 'ca.png'),
(113, 'iu-Latn-CA', 'Inuktitut', 'Latin, Canada', 'Inuktitut', 'ltr', 'CAN', 'IUK', 'ca.png'),
(114, 'iu-Cans', 'Inuktitut', 'Syllabics', 'ᐃᓄᒃᑎᑐᑦ (ᖃᓂᐅᔮᖅᐸᐃᑦ)', 'ltr', 'CAN', 'IUS', 'ca.png'),
(115, 'iu-Cans-CA', 'Inuktitut', 'Syllabics, Canada', 'ᐃᓄᒃᑎᑐᑦ (ᑲᓇᑕᒥ)', 'ltr', 'CAN', 'IUS', 'ca.png'),
(116, 'ga-IE', 'Irish', 'Ireland', 'Gaeilge (Éire)', 'ltr', 'IRL', 'IRE', 'ie.png'),
(117, 'xh-ZA', 'isiXhosa', 'South Africa', 'isiXhosa (uMzantsi Afrika)', 'ltr', 'ZAF', 'XHO', 'za.png'),
(118, 'zu-ZA', 'isiZulu', 'South Africa', 'isiZulu (iNingizimu Afrika)', 'ltr', 'ZAF', 'ZUL', 'za.png'),
(119, 'it', 'Italian', NULL, 'italiano', 'ltr', 'ITA', 'ITA', 'empty.png'),
(120, 'it-IT', 'Italian', 'Italy', 'italiano (Italia)', 'ltr', 'ITA', 'ITA', 'it.png'),
(121, 'it-CH', 'Italian', 'Switzerland', 'italiano (Svizzera)', 'ltr', 'CHE', 'ITS', 'ch.png'),
(122, 'ja-JP', 'Japanese', 'Japan', '日本語 (日本)', 'ltr', 'JPN', 'JPN', 'jp.png'),
(123, 'kn-IN', 'Kannada', 'India', 'ಕನ್ನಡ (ಭಾರತ)', 'ltr', 'IND', 'KDI', 'in.png'),
(124, 'kk-KZ', 'Kazakh', 'Kazakhstan', 'Қазақ (Қазақстан)', 'rtl', 'KAZ', 'KKZ', 'kz.png'),
(125, 'km-KH', 'Khmer', 'Cambodia', 'ខ្មែរ (កម្ពុជា)', 'ltr', 'KHM', 'KHM', 'kh.png'),
(126, 'qut-GT', 'K''iche', 'Guatemala', 'K''iche (Guatemala)', 'ltr', 'GTM', 'QUT', 'gt.png'),
(127, 'rw-RW', 'Kinyarwanda', 'Rwanda', 'Kinyarwanda (Rwanda)', 'ltr', 'RWA', 'KIN', 'rw.png'),
(128, 'sw-KE', 'Kiswahili', 'Kenya', 'Kiswahili (Kenya)', 'ltr', 'KEN', 'SWK', 'ke.png'),
(129, 'kok-IN', 'Konkani', 'India', 'कोंकणी (भारत)', 'ltr', 'IND', 'KNK', 'in.png'),
(130, 'ko-KR', 'Korean', 'Korea', '한국어 (대한민국)', 'ltr', 'KOR', 'KOR', 'kr.png'),
(131, 'ky-KG', 'Kyrgyz', 'Kyrgyzstan', 'Кыргыз (Кыргызстан)', 'ltr', 'KGZ', 'KYR', 'kg.png'),
(132, 'lo-LA', 'Lao', 'Lao P.D.R.', 'ລາວ (ສ.ປ.ປ. ລາວ)', 'ltr', 'LAO', 'LAO', 'la.png'),
(133, 'lv-LV', 'Latvian', 'Latvia', 'latviešu (Latvija)', 'ltr', 'LVA', 'LVI', 'lv.png'),
(134, 'lt-LT', 'Lithuanian', 'Lithuania', 'lietuvių (Lietuva)', 'ltr', 'LTU', 'LTH', 'lt.png'),
(135, 'dsb-DE', 'Lower Sorbian', 'Germany', 'dolnoserbšćina (Nimska)', 'ltr', 'GER', 'DSB', 'de.png'),
(136, 'lb-LU', 'Luxembourgish', 'Luxembourg', 'Lëtzebuergesch (Luxembourg)', 'ltr', 'LUX', 'LBX', 'lu.png'),
(137, 'mk-MK', 'Macedonian', 'Former Yugoslav Republic of Macedonia', 'македонски јазик (Македонија)', 'ltr', 'MKD', 'MKI', 'mk.png'),
(138, 'mk', 'Macedonian', 'FYROM', 'македонски јазик', 'ltr', 'MKD', 'MKI', 'mk.png'),
(139, 'ms', 'Malay', NULL, 'Bahasa Melayu', 'ltr', 'MYS', 'MSL', 'empty.png'),
(140, 'ms-BN', 'Malay', 'Brunei Darussalam', 'Bahasa Melayu (Brunei Darussalam)', 'ltr', 'BRN', 'MSB', 'bn.png'),
(141, 'ms-MY', 'Malay', 'Malaysia', 'Bahasa Melayu (Malaysia)', 'ltr', 'MYS', 'MSL', 'my.png'),
(142, 'ml-IN', 'Malayalam', 'India', 'മലയാളം (ഭാരതം)', 'rtl', 'IND', 'MYM', 'in.png'),
(143, 'mt-MT', 'Maltese', 'Malta', 'Malti (Malta)', 'ltr', 'MLT', 'MLT', 'mt.png'),
(144, 'mi-NZ', 'Maori', 'New Zealand', 'Reo Māori (Aotearoa)', 'ltr', 'NZL', 'MRI', 'nz.png'),
(145, 'arn-CL', 'Mapudungun', 'Chile', 'Mapudungun (Chile)', 'ltr', 'CHL', 'MPD', 'cl.png'),
(146, 'mr-IN', 'Marathi', 'India', 'मराठी (भारत)', 'ltr', 'IND', 'MAR', 'in.png'),
(147, 'moh-CA', 'Mohawk', 'Mohawk', 'Kanien''kéha', 'ltr', 'CAN', 'MWK', 'ca.png'),
(148, 'mn', 'Mongolian', 'Cyrillic', 'Монгол хэл', 'ltr', 'MNG', 'MNN', 'mn.png'),
(149, 'mn-Cyrl', 'Mongolian', 'Cyrillic', 'Монгол хэл', 'ltr', 'MNG', 'MNN', 'mn.png'),
(150, 'mn-MN', 'Mongolian', 'Cyrillic, Mongolia', 'Монгол хэл (Монгол улс)', 'ltr', 'MNG', 'MNN', 'mn.png'),
(151, 'mn-Mong', 'Mongolian', 'Traditional Mongolian', 'ᠮᠤᠨᠭᠭᠤᠯ ᠬᠡᠯᠡ', 'ltr', 'CHN', 'MNG', 'cn.png'),
(152, 'mn-Mong-CN', 'Mongolian', 'Traditional Mongolian, PRC', 'ᠮᠤᠨᠭᠭᠤᠯ ᠬᠡᠯᠡ (ᠪᠦᠭᠦᠳᠡ ᠨᠠᠢᠷᠠᠮᠳᠠᠬᠤ ᠳᠤᠮᠳᠠᠳᠤ ᠠᠷᠠᠳ ᠣᠯᠣᠰ)', 'ltr', 'CHN', 'MNG', 'cn.png'),
(153, 'ne-NP', 'Nepali', 'Nepal', 'नेपाली (नेपाल)', 'ltr', 'NEP', 'NEP', 'np.png'),
(154, 'no', 'Norwegian', NULL, 'norsk', 'ltr', 'NOR', 'NOR', 'empty.png'),
(155, 'nb', 'Norwegian', 'Bokmål', 'norsk (bokmål)', 'ltr', 'NOR', 'NOR', 'no.png'),
(156, 'nn', 'Norwegian', 'Nynorsk', 'norsk (nynorsk)', 'ltr', 'NOR', 'NON', 'no.png'),
(157, 'nb-NO', 'Norwegian, Bokmål', 'Norway', 'norsk, bokmål (Norge)', 'ltr', 'NOR', 'NOR', 'no.png'),
(158, 'nn-NO', 'Norwegian, Nynorsk', 'Norway', 'norsk, nynorsk (Noreg)', 'ltr', 'NOR', 'NON', 'no.png'),
(159, 'oc-FR', 'Occitan', 'France', 'Occitan (França)', 'ltr', 'FRA', 'OCI', 'fr.png'),
(160, 'or-IN', 'Oriya', 'India', 'ଓଡ଼ିଆ (ଭାରତ)', 'ltr', 'IND', 'ORI', 'in.png'),
(161, 'ps-AF', 'Pashto', 'Afghanistan', 'پښتو (افغانستان)‏', 'rtl', 'AFG', 'PAS', 'af.png'),
(162, 'fa-IR', 'Persian‎', NULL, 'فارسى (ایران)‏', 'rtl', 'IRN', 'FAR', 'empty.png'),
(163, 'pl-PL', 'Polish', 'Poland', 'polski (Polska)', 'ltr', 'POL', 'PLK', 'pl.png'),
(164, 'pt', 'Portuguese', NULL, 'Português', 'ltr', 'BRA', 'PTB', 'empty.png'),
(165, 'pt-BR', 'Portuguese', 'Brazil', 'Português (Brasil)', 'ltr', 'BRA', 'PTB', 'br.png'),
(166, 'pt-PT', 'Portuguese', 'Portugal', 'português (Portugal)', 'ltr', 'PRT', 'PTG', 'pt.png'),
(167, 'pa-IN', 'Punjabi', 'India', 'ਪੰਜਾਬੀ (ਭਾਰਤ)', 'rtl', 'IND', 'PAN', 'in.png'),
(168, 'quz', 'Quechua', NULL, 'runasimi', 'ltr', 'BOL', 'QUB', 'empty.png'),
(169, 'quz-BO', 'Quechua', 'Bolivia', 'runasimi (Qullasuyu)', 'ltr', 'BOL', 'QUB', 'bo.png'),
(170, 'quz-EC', 'Quechua', 'Ecuador', 'runasimi (Ecuador)', 'ltr', 'ECU', 'QUE', 'ec.png'),
(171, 'quz-PE', 'Quechua', 'Peru', 'runasimi (Piruw)', 'ltr', 'PER', 'QUP', 'pe.png'),
(172, 'ro-RO', 'Romanian', 'Romania', 'română (România)', 'ltr', 'ROM', 'ROM', 'ro.png'),
(173, 'rm-CH', 'Romansh', 'Switzerland', 'Rumantsch (Svizra)', 'ltr', 'CHE', 'RMC', 'ch.png'),
(174, 'ru-RU', 'Russian', 'Russia', 'русский (Россия)', 'ltr', 'RUS', 'RUS', 'ru.png'),
(175, 'smn', 'Sami', 'Inari', 'sämikielâ', 'ltr', 'FIN', 'SMN', 'fi.png'),
(176, 'smj', 'Sami', 'Lule', 'julevusámegiella', 'ltr', 'SWE', 'SMK', 'se.png'),
(177, 'se', 'Sami', 'Northern', 'davvisámegiella', 'ltr', 'NOR', 'SME', 'no.png'),
(178, 'sms', 'Sami', 'Skolt', 'sääm´ǩiõll', 'ltr', 'FIN', 'SMS', 'fi.png'),
(179, 'sma', 'Sami', 'Southern', 'åarjelsaemiengiele', 'ltr', 'SWE', 'SMB', 'se.png'),
(180, 'smn-FI', 'Sami, Inari', 'Finland', 'sämikielâ (Suomâ)', 'ltr', 'FIN', 'SMN', 'fi.png'),
(181, 'smj-NO', 'Sami, Lule', 'Norway', 'julevusámegiella (Vuodna)', 'ltr', 'NOR', 'SMJ', 'no.png'),
(182, 'smj-SE', 'Sami, Lule', 'Sweden', 'julevusámegiella (Svierik)', 'ltr', 'SWE', 'SMK', 'se.png'),
(183, 'se-FI', 'Sami, Northern', 'Finland', 'davvisámegiella (Suopma)', 'ltr', 'FIN', 'SMG', 'fi.png'),
(184, 'se-NO', 'Sami, Northern', 'Norway', 'davvisámegiella (Norga)', 'ltr', 'NOR', 'SME', 'no.png'),
(185, 'se-SE', 'Sami, Northern', 'Sweden', 'davvisámegiella (Ruoŧŧa)', 'ltr', 'SWE', 'SMF', 'se.png'),
(186, 'sms-FI', 'Sami, Skolt', 'Finland', 'sääm´ǩiõll (Lää´ddjânnam)', 'ltr', 'FIN', 'SMS', 'fi.png'),
(187, 'sma-NO', 'Sami, Southern', 'Norway', 'åarjelsaemiengiele (Nöörje)', 'ltr', 'NOR', 'SMA', 'no.png'),
(188, 'sma-SE', 'Sami, Southern', 'Sweden', 'åarjelsaemiengiele (Sveerje)', 'ltr', 'SWE', 'SMB', 'se.png'),
(189, 'sa-IN', 'Sanskrit', 'India', 'संस्कृत (भारतम्)', 'ltr', 'IND', 'SAN', 'in.png'),
(190, 'gd-GB', 'Scottish Gaelic', 'United Kingdom', 'Gàidhlig (An Rìoghachd Aonaichte)', 'ltr', 'GBR', 'GLA', 'gb.png'),
(191, 'sr', 'Serbian', NULL, 'srpski', 'ltr', 'SRB', 'SRM', 'empty.png'),
(192, 'sr-Cyrl', 'Serbian', 'Cyrillic', 'српски (Ћирилица)', 'ltr', 'SRB', 'SRO', 'rs.png'),
(193, 'sr-Cyrl-BA', 'Serbian', 'Cyrillic, Bosnia and Herzegovina', 'српски (Босна и Херцеговина)', 'ltr', 'BIH', 'SRN', 'ba.png'),
(194, 'sr-Cyrl-ME', 'Serbian', 'Cyrillic, Montenegro', 'српски (Црна Гора)', 'ltr', 'MNE', 'SRQ', 'me.png'),
(195, 'sr-Cyrl-CS', 'Serbian', 'Cyrillic, Serbia and Montenegro (Former)', 'српски (Србија и Црна Гора (Претходно))', 'ltr', 'SCG', 'SRB', 'rs.png'),
(196, 'sr-Cyrl-RS', 'Serbian', 'Cyrillic, Serbia', 'српски (Србија)', 'ltr', 'SRB', 'SRO', 'rs.png'),
(197, 'sr-Latn', 'Serbian', 'Latin', 'srpski (Latinica)', 'ltr', 'SRB', 'SRM', 'rs.png'),
(198, 'sr-Latn-BA', 'Serbian', 'Latin, Bosnia and Herzegovina', 'srpski (Bosna i Hercegovina)', 'ltr', 'BIH', 'SRS', 'ba.png'),
(199, 'sr-Latn-ME', 'Serbian', 'Latin, Montenegro', 'srpski (Crna Gora)', 'ltr', 'MNE', 'SRP', 'me.png'),
(200, 'sr-Latn-CS', 'Serbian', 'Latin, Serbia and Montenegro (Former)', 'srpski (Srbija i Crna Gora (Prethodno))', 'ltr', 'SCG', 'SRL', 'rs.png'),
(201, 'sr-Latn-RS', 'Serbian', 'Latin, Serbia', 'srpski (Srbija)', 'ltr', 'SRB', 'SRM', 'rs.png'),
(202, 'nso-ZA', 'Sesotho sa Leboa', 'South Africa', 'Sesotho sa Leboa (Afrika Borwa)', 'ltr', 'ZAF', 'NSO', 'za.png'),
(203, 'tn-ZA', 'Setswana', 'South Africa', 'Setswana (Aforika Borwa)', 'ltr', 'ZAF', 'TSN', 'za.png'),
(204, 'si-LK', 'Sinhala', 'Sri Lanka', 'සිංහ (ශ්‍රී ලංකා)', 'ltr', 'LKA', 'SIN', 'lk.png'),
(205, 'sk-SK', 'Slovak', 'Slovakia', 'slovenčina (Slovenská republika)', 'ltr', 'SVK', 'SKY', 'sk.png'),
(206, 'sl-SI', 'Slovenian', 'Slovenia', 'slovenski (Slovenija)', 'ltr', 'SVN', 'SLV', 'si.png'),
(207, 'es', 'Spanish', NULL, 'español', 'ltr', 'ESP', 'ESN', 'empty.png'),
(208, 'es-AR', 'Spanish', 'Argentina', 'Español (Argentina)', 'ltr', 'ARG', 'ESS', 'ar.png'),
(209, 'es-BO', 'Spanish', 'Bolivia', 'Español (Bolivia)', 'ltr', 'BOL', 'ESB', 'bo.png'),
(210, 'es-CL', 'Spanish', 'Chile', 'Español (Chile)', 'ltr', 'CHL', 'ESL', 'cl.png'),
(211, 'es-CO', 'Spanish', 'Colombia', 'Español (Colombia)', 'ltr', 'COL', 'ESO', 'co.png'),
(212, 'es-CR', 'Spanish', 'Costa Rica', 'Español (Costa Rica)', 'ltr', 'CRI', 'ESC', 'cr.png'),
(213, 'es-DO', 'Spanish', 'Dominican Republic', 'Español (República Dominicana)', 'ltr', 'DOM', 'ESD', 'do.png'),
(214, 'es-EC', 'Spanish', 'Ecuador', 'Español (Ecuador)', 'ltr', 'ECU', 'ESF', 'ec.png'),
(215, 'es-SV', 'Spanish', 'El Salvador', 'Español (El Salvador)', 'ltr', 'SLV', 'ESE', 'sv.png'),
(216, 'es-GT', 'Spanish', 'Guatemala', 'Español (Guatemala)', 'ltr', 'GTM', 'ESG', 'gt.png'),
(217, 'es-HN', 'Spanish', 'Honduras', 'Español (Honduras)', 'ltr', 'HND', 'ESH', 'hn.png'),
(218, 'es-MX', 'Spanish', 'Mexico', 'Español (México)', 'ltr', 'MEX', 'ESM', 'mx.png'),
(219, 'es-NI', 'Spanish', 'Nicaragua', 'Español (Nicaragua)', 'ltr', 'NIC', 'ESI', 'ni.png'),
(220, 'es-PA', 'Spanish', 'Panama', 'Español (Panamá)', 'ltr', 'PAN', 'ESA', 'pa.png'),
(221, 'es-PY', 'Spanish', 'Paraguay', 'Español (Paraguay)', 'ltr', 'PRY', 'ESZ', 'py.png'),
(222, 'es-PE', 'Spanish', 'Peru', 'Español (Perú)', 'ltr', 'PER', 'ESR', 'pe.png'),
(223, 'es-PR', 'Spanish', 'Puerto Rico', 'Español (Puerto Rico)', 'ltr', 'PRI', 'ESU', 'pr.png'),
(224, 'es-ES', 'Spanish', 'Spain, International Sort', 'Español (España, alfabetización internacional)', 'ltr', 'ESP', 'ESN', 'es.png'),
(225, 'es-US', 'Spanish', 'United States', 'Español (Estados Unidos)', 'ltr', 'USA', 'EST', 'us.png'),
(226, 'es-UY', 'Spanish', 'Uruguay', 'Español (Uruguay)', 'ltr', 'URY', 'ESY', 'uy.png'),
(227, 'es-VE', 'Spanish', 'Venezuela', 'Español (Republica Bolivariana de Venezuela)', 'ltr', 'VEN', 'ESV', 've.png'),
(228, 'sv', 'Swedish', NULL, 'svenska', 'ltr', 'SWE', 'SVE', 'empty.png'),
(229, 'sv-FI', 'Swedish', 'Finland', 'svenska (Finland)', 'ltr', 'FIN', 'SVF', 'fi.png'),
(230, 'sv-SE', 'Swedish', 'Sweden', 'svenska (Sverige)', 'ltr', 'SWE', 'SVE', 'se.png'),
(231, 'syr-SY', 'Syriac', 'Syria', 'ܣܘܪܝܝܐ (سوريا)‏', 'rtl', 'SYR', 'SYR', 'sy.png'),
(232, 'tg', 'Tajik', 'Cyrillic', 'Тоҷикӣ', 'ltr', 'TAJ', 'TAJ', 'tj.png'),
(233, 'tg-Cyrl', 'Tajik', 'Cyrillic', 'Тоҷикӣ', 'ltr', 'TAJ', 'TAJ', 'tj.png'),
(234, 'tg-Cyrl-TJ', 'Tajik', 'Cyrillic, Tajikistan', 'Тоҷикӣ (Тоҷикистон)', 'ltr', 'TAJ', 'TAJ', 'tj.png'),
(235, 'tzm', 'Tamazight', NULL, 'Tamazight', 'ltr', 'DZA', 'TZM', 'empty.png'),
(236, 'tzm-Latn', 'Tamazight', 'Latin', 'Tamazight (Latin)', 'ltr', 'DZA', 'TZM', 'dz.png'),
(237, 'tzm-Latn-DZ', 'Tamazight', 'Latin, Algeria', 'Tamazight (Djazaïr)', 'ltr', 'DZA', 'TZM', 'dz.png'),
(238, 'ta-IN', 'Tamil', 'India', 'தமிழ் (இந்தியா)', 'ltr', 'IND', 'TAM', 'in.png'),
(239, 'tt-RU', 'Tatar', 'Russia', 'Татар (Россия)', 'ltr', 'RUS', 'TTT', 'ru.png'),
(240, 'te-IN', 'Telugu', 'India', 'తెలుగు (భారత దేశం)', 'ltr', 'IND', 'TEL', 'in.png'),
(241, 'th-TH', 'Thai', 'Thailand', 'ไทย (ไทย)', 'ltr', 'THA', 'THA', 'th.png'),
(242, 'bo-CN', 'Tibetan', 'PRC', 'བོད་ཡིག (ཀྲུང་ཧྭ་མི་དམངས་སྤྱི་མཐུན་རྒྱལ་ཁབ།)', 'ltr', 'CHN', 'BOB', 'cn.png'),
(243, 'tr-TR', 'Turkish', 'Turkey', 'Türkçe (Türkiye)', 'ltr', 'TUR', 'TRK', 'tr.png'),
(244, 'tk-TM', 'Turkmen', 'Turkmenistan', 'türkmençe (Türkmenistan)', 'rtl', 'TKM', 'TUK', 'tm.png'),
(245, 'uk-UA', 'Ukrainian', 'Ukraine', 'українська (Україна)', 'ltr', 'UKR', 'UKR', 'ua.png'),
(246, 'hsb-DE', 'Upper Sorbian', 'Germany', 'hornjoserbšćina (Němska)', 'ltr', 'GER', 'HSB', 'de.png'),
(247, 'ur-PK', 'Urdu', 'Islamic Republic of Pakistan', 'اُردو (پاکستان)‏', 'rtl', 'PAK', 'URD', 'pk.png'),
(248, 'ug-CN', 'Uyghur', 'PRC', '(ئۇيغۇر يېزىقى (جۇڭخۇا خەلق جۇمھۇرىيىتى‏', 'rtl', 'CHN', 'UIG', 'cn.png'),
(249, 'uz-Cyrl', 'Uzbek', 'Cyrillic', 'Ўзбек', 'ltr', 'UZB', 'UZB', 'uz.png'),
(250, 'uz-Cyrl-UZ', 'Uzbek', 'Cyrillic, Uzbekistan', 'Ўзбек (Ўзбекистон)', 'ltr', 'UZB', 'UZB', 'uz.png'),
(251, 'uz', 'Uzbek', 'Latin', 'U''zbek', 'ltr', 'UZB', 'UZB', 'uz.png'),
(252, 'uz-Latn', 'Uzbek', 'Latin', 'U''zbek', 'ltr', 'UZB', 'UZB', 'uz.png'),
(253, 'uz-Latn-UZ', 'Uzbek', 'Latin, Uzbekistan', 'U''zbek (U''zbekiston Respublikasi)', 'ltr', 'UZB', 'UZB', 'uz.png'),
(254, 'vi-VN', 'Vietnamese', 'Vietnam', 'Tiếng Việt (Việt Nam)', 'ltr', 'VNM', 'VIT', 'vn.png'),
(255, 'cy-GB', 'Welsh', 'United Kingdom', 'Cymraeg (y Deyrnas Unedig)', 'ltr', 'GBR', 'CYM', 'gb.png'),
(256, 'wo-SN', 'Wolof', 'Senegal', 'Wolof (Sénégal)', 'ltr', 'SEN', 'WOL', 'sn.png'),
(257, 'sah-RU', 'Yakut', 'Russia', 'саха (Россия)', 'ltr', 'RUS', 'SAH', 'ru.png'),
(258, 'ii-CN', 'Yi', 'PRC', 'ꆈꌠꁱꂷ (ꍏꉸꏓꂱꇭꉼꇩ)', 'ltr', 'CHN', 'III', 'cn.png'),
(259, 'yo-NG', 'Yoruba', 'Nigeria', 'Yoruba (Nigeria)', 'ltr', 'NGA', 'YOR', 'ng.png');

UPDATE `plugin_locale` SET `language_iso` = 'af', `name` = 'Afrikaans' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'af-ZA', `name` = 'Afrikaans (Suid Afrika)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sq', `name` = 'shqipe' WHERE `language_iso` = 'al' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sq-AL', `name` = 'shqipe (Shqipëria)' WHERE `language_iso` = 'al' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gsw', `name` = 'Elsässisch' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gsw-FR', `name` = 'Elsässisch (Frànkrisch)' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'am', `name` = 'አማርኛ' WHERE `language_iso` = 'et' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'am-ET', `name` = 'አማርኛ (ኢትዮጵያ)' WHERE `language_iso` = 'et' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar', `name` = 'العربية‏' WHERE `language_iso` = 'sa' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-DZ', `name` = 'العربية (الجزائر)‏' WHERE `language_iso` = 'dz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-BH', `name` = 'العربية (البحرين)‏' WHERE `language_iso` = 'bh' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-EG', `name` = 'العربية (مصر)‏' WHERE `language_iso` = 'eg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-IQ', `name` = 'العربية (العراق)‏' WHERE `language_iso` = 'iq' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-JO', `name` = 'العربية (الأردن)‏' WHERE `language_iso` = 'jo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-KW', `name` = 'العربية (الكويت)‏' WHERE `language_iso` = 'kw' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-LB', `name` = 'العربية (لبنان)‏' WHERE `language_iso` = 'lb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-LY', `name` = 'العربية (ليبيا)‏' WHERE `language_iso` = 'ly' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-MA', `name` = 'العربية (المملكة المغربية)‏' WHERE `language_iso` = 'ma' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-OM', `name` = 'العربية (عمان)‏' WHERE `language_iso` = 'om' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-QA', `name` = 'العربية (قطر)‏' WHERE `language_iso` = 'qa' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-SA', `name` = 'العربية (المملكة العربية السعودية)‏' WHERE `language_iso` = 'sa' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-SY', `name` = 'العربية (سوريا)‏' WHERE `language_iso` = 'sy' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-TN', `name` = 'العربية (تونس)‏' WHERE `language_iso` = 'tn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-AE', `name` = 'العربية (الإمارات العربية المتحدة)‏' WHERE `language_iso` = 'ae' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ar-YE', `name` = 'العربية (اليمن)‏' WHERE `language_iso` = 'ye' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hy', `name` = 'Հայերեն' WHERE `language_iso` = 'am' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hy-AM', `name` = 'Հայերեն (Հայաստան)' WHERE `language_iso` = 'am' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'as', `name` = 'অসমীয়া' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'as-IN', `name` = 'অসমীয়া (ভাৰত)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'az', `name` = 'Azərbaycan­ılı' WHERE `language_iso` = 'az' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'az-Cyrl', `name` = 'Азәрбајҹан дили' WHERE `language_iso` = 'az' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'az-Cyrl-AZ', `name` = 'Азәрбајҹан (Азәрбајҹан)' WHERE `language_iso` = 'az' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'az-Latn', `name` = 'Azərbaycan­ılı' WHERE `language_iso` = 'az' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'az-Latn-AZ', `name` = 'Azərbaycan­ılı (Azərbaycan)' WHERE `language_iso` = 'az' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ba', `name` = 'Башҡорт' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ba-RU', `name` = 'Башҡорт (Россия)' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'eu', `name` = 'euskara' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'eu-ES', `name` = 'euskara (euskara)' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'be', `name` = 'Беларускі' WHERE `language_iso` = 'by' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'be-BY', `name` = 'Беларускі (Беларусь)' WHERE `language_iso` = 'by' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bn', `name` = 'বাংলা' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bn-BD', `name` = 'বাংলা (বাংলাদেশ)' WHERE `language_iso` = 'bd' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bn-IN', `name` = 'বাংলা (ভারত)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bs', `name` = 'bosanski' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bs-Cyrl', `name` = 'босански (Ћирилица)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bs-Cyrl-BA', `name` = 'босански (Босна и Херцеговина)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bs-Latn', `name` = 'bosanski (Latinica)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bs-Latn-BA', `name` = 'bosanski (Bosna i Hercegovina)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'br', `name` = 'brezhoneg' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'br-FR', `name` = 'brezhoneg (Frañs)' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bg', `name` = 'български' WHERE `language_iso` = 'bg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bg-BG', `name` = 'български (България)' WHERE `language_iso` = 'bg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ca', `name` = 'català' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ca-ES', `name` = 'català (català)' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh', `name` = '中文' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-Hans', `name` = '中文(简体)' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-CN', `name` = '中文(中华人民共和国)' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-SG', `name` = '中文(新加坡)' WHERE `language_iso` = 'sg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-Hant', `name` = '中文(繁體)' WHERE `language_iso` = 'hk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-HK', `name` = '中文(香港特別行政區)' WHERE `language_iso` = 'hk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-MO', `name` = '中文(澳門特別行政區)' WHERE `language_iso` = 'mc' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zh-TW', `name` = '中文(台灣)' WHERE `language_iso` = 'tw' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'co', `name` = 'Corsu' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'co-FR', `name` = 'Corsu (France)' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hr', `name` = 'hrvatski' WHERE `language_iso` = 'hr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hr-HR', `name` = 'hrvatski (Hrvatska)' WHERE `language_iso` = 'hr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hr-BA', `name` = 'hrvatski (Bosna i Hercegovina)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'cs', `name` = 'čeština' WHERE `language_iso` = 'cz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'cs-CZ', `name` = 'čeština (Česká republika)' WHERE `language_iso` = 'cz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'da', `name` = 'dansk' WHERE `language_iso` = 'dk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'da-DK', `name` = 'dansk (Danmark)' WHERE `language_iso` = 'dk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'prs', `name` = 'درى‏' WHERE `language_iso` = 'af' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'prs-AF', `name` = 'درى (افغانستان)‏' WHERE `language_iso` = 'af' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'dv', `name` = 'ދިވެހިބަސް‏' WHERE `language_iso` = 'mv' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'dv-MV', `name` = 'ދިވެހިބަސް (ދިވެހި ރާއްޖެ)‏' WHERE `language_iso` = 'mv' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nl', `name` = 'Nederlands' WHERE `language_iso` = 'nl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nl-BE', `name` = 'Nederlands (België)' WHERE `language_iso` = 'be' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nl-NL', `name` = 'Nederlands (Nederland)' WHERE `language_iso` = 'nl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en', `name` = 'English' WHERE `language_iso` = 'us' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-AU', `name` = 'English (Australia)' WHERE `language_iso` = 'au' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-BZ', `name` = 'English (Belize)' WHERE `language_iso` = 'bz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-CA', `name` = 'English (Canada)' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-IN', `name` = 'English (India)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-IE', `name` = 'English (Ireland)' WHERE `language_iso` = 'ie' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-JM', `name` = 'English (Jamaica)' WHERE `language_iso` = 'jm' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-MY', `name` = 'English (Malaysia)' WHERE `language_iso` = 'my' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-NZ', `name` = 'English (New Zealand)' WHERE `language_iso` = 'nz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-PH', `name` = 'English (Philippines)' WHERE `language_iso` = 'ph' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-SG', `name` = 'English (Singapore)' WHERE `language_iso` = 'sg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-ZA', `name` = 'English (South Africa)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-TT', `name` = 'English (Trinidad y Tobago)' WHERE `language_iso` = 'tt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-GB', `name` = 'English (United Kingdom)' WHERE `language_iso` = 'gb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-US', `name` = 'English (United States)' WHERE `language_iso` = 'us' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'en-ZW', `name` = 'English (Zimbabwe)' WHERE `language_iso` = 'zw' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'et', `name` = 'eesti' WHERE `language_iso` = 'ee' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'et-EE', `name` = 'eesti (Eesti)' WHERE `language_iso` = 'ee' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fo', `name` = 'føroyskt' WHERE `language_iso` = 'fo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fo-FO', `name` = 'føroyskt (Føroyar)' WHERE `language_iso` = 'fo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fil', `name` = 'Filipino' WHERE `language_iso` = 'ph' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fil-PH', `name` = 'Filipino (Pilipinas)' WHERE `language_iso` = 'ph' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fi', `name` = 'suomi' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fi-FI', `name` = 'suomi (Suomi)' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr', `name` = 'français' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-BE', `name` = 'français (Belgique)' WHERE `language_iso` = 'be' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-CA', `name` = 'français (Canada)' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-FR', `name` = 'français (France)' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-LU', `name` = 'français (Luxembourg)' WHERE `language_iso` = 'lu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-MC', `name` = 'français (Principauté de Monaco)' WHERE `language_iso` = 'mc' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fr-CH', `name` = 'français (Suisse)' WHERE `language_iso` = 'ch' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fy', `name` = 'Frysk' WHERE `language_iso` = 'nl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fy-NL', `name` = 'Frysk (Nederlân)' WHERE `language_iso` = 'nl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gl', `name` = 'galego' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gl-ES', `name` = 'galego (galego)' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ka', `name` = 'ქართული' WHERE `language_iso` = 'ge' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ka-GE', `name` = 'ქართული (საქართველო)' WHERE `language_iso` = 'ge' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de', `name` = 'Deutsch' WHERE `language_iso` = 'de' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de-AT', `name` = 'Deutsch (Österreich)' WHERE `language_iso` = 'at' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de-DE', `name` = 'Deutsch (Deutschland)' WHERE `language_iso` = 'de' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de-LI', `name` = 'Deutsch (Liechtenstein)' WHERE `language_iso` = 'li' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de-LU', `name` = 'Deutsch (Luxemburg)' WHERE `language_iso` = 'lu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'de-CH', `name` = 'Deutsch (Schweiz)' WHERE `language_iso` = 'ch' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'el', `name` = 'Ελληνικά' WHERE `language_iso` = 'gr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'el-GR', `name` = 'Ελληνικά (Ελλάδα)' WHERE `language_iso` = 'gr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kl', `name` = 'kalaallisut' WHERE `language_iso` = 'gl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kl-GL', `name` = 'kalaallisut (Kalaallit Nunaat)' WHERE `language_iso` = 'gl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gu', `name` = 'ગુજરાતી' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gu-IN', `name` = 'ગુજરાતી (ભારત)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ha', `name` = 'Hausa' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ha-Latn', `name` = 'Hausa (Latin)' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ha-Latn-NG', `name` = 'Hausa (Nigeria)' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'he', `name` = 'עברית‏' WHERE `language_iso` = 'il' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'he-IL', `name` = 'עברית (ישראל)‏' WHERE `language_iso` = 'il' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hi', `name` = 'हिंदी' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hi-IN', `name` = 'हिंदी (भारत)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hu', `name` = 'magyar' WHERE `language_iso` = 'hu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'hu-HU', `name` = 'magyar (Magyarország)' WHERE `language_iso` = 'hu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'is', `name` = 'íslenska' WHERE `language_iso` = 'is' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'is-IS', `name` = 'íslenska (Ísland)' WHERE `language_iso` = 'is' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ig', `name` = 'Igbo' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ig-NG', `name` = 'Igbo (Nigeria)' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'id', `name` = 'Bahasa Indonesia' WHERE `language_iso` = 'id' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'id-ID', `name` = 'Bahasa Indonesia (Indonesia)' WHERE `language_iso` = 'id' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'iu', `name` = 'Inuktitut' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'iu-Latn', `name` = 'Inuktitut (Qaliujaaqpait)' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'iu-Latn-CA', `name` = 'Inuktitut' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'iu-Cans', `name` = 'ᐃᓄᒃᑎᑐᑦ (ᖃᓂᐅᔮᖅᐸᐃᑦ)' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'iu-Cans-CA', `name` = 'ᐃᓄᒃᑎᑐᑦ (ᑲᓇᑕᒥ)' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ga', `name` = 'Gaeilge' WHERE `language_iso` = 'ie' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ga-IE', `name` = 'Gaeilge (Éire)' WHERE `language_iso` = 'ie' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'xh', `name` = 'isiXhosa' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'xh-ZA', `name` = 'isiXhosa (uMzantsi Afrika)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zu', `name` = 'isiZulu' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'zu-ZA', `name` = 'isiZulu (iNingizimu Afrika)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'it', `name` = 'italiano' WHERE `language_iso` = 'it' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'it-IT', `name` = 'italiano (Italia)' WHERE `language_iso` = 'it' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'it-CH', `name` = 'italiano (Svizzera)' WHERE `language_iso` = 'ch' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ja', `name` = '日本語' WHERE `language_iso` = 'jp' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ja-JP', `name` = '日本語 (日本)' WHERE `language_iso` = 'jp' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kn', `name` = 'ಕನ್ನಡ' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kn-IN', `name` = 'ಕನ್ನಡ (ಭಾರತ)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kk', `name` = 'Қазақ' WHERE `language_iso` = 'kz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kk-KZ', `name` = 'Қазақ (Қазақстан)' WHERE `language_iso` = 'kz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'km', `name` = 'ខ្មែរ' WHERE `language_iso` = 'kh' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'km-KH', `name` = 'ខ្មែរ (កម្ពុជា)' WHERE `language_iso` = 'kh' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'qut', `name` = 'K\'iche' WHERE `language_iso` = 'gt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'qut-GT', `name` = 'K\'iche (Guatemala)' WHERE `language_iso` = 'gt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'rw', `name` = 'Kinyarwanda' WHERE `language_iso` = 'rw' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'rw-RW', `name` = 'Kinyarwanda (Rwanda)' WHERE `language_iso` = 'rw' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sw', `name` = 'Kiswahili' WHERE `language_iso` = 'ke' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sw-KE', `name` = 'Kiswahili (Kenya)' WHERE `language_iso` = 'ke' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kok', `name` = 'कोंकणी' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'kok-IN', `name` = 'कोंकणी (भारत)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ko', `name` = '한국어' WHERE `language_iso` = 'kr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ko-KR', `name` = '한국어 (대한민국)' WHERE `language_iso` = 'kr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ky', `name` = 'Кыргыз' WHERE `language_iso` = 'kg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ky-KG', `name` = 'Кыргыз (Кыргызстан)' WHERE `language_iso` = 'kg' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lo', `name` = 'ລາວ' WHERE `language_iso` = 'la' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lo-LA', `name` = 'ລາວ (ສ.ປ.ປ. ລາວ)' WHERE `language_iso` = 'la' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lv', `name` = 'latviešu' WHERE `language_iso` = 'lv' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lv-LV', `name` = 'latviešu (Latvija)' WHERE `language_iso` = 'lv' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lt', `name` = 'lietuvių' WHERE `language_iso` = 'lt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lt-LT', `name` = 'lietuvių (Lietuva)' WHERE `language_iso` = 'lt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lb', `name` = 'Lëtzebuergesch' WHERE `language_iso` = 'lu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'lb-LU', `name` = 'Lëtzebuergesch (Luxembourg)' WHERE `language_iso` = 'lu' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mk-MK', `name` = 'македонски јазик (Македонија)' WHERE `language_iso` = 'mk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mk', `name` = 'македонски јазик' WHERE `language_iso` = 'mk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ms', `name` = 'Bahasa Melayu' WHERE `language_iso` = 'my' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ms-BN', `name` = 'Bahasa Melayu (Brunei Darussalam)' WHERE `language_iso` = 'bn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ms-MY', `name` = 'Bahasa Melayu (Malaysia)' WHERE `language_iso` = 'my' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ml', `name` = 'മലയാളം' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ml-IN', `name` = 'മലയാളം (ഭാരതം)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mt', `name` = 'Malti' WHERE `language_iso` = 'mt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mt-MT', `name` = 'Malti (Malta)' WHERE `language_iso` = 'mt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mi', `name` = 'Reo Māori' WHERE `language_iso` = 'nz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mi-NZ', `name` = 'Reo Māori (Aotearoa)' WHERE `language_iso` = 'nz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'arn', `name` = 'Mapudungun' WHERE `language_iso` = 'cl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'arn-CL', `name` = 'Mapudungun (Chile)' WHERE `language_iso` = 'cl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mr', `name` = 'मराठी' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mr-IN', `name` = 'मराठी (भारत)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'moh', `name` = 'Kanien\'kéha' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'moh-CA', `name` = 'Kanien\'kéha' WHERE `language_iso` = 'ca' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mn', `name` = 'Монгол хэл' WHERE `language_iso` = 'mn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mn-Cyrl', `name` = 'Монгол хэл' WHERE `language_iso` = 'mn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mn-MN', `name` = 'Монгол хэл (Монгол улс)' WHERE `language_iso` = 'mn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mn-Mong', `name` = 'ᠮᠤᠨᠭᠭᠤᠯ ᠬᠡᠯᠡ' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'mn-Mong-CN', `name` = 'ᠮᠤᠨᠭᠭᠤᠯ ᠬᠡᠯᠡ (ᠪᠦᠭᠦᠳᠡ ᠨᠠᠢᠷᠠᠮᠳᠠᠬᠤ ᠳᠤᠮᠳᠠᠳᠤ ᠠᠷᠠᠳ ᠣᠯᠣᠰ)' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'no', `name` = 'norsk' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nb', `name` = 'norsk (bokmål)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nn', `name` = 'norsk (nynorsk)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nb-NO', `name` = 'norsk, bokmål (Norge)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nn-NO', `name` = 'norsk, nynorsk (Noreg)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'oc', `name` = 'Occitan' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'oc-FR', `name` = 'Occitan (França)' WHERE `language_iso` = 'fr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'or', `name` = 'ଓଡ଼ିଆ' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'or-IN', `name` = 'ଓଡ଼ିଆ (ଭାରତ)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ps', `name` = 'پښتو‏' WHERE `language_iso` = 'af' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ps-AF', `name` = 'پښتو (افغانستان)‏' WHERE `language_iso` = 'af' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fa', `name` = 'فارسى‏' WHERE `language_iso` = 'ir' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'fa-IR', `name` = 'فارسى (ایران)‏' WHERE `language_iso` = 'ir' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pl', `name` = 'polski' WHERE `language_iso` = 'pl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pl-PL', `name` = 'polski (Polska)' WHERE `language_iso` = 'pl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pt', `name` = 'Português' WHERE `language_iso` = 'br' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pt-BR', `name` = 'Português (Brasil)' WHERE `language_iso` = 'br' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pt-PT', `name` = 'português (Portugal)' WHERE `language_iso` = 'pt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pa', `name` = 'ਪੰਜਾਬੀ' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'pa-IN', `name` = 'ਪੰਜਾਬੀ (ਭਾਰਤ)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'quz', `name` = 'runasimi' WHERE `language_iso` = 'bo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'quz-BO', `name` = 'runasimi (Qullasuyu)' WHERE `language_iso` = 'bo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'quz-EC', `name` = 'runasimi (Ecuador)' WHERE `language_iso` = 'ec' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'quz-PE', `name` = 'runasimi (Piruw)' WHERE `language_iso` = 'pe' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'rm', `name` = 'Rumantsch' WHERE `language_iso` = 'ch' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'rm-CH', `name` = 'Rumantsch (Svizra)' WHERE `language_iso` = 'ch' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ru', `name` = 'русский' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ru-RU', `name` = 'русский (Россия)' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'smn', `name` = 'sämikielâ' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'smj', `name` = 'julevusámegiella' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'se', `name` = 'davvisámegiella' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sms', `name` = 'sääm´ǩiõll' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sma', `name` = 'åarjelsaemiengiele' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'smn-FI', `name` = 'sämikielâ (Suomâ)' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'smj-NO', `name` = 'julevusámegiella (Vuodna)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'smj-SE', `name` = 'julevusámegiella (Svierik)' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'se-FI', `name` = 'davvisámegiella (Suopma)' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'se-NO', `name` = 'davvisámegiella (Norga)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'se-SE', `name` = 'davvisámegiella (Ruoŧŧa)' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sms-FI', `name` = 'sääm´ǩiõll (Lää´ddjânnam)' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sma-NO', `name` = 'åarjelsaemiengiele (Nöörje)' WHERE `language_iso` = 'no' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sma-SE', `name` = 'åarjelsaemiengiele (Sveerje)' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sa', `name` = 'संस्कृत' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sa-IN', `name` = 'संस्कृत (भारतम्)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gd', `name` = 'Gàidhlig' WHERE `language_iso` = 'gb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'gd-GB', `name` = 'Gàidhlig (An Rìoghachd Aonaichte)' WHERE `language_iso` = 'gb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr', `name` = 'srpski' WHERE `language_iso` = 'rs' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Cyrl', `name` = 'српски (Ћирилица)' WHERE `language_iso` = 'rs' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Cyrl-BA', `name` = 'српски (Босна и Херцеговина)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Cyrl-ME', `name` = 'српски (Црна Гора)' WHERE `language_iso` = 'me' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Cyrl-RS', `name` = 'српски (Србија)' WHERE `language_iso` = 'rs' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Latn', `name` = 'srpski (Latinica)' WHERE `language_iso` = 'rs' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Latn-BA', `name` = 'srpski (Bosna i Hercegovina)' WHERE `language_iso` = 'ba' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Latn-ME', `name` = 'srpski (Crna Gora)' WHERE `language_iso` = 'me' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sr-Latn-RS', `name` = 'srpski (Srbija)' WHERE `language_iso` = 'rs' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nso', `name` = 'Sesotho sa Leboa' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'nso-ZA', `name` = 'Sesotho sa Leboa (Afrika Borwa)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tn', `name` = 'Setswana' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tn-ZA', `name` = 'Setswana (Aforika Borwa)' WHERE `language_iso` = 'za' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'si', `name` = 'සිංහ' WHERE `language_iso` = 'lk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'si-LK', `name` = 'සිංහ (ශ්‍රී ලංකා)' WHERE `language_iso` = 'lk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sk', `name` = 'slovenčina' WHERE `language_iso` = 'sk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sk-SK', `name` = 'slovenčina (Slovenská republika)' WHERE `language_iso` = 'sk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sl', `name` = 'slovenski' WHERE `language_iso` = 'si' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sl-SI', `name` = 'slovenski (Slovenija)' WHERE `language_iso` = 'si' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es', `name` = 'español' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-AR', `name` = 'Español (Argentina)' WHERE `language_iso` = 'ar' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-BO', `name` = 'Español (Bolivia)' WHERE `language_iso` = 'bo' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-CL', `name` = 'Español (Chile)' WHERE `language_iso` = 'cl' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-CO', `name` = 'Español (Colombia)' WHERE `language_iso` = 'co' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-CR', `name` = 'Español (Costa Rica)' WHERE `language_iso` = 'cr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-DO', `name` = 'Español (República Dominicana)' WHERE `language_iso` = 'do' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-EC', `name` = 'Español (Ecuador)' WHERE `language_iso` = 'ec' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-SV', `name` = 'Español (El Salvador)' WHERE `language_iso` = 'sv' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-GT', `name` = 'Español (Guatemala)' WHERE `language_iso` = 'gt' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-HN', `name` = 'Español (Honduras)' WHERE `language_iso` = 'hn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-MX', `name` = 'Español (México)' WHERE `language_iso` = 'mx' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-NI', `name` = 'Español (Nicaragua)' WHERE `language_iso` = 'ni' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-PA', `name` = 'Español (Panamá)' WHERE `language_iso` = 'pa' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-PY', `name` = 'Español (Paraguay)' WHERE `language_iso` = 'py' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-PE', `name` = 'Español (Perú)' WHERE `language_iso` = 'pe' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-PR', `name` = 'Español (Puerto Rico)' WHERE `language_iso` = 'pr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-ES', `name` = 'Español (España, alfabetización internacional)' WHERE `language_iso` = 'es' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-US', `name` = 'Español (Estados Unidos)' WHERE `language_iso` = 'us' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-UY', `name` = 'Español (Uruguay)' WHERE `language_iso` = 'uy' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'es-VE', `name` = 'Español (Republica Bolivariana de Venezuela)' WHERE `language_iso` = 've' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sv', `name` = 'svenska' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sv-FI', `name` = 'svenska (Finland)' WHERE `language_iso` = 'fi' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sv-SE', `name` = 'svenska (Sverige)' WHERE `language_iso` = 'se' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'syr', `name` = 'ܣܘܪܝܝܐ‏' WHERE `language_iso` = 'sy' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'syr-SY', `name` = 'ܣܘܪܝܝܐ (سوريا)‏' WHERE `language_iso` = 'sy' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tzm', `name` = 'Tamazight' WHERE `language_iso` = 'dz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tzm-Latn', `name` = 'Tamazight (Latin)' WHERE `language_iso` = 'dz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tzm-Latn-DZ', `name` = 'Tamazight (Djazaïr)' WHERE `language_iso` = 'dz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ta', `name` = 'தமிழ்' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ta-IN', `name` = 'தமிழ் (இந்தியா)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tt', `name` = 'Татар' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tt-RU', `name` = 'Татар (Россия)' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'te', `name` = 'తెలుగు' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'te-IN', `name` = 'తెలుగు (భారత దేశం)' WHERE `language_iso` = 'in' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'th', `name` = 'ไทย' WHERE `language_iso` = 'th' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'th-TH', `name` = 'ไทย (ไทย)' WHERE `language_iso` = 'th' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bo', `name` = 'བོད་ཡིག' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'bo-CN', `name` = 'བོད་ཡིག (ཀྲུང་ཧྭ་མི་དམངས་སྤྱི་མཐུན་རྒྱལ་ཁབ།)' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tr', `name` = 'Türkçe' WHERE `language_iso` = 'tr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tr-TR', `name` = 'Türkçe (Türkiye)' WHERE `language_iso` = 'tr' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tk', `name` = 'türkmençe' WHERE `language_iso` = 'tm' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'tk-TM', `name` = 'türkmençe (Türkmenistan)' WHERE `language_iso` = 'tm' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uk', `name` = 'українська' WHERE `language_iso` = 'ua' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uk-UA', `name` = 'українська (Україна)' WHERE `language_iso` = 'ua' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ur', `name` = 'اُردو‏' WHERE `language_iso` = 'pk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ur-PK', `name` = 'اُردو (پاکستان)‏' WHERE `language_iso` = 'pk' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ug', `name` = 'ئۇيغۇر يېزىقى‏' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ug-CN', `name` = '(ئۇيغۇر يېزىقى (جۇڭخۇا خەلق جۇمھۇرىيىتى‏' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uz-Cyrl', `name` = 'Ўзбек' WHERE `language_iso` = 'uz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uz-Cyrl-UZ', `name` = 'Ўзбек (Ўзбекистон)' WHERE `language_iso` = 'uz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uz', `name` = 'U\'zbek' WHERE `language_iso` = 'uz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uz-Latn', `name` = 'U\'zbek' WHERE `language_iso` = 'uz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'uz-Latn-UZ', `name` = 'U\'zbek (U\'zbekiston Respublikasi)' WHERE `language_iso` = 'uz' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'vi', `name` = 'Tiếng Việt' WHERE `language_iso` = 'vn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'vi-VN', `name` = 'Tiếng Việt (Việt Nam)' WHERE `language_iso` = 'vn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'cy', `name` = 'Cymraeg' WHERE `language_iso` = 'gb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'cy-GB', `name` = 'Cymraeg (y Deyrnas Unedig)' WHERE `language_iso` = 'gb' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'wo', `name` = 'Wolof' WHERE `language_iso` = 'sn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'wo-SN', `name` = 'Wolof (Sénégal)' WHERE `language_iso` = 'sn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sah', `name` = 'саха' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'sah-RU', `name` = 'саха (Россия)' WHERE `language_iso` = 'ru' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ii', `name` = 'ꆈꌠꁱꂷ' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'ii-CN', `name` = 'ꆈꌠꁱꂷ (ꍏꉸꏓꂱꇭꉼꇩ)' WHERE `language_iso` = 'cn' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'yo', `name` = 'Yoruba' WHERE `language_iso` = 'ng' LIMIT 1;
UPDATE `plugin_locale` SET `language_iso` = 'yo-NG', `name` = 'Yoruba (Nigeria)' WHERE `language_iso` = 'ng' LIMIT 1;

SET @name := (SELECT `native` FROM `plugin_locale_languages` WHERE `iso` = 'en-GB' LIMIT 1);
UPDATE `plugin_locale` SET `name` = @name WHERE `language_iso` = 'en-GB' LIMIT 1;

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_lbl_dir', 'backend', 'Locale plugin / Text direction', 'plugin', '2016-02-05 10:14:28');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Text direction', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_lbl_fend', 'backend', 'Locale plugin / Front end', 'plugin', '2016-02-05 10:17:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Front end', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_dir_ARRAY_ltr', 'arrays', 'Locale plugin / Left to Right', 'plugin', '2016-02-05 10:54:19');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Left to Right', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_dir_ARRAY_rtl', 'arrays', 'Locale plugin / Right to Left', 'plugin', '2016-02-05 10:54:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Right to Left', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_flag_reset_title', 'backend', 'Locale plugin / Reset flag', 'plugin', '2016-02-05 14:24:57');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reset flag', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_flag_reset_content', 'backend', 'Locale plugin / Reset flag: confirmation', 'plugin', '2016-02-05 14:25:26');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Are you sure you want to reset selected flag?', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_btn_reset', 'backend', 'Locale plugin / Button: Reset', 'plugin', '2016-02-05 14:27:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reset', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_tooltip_upload', 'backend', 'Locale plugin / Upload tooltip', 'plugin', '2016-02-05 14:32:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Click to upload', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_tooltip_reset', 'backend', 'Locale plugin / Reset tooltip', 'plugin', '2016-02-05 14:32:59');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Click to reset', 'plugin');

INSERT INTO `fields` VALUES (NULL, 'plugin_locale_lbl_language', 'backend', 'Locale plugin / Language', 'plugin', '2016-02-05 14:55:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Language', 'plugin');

COMMIT;