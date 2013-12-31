CREATE TABLE IF NOT EXISTS `#__bts_station` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`duplicate` INT(11)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`bts_name` VARCHAR(20)  NOT NULL ,
`network` VARCHAR(20)  NOT NULL ,
`address` VARCHAR(255)  NOT NULL ,
`latitude` FLOAT NOT NULL ,
`longitude` FLOAT NOT NULL ,
`province_id` VARCHAR(255)  NOT NULL ,
`province` VARCHAR(255)  NOT NULL ,
`district` VARCHAR(255)  NOT NULL ,
`commune` VARCHAR(255)  NOT NULL ,
`mscmss` VARCHAR(255)  NOT NULL ,
`bsc_name` VARCHAR(255)  NOT NULL ,
`trautc` VARCHAR(255)  NOT NULL ,
`pcumfs` VARCHAR(255)  NOT NULL ,
`station_code` VARCHAR(255)  NOT NULL ,
`co_site` VARCHAR(255)  NOT NULL ,
`localnumber` VARCHAR(255)  NOT NULL ,
`activitydate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`activitystatus` VARCHAR(255)  NOT NULL ,
`site_id` VARCHAR(255)  NOT NULL ,
`lac` VARCHAR(255)  NOT NULL ,
`devicetype` VARCHAR(255)  NOT NULL ,
`stationtype` VARCHAR(255)  NOT NULL ,
`configuration` VARCHAR(255)  NOT NULL ,
`combine` VARCHAR(255)  NOT NULL ,
`typestation` VARCHAR(255)  NOT NULL ,
`indoormaintenance` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`outdoormaintenance` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`maintenanceby` VARCHAR(255)  NOT NULL ,
`manager` VARCHAR(255)  NOT NULL ,
`mobile` VARCHAR(255)  NOT NULL ,
`project` VARCHAR(255)  NOT NULL ,
`caremanagement` VARCHAR(255)  NOT NULL ,
`backlog` VARCHAR(255)  NOT NULL ,
`note` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__bts_warning` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`station_id` INT NOT NULL ,
`warning_description` VARCHAR(255)  NOT NULL ,
`device` VARCHAR(255)  NOT NULL ,
`level` TINYINT(1)  NOT NULL ,
`warning_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`maintenance_by` INT(11)  NOT NULL ,
`maintenance_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`approve_by` INT(11)  NOT NULL ,
`approve_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`maintenance_state` TINYINT(1)  NOT NULL ,
`approve_state` TINYINT(1)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__bts_note` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`created_time` DATETIME NOT NULL ,
`approved_by` INT(11)  NOT NULL ,
`approved_time` DATETIME NOT NULL ,
`station_id` INT NOT NULL ,
`note` VARCHAR(255)  NOT NULL ,
`approved` TINYINT(1)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__bts_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created_time` DATETIME NOT NULL ,
`author` VARCHAR(255)  NOT NULL ,
`activity` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
