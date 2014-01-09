CREATE TABLE IF NOT EXISTS `#__bts_station` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`duplicate` INT(11)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`bts_name` VARCHAR(50)  NOT NULL ,
`network` VARCHAR(20)  NOT NULL ,
`address` VARCHAR(255)  NOT NULL ,
`latitude` DECIMAL(10, 6) NOT NULL ,
`longitude` DECIMAL(10, 6) NOT NULL ,
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

`state` TINYINT(1)  NOT NULL ,
`created_time` DATETIME NOT NULL ,
`author` VARCHAR(255)  NOT NULL ,
`activity` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__bts_config` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`field` VARCHAR(255)  NOT NULL ,
`label` VARCHAR(255)  NOT NULL ,
`displayable` TINYINT(1)  NOT NULL ,
`editable` TINYINT(1)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__bts_config`  VALUES ('1', '1', '1', 'bts_name', 'Tên trạm', '1', '0'), ('2', '2', '1', 'network', 'Trạm 2G/3G', '1', '0'), ('3', '3', '1', 'province', 'Tên tỉnh/TP', '1', '1'), ('4', '4', '1', 'address', 'Địa chỉ', '1', '1'), ('5', '5', '1', 'mscmss', 'MSC/MSS', '1', '0'), ('6', '6', '1', 'bsc_name', 'BSC/RNC', '1', '0'), ('7', '7', '1', 'configuration', 'Cấu hình', '1', '0'), ('8', '8', '1', 'latitude', 'Vĩ độ', '1', '1'), ('9', '9', '1', 'longitude', 'Kinh độ', '1', '1'), ('10', '10', '1', 'manager', 'Người quản lý', '1', '1'), ('11', '11', '1', 'mobile', 'ĐT người QL', '1', '1'), ('12', '12', '1', 'backlog', 'Tồn đọng', '1', '1'), ('13', '13', '1', 'note', 'Ghi chú', '1', '1'), ('14', '14', '1', 'province_id', 'Mã tỉnh/TP', '0', '1'), ('15', '15', '1', 'district', 'Quận/Huyện', '0', '1'), ('16', '16', '1', 'commune', 'Phường/Xã/TT', '0', '1'), ('17', '17', '1', 'co_site', 'Co-site', '0', '1'), ('18', '18', '1', 'combine', 'Chung CSHT', '0', '1'), ('19', '19', '1', 'indoormaintenance', 'TGBD Indoor', '0', '1'), ('20', '20', '1', 'outdoormaintenance', 'TGBD Outdoor', '0', '1'), ('21', '21', '1', 'maintenanceby', 'ĐV bảo dưỡng', '0', '1'), ('22', '22', '1', 'caremanagement', 'ĐVQLCSHT', '0', '1'), ('23', '23', '1', 'id', 'ID', '0', '0'), ('24', '24', '1', 'ordering', 'Sắp xếp', '0', '0'), ('25', '25', '1', 'state', 'Hiện', '0', '0'), ('26', '26', '1', 'checked_out', 'N/A', '0', '0'), ('27', '27', '1', 'checked_out_time', 'N/A', '0', '0'), ('28', '28', '1', 'created_by', 'Người tạo', '0', '0'), ('29', '29', '1', 'trautc', 'TRAU/TC', '0', '0'), ('30', '30', '1', 'pcumfs', 'PCU/MFS', '0', '0'), ('31', '31', '1', 'station_code', 'Mã trạm', '0', '0'), ('32', '32', '1', 'localnumber', 'Location number', '0', '0'), ('33', '33', '1', 'activitydate', 'Ngày hoạt động', '0', '0'), ('34', '34', '1', 'activitystatus', 'Trạng thái', '0', '0'), ('35', '35', '1', 'site_id', 'Site ID', '0', '0'), ('36', '36', '1', 'lac', 'LAC', '0', '0'), ('37', '37', '1', 'devicetype', 'Loại thiết bị', '0', '0'), ('38', '38', '1', 'stationtype', 'Loại trạm', '0', '0'), ('39', '39', '1', 'typestation', 'Trạm loại', '0', '0'), ('40', '40', '1', 'project', 'Dự án', '0', '0');
