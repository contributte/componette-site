ALTER TABLE `addon` ADD `rating` int(11) unsigned NULL AFTER `name`;
ALTER TABLE `addon` CHANGE `owner` `author` varchar(100) COLLATE 'utf8_general_ci' NOT NULL AFTER `state`;
ALTER TABLE `github` CHANGE `description` `description` varchar(255) COLLATE 'utf8mb4_general_ci' NULL AFTER `addon_id`;
DROP TABLE `bower`;
UPDATE `addon` SET `type` = 'UNTYPE' WHERE `type` = 'BOWER';
ALTER TABLE `addon` CHANGE `type` `type` enum('COMPOSER','UNKNOWN','UNTYPE') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'UNKNOWN' AFTER `id`;

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `composer_statistics`;
CREATE TABLE `composer_statistics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) unsigned NOT NULL,
  `type` enum('ALL','BRANCH','TAG') NOT NULL,
  `custom` varchar(100) NOT NULL,
  `data` blob NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addon_id` (`addon_id`),
  CONSTRAINT `composer_statistics_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `github_composer`;
CREATE TABLE `github_composer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `github_id` int(11) NOT NULL,
  `type` enum('BRANCH','TAG') NOT NULL,
  `custom` varchar(50) NOT NULL,
  `data` blob NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `github_id` (`github_id`),
  CONSTRAINT `github_composer_ibfk_1` FOREIGN KEY (`github_id`) REFERENCES `github` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
