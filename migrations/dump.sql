SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `metadatas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package` int(10) unsigned NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `readme` enum('MARKDOWN','TEXY','RAW') DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `stars` int(10) unsigned DEFAULT NULL,
  `downloads` int(10) unsigned DEFAULT NULL,
  `watchers` int(10) unsigned DEFAULT NULL,
  `issues` int(10) unsigned DEFAULT NULL,
  `forks` int(10) unsigned DEFAULT NULL,
  `releases` int(10) unsigned DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `content` text,
  `extra` blob,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `pushed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package` (`package`),
  CONSTRAINT `metadatas_ibfk_1` FOREIGN KEY (`package`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('COMPOSER','BOWER','UNKNOWN') NOT NULL DEFAULT 'UNKNOWN',
  `state` enum('ACTIVE','ARCHIVED','QUEUED') NOT NULL DEFAULT 'QUEUED',
  `repository` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repository` (`repository`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `packages_x_tags` (
  `packages_id` int(10) unsigned NOT NULL,
  `tags_id` int(10) unsigned NOT NULL,
  KEY `packages_id` (`packages_id`),
  KEY `tags_id` (`tags_id`),
  CONSTRAINT `packages_x_tags_ibfk_1` FOREIGN KEY (`packages_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `packages_x_tags_ibfk_2` FOREIGN KEY (`tags_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
