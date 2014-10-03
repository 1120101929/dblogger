/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50620
Source Host           : 127.0.0.1:3306
Source Database       : extalia

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2014-10-03 16:04:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
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

-- ----------------------------
-- Records of accounts
-- ----------------------------
INSERT INTO `accounts` VALUES ('adm', '0DPiKuNIrrVmD8IUCuw1hQxNqZc=', '1409811978693', '200', '127.0.0.1', '1384404098000', '2', 'androidmend@gmail.com', null, '1', '2013-11-01 20:37:28');
INSERT INTO `accounts` VALUES ('admin', '0DPiKuNIrrVmD8IUCuw1hQxNqZc=', '1409851051878', '200', '127.0.0.1', '1408982294938', '4', 'victor.mendonca@live.com', null, '1', '2013-11-01 20:37:32');
