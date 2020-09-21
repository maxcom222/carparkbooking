DROP TABLE IF EXISTS `plugin_paypal`;
CREATE TABLE IF NOT EXISTS `plugin_paypal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `subscr_id` varchar(25) DEFAULT NULL,
  `txn_id` varchar(25) DEFAULT NULL,
  `txn_type` varchar(50) DEFAULT NULL,
  `mc_gross` decimal(9,2) unsigned DEFAULT NULL,
  `mc_currency` varchar(3) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fid` (`foreign_id`,`subscr_id`,`txn_id`,`txn_type`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_dt', 'backend', 'Paypal plugin / Date & Time', 'plugin', '2014-07-18 14:21:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date/Time', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_mc_gross', 'backend', 'Paypal plugin / MC Gross', 'plugin', '2014-07-18 14:21:59');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'MC Gross', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_mc_currency', 'backend', 'Paypal plugin / MC Currency', 'plugin', '2014-07-18 14:22:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'MC Currency', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_payer_email', 'backend', 'Paypal plugin / Payer email', 'plugin', '2014-07-18 14:22:31');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payer email', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_txn_type', 'backend', 'Paypal plugin / Txn type', 'plugin', '2014-07-18 14:22:49');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Txn type', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_txn_id', 'backend', 'Paypal plugin / Txn ID', 'plugin', '2014-07-18 14:22:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Txn ID', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_subscr_id', 'backend', 'Paypal plugin / Subscription ID', 'plugin', '2014-07-18 14:23:13');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Subscription ID', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_foreign_id', 'backend', 'Paypal plugin / Foreign ID', 'plugin', '2014-07-18 14:23:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Foreign ID', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_btn_close', 'backend', 'Paypal plugin / Close', 'plugin', '2014-07-18 14:27:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_info_title', 'backend', 'Paypal plugin / Transaction details', 'plugin', '2014-07-18 14:30:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'Transaction details', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_btn_view', 'backend', 'Paypal plugin / View', 'plugin', '2014-07-18 14:37:18');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'View', 'plugin');

INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`) VALUES
(NULL, 'plugin_paypal_menu_ipn', 'backend', 'Paypal plugin / IPN', 'plugin', '2014-07-18 14:53:47');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, @id, 'pjField', '::LOCALE::', 'title', 'IPN', 'plugin');