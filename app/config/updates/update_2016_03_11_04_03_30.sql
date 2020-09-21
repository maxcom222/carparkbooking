
START TRANSACTION;

ALTER TABLE `bookings` ADD COLUMN `discount` decimal(9,2) unsigned default NULL AFTER `deposit`;

ALTER TABLE `bookings` ADD COLUMN `discount_code` varchar(255) default NULL AFTER `discount`;

COMMIT;