-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `addon`;
CREATE TABLE `addon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('COMPOSER','BOWER','UNKNOWN','UNTYPE') NOT NULL DEFAULT 'UNKNOWN',
  `state` enum('ACTIVE','ARCHIVED','QUEUED') NOT NULL DEFAULT 'QUEUED',
  `owner` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_repository` (`owner`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `addon_x_tag`;
CREATE TABLE `addon_x_tag` (
  `addon_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `packages_id_tags_id` (`addon_id`,`tag_id`),
  KEY `packages_id` (`addon_id`),
  KEY `tags_id` (`tag_id`),
  CONSTRAINT `addon_x_tag_ibfk_3` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addon_x_tag_ibfk_4` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addon_x_tag_ibfk_5` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `bower`;
CREATE TABLE `bower` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) unsigned NOT NULL,
  `downloads` int(11) unsigned DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `crawled_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addon_id` (`addon_id`),
  CONSTRAINT `bower_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `composer`;
CREATE TABLE `composer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `type` varchar(100) DEFAULT NULL,
  `downloads` int(11) unsigned DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `crawled_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addon_id` (`addon_id`),
  CONSTRAINT `composer_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `github`;
CREATE TABLE `github` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `readme` enum('MARKDOWN','TEXY','RAW') DEFAULT NULL,
  `content` text,
  `homepage` varchar(255) DEFAULT NULL,
  `stars` int(11) unsigned DEFAULT NULL,
  `watchers` int(11) unsigned DEFAULT NULL,
  `issues` int(11) unsigned DEFAULT NULL,
  `forks` int(11) unsigned DEFAULT NULL,
  `releases` int(11) unsigned DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `fork` tinyint(1) unsigned DEFAULT NULL,
  `extra` blob,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pushed_at` datetime DEFAULT NULL,
  `crawled_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package` (`addon_id`),
  CONSTRAINT `github_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `color` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2016-01-15 11:29:43
