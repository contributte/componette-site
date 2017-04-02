ALTER TABLE `addon` ADD `rating` int(11) unsigned NULL AFTER `name`;
ALTER TABLE `addon` CHANGE `owner` `author` varchar(100) COLLATE 'utf8_general_ci' NOT NULL AFTER `state`;
