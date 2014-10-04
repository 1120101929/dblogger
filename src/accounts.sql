CREATE TABLE `accounts` (
  `login` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL,
  `lastactive` decimal(20,0) unsigned NOT NULL DEFAULT '0',
  `access_level` int(11) NOT NULL DEFAULT '0',
  `lastIP` varchar(20) DEFAULT NULL,
  `vip_end_date` decimal(20,0) unsigned NOT NULL DEFAULT '0',
  `vip_level` int(2) unsigned NOT NULL DEFAULT '0',
  `email` varchar(64) DEFAULT NULL,
  `network` varchar(15) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;