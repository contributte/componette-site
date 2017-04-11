ALTER TABLE `github`
CHANGE `content_raw` `content_raw` mediumtext COLLATE 'utf8mb4_general_ci' NULL AFTER `description`,
CHANGE `content_html` `content_html` mediumtext COLLATE 'utf8mb4_general_ci' NULL AFTER `content_raw`;
