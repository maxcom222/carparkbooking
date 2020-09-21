DROP TABLE IF EXISTS `plugin_country`;
CREATE TABLE IF NOT EXISTS `plugin_country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alpha_2` varchar(2) DEFAULT NULL,
  `alpha_3` varchar(3) DEFAULT NULL,
  `status` enum('T','F') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha_2` (`alpha_2`),
  UNIQUE KEY `alpha_3` (`alpha_3`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `plugin_country` (`id`, `alpha_2`, `alpha_3`, `status`) VALUES
(1, 'AF', 'AFG', 'T'),
(2, 'AX', 'ALA', 'T'),
(3, 'AL', 'ALB', 'T'),
(4, 'DZ', 'DZA', 'T'),
(5, 'AS', 'ASM', 'T'),
(6, 'AD', 'AND', 'T'),
(7, 'AO', 'AGO', 'T'),
(8, 'AI', 'AIA', 'T'),
(9, 'AQ', 'ATA', 'T'),
(10, 'AG', 'ATG', 'T'),
(11, 'AR', 'ARG', 'T'),
(12, 'AM', 'ARM', 'T'),
(13, 'AW', 'ABW', 'T'),
(14, 'AU', 'AUS', 'T'),
(15, 'AT', 'AUT', 'T'),
(16, 'AZ', 'AZE', 'T'),
(17, 'BS', 'BHS', 'T'),
(18, 'BH', 'BHR', 'T'),
(19, 'BD', 'BGD', 'T'),
(20, 'BB', 'BRB', 'T'),
(21, 'BY', 'BLR', 'T'),
(22, 'BE', 'BEL', 'T'),
(23, 'BZ', 'BLZ', 'T'),
(24, 'BJ', 'BEN', 'T'),
(25, 'BM', 'BMU', 'T'),
(26, 'BT', 'BTN', 'T'),
(27, 'BO', 'BOL', 'T'),
(28, 'BQ', 'BES', 'T'),
(29, 'BA', 'BIH', 'T'),
(30, 'BW', 'BWA', 'T'),
(31, 'BV', 'BVT', 'T'),
(32, 'BR', 'BRA', 'T'),
(33, 'IO', 'IOT', 'T'),
(34, 'BN', 'BRN', 'T'),
(35, 'BG', 'BGR', 'T'),
(36, 'BF', 'BFA', 'T'),
(37, 'BI', 'BDI', 'T'),
(38, 'KH', 'KHM', 'T'),
(39, 'CM', 'CMR', 'T'),
(40, 'CA', 'CAN', 'T'),
(41, 'CV', 'CPV', 'T'),
(42, 'KY', 'CYM', 'T'),
(43, 'CF', 'CAF', 'T'),
(44, 'TD', 'TCD', 'T'),
(45, 'CL', 'CHL', 'T'),
(46, 'CN', 'CHN', 'T'),
(47, 'CX', 'CXR', 'T'),
(48, 'CC', 'CCK', 'T'),
(49, 'CO', 'COL', 'T'),
(50, 'KM', 'COM', 'T'),
(51, 'CG', 'COG', 'T'),
(52, 'CD', 'COD', 'T'),
(53, 'CK', 'COK', 'T'),
(54, 'CR', 'CRI', 'T'),
(55, 'CI', 'CIV', 'T'),
(56, 'HR', 'HRV', 'T'),
(57, 'CU', 'CUB', 'T'),
(58, 'CW', 'CUW', 'T'),
(59, 'CY', 'CYP', 'T'),
(60, 'CZ', 'CZE', 'T'),
(61, 'DK', 'DNK', 'T'),
(62, 'DJ', 'DJI', 'T'),
(63, 'DM', 'DMA', 'T'),
(64, 'DO', 'DOM', 'T'),
(65, 'EC', 'ECU', 'T'),
(66, 'EG', 'EGY', 'T'),
(67, 'SV', 'SLV', 'T'),
(68, 'GQ', 'GNQ', 'T'),
(69, 'ER', 'ERI', 'T'),
(70, 'EE', 'EST', 'T'),
(71, 'ET', 'ETH', 'T'),
(72, 'FK', 'FLK', 'T'),
(73, 'FO', 'FRO', 'T'),
(74, 'FJ', 'FJI', 'T'),
(75, 'FI', 'FIN', 'T'),
(76, 'FR', 'FRA', 'T'),
(77, 'GF', 'GUF', 'T'),
(78, 'PF', 'PYF', 'T'),
(79, 'TF', 'ATF', 'T'),
(80, 'GA', 'GAB', 'T'),
(81, 'GM', 'GMB', 'T'),
(82, 'GE', 'GEO', 'T'),
(83, 'DE', 'DEU', 'T'),
(84, 'GH', 'GHA', 'T'),
(85, 'GI', 'GIB', 'T'),
(86, 'GR', 'GRC', 'T'),
(87, 'GL', 'GRL', 'T'),
(88, 'GD', 'GRD', 'T'),
(89, 'GP', 'GLP', 'T'),
(90, 'GU', 'GUM', 'T'),
(91, 'GT', 'GTM', 'T'),
(92, 'GG', 'GGY', 'T'),
(93, 'GN', 'GIN', 'T'),
(94, 'GW', 'GNB', 'T'),
(95, 'GY', 'GUY', 'T'),
(96, 'HT', 'HTI', 'T'),
(97, 'HM', 'HMD', 'T'),
(98, 'VA', 'VAT', 'T'),
(99, 'HN', 'HND', 'T'),
(100, 'HK', 'HKG', 'T'),
(101, 'HU', 'HUN', 'T'),
(102, 'IS', 'ISL', 'T'),
(103, 'IN', 'IND', 'T'),
(104, 'ID', 'IDN', 'T'),
(105, 'IR', 'IRN', 'T'),
(106, 'IQ', 'IRQ', 'T'),
(107, 'IE', 'IRL', 'T'),
(108, 'IM', 'IMN', 'T'),
(109, 'IL', 'ISR', 'T'),
(110, 'IT', 'ITA', 'T'),
(111, 'JM', 'JAM', 'T'),
(112, 'JP', 'JPN', 'T'),
(113, 'JE', 'JEY', 'T'),
(114, 'JO', 'JOR', 'T'),
(115, 'KZ', 'KAZ', 'T'),
(116, 'KE', 'KEN', 'T'),
(117, 'KI', 'KIR', 'T'),
(118, 'KP', 'PRK', 'T'),
(119, 'KR', 'KOR', 'T'),
(120, 'KW', 'KWT', 'T'),
(121, 'KG', 'KGZ', 'T'),
(122, 'LA', 'LAO', 'T'),
(123, 'LV', 'LVA', 'T'),
(124, 'LB', 'LBN', 'T'),
(125, 'LS', 'LSO', 'T'),
(126, 'LR', 'LBR', 'T'),
(127, 'LY', 'LBY', 'T'),
(128, 'LI', 'LIE', 'T'),
(129, 'LT', 'LTU', 'T'),
(130, 'LU', 'LUX', 'T'),
(131, 'MO', 'MAC', 'T'),
(132, 'MK', 'MKD', 'T'),
(133, 'MG', 'MDG', 'T'),
(134, 'MW', 'MWI', 'T'),
(135, 'MY', 'MYS', 'T'),
(136, 'MV', 'MDV', 'T'),
(137, 'ML', 'MLI', 'T'),
(138, 'MT', 'MLT', 'T'),
(139, 'MH', 'MHL', 'T'),
(140, 'MQ', 'MTQ', 'T'),
(141, 'MR', 'MRT', 'T'),
(142, 'MU', 'MUS', 'T'),
(143, 'YT', 'MYT', 'T'),
(144, 'MX', 'MEX', 'T'),
(145, 'FM', 'FSM', 'T'),
(146, 'MD', 'MDA', 'T'),
(147, 'MC', 'MCO', 'T'),
(148, 'MN', 'MNG', 'T'),
(149, 'ME', 'MNE', 'T'),
(150, 'MS', 'MSR', 'T'),
(151, 'MA', 'MAR', 'T'),
(152, 'MZ', 'MOZ', 'T'),
(153, 'MM', 'MMR', 'T'),
(154, 'NA', 'NAM', 'T'),
(155, 'NR', 'NRU', 'T'),
(156, 'NP', 'NPL', 'T'),
(157, 'NL', 'NLD', 'T'),
(158, 'NC', 'NCL', 'T'),
(159, 'NZ', 'NZL', 'T'),
(160, 'NI', 'NIC', 'T'),
(161, 'NE', 'NER', 'T'),
(162, 'NG', 'NGA', 'T'),
(163, 'NU', 'NIU', 'T'),
(164, 'NF', 'NFK', 'T'),
(165, 'MP', 'MNP', 'T'),
(166, 'NO', 'NOR', 'T'),
(167, 'OM', 'OMN', 'T'),
(168, 'PK', 'PAK', 'T'),
(169, 'PW', 'PLW', 'T'),
(170, 'PS', 'PSE', 'T'),
(171, 'PA', 'PAN', 'T'),
(172, 'PG', 'PNG', 'T'),
(173, 'PY', 'PRY', 'T'),
(174, 'PE', 'PER', 'T'),
(175, 'PH', 'PHL', 'T'),
(176, 'PN', 'PCN', 'T'),
(177, 'PL', 'POL', 'T'),
(178, 'PT', 'PRT', 'T'),
(179, 'PR', 'PRI', 'T'),
(180, 'QA', 'QAT', 'T'),
(181, 'RE', 'REU', 'T'),
(182, 'RO', 'ROU', 'T'),
(183, 'RU', 'RUS', 'T'),
(184, 'RW', 'RWA', 'T'),
(185, 'BL', 'BLM', 'T'),
(186, 'SH', 'SHN', 'T'),
(187, 'KN', 'KNA', 'T'),
(188, 'LC', 'LCA', 'T'),
(189, 'MF', 'MAF', 'T'),
(190, 'PM', 'SPM', 'T'),
(191, 'VC', 'VCT', 'T'),
(192, 'WS', 'WSM', 'T'),
(193, 'SM', 'SMR', 'T'),
(194, 'ST', 'STP', 'T'),
(195, 'SA', 'SAU', 'T'),
(196, 'SN', 'SEN', 'T'),
(197, 'RS', 'SRB', 'T'),
(198, 'SC', 'SYC', 'T'),
(199, 'SL', 'SLE', 'T'),
(200, 'SG', 'SGP', 'T'),
(201, 'SX', 'SXM', 'T'),
(202, 'SK', 'SVK', 'T'),
(203, 'SI', 'SVN', 'T'),
(204, 'SB', 'SLB', 'T'),
(205, 'SO', 'SOM', 'T'),
(206, 'ZA', 'ZAF', 'T'),
(207, 'GS', 'SGS', 'T'),
(208, 'SS', 'SSD', 'T'),
(209, 'ES', 'ESP', 'T'),
(210, 'LK', 'LKA', 'T'),
(211, 'SD', 'SDN', 'T'),
(212, 'SR', 'SUR', 'T'),
(213, 'SJ', 'SJM', 'T'),
(214, 'SZ', 'SWZ', 'T'),
(215, 'SE', 'SWE', 'T'),
(216, 'CH', 'CHE', 'T'),
(217, 'SY', 'SYR', 'T'),
(218, 'TW', 'TWN', 'T'),
(219, 'TJ', 'TJK', 'T'),
(220, 'TZ', 'TZA', 'T'),
(221, 'TH', 'THA', 'T'),
(222, 'TL', 'TLS', 'T'),
(223, 'TG', 'TGO', 'T'),
(224, 'TK', 'TKL', 'T'),
(225, 'TO', 'TON', 'T'),
(226, 'TT', 'TTO', 'T'),
(227, 'TN', 'TUN', 'T'),
(228, 'TR', 'TUR', 'T'),
(229, 'TM', 'TKM', 'T'),
(230, 'TC', 'TCA', 'T'),
(231, 'TV', 'TUV', 'T'),
(232, 'UG', 'UGA', 'T'),
(233, 'UA', 'UKR', 'T'),
(234, 'AE', 'ARE', 'T'),
(235, 'GB', 'GBR', 'T'),
(236, 'US', 'USA', 'T'),
(237, 'UM', 'UMI', 'T'),
(238, 'UY', 'URY', 'T'),
(239, 'UZ', 'UZB', 'T'),
(240, 'VU', 'VUT', 'T'),
(241, 'VE', 'VEN', 'T'),
(242, 'VN', 'VNM', 'T'),
(243, 'VG', 'VGB', 'T'),
(244, 'VI', 'VIR', 'T'),
(245, 'WF', 'WLF', 'T'),
(246, 'EH', 'ESH', 'T'),
(247, 'YE', 'YEM', 'T'),
(248, 'ZM', 'ZMB', 'T'),
(249, 'ZW', 'ZWE', 'T');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_name', 'backend', 'Country plugin / Country name', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country name', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_alpha_2', 'backend', 'Country plugin / Alpha 2', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Alpha 2', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_alpha_3', 'backend', 'Country plugin / Alpha 3', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Alpha 3', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_status', 'backend', 'Country plugin / Status', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Status', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_btn_add', 'backend', 'Country plugin / Button Add', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add +', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_statuses_ARRAY_T', 'arrays', 'Country plugin / Status (active)', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Active', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_statuses_ARRAY_F', 'arrays', 'Country plugin / Status (inactive)', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Inactive', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_btn_save', 'backend', 'Country plugin / Button Save', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Save', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_btn_cancel', 'backend', 'Country plugin / Button Cancel', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cancel', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_menu_countries', 'backend', 'Country plugin / Menu Countries', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Countries', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY01', 'arrays', 'error_titles_ARRAY_PCY01', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country updated', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY03', 'arrays', 'error_titles_ARRAY_PCY03', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country added', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY04', 'arrays', 'error_titles_ARRAY_PCY04', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country failed to add', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY08', 'arrays', 'error_titles_ARRAY_PCY08', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country not found', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY10', 'arrays', 'error_titles_ARRAY_PCY10', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add country', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY11', 'arrays', 'error_titles_ARRAY_PCY11', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update country', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_titles_ARRAY_PCY12', 'arrays', 'error_titles_ARRAY_PCY12', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Manage countries', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY01', 'arrays', 'error_bodies_ARRAY_PCY01', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country has been updated successfully.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY03', 'arrays', 'error_bodies_ARRAY_PCY03', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country has been added successfully.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY04', 'arrays', 'error_bodies_ARRAY_PCY04', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country has not been added successfully.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY08', 'arrays', 'error_bodies_ARRAY_PCY08', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country you are looking for has not been found.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY10', 'arrays', 'error_bodies_ARRAY_PCY10', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use form below to add a country.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY11', 'arrays', 'error_bodies_ARRAY_PCY11', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use form below to update a country.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'error_bodies_ARRAY_PCY12', 'arrays', 'error_bodies_ARRAY_PCY12', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use grid below to organize your country list.', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_delete_confirmation', 'backend', 'Country plugin / Delete confirmation', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Are you sure you want to delete selected country?', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_delete_selected', 'backend', 'Country plugin / Delete selected', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Delete selected', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_btn_all', 'backend', 'Country plugin / Button All', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'All', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_country_btn_search', 'backend', 'Country plugin / Button Search', 'plugin', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Search', 'plugin');

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, '1', 'pjCountry', '::LOCALE::', 'name', 'Afghanistan', 'plugin'),
(NULL, '2', 'pjCountry', '::LOCALE::', 'name', 'Åland Islands', 'plugin'),
(NULL, '3', 'pjCountry', '::LOCALE::', 'name', 'Albania', 'plugin'),
(NULL, '4', 'pjCountry', '::LOCALE::', 'name', 'Algeria', 'plugin'),
(NULL, '5', 'pjCountry', '::LOCALE::', 'name', 'American Samoa', 'plugin'),
(NULL, '6', 'pjCountry', '::LOCALE::', 'name', 'Andorra', 'plugin'),
(NULL, '7', 'pjCountry', '::LOCALE::', 'name', 'Angola', 'plugin'),
(NULL, '8', 'pjCountry', '::LOCALE::', 'name', 'Anguilla', 'plugin'),
(NULL, '9', 'pjCountry', '::LOCALE::', 'name', 'Antarctica', 'plugin'),
(NULL, '10', 'pjCountry', '::LOCALE::', 'name', 'Antigua and Barbuda', 'plugin'),
(NULL, '11', 'pjCountry', '::LOCALE::', 'name', 'Argentina', 'plugin'),
(NULL, '12', 'pjCountry', '::LOCALE::', 'name', 'Armenia', 'plugin'),
(NULL, '13', 'pjCountry', '::LOCALE::', 'name', 'Aruba', 'plugin'),
(NULL, '14', 'pjCountry', '::LOCALE::', 'name', 'Australia', 'plugin'),
(NULL, '15', 'pjCountry', '::LOCALE::', 'name', 'Austria', 'plugin'),
(NULL, '16', 'pjCountry', '::LOCALE::', 'name', 'Azerbaijan', 'plugin'),
(NULL, '17', 'pjCountry', '::LOCALE::', 'name', 'Bahamas', 'plugin'),
(NULL, '18', 'pjCountry', '::LOCALE::', 'name', 'Bahrain', 'plugin'),
(NULL, '19', 'pjCountry', '::LOCALE::', 'name', 'Bangladesh', 'plugin'),
(NULL, '20', 'pjCountry', '::LOCALE::', 'name', 'Barbados', 'plugin'),
(NULL, '21', 'pjCountry', '::LOCALE::', 'name', 'Belarus', 'plugin'),
(NULL, '22', 'pjCountry', '::LOCALE::', 'name', 'Belgium', 'plugin'),
(NULL, '23', 'pjCountry', '::LOCALE::', 'name', 'Belize', 'plugin'),
(NULL, '24', 'pjCountry', '::LOCALE::', 'name', 'Benin', 'plugin'),
(NULL, '25', 'pjCountry', '::LOCALE::', 'name', 'Bermuda', 'plugin'),
(NULL, '26', 'pjCountry', '::LOCALE::', 'name', 'Bhutan', 'plugin'),
(NULL, '27', 'pjCountry', '::LOCALE::', 'name', 'Bolivia, Plurinational State of', 'plugin'),
(NULL, '28', 'pjCountry', '::LOCALE::', 'name', 'Bonaire, Sint Eustatius and Saba', 'plugin'),
(NULL, '29', 'pjCountry', '::LOCALE::', 'name', 'Bosnia and Herzegovina', 'plugin'),
(NULL, '30', 'pjCountry', '::LOCALE::', 'name', 'Botswana', 'plugin'),
(NULL, '31', 'pjCountry', '::LOCALE::', 'name', 'Bouvet Island', 'plugin'),
(NULL, '32', 'pjCountry', '::LOCALE::', 'name', 'Brazil', 'plugin'),
(NULL, '33', 'pjCountry', '::LOCALE::', 'name', 'British Indian Ocean Territory', 'plugin'),
(NULL, '34', 'pjCountry', '::LOCALE::', 'name', 'Brunei Darussalam', 'plugin'),
(NULL, '35', 'pjCountry', '::LOCALE::', 'name', 'Bulgaria', 'plugin'),
(NULL, '36', 'pjCountry', '::LOCALE::', 'name', 'Burkina Faso', 'plugin'),
(NULL, '37', 'pjCountry', '::LOCALE::', 'name', 'Burundi', 'plugin'),
(NULL, '38', 'pjCountry', '::LOCALE::', 'name', 'Cambodia', 'plugin'),
(NULL, '39', 'pjCountry', '::LOCALE::', 'name', 'Cameroon', 'plugin'),
(NULL, '40', 'pjCountry', '::LOCALE::', 'name', 'Canada', 'plugin'),
(NULL, '41', 'pjCountry', '::LOCALE::', 'name', 'Cape Verde', 'plugin'),
(NULL, '42', 'pjCountry', '::LOCALE::', 'name', 'Cayman Islands', 'plugin'),
(NULL, '43', 'pjCountry', '::LOCALE::', 'name', 'Central African Republic', 'plugin'),
(NULL, '44', 'pjCountry', '::LOCALE::', 'name', 'Chad', 'plugin'),
(NULL, '45', 'pjCountry', '::LOCALE::', 'name', 'Chile', 'plugin'),
(NULL, '46', 'pjCountry', '::LOCALE::', 'name', 'China', 'plugin'),
(NULL, '47', 'pjCountry', '::LOCALE::', 'name', 'Christmas Island', 'plugin'),
(NULL, '48', 'pjCountry', '::LOCALE::', 'name', 'Cocos array(Keeling) Islands', 'plugin'),
(NULL, '49', 'pjCountry', '::LOCALE::', 'name', 'Colombia', 'plugin'),
(NULL, '50', 'pjCountry', '::LOCALE::', 'name', 'Comoros', 'plugin'),
(NULL, '51', 'pjCountry', '::LOCALE::', 'name', 'Congo', 'plugin'),
(NULL, '52', 'pjCountry', '::LOCALE::', 'name', 'Congo, the Democratic Republic of the', 'plugin'),
(NULL, '53', 'pjCountry', '::LOCALE::', 'name', 'Cook Islands', 'plugin'),
(NULL, '54', 'pjCountry', '::LOCALE::', 'name', 'Costa Rica', 'plugin'),
(NULL, '55', 'pjCountry', '::LOCALE::', 'name', 'Côte d''Ivoire', 'plugin'),
(NULL, '56', 'pjCountry', '::LOCALE::', 'name', 'Croatia', 'plugin'),
(NULL, '57', 'pjCountry', '::LOCALE::', 'name', 'Cuba', 'plugin'),
(NULL, '58', 'pjCountry', '::LOCALE::', 'name', 'Curaçao', 'plugin'),
(NULL, '59', 'pjCountry', '::LOCALE::', 'name', 'Cyprus', 'plugin'),
(NULL, '60', 'pjCountry', '::LOCALE::', 'name', 'Czech Republic', 'plugin'),
(NULL, '61', 'pjCountry', '::LOCALE::', 'name', 'Denmark', 'plugin'),
(NULL, '62', 'pjCountry', '::LOCALE::', 'name', 'Djibouti', 'plugin'),
(NULL, '63', 'pjCountry', '::LOCALE::', 'name', 'Dominica', 'plugin'),
(NULL, '64', 'pjCountry', '::LOCALE::', 'name', 'Dominican Republic', 'plugin'),
(NULL, '65', 'pjCountry', '::LOCALE::', 'name', 'Ecuador', 'plugin'),
(NULL, '66', 'pjCountry', '::LOCALE::', 'name', 'Egypt', 'plugin'),
(NULL, '67', 'pjCountry', '::LOCALE::', 'name', 'El Salvador', 'plugin'),
(NULL, '68', 'pjCountry', '::LOCALE::', 'name', 'Equatorial Guinea', 'plugin'),
(NULL, '69', 'pjCountry', '::LOCALE::', 'name', 'Eritrea', 'plugin'),
(NULL, '70', 'pjCountry', '::LOCALE::', 'name', 'Estonia', 'plugin'),
(NULL, '71', 'pjCountry', '::LOCALE::', 'name', 'Ethiopia', 'plugin'),
(NULL, '72', 'pjCountry', '::LOCALE::', 'name', 'Falkland Islands array(Malvinas)', 'plugin'),
(NULL, '73', 'pjCountry', '::LOCALE::', 'name', 'Faroe Islands', 'plugin'),
(NULL, '74', 'pjCountry', '::LOCALE::', 'name', 'Fiji', 'plugin'),
(NULL, '75', 'pjCountry', '::LOCALE::', 'name', 'Finland', 'plugin'),
(NULL, '76', 'pjCountry', '::LOCALE::', 'name', 'France', 'plugin'),
(NULL, '77', 'pjCountry', '::LOCALE::', 'name', 'French Guiana', 'plugin'),
(NULL, '78', 'pjCountry', '::LOCALE::', 'name', 'French Polynesia', 'plugin'),
(NULL, '79', 'pjCountry', '::LOCALE::', 'name', 'French Southern Territories', 'plugin'),
(NULL, '80', 'pjCountry', '::LOCALE::', 'name', 'Gabon', 'plugin'),
(NULL, '81', 'pjCountry', '::LOCALE::', 'name', 'Gambia', 'plugin'),
(NULL, '82', 'pjCountry', '::LOCALE::', 'name', 'Georgia', 'plugin'),
(NULL, '83', 'pjCountry', '::LOCALE::', 'name', 'Germany', 'plugin'),
(NULL, '84', 'pjCountry', '::LOCALE::', 'name', 'Ghana', 'plugin'),
(NULL, '85', 'pjCountry', '::LOCALE::', 'name', 'Gibraltar', 'plugin'),
(NULL, '86', 'pjCountry', '::LOCALE::', 'name', 'Greece', 'plugin'),
(NULL, '87', 'pjCountry', '::LOCALE::', 'name', 'Greenland', 'plugin'),
(NULL, '88', 'pjCountry', '::LOCALE::', 'name', 'Grenada', 'plugin'),
(NULL, '89', 'pjCountry', '::LOCALE::', 'name', 'Guadeloupe', 'plugin'),
(NULL, '90', 'pjCountry', '::LOCALE::', 'name', 'Guam', 'plugin'),
(NULL, '91', 'pjCountry', '::LOCALE::', 'name', 'Guatemala', 'plugin'),
(NULL, '92', 'pjCountry', '::LOCALE::', 'name', 'Guernsey', 'plugin'),
(NULL, '93', 'pjCountry', '::LOCALE::', 'name', 'Guinea', 'plugin'),
(NULL, '94', 'pjCountry', '::LOCALE::', 'name', 'Guinea-Bissau', 'plugin'),
(NULL, '95', 'pjCountry', '::LOCALE::', 'name', 'Guyana', 'plugin'),
(NULL, '96', 'pjCountry', '::LOCALE::', 'name', 'Haiti', 'plugin'),
(NULL, '97', 'pjCountry', '::LOCALE::', 'name', 'Heard Island and McDonald Islands', 'plugin'),
(NULL, '98', 'pjCountry', '::LOCALE::', 'name', 'Holy See array(Vatican City State)', 'plugin'),
(NULL, '99', 'pjCountry', '::LOCALE::', 'name', 'Honduras', 'plugin'),
(NULL, '100', 'pjCountry', '::LOCALE::', 'name', 'Hong Kong', 'plugin'),
(NULL, '101', 'pjCountry', '::LOCALE::', 'name', 'Hungary', 'plugin'),
(NULL, '102', 'pjCountry', '::LOCALE::', 'name', 'Iceland', 'plugin'),
(NULL, '103', 'pjCountry', '::LOCALE::', 'name', 'India', 'plugin'),
(NULL, '104', 'pjCountry', '::LOCALE::', 'name', 'Indonesia', 'plugin'),
(NULL, '105', 'pjCountry', '::LOCALE::', 'name', 'Iran, Islamic Republic of', 'plugin'),
(NULL, '106', 'pjCountry', '::LOCALE::', 'name', 'Iraq', 'plugin'),
(NULL, '107', 'pjCountry', '::LOCALE::', 'name', 'Ireland', 'plugin'),
(NULL, '108', 'pjCountry', '::LOCALE::', 'name', 'Isle of Man', 'plugin'),
(NULL, '109', 'pjCountry', '::LOCALE::', 'name', 'Israel', 'plugin'),
(NULL, '110', 'pjCountry', '::LOCALE::', 'name', 'Italy', 'plugin'),
(NULL, '111', 'pjCountry', '::LOCALE::', 'name', 'Jamaica', 'plugin'),
(NULL, '112', 'pjCountry', '::LOCALE::', 'name', 'Japan', 'plugin'),
(NULL, '113', 'pjCountry', '::LOCALE::', 'name', 'Jersey', 'plugin'),
(NULL, '114', 'pjCountry', '::LOCALE::', 'name', 'Jordan', 'plugin'),
(NULL, '115', 'pjCountry', '::LOCALE::', 'name', 'Kazakhstan', 'plugin'),
(NULL, '116', 'pjCountry', '::LOCALE::', 'name', 'Kenya', 'plugin'),
(NULL, '117', 'pjCountry', '::LOCALE::', 'name', 'Kiribati', 'plugin'),
(NULL, '118', 'pjCountry', '::LOCALE::', 'name', 'Korea, Democratic People''s Republic of', 'plugin'),
(NULL, '119', 'pjCountry', '::LOCALE::', 'name', 'Korea, Republic of', 'plugin'),
(NULL, '120', 'pjCountry', '::LOCALE::', 'name', 'Kuwait', 'plugin'),
(NULL, '121', 'pjCountry', '::LOCALE::', 'name', 'Kyrgyzstan', 'plugin'),
(NULL, '122', 'pjCountry', '::LOCALE::', 'name', 'Lao People''s Democratic Republic', 'plugin'),
(NULL, '123', 'pjCountry', '::LOCALE::', 'name', 'Latvia', 'plugin'),
(NULL, '124', 'pjCountry', '::LOCALE::', 'name', 'Lebanon', 'plugin'),
(NULL, '125', 'pjCountry', '::LOCALE::', 'name', 'Lesotho', 'plugin'),
(NULL, '126', 'pjCountry', '::LOCALE::', 'name', 'Liberia', 'plugin'),
(NULL, '127', 'pjCountry', '::LOCALE::', 'name', 'Libya', 'plugin'),
(NULL, '128', 'pjCountry', '::LOCALE::', 'name', 'Liechtenstein', 'plugin'),
(NULL, '129', 'pjCountry', '::LOCALE::', 'name', 'Lithuania', 'plugin'),
(NULL, '130', 'pjCountry', '::LOCALE::', 'name', 'Luxembourg', 'plugin'),
(NULL, '131', 'pjCountry', '::LOCALE::', 'name', 'Macao', 'plugin'),
(NULL, '132', 'pjCountry', '::LOCALE::', 'name', 'Macedonia, The Former Yugoslav Republic of', 'plugin'),
(NULL, '133', 'pjCountry', '::LOCALE::', 'name', 'Madagascar', 'plugin'),
(NULL, '134', 'pjCountry', '::LOCALE::', 'name', 'Malawi', 'plugin'),
(NULL, '135', 'pjCountry', '::LOCALE::', 'name', 'Malaysia', 'plugin'),
(NULL, '136', 'pjCountry', '::LOCALE::', 'name', 'Maldives', 'plugin'),
(NULL, '137', 'pjCountry', '::LOCALE::', 'name', 'Mali', 'plugin'),
(NULL, '138', 'pjCountry', '::LOCALE::', 'name', 'Malta', 'plugin'),
(NULL, '139', 'pjCountry', '::LOCALE::', 'name', 'Marshall Islands', 'plugin'),
(NULL, '140', 'pjCountry', '::LOCALE::', 'name', 'Martinique', 'plugin'),
(NULL, '141', 'pjCountry', '::LOCALE::', 'name', 'Mauritania', 'plugin'),
(NULL, '142', 'pjCountry', '::LOCALE::', 'name', 'Mauritius', 'plugin'),
(NULL, '143', 'pjCountry', '::LOCALE::', 'name', 'Mayotte', 'plugin'),
(NULL, '144', 'pjCountry', '::LOCALE::', 'name', 'Mexico', 'plugin'),
(NULL, '145', 'pjCountry', '::LOCALE::', 'name', 'Micronesia, Federated States of', 'plugin'),
(NULL, '146', 'pjCountry', '::LOCALE::', 'name', 'Moldova, Republic of', 'plugin'),
(NULL, '147', 'pjCountry', '::LOCALE::', 'name', 'Monaco', 'plugin'),
(NULL, '148', 'pjCountry', '::LOCALE::', 'name', 'Mongolia', 'plugin'),
(NULL, '149', 'pjCountry', '::LOCALE::', 'name', 'Montenegro', 'plugin'),
(NULL, '150', 'pjCountry', '::LOCALE::', 'name', 'Montserrat', 'plugin'),
(NULL, '151', 'pjCountry', '::LOCALE::', 'name', 'Morocco', 'plugin'),
(NULL, '152', 'pjCountry', '::LOCALE::', 'name', 'Mozambique', 'plugin'),
(NULL, '153', 'pjCountry', '::LOCALE::', 'name', 'Myanmar', 'plugin'),
(NULL, '154', 'pjCountry', '::LOCALE::', 'name', 'Namibia', 'plugin'),
(NULL, '155', 'pjCountry', '::LOCALE::', 'name', 'Nauru', 'plugin'),
(NULL, '156', 'pjCountry', '::LOCALE::', 'name', 'Nepal', 'plugin'),
(NULL, '157', 'pjCountry', '::LOCALE::', 'name', 'Netherlands', 'plugin'),
(NULL, '158', 'pjCountry', '::LOCALE::', 'name', 'New Caledonia', 'plugin'),
(NULL, '159', 'pjCountry', '::LOCALE::', 'name', 'New Zealand', 'plugin'),
(NULL, '160', 'pjCountry', '::LOCALE::', 'name', 'Nicaragua', 'plugin'),
(NULL, '161', 'pjCountry', '::LOCALE::', 'name', 'Niger', 'plugin'),
(NULL, '162', 'pjCountry', '::LOCALE::', 'name', 'Nigeria', 'plugin'),
(NULL, '163', 'pjCountry', '::LOCALE::', 'name', 'Niue', 'plugin'),
(NULL, '164', 'pjCountry', '::LOCALE::', 'name', 'Norfolk Island', 'plugin'),
(NULL, '165', 'pjCountry', '::LOCALE::', 'name', 'Northern Mariana Islands', 'plugin'),
(NULL, '166', 'pjCountry', '::LOCALE::', 'name', 'Norway', 'plugin'),
(NULL, '167', 'pjCountry', '::LOCALE::', 'name', 'Oman', 'plugin'),
(NULL, '168', 'pjCountry', '::LOCALE::', 'name', 'Pakistan', 'plugin'),
(NULL, '169', 'pjCountry', '::LOCALE::', 'name', 'Palau', 'plugin'),
(NULL, '170', 'pjCountry', '::LOCALE::', 'name', 'Palestine, State of', 'plugin'),
(NULL, '171', 'pjCountry', '::LOCALE::', 'name', 'Panama', 'plugin'),
(NULL, '172', 'pjCountry', '::LOCALE::', 'name', 'Papua New Guinea', 'plugin'),
(NULL, '173', 'pjCountry', '::LOCALE::', 'name', 'Paraguay', 'plugin'),
(NULL, '174', 'pjCountry', '::LOCALE::', 'name', 'Peru', 'plugin'),
(NULL, '175', 'pjCountry', '::LOCALE::', 'name', 'Philippines', 'plugin'),
(NULL, '176', 'pjCountry', '::LOCALE::', 'name', 'Pitcairn', 'plugin'),
(NULL, '177', 'pjCountry', '::LOCALE::', 'name', 'Poland', 'plugin'),
(NULL, '178', 'pjCountry', '::LOCALE::', 'name', 'Portugal', 'plugin'),
(NULL, '179', 'pjCountry', '::LOCALE::', 'name', 'Puerto Rico', 'plugin'),
(NULL, '180', 'pjCountry', '::LOCALE::', 'name', 'Qatar', 'plugin'),
(NULL, '181', 'pjCountry', '::LOCALE::', 'name', 'Réunion', 'plugin'),
(NULL, '182', 'pjCountry', '::LOCALE::', 'name', 'Romania', 'plugin'),
(NULL, '183', 'pjCountry', '::LOCALE::', 'name', 'Russian Federation', 'plugin'),
(NULL, '184', 'pjCountry', '::LOCALE::', 'name', 'Rwanda', 'plugin'),
(NULL, '185', 'pjCountry', '::LOCALE::', 'name', 'Saint Barthélemy', 'plugin'),
(NULL, '186', 'pjCountry', '::LOCALE::', 'name', 'Saint Helena, Ascension and Tristan da Cunha', 'plugin'),
(NULL, '187', 'pjCountry', '::LOCALE::', 'name', 'Saint Kitts and Nevis', 'plugin'),
(NULL, '188', 'pjCountry', '::LOCALE::', 'name', 'Saint Lucia', 'plugin'),
(NULL, '189', 'pjCountry', '::LOCALE::', 'name', 'Saint Martin array(French part)', 'plugin'),
(NULL, '190', 'pjCountry', '::LOCALE::', 'name', 'Saint Pierre and Miquelon', 'plugin'),
(NULL, '191', 'pjCountry', '::LOCALE::', 'name', 'Saint Vincent and the Grenadines', 'plugin'),
(NULL, '192', 'pjCountry', '::LOCALE::', 'name', 'Samoa', 'plugin'),
(NULL, '193', 'pjCountry', '::LOCALE::', 'name', 'San Marino', 'plugin'),
(NULL, '194', 'pjCountry', '::LOCALE::', 'name', 'Sao Tome and Principe', 'plugin'),
(NULL, '195', 'pjCountry', '::LOCALE::', 'name', 'Saudi Arabia', 'plugin'),
(NULL, '196', 'pjCountry', '::LOCALE::', 'name', 'Senegal', 'plugin'),
(NULL, '197', 'pjCountry', '::LOCALE::', 'name', 'Serbia', 'plugin'),
(NULL, '198', 'pjCountry', '::LOCALE::', 'name', 'Seychelles', 'plugin'),
(NULL, '199', 'pjCountry', '::LOCALE::', 'name', 'Sierra Leone', 'plugin'),
(NULL, '200', 'pjCountry', '::LOCALE::', 'name', 'Singapore', 'plugin'),
(NULL, '201', 'pjCountry', '::LOCALE::', 'name', 'Sint Maarten array(Dutch part)', 'plugin'),
(NULL, '202', 'pjCountry', '::LOCALE::', 'name', 'Slovakia', 'plugin'),
(NULL, '203', 'pjCountry', '::LOCALE::', 'name', 'Slovenia', 'plugin'),
(NULL, '204', 'pjCountry', '::LOCALE::', 'name', 'Solomon Islands', 'plugin'),
(NULL, '205', 'pjCountry', '::LOCALE::', 'name', 'Somalia', 'plugin'),
(NULL, '206', 'pjCountry', '::LOCALE::', 'name', 'South Africa', 'plugin'),
(NULL, '207', 'pjCountry', '::LOCALE::', 'name', 'South Georgia and the South Sandwich Islands', 'plugin'),
(NULL, '208', 'pjCountry', '::LOCALE::', 'name', 'South Sudan', 'plugin'),
(NULL, '209', 'pjCountry', '::LOCALE::', 'name', 'Spain', 'plugin'),
(NULL, '210', 'pjCountry', '::LOCALE::', 'name', 'Sri Lanka', 'plugin'),
(NULL, '211', 'pjCountry', '::LOCALE::', 'name', 'Sudan', 'plugin'),
(NULL, '212', 'pjCountry', '::LOCALE::', 'name', 'Suriname', 'plugin'),
(NULL, '213', 'pjCountry', '::LOCALE::', 'name', 'Svalbard and Jan Mayen', 'plugin'),
(NULL, '214', 'pjCountry', '::LOCALE::', 'name', 'Swaziland', 'plugin'),
(NULL, '215', 'pjCountry', '::LOCALE::', 'name', 'Sweden', 'plugin'),
(NULL, '216', 'pjCountry', '::LOCALE::', 'name', 'Switzerland', 'plugin'),
(NULL, '217', 'pjCountry', '::LOCALE::', 'name', 'Syrian Arab Republic', 'plugin'),
(NULL, '218', 'pjCountry', '::LOCALE::', 'name', 'Taiwan, Province of China', 'plugin'),
(NULL, '219', 'pjCountry', '::LOCALE::', 'name', 'Tajikistan', 'plugin'),
(NULL, '220', 'pjCountry', '::LOCALE::', 'name', 'Tanzania, United Republic of', 'plugin'),
(NULL, '221', 'pjCountry', '::LOCALE::', 'name', 'Thailand', 'plugin'),
(NULL, '222', 'pjCountry', '::LOCALE::', 'name', 'Timor-Leste', 'plugin'),
(NULL, '223', 'pjCountry', '::LOCALE::', 'name', 'Togo', 'plugin'),
(NULL, '224', 'pjCountry', '::LOCALE::', 'name', 'Tokelau', 'plugin'),
(NULL, '225', 'pjCountry', '::LOCALE::', 'name', 'Tonga', 'plugin'),
(NULL, '226', 'pjCountry', '::LOCALE::', 'name', 'Trinidad and Tobago', 'plugin'),
(NULL, '227', 'pjCountry', '::LOCALE::', 'name', 'Tunisia', 'plugin'),
(NULL, '228', 'pjCountry', '::LOCALE::', 'name', 'Turkey', 'plugin'),
(NULL, '229', 'pjCountry', '::LOCALE::', 'name', 'Turkmenistan', 'plugin'),
(NULL, '230', 'pjCountry', '::LOCALE::', 'name', 'Turks and Caicos Islands', 'plugin'),
(NULL, '231', 'pjCountry', '::LOCALE::', 'name', 'Tuvalu', 'plugin'),
(NULL, '232', 'pjCountry', '::LOCALE::', 'name', 'Uganda', 'plugin'),
(NULL, '233', 'pjCountry', '::LOCALE::', 'name', 'Ukraine', 'plugin'),
(NULL, '234', 'pjCountry', '::LOCALE::', 'name', 'United Arab Emirates', 'plugin'),
(NULL, '235', 'pjCountry', '::LOCALE::', 'name', 'United Kingdom', 'plugin'),
(NULL, '236', 'pjCountry', '::LOCALE::', 'name', 'United States', 'plugin'),
(NULL, '237', 'pjCountry', '::LOCALE::', 'name', 'United States Minor Outlying Islands', 'plugin'),
(NULL, '238', 'pjCountry', '::LOCALE::', 'name', 'Uruguay', 'plugin'),
(NULL, '239', 'pjCountry', '::LOCALE::', 'name', 'Uzbekistan', 'plugin'),
(NULL, '240', 'pjCountry', '::LOCALE::', 'name', 'Vanuatu', 'plugin'),
(NULL, '241', 'pjCountry', '::LOCALE::', 'name', 'Venezuela, Bolivarian Republic of', 'plugin'),
(NULL, '242', 'pjCountry', '::LOCALE::', 'name', 'Viet Nam', 'plugin'),
(NULL, '243', 'pjCountry', '::LOCALE::', 'name', 'Virgin Islands, British', 'plugin'),
(NULL, '244', 'pjCountry', '::LOCALE::', 'name', 'Virgin Islands, U.S.', 'plugin'),
(NULL, '245', 'pjCountry', '::LOCALE::', 'name', 'Wallis and Futuna', 'plugin'),
(NULL, '246', 'pjCountry', '::LOCALE::', 'name', 'Western Sahara', 'plugin'),
(NULL, '247', 'pjCountry', '::LOCALE::', 'name', 'Yemen', 'plugin'),
(NULL, '248', 'pjCountry', '::LOCALE::', 'name', 'Zambia', 'plugin'),
(NULL, '249', 'pjCountry', '::LOCALE::', 'name', 'Zimbabwe', 'plugin');