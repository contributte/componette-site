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
  `type` enum('COMPOSER','BOWER', 'UNKNOWN') NOT NULL DEFAULT 'UNKNOWN',
  `state` enum('ACTIVE','ARCHIVED','QUEUED') NOT NULL DEFAULT 'QUEUED',
  `repository` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFALT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repository` (`repository`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2015-10-05 10:45:01
