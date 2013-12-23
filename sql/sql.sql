CREATE TABLE `datacloud`.`<table_name>` (
	`id` mediumint UNSIGNED AUTO_INCREMENT,
	`title` varchar(100),
	`content` text,
	`created` int,
	PRIMARY KEY (`id`),
	INDEX  (title)
);