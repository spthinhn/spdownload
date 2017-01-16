<?php

$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`parent` INT(11) NOT NULL DEFAULT '0',
	`thumb` VARCHAR(255),
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql);


$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_softs` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255),
	`description` TEXT,
	`icon` VARCHAR(255),
	`license` TEXT,
	`downloads` INT(11),
	`authorId` INT(11),
	`added` INT(11),
	`updated` INT(11),

	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql);

$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_softs_categories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`softId` INT(11),
	`categoryId` INT(11),

	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql);

$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_files` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`subName` VARCHAR(255),
	`size` INT(11),
	`mimeType` VARCHAR(255),
	`downloads` INT(11),
	`softId` INT(11),
	`version` INT(11),
	`note` INT(11),
	`platformId` INT(11),
	`isMain` TINYINT(2),
	`added` INT(11),

	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql);

$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_platforms` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`thumb` VARCHAR(255),

	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

Ow::getDbo()->query($sql);

$sql = "CREATE TABLE `". OW_DB_PREFIX ."spdownload_thumbs` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`softId` INT(11),
	`thumb` VARCHAR(255),

	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

Ow::getDbo()->query($sql);

BOL_LanguageService::getInstance()->addPrefix('spdownload', 'Simple Download');