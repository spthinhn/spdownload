<?php

$sql = "CREATE TABLE `" . OW_DB_PREFIX . "spdownload_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`parent` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";
//installing database
OW::getDbo()->query($sql);

BOL_LanguageService::getInstance()->addPrefix('spdownload', 'Simple Download');