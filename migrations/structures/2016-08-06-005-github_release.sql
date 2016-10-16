SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `github_release`;
CREATE TABLE `github_release` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `github_id` int(11) NOT NULL,
  `gid` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `draft` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `prerelease` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `published_at` datetime NOT NULL,
  `body` text NOT NULL,
  `crawled_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `github_id` (`github_id`),
  CONSTRAINT `github_release_ibfk_1` FOREIGN KEY (`github_id`) REFERENCES `github` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
