/*
SQLyog Ultimate v8.32 
MySQL - 5.0.41-community-nt : Database - cndw
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cndw` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cndw`;

/*Table structure for table `cndw_act` */

DROP TABLE IF EXISTS `cndw_act`;

CREATE TABLE `cndw_act` (
  `Id` smallint(4) unsigned NOT NULL auto_increment,
  `parent_id` smallint(4) unsigned default '0' COMMENT '父ID,如果为0就是class,不然就是action',
  `value` varchar(50) NOT NULL COMMENT 'CLASS/FUNCTION名字',
  `allow` varchar(255) default NULL COMMENT '角色',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COMMENT='act权限表';

/*Data for the table `cndw_act` */

insert  into `cndw_act`(`Id`,`parent_id`,`value`,`allow`) values (1,0,'Index','bto,juese,guest'),(111,54,'Default_ImgUpload','RBAC_EVERYONE'),(3,0,'GameOperator','bto,juese'),(4,0,'GameSerList','bto,juese'),(5,0,'Prem','bto,juese'),(6,0,'Question','bto,juese'),(36,0,'Menu','bto,juese'),(8,0,'SysManagement','bto,kefu,juese'),(9,0,'User','bto,juese'),(10,0,'WorkOrder','bto,kefu,juese'),(11,1,'Index_Login','RBAC_EVERYONE'),(12,1,'Index_Index','RBAC_EVERYONE'),(13,1,'Index_Top','RBAC_EVERYONE'),(14,1,'Index_Left','RBAC_EVERYONE'),(15,1,'Index_Right','RBAC_EVERYONE'),(16,3,'GameOperator_Index','bto,kefu,juese'),(17,3,'GameOperator_Add','bto,kefu,juese'),(18,3,'GameOperator_CreateCache','bto,kefu,juese'),(19,4,'GameSerList_Index','kefu,juese'),(20,4,'GameSerList_Add','bto,kefu,juese'),(21,4,'GameSerList_CreateCache','bto,kefu,juese'),(22,5,'Prem_AddCtl','bto,kefu,juese'),(23,5,'Prem_AddAct','bto,kefu,juese'),(24,5,'Prem_CreateAct','bto,kefu,juese'),(25,5,'Prem_EditPrem','bto,kefu,juese'),(26,6,'Question_Index','bto,juese'),(27,6,'Question_Ask','bto,juese'),(28,8,'SysManagement_QuestionIndex','bto,kefu,juese'),(29,8,'SysManagement_QuestionEdit','bto,kefu,juese'),(30,8,'SysManagement_QuestionDel','bto,juese'),(31,8,'SysManagement_SysSetupIndex','bto,kefu,juese'),(32,8,'SysManagement_SysSetupEdit','bto,kefu,juese'),(33,8,'SysManagement_CreateCache','bto,kefu,juese'),(34,10,'WorkOrder_Index','bto,kefu,juese'),(35,10,'WorkOrder_Detail','bto,kefu,juese'),(38,1,'Index_LoginOut','RBAC_EVERYONE'),(39,3,'GameOperator_Edit','bto,kefu,juese'),(40,6,'Question_Add','bto,juese'),(80,36,'Menu_AddMain','juese'),(42,10,'WorkOrder_Reply','bto,kefu,juese'),(76,9,'User_UserAddOperator','juese'),(47,9,'User_RolesAdd','bto,juese'),(77,9,'User_SortUserOperator','juese'),(75,9,'User_UserManagerOperator','juese'),(45,9,'User_Add','bto,juese'),(46,9,'User_RolesIndex','bto,juese'),(44,9,'User_Index','bto,juese'),(43,36,'Menu_Index','bto,juese'),(52,9,'User_DepartmentIndex','bto,juese'),(53,9,'User_DepartmentAdd','bto,juese'),(54,0,'Default','RBAC_EVERYONE'),(55,54,'Default_VerifyCode','RBAC_EVERYONE'),(59,0,'Faq','bto,juese'),(60,0,'Group','bto,juese'),(61,0,'MyTask','bto,juese'),(62,1,'Index_SetOnline','RBAC_EVERYONE'),(64,3,'GameOperator_Del','juese'),(65,5,'Prem_EditCtl','juese'),(66,5,'Prem_DelCtl','juese'),(67,5,'Prem_EditAct','juese'),(68,5,'Prem_DelAct','juese'),(69,8,'SysManagement_QuestionCreateCache','juese'),(70,8,'SysManagement_QuestionForm','bto,juese'),(71,8,'SysManagement_QuestionFormAdd','bto,juese'),(72,8,'SysManagement_QuestionFormEdit','bto,juese'),(73,8,'SysManagement_QuestionFormDel','juese'),(74,8,'SysManagement_QuestionAdd','juese'),(78,9,'User_DelUserOperator','juese'),(79,9,'User_DepartmentCreateCache','juese'),(81,36,'Menu_CreateCache','juese'),(82,36,'Menu_AddChild','juese'),(83,36,'Menu_EditChild','juese'),(84,36,'Menu_EditMain','juese'),(85,36,'Menu_DelChild','juese'),(86,36,'Menu_DelMain','juese'),(87,61,'MyTask_Index','juese'),(88,60,'Group_Index','juese'),(89,60,'Group_Del','juese'),(90,60,'Group_Edit','juese'),(91,60,'Group_Monitor','juese'),(92,60,'Group_KickUser','juese'),(93,60,'Group_KickAllUser','juese'),(94,60,'Group_RoomOnOff','juese'),(95,60,'Group_UserEnterRoom','juese'),(96,60,'Group_Add','juese'),(97,59,'Faq_PlayerKindAdd','juese'),(98,59,'Faq_PlayerIndex','bto,juese'),(99,59,'Faq_PlayerAdd','bto,juese'),(100,59,'Faq_PlayerEdit','juese'),(101,59,'Faq_PlayerDel','juese'),(145,3,'GameOperator_CreateCacheGameOperator',''),(106,59,'Faq_ServiceIndex','juese'),(107,59,'Faq_ServiceKindAdd','bto,juese'),(108,59,'Faq_ServiceAdd','bto,juese'),(109,59,'Faq_ServiceEdit','bto,juese'),(110,59,'Faq_ServiceDel','juese'),(112,3,'GameOperator_GameOperatorIndex','juese'),(113,3,'GameOperator_AddGameOperator','juese'),(114,59,'Faq_PlayerKindTree','juese'),(115,59,'Faq_PlayerKindFaq','juese'),(116,59,'Faq_PlayerKindIndex','juese'),(117,59,'Faq_PlayerKindEdit','juese'),(118,59,'Faq_PlayerKindDel','juese'),(119,3,'GameOperator_SetupGameOperator','juese'),(120,9,'User_Edit','juese'),(121,9,'User_Del','juese'),(143,0,'Askform','bto,juese'),(146,3,'GameOperator_DelGameOperator',''),(144,143,'Askform_Ls','bto,juese'),(122,9,'User_OrgIndex','juese'),(123,9,'User_OrgAdd','juese'),(124,9,'User_OrgEdit','juese'),(125,9,'User_OrgDel','juese'),(126,9,'User_OrgCreateCache','juese'),(127,0,'QualityCheck','juese'),(128,127,'QualityCheck_Index','juese'),(129,9,'User_CreateCache','juese'),(130,127,'QualityCheck_Dialog','juese'),(131,127,'QualityCheck_ShowQuality','juese'),(132,127,'QualityCheck_MyQualityTask','juese'),(133,127,'QualityCheck_QualityDetail','juese'),(134,61,'MyTask_MyReplyQulity','juese'),(135,127,'QualityCheck_MyTask',''),(136,0,'Verify',''),(137,0,'Stats',''),(138,136,'Verify_Index',''),(139,136,'Verify_ChangeStatus',''),(140,136,'Verify_OrderVerify',''),(141,136,'Verify_Detail',''),(142,136,'Verify_ReplyDialog',''),(147,0,'InterfacePassport','RBAC_EVERYONE'),(148,147,'InterfacePassport_Login','RBAC_EVERYONE'),(149,0,'GmFRG','RBAC_EVERYONE'),(150,149,'GmFRG_UserSearch','RBAC_EVERYONE'),(151,4,'GameSerList_Edit',''),(152,4,'GameSerList_Del','');

/*Table structure for table `cndw_askform` */

DROP TABLE IF EXISTS `cndw_askform`;

CREATE TABLE `cndw_askform` (
  `Id` bigint(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL COMMENT '问题标题',
  `describe` text COMMENT '简介',
  `start_time` bigint(20) default NULL COMMENT '开始时间',
  `end_time` bigint(20) default NULL COMMENT '结束时间',
  `create_time` bigint(20) default NULL COMMENT '创建时间',
  `option_count` int(3) default '0' COMMENT '选项数',
  `status` int(2) default '1' COMMENT '状态1=正常,0=非正常',
  `user_id` int(11) default NULL COMMENT '发布人ID',
  `user_name` varchar(20) default NULL COMMENT '发布人姓名',
  `attend_count` int(11) default '0' COMMENT '参与人数',
  PRIMARY KEY  (`Id`),
  KEY `NewIndex1` (`status`),
  KEY `NewIndex2` (`start_time`,`end_time`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='问卷表';

/*Data for the table `cndw_askform` */

insert  into `cndw_askform`(`Id`,`title`,`describe`,`start_time`,`end_time`,`create_time`,`option_count`,`status`,`user_id`,`user_name`,`attend_count`) values (1,'问卷调查','这是一个测试的问卷',1284364199,1285364199,1284364199,4,1,1,'1',9);

/*Table structure for table `cndw_askform_log` */

DROP TABLE IF EXISTS `cndw_askform_log`;

CREATE TABLE `cndw_askform_log` (
  `Id` bigint(20) NOT NULL auto_increment,
  `askform_id` bigint(20) default NULL COMMENT '问卷ID',
  `user_id` bigint(20) default NULL COMMENT '参与调查者ID',
  `attend_time` bigint(20) default NULL COMMENT '参与时间',
  `option` text COMMENT '选择项(php数组array(问题ID=>答案))',
  PRIMARY KEY  (`Id`),
  KEY `NewIndex1` (`askform_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='参与问卷调查历史表';

/*Data for the table `cndw_askform_log` */

insert  into `cndw_askform_log`(`Id`,`askform_id`,`user_id`,`attend_time`,`option`) values (1,1,9197546,1284372097,NULL),(2,1,9197546,1284372156,NULL),(3,1,9197546,1284372171,NULL),(4,1,9197546,1284372182,NULL),(5,1,9197546,1284372314,NULL),(6,1,9197546,1284372473,NULL),(7,1,8811784,1284380544,NULL);

/*Table structure for table `cndw_askform_option` */

DROP TABLE IF EXISTS `cndw_askform_option`;

CREATE TABLE `cndw_askform_option` (
  `Id` bigint(20) NOT NULL auto_increment,
  `askform_id` bigint(20) default NULL COMMENT '问题ID',
  `title` varchar(250) default NULL COMMENT '标题',
  `types` int(2) default NULL COMMENT '类型1=单选，2=多择',
  `content` text COMMENT '内容(php数据)',
  `allow_other` int(2) default '0' COMMENT '是否允许其他择项1=允许,0=不允许',
  `result` text COMMENT '结果',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='问卷选项表';

/*Data for the table `cndw_askform_option` */

insert  into `cndw_askform_option`(`Id`,`askform_id`,`title`,`types`,`content`,`allow_other`,`result`) values (1,1,'测试一?',1,'a:4:{i:0;s:7:\"选项1\";i:1;s:7:\"选项2\";i:2;s:7:\"选项3\";i:3;s:7:\"选项4\";}﻿ ',0,'a:3:{i:0;i:2567;i:1;i:1291;i:2;i:1;}'),(2,1,'测试二?',1,'a:4:{i:0;s:7:\"选项1\";i:1;s:7:\"选项2\";i:2;s:7:\"选项3\";i:3;s:7:\"选项4\";}﻿ ',1,'a:2:{i:1;i:1293;i:-1;i:2566;}'),(3,1,'测试三?',2,'a:4:{i:0;s:7:\"选项1\";i:1;s:7:\"选项2\";i:2;s:7:\"选项3\";i:3;s:7:\"选项4\";}﻿ ',0,'a:4:{i:0;i:3859;i:1;i:2567;i:2;i:2567;i:3;i:2567;}'),(4,1,'测试四?',2,'a:4:{i:0;s:7:\"选项1\";i:1;s:7:\"选项2\";i:2;s:7:\"选项3\";i:3;s:7:\"选项4\";}﻿ ',1,'a:5:{i:0;i:3858;i:1;i:2568;i:2;i:2567;i:3;i:2567;i:-1;i:2566;}');

/*Table structure for table `cndw_department` */

DROP TABLE IF EXISTS `cndw_department`;

CREATE TABLE `cndw_department` (
  `Id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) default NULL COMMENT '部门名称',
  `description` text,
  `date_created` int(10) unsigned NOT NULL COMMENT '创建日期',
  `date_updated` int(10) unsigned NOT NULL COMMENT '更新日期',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='部门';

/*Data for the table `cndw_department` */

insert  into `cndw_department`(`Id`,`name`,`description`,`date_created`,`date_updated`) values (1,'客服部','客服部',1281980021,1281980021),(2,'技术部','商业大亨部门',1281980055,1281980055);

/*Table structure for table `cndw_game_operator` */

DROP TABLE IF EXISTS `cndw_game_operator`;

CREATE TABLE `cndw_game_operator` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(3) unsigned NOT NULL COMMENT '游戏类型id',
  `operator_id` smallint(4) unsigned NOT NULL COMMENT '运营商id',
  `vip_setup` text NOT NULL COMMENT '游戏.运营商里的VIP设置',
  PRIMARY KEY  (`Id`),
  KEY `game_type_id` (`game_type_id`,`operator_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='游戏与运营商关联表';

/*Data for the table `cndw_game_operator` */

insert  into `cndw_game_operator`(`Id`,`game_type_id`,`operator_id`,`vip_setup`) values (1,1,1,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:5:\"67600\";}}'),(2,1,2,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(3,1,3,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:5:\"20000\";}}'),(4,1,4,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(5,1,5,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(6,1,6,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(7,1,7,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(15,1,9,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(20,3,3,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;s:2:\"70\";i:1;s:2:\"60\";i:2;s:2:\"50\";i:3;s:2:\"40\";i:4;s:2:\"30\";i:5;s:2:\"10\";i:6;s:1:\"0\";}s:7:\"vip_pay\";a:7:{i:0;s:1:\"0\";i:1;s:4:\"1000\";i:2;s:4:\"2000\";i:3;s:4:\"3000\";i:4;s:4:\"4000\";i:5;s:4:\"5000\";i:6;s:4:\"6000\";}}'),(21,3,4,'a:2:{s:11:\"vip_timeout\";a:7:{i:0;i:70;i:1;i:60;i:2;i:50;i:3;i:40;i:4;i:30;i:5;i:10;i:6;i:0;}s:7:\"vip_pay\";a:7:{i:0;i:0;i:1;i:1000;i:2;i:2000;i:3;i:3000;i:4;i:4000;i:5;i:5000;i:6;i:6000;}}');

/*Table structure for table `cndw_gameser_list` */

DROP TABLE IF EXISTS `cndw_gameser_list`;

CREATE TABLE `cndw_gameser_list` (
  `Id` smallint(4) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(1) unsigned NOT NULL COMMENT '游戏类型对应的ID',
  `operator_id` tinyint(3) unsigned NOT NULL COMMENT '运营商id',
  `marking` varchar(20) default NULL COMMENT '标识',
  `server_name` varchar(50) default NULL COMMENT '服务器名字',
  `server_url` varchar(200) default NULL COMMENT '服务器地址',
  PRIMARY KEY  (`Id`),
  KEY `marking` (`marking`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='游戏服务器列表';

/*Data for the table `cndw_gameser_list` */

insert  into `cndw_gameser_list`(`Id`,`game_type_id`,`operator_id`,`marking`,`server_name`,`server_url`) values (1,1,9,NULL,'双线一区',NULL),(2,1,9,NULL,'双线二区',NULL),(3,1,2,NULL,'双线三区',NULL),(4,1,2,NULL,'电信四项',NULL),(5,1,2,NULL,'电信五区',NULL),(6,1,2,NULL,'电信六区',NULL),(7,1,2,NULL,'联通一区',NULL),(8,1,2,NULL,'联通二区',NULL),(9,1,2,NULL,'联通三区',NULL),(10,1,1,NULL,'双线一区',NULL),(11,1,1,NULL,'双线二区',NULL),(12,1,1,NULL,'双线三区',NULL),(13,1,1,NULL,'双线四区',NULL),(14,1,3,NULL,'电信一区',NULL),(15,1,3,NULL,'电信二区',NULL),(16,1,3,NULL,'电信三区',NULL),(17,1,3,NULL,'电信四区',NULL),(18,1,3,NULL,'电信五区',NULL),(19,1,4,NULL,'双线一区',NULL),(20,1,4,NULL,'双线二区',NULL),(21,1,4,NULL,'双线三区',NULL),(22,1,4,NULL,'电信四项',NULL),(23,1,4,NULL,'双线五区',NULL),(24,1,5,NULL,'电信一区',NULL),(25,1,5,NULL,'电信二区',NULL),(26,1,5,NULL,'电信三区',NULL),(27,1,5,NULL,'电信四区',NULL),(28,1,6,NULL,'网通一区',NULL),(29,1,6,NULL,'网通二区',NULL),(30,1,6,NULL,'网通三区',NULL),(31,1,7,NULL,'铁通一区',NULL),(32,1,7,NULL,'铁通二区',NULL),(33,1,7,NULL,'铁通三区',NULL),(34,1,7,NULL,'铁通四区',NULL),(35,1,7,NULL,'电信一区',NULL),(36,1,7,NULL,'电信二区',NULL),(37,1,7,'eqds','电信二区','http://www.sina.com.cn');

/*Table structure for table `cndw_menu` */

DROP TABLE IF EXISTS `cndw_menu`;

CREATE TABLE `cndw_menu` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `parent_id` int(11) unsigned default '0' COMMENT '父类ID',
  `status` tinyint(1) default '1' COMMENT '是否在菜单显示',
  `value` varchar(50) NOT NULL COMMENT '标识,用于转换URL用',
  `name` varchar(50) default NULL COMMENT '菜单名称',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COMMENT='后台菜单列表';

/*Data for the table `cndw_menu` */

insert  into `cndw_menu`(`Id`,`parent_id`,`status`,`value`,`name`) values (1,0,1,'WorkOrder','工单管理'),(28,0,1,'Faq','FAQ管理'),(26,5,1,'GameOperator_Index','运营商列表'),(5,0,1,'SysManagement','系统管理'),(6,0,1,'User','用户管理'),(7,0,1,'Prem','权限管理'),(8,1,1,'WorkOrder_Index','工单列表'),(29,28,1,'Faq_PlayerIndex','玩家FAQ'),(12,5,1,'SysManagement_SysSetupIndex','系统变量'),(13,5,1,'SysManagement_QuestionIndex','问题类型'),(14,5,1,'Menu_Index','菜单管理'),(16,6,1,'User_DepartmentIndex','部门'),(18,6,1,'User_RolesIndex','职位(角色)'),(19,6,1,'User_Index','用户管理'),(20,7,1,'Prem_AddCtl','添加控制器'),(21,7,1,'Prem_AddAct','添加方法'),(22,7,1,'Prem_CreateAct','生成Act文件'),(38,150,1,'Group_Index','组别管理'),(25,150,1,'WorkOrder_Debug','发送工单测试'),(27,5,1,'GameSerList_Index','服务器列表'),(30,28,1,'Faq_ServiceIndex','客服FAQ'),(151,0,0,'Askform','问卷管理'),(37,1,1,'MyTask_Index','我的任务'),(39,0,0,'NoDisplay','不显示控制器/方法'),(40,39,0,'Index','后台主显示功能'),(41,39,0,'Default','默认公共方式'),(42,39,0,'Menu','后台菜单'),(43,39,0,'Question','提问类型管理'),(44,39,0,'Group','房间组别管理'),(48,39,0,'MyTask','我的任务'),(46,39,0,'GameOperator','运营商管理'),(47,39,0,'GameSerList','服务器列表管理'),(49,39,0,'Index_LoginOut','用户退出'),(50,39,0,'Index_SetOnline','用户登录在线状态'),(51,39,0,'Index_Right','后台右下主显示功能'),(52,39,0,'Index_Left','后右左侧菜单'),(53,39,0,'Index_Top','后台顶部'),(54,39,0,'Index_Index','后台主显示页面'),(55,39,0,'Index_Login','用户登录'),(56,39,0,'GameOperator_Edit','运营商编辑'),(57,39,0,'GameOperator_Del','删除运营商'),(58,39,0,'GameOperator_Add','增加运营商'),(59,39,0,'GameOperator_CreateCache','生成运营商缓存'),(60,39,0,'GameSerList_CreateCache','服务器列表生成缓存'),(61,39,0,'GameSerList_Add','增加游戏服务器'),(62,39,0,'Prem_EditCtl','编辑ACT控制器权限'),(63,39,0,'Prem_DelCtl','删除ACT控制器权限'),(64,39,0,'Prem_EditAct','编辑ACT方法权限'),(65,39,0,'Prem_DelAct','删除ACT方法权限'),(66,39,0,'Prem_EditPrem','修改用户权限'),(67,39,0,'Question_Add','后台手动提交问题'),(68,39,0,'Question_Index','后台手动提交问题第一步'),(69,39,0,'Question_Ask','后台手动提交问题第二步'),(70,39,0,'User_UserAddOperator','增加用户运营商管理'),(71,39,0,'User_RolesAdd','增加用户角色'),(72,39,0,'User_SortUserOperator','对用户运营商排序'),(73,39,0,'User_UserManagerOperator','管理用户运营商列表'),(74,39,0,'User_Add','增加用户'),(75,39,0,'User_DelUserOperator','删除用户运营商'),(76,39,0,'User_DepartmentAdd','增加部门'),(77,39,0,'User_DepartmentCreateCache','生成部门缓存'),(78,39,0,'WorkOrder_Detail','工单详细'),(79,39,0,'WorkOrder_Reply','回复工单消息'),(80,39,0,'Menu_EditMain','更改菜单主项'),(81,39,0,'Menu_DelChild','删除子菜单'),(82,39,0,'Menu_DelMain','删除主菜单'),(83,39,0,'Menu_EditChild','编辑子菜单'),(84,39,0,'Menu_AddChild','增加子菜单'),(85,39,0,'Menu_CreateCache','生成菜单缓存'),(86,39,0,'Menu_AddMain','增加主菜单'),(87,39,0,'Faq_PlayerEdit','玩家FAQ编辑'),(88,39,0,'Faq_PlayerDel','玩家FAQ删除'),(153,39,0,'GameOperator_DelGameOperator','删除游戏运营商关联索引'),(152,151,1,'Askform_Ls','问卷查看'),(91,39,0,'SysManagement_QuestionAdd','增加提问类型'),(92,39,0,'SysManagement_QuestionFormDel','删除提问类型表单子选项'),(93,39,0,'SysManagement_QuestionFormEdit','更改提问类型表单子项'),(94,39,0,'SysManagement_QuestionFormAdd','增加提问类型表单子项'),(95,39,0,'SysManagement_QuestionCreateCache','生成提问类型缓存'),(96,39,0,'SysManagement_QuestionForm','提问类型表单子项管理'),(97,39,0,'SysManagement_SysSetupEdit','编辑系统变量'),(98,39,0,'SysManagement_CreateCache','系统变量生成缓存'),(99,39,0,'SysManagement_QuestionDel','删除提问类型'),(100,39,0,'SysManagement_QuestionEdit','编辑提问类型'),(101,39,0,'Faq_PlayerKeywordsDel','删除玩家FAQ关键字'),(102,39,0,'Faq_ServiceKindAdd','增加客服FAQ类型'),(103,39,0,'Faq_ServiceAdd','增加客服FAQ'),(104,39,0,'Faq_ServiceEdit','编辑客服FAQ'),(105,39,0,'Faq_PlayerAdd','增加玩家FAQ'),(106,39,0,'Faq_PlayerKindAdd','增加玩家FAQ类型'),(107,39,0,'Faq_ServiceDel','删除客服FAQ'),(108,39,0,'Group_UserEnterRoom','用户进入房间'),(109,39,0,'Group_Del','删除房间'),(110,39,0,'Group_Edit','编辑房间'),(111,39,0,'Group_Add','增加房间'),(112,39,0,'Group_KickUser','房间踢出用户'),(113,39,0,'Group_KickAllUser','房间踢出所有用户'),(114,39,0,'Group_RoomOnOff','房间入口出口开关'),(115,39,0,'Group_Monitor','房间监控'),(130,6,1,'User_OrgIndex','组别管理'),(117,39,0,'Default_VerifyCode','验证码'),(118,39,0,'Default_ImgUpload','图片上传'),(119,5,1,'GameOperator_GameOperatorIndex','游戏运营商索引关联'),(120,39,0,'GameOperator_AddGameOperator','增加游戏运营商索引'),(121,39,0,'Faq_PlayerKindTree','玩家ajax显示FAQ分类'),(122,39,0,'Faq_PlayerKindFaq','玩家ajax显示分类FAQ列表'),(123,39,0,'Faq_PlayerKindIndex','玩家FAQ分类管理'),(124,39,0,'Faq_PlayerKindEdit','玩家FAQ分类编辑'),(125,39,0,'Faq_PlayerKindDel','玩家FAQ分类删除'),(126,39,0,'GameOperator_SetupGameOperator','游戏|运营商的相关设置'),(127,39,0,'User_Edit','编辑用户'),(128,39,0,'User_Del','删除用户'),(129,0,1,'QualityCheck','质检项目'),(131,39,0,'User_OrgAdd','增加组别'),(132,39,0,'User_OrgEdit','编辑组别'),(133,39,0,'User_OrgDel','删除组别'),(134,39,0,'User_OrgCreateCache','建立组别缓存'),(135,129,1,'QualityCheck_Index','质检管理'),(136,39,0,'User_CreateCache','建立用户表缓存'),(137,39,0,'QualityCheck_Dialog','察看工单对话'),(138,39,0,'QualityCheck_ShowQuality','察看对话质检'),(139,129,1,'QualityCheck_MyTask','我质检过的工单'),(140,129,1,'QualityCheck_MyQualityTask','我质检过的回复'),(141,1,1,'MyTask_MyReplyQulity','质检的回复'),(142,39,0,'QualityCheck_QualityDetail','察看质检详细'),(143,0,1,'Stats','数据统计'),(144,0,1,'Verify','查证处理'),(145,144,1,'Verify_Index','查证处理管理'),(146,144,1,'Verify_OrderVerify','增加查证处理'),(147,39,0,'Verify_ChangeStatus','修改查证处理状态'),(148,39,0,'Verify_Detail','查证处理详细'),(149,39,0,'Verify_ReplyDialog','回复留言'),(150,0,1,'Master','管理员功能'),(154,39,0,'GameOperator_CreateCacheGameOperator','建立游戏运营商关联索引缓存'),(155,0,1,'GmFRG','富人国GM'),(156,155,1,'GmFRG_UserSearch','用户查询'),(157,39,0,'GameSerList_Edit','编辑服务器列表'),(158,39,0,'GameSerList_Del','删除服务器列表');

/*Table structure for table `cndw_online_user` */

DROP TABLE IF EXISTS `cndw_online_user`;

CREATE TABLE `cndw_online_user` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `user_name` varchar(50) NOT NULL,
  `last_time` int(10) default '0' COMMENT '上一次刷新的时间',
  KEY `user_id` (`user_id`,`user_name`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Data for the table `cndw_online_user` */

insert  into `cndw_online_user`(`user_id`,`user_name`,`last_time`) values (4,'zlsky',1284687055),(22,'a',1284687055),(23,'b',1284687055),(24,'c',1284687055),(25,'d',1284687055),(37,'chengxi_c',1284687055),(40,'fox',1284687055),(47,'chseason',1284687055);

/*Table structure for table `cndw_operator_list` */

DROP TABLE IF EXISTS `cndw_operator_list`;

CREATE TABLE `cndw_operator_list` (
  `Id` smallint(4) unsigned NOT NULL auto_increment,
  `operator_name` varchar(50) default NULL COMMENT '运营商名称',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='运营商列表';

/*Data for the table `cndw_operator_list` */

insert  into `cndw_operator_list`(`Id`,`operator_name`) values (1,'360安全卫士'),(2,'百度'),(3,'新浪'),(4,'网易163'),(5,'pconline'),(6,'人人网'),(7,'开心网'),(8,'超级明星'),(9,'官网');

/*Table structure for table `cndw_org` */

DROP TABLE IF EXISTS `cndw_org`;

CREATE TABLE `cndw_org` (
  `Id` tinyint(3) NOT NULL auto_increment,
  `name` varchar(20) default NULL COMMENT '组名',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组别表';

/*Data for the table `cndw_org` */

insert  into `cndw_org`(`Id`,`name`) values (1,'海外组'),(2,'官网组'),(3,'测试组');

/*Table structure for table `cndw_player_faq` */

DROP TABLE IF EXISTS `cndw_player_faq`;

CREATE TABLE `cndw_player_faq` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(1) default '0' COMMENT '游戏类型id',
  `kind_id` int(11) default '0' COMMENT '问题类型ID',
  `question` text COMMENT '问题',
  `answer` text COMMENT '答案',
  PRIMARY KEY  (`Id`),
  KEY `game_type` (`game_type_id`),
  KEY `kind_id` (`kind_id`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COMMENT='玩家FAQ';

/*Data for the table `cndw_player_faq` */

insert  into `cndw_player_faq`(`Id`,`game_type_id`,`kind_id`,`question`,`answer`) values (2,1,7,'豪宅仓库原料怎么运输 ','工厂仓库原料运输至豪宅仓库后是不能够往外运输的。当您的工厂生产所需原料不足时，会自动调用豪宅仓库中的原料进行生产。取消生产之后原料会返还到工厂仓库，但超出工厂仓库部分会自动消失，请您注意。'),(3,1,7,'如何获得信赖度 1','尊敬的玩家，投资人的信赖度可以通过执行业绩报告，董事会，常务工作有几率获得。'),(4,1,7,'人物经验怎么获得？ ','    人物三项属性分配总和只能达到2000点，这是属于游戏的正常设置，另外当总分配点达到2000点之后，人物属性点累积到50点时候会出现“兑换”字的兑换按钮，人物属性点每50点可以兑换一点店铺升级点，每70点可以兑换一张兑奖资格券。\r\n'),(5,1,7,'如何加好友？ ','请您点击人物--名片夹--通过搜索交换名片好友名称，然后就可以点击查看对方的信息，然后点击交换名片即可向对方申请交换，而申请过后需要对方玩家通过后才能成功交换名片。'),(6,1,7,'魅力值如何提升？ ','当您对自己的店铺或其他玩家的店铺进行打扫，或者对其他玩家的店铺进行捣乱时，都可以增加魅力值。 根据魅力点数的不同，玩家可实施的捣乱和清洁技能的等级也不一样，如同策略的实施一样，魅力值每上升一个级别则捣乱和清洁的技能增加一个，同时每天可执行的捣乱和打扫的次数也会相应的增加。'),(7,1,7,'战略合作有什么好处 ','不同行业的战略合作关系的效果也是不一样的，其主要的效果可以分为三种：增加店铺的收入；提高工厂获得原料的速度；增加工厂货物产量。详情您可以通过把鼠标移动到“合作”按钮上，即可显示与该玩家合作的效果情况。'),(8,1,7,'名片夹内的好友如何进行互动？ ','游戏内每天可以与好友进行三次互动，互动可以提升双方的好友度，随着友好度的提升，可互动的类型也会随之提升，当双方友好度达到90或以上时，如果非同行业的，并且双方合作人数未满，可以申请达成战略合作关系，详情您可以点击新手FAQ进行了解！'),(9,1,7,'怎么我今天进行出差没有双倍奖励？ ','     人物进行常务工作“出差”只有每周二进行该项常务才会获得双倍奖励哦！其他时间进行“出差”是不能获得双倍奖励的，以下是关于双倍周奖励的详细资料，您可以参考来安排自己在游戏内的操作，以获取更大的利益！\r\n每周一：人物常务工作：思考、看报纸、巡视公司、沟通执行后获得的奖励双倍； \r\n每周二：人物常务工作：分析、谈判、出差、开会执行后所获得的奖励双倍； \r\n每周三：日常任务奖励双倍； \r\n每周四：员工常务工作：接电话、派传单执行后获得的奖励双倍； \r\n每周五：员工常务工作：写报表、策划方案、脑力训练、商场促销执行后获得双倍奖励； \r\n每周六：深造员工成功几率双倍； \r\n每周日：黑衣人奖励双倍。'),(10,1,7,' 疲劳度有什么用？1','    疲劳度是人物进行常务工作所需的条件来的，不同的常务工作需要消耗增加不同数值的疲劳度，某些工作可以恢复减少疲劳度。具体信息如下： <br />\r\n（1）.疲劳度上限1000<br />\r\n（2）.疲劳度每天0：00分自动恢复到1000点<br />\r\n（3）.疲劳度在常务工作执行前检测,不达到所需点数不予执行；疲劳度在常务开始执行时消耗<br />\r\n（4）.常务工作内增设“休息”，效果为疲劳度恢复减少24点<br />\r\n（5）“开会”常务工作有次数限制，但不受疲劳度影响'),(11,1,7,'硬币可以兑换什么？ 1','   <span style=\\\"color:#e53333;\\\">尊敬的玩家，具体兑换的物品如下，请您查看：</span><br />\r\n招聘协议2：需要银硬币1 以及铜硬币3  <br />\r\n赠送金币30：需要银硬币6 以及铜硬币12  <br />\r\n黄金实验瓶5：需要银硬币10 以及铜硬币20  <br />\r\n黄金合约2：需要银硬币1 以及铜硬币3  <br />\r\n砖头10：需要银硬币5 以及铜硬币10  <br />\r\n初级实验手册10：需要银硬币10以及铜硬币20  <br />\r\n赠送金币150：需要银硬币12以及铜硬币25'),(12,1,8,'座驾合成后，属性变差了？ ','通过合成可以合成出许多在市面上买不到的座驾，它们有几率合成出属性十分卓越，并且可以自定义名称，但也有几率合成出属性一般的座驾。合成成功率最大为100%，超过100%的按照100%来计算，放入名贵的座驾虽然可以获得很高的合成成功率，但合成出来后的座驾属性可能会低于放入的座驾属性，请玩家谨慎选择。'),(13,1,8,'在哪里买车？ ','    请您点击城市—极速车行即可选择购买您所需的座驾，另外通过援助贝尔和法老的宝藏均有几率可以获得座驾哦。更多游戏信息请查看位于游戏聊天频道上方的“新手FAQ”。'),(14,1,8,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！\r\n'),(15,1,8,'使用了座驾，但做常务工作怎么没有效果的？ ','人物常务工作中“看报纸”、“思考”、“开会”、“休息”是不受座驾效果影响，另外建议您可以通过卸下座驾，以及装备该座驾来对比使用座驾前和使用座驾后的常务工作时间，即可清楚地了解到该座驾实际的效果，如确实存在座驾属性异常情况的话，欢迎您继续与我们联系，我们会为您进行相关的查证处理的，请您放心！'),(16,1,8,'租用的座驾有什么效果？ ','&nbsp;&nbsp;&nbsp; 租用的座驾可以用来进行旅游，但租用的座驾没有属性效果，所以对常务工作也没有任何的加成效果，另外租用的座驾也不可用来完成金字塔等活动任务！'),(17,1,8,'我有座驾，但做看报纸的时间没有减短','人物常务工作中“看报纸”、“思考”是不受座驾效果影响的，另外人物常务“开会”、“休息”也与座驾没有直接的联系'),(18,1,8,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！'),(19,1,8,'为什么我拥有机动车了，为什么不能去旅游？',' 每个旅游地区的座驾要求都是不一样的，除了座驾类型要满足要求外，还需要其座驾等级满足要求才可进行旅游，请您留意下您的座驾是否满足要求，如果未能满足要求的话，是不能进行旅游的哦！'),(20,1,8,'有没二手车行？ ','游戏尚未提供卖车和车辆交易的功能哦。建议您可以把不再使用的汽车收藏起来，以丰富您的车库哦，另外豪宅内的“立体停车场”还可以展示永久型金币座驾哦！您可以留意下，谢谢！\r\n'),(21,1,3,'我充值成功，没获得金币？ ','当系统提示您充值成功后，建议您刷新下游戏页面，即可获得充值所得的金币。另外如果没有立即获得金币，请稍等几分钟，金币到账有一定的延迟时间是正常的情况，请您放心！如长时间金币都无法到账的话，请您及时与我们联系，我们会尽快为您进行查证处理的'),(22,1,3,'官网充值失败 ','    为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！\r\n具体地址如下http://bbs.uwan.com/forum-19-1.html\r\n格式如下： \r\n充值账号： （必填） \r\n充值服务器：（必填） \r\n充值时间： （必填） \r\n充值金额： （必填） \r\n充值类型： （必填） \r\n游戏充值订单号： （必填） \r\n银行交易订单号： （网银充值时须填写） \r\n银行交易流水号： （网银充值时须填写） \r\n卡类充值请提供充值卡类型及卡号。'),(23,1,3,'官网充值失败 ','&nbsp;&nbsp;&nbsp; 为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！<br />\r\n具体地址如下<a href=\\\"http://bbs.uwan.com/forum-19-1.html\\\">http://bbs.uwan.com/forum-19-1.html</a><br />\r\n格式如下： <br />\r\n充值账号： （必填） <br />\r\n充值服务器：（必填） <br />\r\n充值时间： （必填） <br />\r\n充值金额： （必填） <br />\r\n充值类型： （必填） <br />\r\n游戏充值订单号： （必填） <br />\r\n银行交易订单号： （网银充值时须填写） <br />\r\n银行交易流水号： （网银充值时须填'),(24,1,3,' 能用手机短信方式充值吗？ ','现在游戏中是可以使用手机短信的方式进行充值的，使用手机短信充值时需要支付50%的手续费，详情建议您点击游戏内的“我要充值”来进行了解，感谢您的支持和理解！\r\n'),(25,1,4,'新手怎么领取金币？ ','尊敬的玩家，每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；<br />\r\n&nbsp;&nbsp;&nbsp; 请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。'),(26,1,4,'新手怎么领取金币？ ','每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；\r\n    请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。\r\n'),(27,1,4,'赠送金币可以干什么？ ','赠送金币可以实现金币的部分功能，赠送金币可以在商城购买VIP卡、劲霸浓鱼汤、猛龙保安队、和解协议、空心爆米花、搬家公司、企业更名申请表、解冻剂、不松口果汁、保密协议、探索装备（探索装备需有限公司等级或以上才可以使用赠送金币购买）等部分道具，您可以点击该物品并选择用赠送金币支付。\r\n　　您也可以使用赠送金币，为能够进行加速的升级过程进行加速升级哦。赠送金币还能作为批量采购、批量停业和批量开业的手续费使用。\r\n'),(28,1,5,'拆除工厂后，售销经验还在吗？仓库等级会不会少？ ','工厂拆除后工厂的一切数据将被清空（包括技术经验和销售经验），除原料外其他物品信息保留。所以您的设备等级和仓库等级都会没有了，需要重新开始。  \r\n'),(29,1,6,'工厂仓库中的材料怎么用？ ','尊敬的玩家，工厂仓库中的材料是配合相应的图纸用于在工厂生产道具，工厂生产道具必须拥有图纸、材料都符合条件，首先您选择图纸，点击使用了图纸之后，图纸便会进入到工厂中图纸车间，这样您以后就可以在工厂中生产道具了。具体操作流程为：我的工厂-生产车间-生产道具。根据图纸的不同，会消耗相应所需的材料而生产出各种各样的道具来哦。\r\n'),(30,1,5,'如何获得销售经验？ ','销售经验必须通过向系统或者玩家销售工厂当前可生产的最高等级货物才能获得，并且必须是自行生产的货物。另外通过交易市场收购的货物再进行销售是无法获得销售经验的，并且将自己工厂所生产的货物运输到店铺里面使用也是不会获得销售经验的，请您留意！\r\n'),(31,1,5,'原油原料 ','原油可以通过建立油井来开采原油，油井可通过勘测油田和竞标油田的方式来获取油田进行搭建,另外,您也可以尝试通过交易市场收购原油原料,但由于原油原料比较稀缺,有时候交易市场也不一定有出售喔.   '),(32,1,5,'创建工厂需要什么条件 ','创建工厂需要满足以下条件：<br />\r\n1：公司等级到达小型公司（事业--公司信息--可以查看到公司等级）。<br />\r\n2：提升一名员工为工厂主任（该员工拥有产品天赋（初级），事业--公司架构--提升为工厂主任）。<br />\r\n3：创建费用：10000G币。<br />\r\n&nbsp;&nbsp;&nbsp; 当您满足了以上条件后，即可点击“创建工厂”来进行工厂的建设。'),(33,1,5,'工厂如何采购原材料 ',' 工厂每天会自动获得系统赠送的原料，另外也可以点击“城市”-“交易市场”-“我要收购”来收购其他玩家出售的原料，或者在商场用金币购买“物资箱”来使用获得原料，现在游戏当中还可以通过使用原料卡来获取原料，原料卡主要是通过参加法老的宝藏来获得。 '),(34,1,5,' 如何提高原料的获取速度 ','您可以通过提升人物属性—智慧属性、工厂对应行业销售经验来提高原料的获取速度，更多提升原料获得速度的途径，您可以通过将鼠标移动到工厂信息内的“原料产量”即可显示相关提示。'),(35,1,5,'工厂的销售经验有什么用 ','销售经验是跟您的工厂原料获取速度有关的哦，提升某一行业的销售经验，可以提升对应该行业原料的获取速度，增加销售经验是需要销售您当前工厂所能生产的最高级别的货物才能增加的。\r\n'),(36,1,5,'天然气有什么用？ ','将天然气运输到店铺里面使用可以增加店铺的收入，每8个小时使用一罐天然气，不同等级的天然气，其效果也是不一样的，另外天然气还可以挂单到交易市场进行出售。\r\n'),(37,1,5,'特产货物 ','您可以把仓库内符合行业的特产货物放在您的店铺进行销售哦。选择您需要进行销售的店铺，在系统货物/工厂货物右边点击“切换为该货物”，点击“从仓库运输”再按系统提示继续操作即可。     \r\n    您也可以把特产货物放到交易系统卖给其他玩家。点击界面右下方的“交易”，在右边一栏选择“我要出售”，再按系统指示完成操作即可。\r\n'),(38,1,5,'如何创建工厂 ','当您满足了创建工厂的所有条件之后，请您点击事业-创建工厂，接着选择工业区及街道，然后点击一块空地就可以选择工厂类型来创建您的工厂了！创建工厂的时候系统会提示您开设工厂的具体要求的。\r\n'),(39,1,5,'如何获得原料卡 ','尊敬的玩家，您可以通过探索法老的宝藏、勇攀高峰、援助贝尔、充值礼包、日充值奖励等途径获得原料卡；部分的活动奖励中也包含原料卡。更多的获得途径欢迎您在游戏中与其他玩家沟通获得，或者留意论坛公告。祝您游戏愉快！'),(40,1,4,' G币可以兑换为金币吗？ ','游戏内暂时是不提供G币兑换金币的功能，金币主要是通过充值来获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得哦！请您留意下相关情况，谢谢！\r\n'),(41,1,4,'如何获得金币？ ','金币主要是通过充值获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得。如果雕像主人有设置敬仰奖励的话，您在敬仰时也是可以获得相应的金币，留下祝福后雕像主人还可以根据他自己的意愿赏金币给您。部分的活动（例如起点树活动）也是可以获得金币的，请您密切留意论坛公告。'),(42,1,4,'赠送金币怎么获得 ','新手进入游戏前三天可以到城市--福利社领取赠送金币，您的公司升级时，系统也会赠送公司升级大礼包，部分升级礼包里面也会有赠送金币，VIP每天登陆奖励有机会获得，兑奖资格券达到规定的数目可以兑换赠送金币。参加16小时活动：晨跑和吃早餐有几率获得的铜币和银币，当达到一定数量可以兑换赠送金币哦，部分活动也有一定几率获得赠送金币哦。'),(43,1,4,'赠送金币可以干什么？ ','赠送金币可以实现金币的部分功能，赠送金币可以在商城购买VIP卡、劲霸浓鱼汤、猛龙保安队、和解协议、空心爆米花、搬家公司、企业更名申请表、解冻剂、不松口果汁、保密协议、探索装备（探索装备需有限公司等级或以上才可以使用赠送金币购买）等部分道具，您可以点击该物品并选择用赠送金币支付。<br />\r\n　　您也可以使用赠送金币，为能够进行加速的升级过程进行加速升级哦。赠送金币还能作为批量采购、批量停业和批量开业的手续费使用。<br />'),(44,1,3,'我有座驾，但做看报纸的时间没有减短 ','人物常务工作中“看报纸”、“思考”是不受座驾效果影响的，另外人物常务“开会”、“休息”也与座驾没有直接的联系，请您留意下相关情况!\r\n'),(45,1,8,' 租用的座驾有什么效果？ ','租用的座驾可以用来进行旅游，但租用的座驾没有属性效果，所以对常务工作也没有任何的加成效果，另外租用的座驾也不可用来完成金字塔等活动任务！\r\n'),(46,2,13,'如何提升社会地位 ','人物的社会地位与人物的经验值有关，对应数值如下：社会底层人士 0 平民 70 市民 300 上流人士 600 贵族 1680 名门 2350 伯爵 4160 侯爵 8240 公爵 20600；获得人物经验途径有：1、投资人任务获得固定奖励；2、人物常务工作有几率获得；3、与黑衣人物质代表谈判有几率获得；4、更多获得人物经验值途径请留意官方活动与公告。'),(47,2,13,'豪宅仓库原料怎么运输 ','工厂仓库原料运输至豪宅仓库后是不能够往外运输的。当您的工厂生产所需原料不足时，会自动调用豪宅仓库中的原料进行生产。取消生产之后原料会返还到工厂仓库，但超出工厂仓库部分会自动消失，请您注意。'),(48,2,13,'如何获得信赖度 ','尊敬的玩家，投资人的信赖度可以通过执行业绩报告，董事会，常务工作有几率获得。'),(49,2,13,'人物经验怎么获得？ ','    人物三项属性分配总和只能达到2000点，这是属于游戏的正常设置，另外当总分配点达到2000点之后，人物属性点累积到50点时候会出现“兑换”字的兑换按钮，人物属性点每50点可以兑换一点店铺升级点，每70点可以兑换一张兑奖资格券。\r\n'),(50,2,13,'如何加好友？ ','请您点击人物--名片夹--通过搜索交换名片好友名称，然后就可以点击查看对方的信息，然后点击交换名片即可向对方申请交换，而申请过后需要对方玩家通过后才能成功交换名片。'),(51,2,13,'魅力值如何提升？ ','当您对自己的店铺或其他玩家的店铺进行打扫，或者对其他玩家的店铺进行捣乱时，都可以增加魅力值。 根据魅力点数的不同，玩家可实施的捣乱和清洁技能的等级也不一样，如同策略的实施一样，魅力值每上升一个级别则捣乱和清洁的技能增加一个，同时每天可执行的捣乱和打扫的次数也会相应的增加。'),(52,2,13,'战略合作有什么好处 ','不同行业的战略合作关系的效果也是不一样的，其主要的效果可以分为三种：增加店铺的收入；提高工厂获得原料的速度；增加工厂货物产量。详情您可以通过把鼠标移动到“合作”按钮上，即可显示与该玩家合作的效果情况。'),(53,2,13,'名片夹内的好友如何进行互动？ ','游戏内每天可以与好友进行三次互动，互动可以提升双方的好友度，随着友好度的提升，可互动的类型也会随之提升，当双方友好度达到90或以上时，如果非同行业的，并且双方合作人数未满，可以申请达成战略合作关系，详情您可以点击新手FAQ进行了解！'),(54,2,13,'怎么我今天进行出差没有双倍奖励？ ','     人物进行常务工作“出差”只有每周二进行该项常务才会获得双倍奖励哦！其他时间进行“出差”是不能获得双倍奖励的，以下是关于双倍周奖励的详细资料，您可以参考来安排自己在游戏内的操作，以获取更大的利益！\r\n每周一：人物常务工作：思考、看报纸、巡视公司、沟通执行后获得的奖励双倍； \r\n每周二：人物常务工作：分析、谈判、出差、开会执行后所获得的奖励双倍； \r\n每周三：日常任务奖励双倍； \r\n每周四：员工常务工作：接电话、派传单执行后获得的奖励双倍； \r\n每周五：员工常务工作：写报表、策划方案、脑力训练、商场促销执行后获得双倍奖励； \r\n每周六：深造员工成功几率双倍； \r\n每周日：黑衣人奖励双倍。'),(55,2,14,' 疲劳度有什么用？1','    疲劳度是人物进行常务工作所需的条件来的，不同的常务工作需要消耗增加不同数值的疲劳度，某些工作可以恢复减少疲劳度。具体信息如下： <br />\r\n（1）.疲劳度上限1000<br />\r\n（2）.疲劳度每天0：00分自动恢复到1000点<br />\r\n（3）.疲劳度在常务工作执行前检测,不达到所需点数不予执行；疲劳度在常务开始执行时消耗<br />\r\n（4）.常务工作内增设“休息”，效果为疲劳度恢复减少24点<br />\r\n（5）“开会”常务工作有次数限制，但不受疲劳度影响'),(56,2,14,'硬币可以兑换什么？ 1','   <span style=\\\"color:#e53333;\\\">尊敬的玩家，具体兑换的物品如下，请您查看：</span><br />\r\n招聘协议2：需要银硬币1 以及铜硬币3  <br />\r\n赠送金币30：需要银硬币6 以及铜硬币12  <br />\r\n黄金实验瓶5：需要银硬币10 以及铜硬币20  <br />\r\n黄金合约2：需要银硬币1 以及铜硬币3  <br />\r\n砖头10：需要银硬币5 以及铜硬币10  <br />\r\n初级实验手册10：需要银硬币10以及铜硬币20  <br />\r\n赠送金币150：需要银硬币12以及铜硬币25'),(57,2,14,'座驾合成后，属性变差了？ ','通过合成可以合成出许多在市面上买不到的座驾，它们有几率合成出属性十分卓越，并且可以自定义名称，但也有几率合成出属性一般的座驾。合成成功率最大为100%，超过100%的按照100%来计算，放入名贵的座驾虽然可以获得很高的合成成功率，但合成出来后的座驾属性可能会低于放入的座驾属性，请玩家谨慎选择。'),(58,2,14,'在哪里买车？ ','    请您点击城市—极速车行即可选择购买您所需的座驾，另外通过援助贝尔和法老的宝藏均有几率可以获得座驾哦。更多游戏信息请查看位于游戏聊天频道上方的“新手FAQ”。'),(59,2,14,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！\r\n'),(60,2,14,'使用了座驾，但做常务工作怎么没有效果的？ ','人物常务工作中“看报纸”、“思考”、“开会”、“休息”是不受座驾效果影响，另外建议您可以通过卸下座驾，以及装备该座驾来对比使用座驾前和使用座驾后的常务工作时间，即可清楚地了解到该座驾实际的效果，如确实存在座驾属性异常情况的话，欢迎您继续与我们联系，我们会为您进行相关的查证处理的，请您放心！'),(61,2,14,'租用的座驾有什么效果？ ','&nbsp;&nbsp;&nbsp; 租用的座驾可以用来进行旅游，但租用的座驾没有属性效果，所以对常务工作也没有任何的加成效果，另外租用的座驾也不可用来完成金字塔等活动任务！'),(62,2,14,'我有座驾，但做看报纸的时间没有减短','人物常务工作中“看报纸”、“思考”是不受座驾效果影响的，另外人物常务“开会”、“休息”也与座驾没有直接的联系'),(63,2,15,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！'),(64,2,15,'为什么我拥有机动车了，为什么不能去旅游？',' 每个旅游地区的座驾要求都是不一样的，除了座驾类型要满足要求外，还需要其座驾等级满足要求才可进行旅游，请您留意下您的座驾是否满足要求，如果未能满足要求的话，是不能进行旅游的哦！'),(65,2,15,'有没二手车行？ ','游戏尚未提供卖车和车辆交易的功能哦。建议您可以把不再使用的汽车收藏起来，以丰富您的车库哦，另外豪宅内的“立体停车场”还可以展示永久型金币座驾哦！您可以留意下，谢谢！\r\n'),(66,2,15,'我充值成功，没获得金币？ ','当系统提示您充值成功后，建议您刷新下游戏页面，即可获得充值所得的金币。另外如果没有立即获得金币，请稍等几分钟，金币到账有一定的延迟时间是正常的情况，请您放心！如长时间金币都无法到账的话，请您及时与我们联系，我们会尽快为您进行查证处理的'),(67,2,15,'官网充值失败 ','    为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！\r\n具体地址如下http://bbs.uwan.com/forum-19-1.html\r\n格式如下： \r\n充值账号： （必填） \r\n充值服务器：（必填） \r\n充值时间： （必填） \r\n充值金额： （必填） \r\n充值类型： （必填） \r\n游戏充值订单号： （必填） \r\n银行交易订单号： （网银充值时须填写） \r\n银行交易流水号： （网银充值时须填写） \r\n卡类充值请提供充值卡类型及卡号。'),(68,2,15,'官网充值失败 ','&nbsp;&nbsp;&nbsp; 为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！<br />\r\n具体地址如下<a href=\\\"http://bbs.uwan.com/forum-19-1.html\\\">http://bbs.uwan.com/forum-19-1.html</a><br />\r\n格式如下： <br />\r\n充值账号： （必填） <br />\r\n充值服务器：（必填） <br />\r\n充值时间： （必填） <br />\r\n充值金额： （必填） <br />\r\n充值类型： （必填） <br />\r\n游戏充值订单号： （必填） <br />\r\n银行交易订单号： （网银充值时须填写） <br />\r\n银行交易流水号： （网银充值时须填'),(69,2,15,' 能用手机短信方式充值吗？ ','现在游戏中是可以使用手机短信的方式进行充值的，使用手机短信充值时需要支付50%的手续费，详情建议您点击游戏内的“我要充值”来进行了解，感谢您的支持和理解！\r\n'),(70,2,15,'新手怎么领取金币？ ','尊敬的玩家，每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；<br />\r\n&nbsp;&nbsp;&nbsp; 请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。'),(71,2,15,'新手怎么领取金币？ ','每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；\r\n    请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。\r\n'),(72,2,15,'赠送金币可以干什么？ ','赠送金币可以实现金币的部分功能，赠送金币可以在商城购买VIP卡、劲霸浓鱼汤、猛龙保安队、和解协议、空心爆米花、搬家公司、企业更名申请表、解冻剂、不松口果汁、保密协议、探索装备（探索装备需有限公司等级或以上才可以使用赠送金币购买）等部分道具，您可以点击该物品并选择用赠送金币支付。\r\n　　您也可以使用赠送金币，为能够进行加速的升级过程进行加速升级哦。赠送金币还能作为批量采购、批量停业和批量开业的手续费使用。\r\n'),(73,2,15,'拆除工厂后，售销经验还在吗？仓库等级会不会少？ ','工厂拆除后工厂的一切数据将被清空（包括技术经验和销售经验），除原料外其他物品信息保留。所以您的设备等级和仓库等级都会没有了，需要重新开始。  \r\n'),(74,2,16,'工厂仓库中的材料怎么用？ ','尊敬的玩家，工厂仓库中的材料是配合相应的图纸用于在工厂生产道具，工厂生产道具必须拥有图纸、材料都符合条件，首先您选择图纸，点击使用了图纸之后，图纸便会进入到工厂中图纸车间，这样您以后就可以在工厂中生产道具了。具体操作流程为：我的工厂-生产车间-生产道具。根据图纸的不同，会消耗相应所需的材料而生产出各种各样的道具来哦。\r\n'),(75,2,16,'如何获得销售经验？ ','销售经验必须通过向系统或者玩家销售工厂当前可生产的最高等级货物才能获得，并且必须是自行生产的货物。另外通过交易市场收购的货物再进行销售是无法获得销售经验的，并且将自己工厂所生产的货物运输到店铺里面使用也是不会获得销售经验的，请您留意！\r\n'),(76,2,16,'原油原料 ','原油可以通过建立油井来开采原油，油井可通过勘测油田和竞标油田的方式来获取油田进行搭建,另外,您也可以尝试通过交易市场收购原油原料,但由于原油原料比较稀缺,有时候交易市场也不一定有出售喔.   '),(77,2,16,'创建工厂需要什么条件 ','创建工厂需要满足以下条件：<br />\r\n1：公司等级到达小型公司（事业--公司信息--可以查看到公司等级）。<br />\r\n2：提升一名员工为工厂主任（该员工拥有产品天赋（初级），事业--公司架构--提升为工厂主任）。<br />\r\n3：创建费用：10000G币。<br />\r\n&nbsp;&nbsp;&nbsp; 当您满足了以上条件后，即可点击“创建工厂”来进行工厂的建设。'),(78,2,16,'工厂如何采购原材料 ',' 工厂每天会自动获得系统赠送的原料，另外也可以点击“城市”-“交易市场”-“我要收购”来收购其他玩家出售的原料，或者在商场用金币购买“物资箱”来使用获得原料，现在游戏当中还可以通过使用原料卡来获取原料，原料卡主要是通过参加法老的宝藏来获得。 '),(79,2,16,' 如何提高原料的获取速度 ','您可以通过提升人物属性—智慧属性、工厂对应行业销售经验来提高原料的获取速度，更多提升原料获得速度的途径，您可以通过将鼠标移动到工厂信息内的“原料产量”即可显示相关提示。'),(80,2,16,'工厂的销售经验有什么用 ','销售经验是跟您的工厂原料获取速度有关的哦，提升某一行业的销售经验，可以提升对应该行业原料的获取速度，增加销售经验是需要销售您当前工厂所能生产的最高级别的货物才能增加的。\r\n'),(81,2,16,'天然气有什么用？ ','将天然气运输到店铺里面使用可以增加店铺的收入，每8个小时使用一罐天然气，不同等级的天然气，其效果也是不一样的，另外天然气还可以挂单到交易市场进行出售。\r\n'),(82,2,16,'特产货物 ','您可以把仓库内符合行业的特产货物放在您的店铺进行销售哦。选择您需要进行销售的店铺，在系统货物/工厂货物右边点击“切换为该货物”，点击“从仓库运输”再按系统提示继续操作即可。     \r\n    您也可以把特产货物放到交易系统卖给其他玩家。点击界面右下方的“交易”，在右边一栏选择“我要出售”，再按系统指示完成操作即可。\r\n'),(83,2,16,'如何创建工厂 ','当您满足了创建工厂的所有条件之后，请您点击事业-创建工厂，接着选择工业区及街道，然后点击一块空地就可以选择工厂类型来创建您的工厂了！创建工厂的时候系统会提示您开设工厂的具体要求的。\r\n'),(84,2,16,'如何获得原料卡 ','尊敬的玩家，您可以通过探索法老的宝藏、勇攀高峰、援助贝尔、充值礼包、日充值奖励等途径获得原料卡；部分的活动奖励中也包含原料卡。更多的获得途径欢迎您在游戏中与其他玩家沟通获得，或者留意论坛公告。祝您游戏愉快！'),(85,2,17,' G币可以兑换为金币吗？ ','游戏内暂时是不提供G币兑换金币的功能，金币主要是通过充值来获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得哦！请您留意下相关情况，谢谢！\r\n'),(86,2,17,'如何获得金币？ ','金币主要是通过充值获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得。如果雕像主人有设置敬仰奖励的话，您在敬仰时也是可以获得相应的金币，留下祝福后雕像主人还可以根据他自己的意愿赏金币给您。部分的活动（例如起点树活动）也是可以获得金币的，请您密切留意论坛公告。'),(87,2,17,'赠送金币怎么获得 ','新手进入游戏前三天可以到城市--福利社领取赠送金币，您的公司升级时，系统也会赠送公司升级大礼包，部分升级礼包里面也会有赠送金币，VIP每天登陆奖励有机会获得，兑奖资格券达到规定的数目可以兑换赠送金币。参加16小时活动：晨跑和吃早餐有几率获得的铜币和银币，当达到一定数量可以兑换赠送金币哦，部分活动也有一定几率获得赠送金币哦。'),(88,2,17,'赠送金币可以干什么？ ','赠送金币可以实现金币的部分功能，赠送金币可以在商城购买VIP卡、劲霸浓鱼汤、猛龙保安队、和解协议、空心爆米花、搬家公司、企业更名申请表、解冻剂、不松口果汁、保密协议、探索装备（探索装备需有限公司等级或以上才可以使用赠送金币购买）等部分道具，您可以点击该物品并选择用赠送金币支付。<br />\r\n　　您也可以使用赠送金币，为能够进行加速的升级过程进行加速升级哦。赠送金币还能作为批量采购、批量停业和批量开业的手续费使用。<br />'),(89,2,17,'我有座驾，但做看报纸的时间没有减短 ','人物常务工作中“看报纸”、“思考”是不受座驾效果影响的，另外人物常务“开会”、“休息”也与座驾没有直接的联系，请您留意下相关情况!\r\n'),(90,2,17,' 租用的座驾有什么效果？ ','租用的座驾可以用来进行旅游，但租用的座驾没有属性效果，所以对常务工作也没有任何的加成效果，另外租用的座驾也不可用来完成金字塔等活动任务！\r\n'),(91,3,18,'如何提升社会地位 ','人物的社会地位与人物的经验值有关，对应数值如下：社会底层人士 0 平民 70 市民 300 上流人士 600 贵族 1680 名门 2350 伯爵 4160 侯爵 8240 公爵 20600；获得人物经验途径有：1、投资人任务获得固定奖励；2、人物常务工作有几率获得；3、与黑衣人物质代表谈判有几率获得；4、更多获得人物经验值途径请留意官方活动与公告。'),(92,3,18,'豪宅仓库原料怎么运输 ','工厂仓库原料运输至豪宅仓库后是不能够往外运输的。当您的工厂生产所需原料不足时，会自动调用豪宅仓库中的原料进行生产。取消生产之后原料会返还到工厂仓库，但超出工厂仓库部分会自动消失，请您注意。'),(93,3,18,'如何获得信赖度 ','尊敬的玩家，投资人的信赖度可以通过执行业绩报告，董事会，常务工作有几率获得。'),(94,3,18,'人物经验怎么获得？ ','    人物三项属性分配总和只能达到2000点，这是属于游戏的正常设置，另外当总分配点达到2000点之后，人物属性点累积到50点时候会出现“兑换”字的兑换按钮，人物属性点每50点可以兑换一点店铺升级点，每70点可以兑换一张兑奖资格券。\r\n'),(95,3,18,'如何加好友？ ','请您点击人物--名片夹--通过搜索交换名片好友名称，然后就可以点击查看对方的信息，然后点击交换名片即可向对方申请交换，而申请过后需要对方玩家通过后才能成功交换名片。'),(96,3,18,'魅力值如何提升？ ','当您对自己的店铺或其他玩家的店铺进行打扫，或者对其他玩家的店铺进行捣乱时，都可以增加魅力值。 根据魅力点数的不同，玩家可实施的捣乱和清洁技能的等级也不一样，如同策略的实施一样，魅力值每上升一个级别则捣乱和清洁的技能增加一个，同时每天可执行的捣乱和打扫的次数也会相应的增加。'),(97,3,18,'战略合作有什么好处 ','不同行业的战略合作关系的效果也是不一样的，其主要的效果可以分为三种：增加店铺的收入；提高工厂获得原料的速度；增加工厂货物产量。详情您可以通过把鼠标移动到“合作”按钮上，即可显示与该玩家合作的效果情况。'),(98,3,18,'名片夹内的好友如何进行互动？ ','游戏内每天可以与好友进行三次互动，互动可以提升双方的好友度，随着友好度的提升，可互动的类型也会随之提升，当双方友好度达到90或以上时，如果非同行业的，并且双方合作人数未满，可以申请达成战略合作关系，详情您可以点击新手FAQ进行了解！'),(99,3,18,'怎么我今天进行出差没有双倍奖励？ ','     人物进行常务工作“出差”只有每周二进行该项常务才会获得双倍奖励哦！其他时间进行“出差”是不能获得双倍奖励的，以下是关于双倍周奖励的详细资料，您可以参考来安排自己在游戏内的操作，以获取更大的利益！\r\n每周一：人物常务工作：思考、看报纸、巡视公司、沟通执行后获得的奖励双倍； \r\n每周二：人物常务工作：分析、谈判、出差、开会执行后所获得的奖励双倍； \r\n每周三：日常任务奖励双倍； \r\n每周四：员工常务工作：接电话、派传单执行后获得的奖励双倍； \r\n每周五：员工常务工作：写报表、策划方案、脑力训练、商场促销执行后获得双倍奖励； \r\n每周六：深造员工成功几率双倍； \r\n每周日：黑衣人奖励双倍。'),(100,3,18,' 疲劳度有什么用？1','    疲劳度是人物进行常务工作所需的条件来的，不同的常务工作需要消耗增加不同数值的疲劳度，某些工作可以恢复减少疲劳度。具体信息如下： <br />\r\n（1）.疲劳度上限1000<br />\r\n（2）.疲劳度每天0：00分自动恢复到1000点<br />\r\n（3）.疲劳度在常务工作执行前检测,不达到所需点数不予执行；疲劳度在常务开始执行时消耗<br />\r\n（4）.常务工作内增设“休息”，效果为疲劳度恢复减少24点<br />\r\n（5）“开会”常务工作有次数限制，但不受疲劳度影响'),(101,3,18,'硬币可以兑换什么？ 1','   <span style=\\\"color:#e53333;\\\">尊敬的玩家，具体兑换的物品如下，请您查看：</span><br />\r\n招聘协议2：需要银硬币1 以及铜硬币3  <br />\r\n赠送金币30：需要银硬币6 以及铜硬币12  <br />\r\n黄金实验瓶5：需要银硬币10 以及铜硬币20  <br />\r\n黄金合约2：需要银硬币1 以及铜硬币3  <br />\r\n砖头10：需要银硬币5 以及铜硬币10  <br />\r\n初级实验手册10：需要银硬币10以及铜硬币20  <br />\r\n赠送金币150：需要银硬币12以及铜硬币25'),(102,3,18,'座驾合成后，属性变差了？ ','通过合成可以合成出许多在市面上买不到的座驾，它们有几率合成出属性十分卓越，并且可以自定义名称，但也有几率合成出属性一般的座驾。合成成功率最大为100%，超过100%的按照100%来计算，放入名贵的座驾虽然可以获得很高的合成成功率，但合成出来后的座驾属性可能会低于放入的座驾属性，请玩家谨慎选择。'),(103,3,18,'在哪里买车？ ','    请您点击城市—极速车行即可选择购买您所需的座驾，另外通过援助贝尔和法老的宝藏均有几率可以获得座驾哦。更多游戏信息请查看位于游戏聊天频道上方的“新手FAQ”。'),(104,3,18,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！\r\n'),(105,3,18,'使用了座驾，但做常务工作怎么没有效果的？ ','人物常务工作中“看报纸”、“思考”、“开会”、“休息”是不受座驾效果影响，另外建议您可以通过卸下座驾，以及装备该座驾来对比使用座驾前和使用座驾后的常务工作时间，即可清楚地了解到该座驾实际的效果，如确实存在座驾属性异常情况的话，欢迎您继续与我们联系，我们会为您进行相关的查证处理的，请您放心！'),(106,3,18,'租用的座驾有什么效果？ ','&nbsp;&nbsp;&nbsp; 租用的座驾可以用来进行旅游，但租用的座驾没有属性效果，所以对常务工作也没有任何的加成效果，另外租用的座驾也不可用来完成金字塔等活动任务！'),(108,3,19,'座驾合成一定能合成好东西吗？ ','合成出来的座驾属性高低是有一定几率的，并且放入合成的座驾只会影响到合成的成功率，与合成出来的座驾属性是没有关系的，另外放入合成的座驾无论其是否成功都会消失，还请您留意！'),(109,3,19,'为什么我拥有机动车了，为什么不能去旅游？',' 每个旅游地区的座驾要求都是不一样的，除了座驾类型要满足要求外，还需要其座驾等级满足要求才可进行旅游，请您留意下您的座驾是否满足要求，如果未能满足要求的话，是不能进行旅游的哦！'),(110,3,19,'有没二手车行？ ','游戏尚未提供卖车和车辆交易的功能哦。建议您可以把不再使用的汽车收藏起来，以丰富您的车库哦，另外豪宅内的“立体停车场”还可以展示永久型金币座驾哦！您可以留意下，谢谢！\r\n'),(111,3,19,'我充值成功，没获得金币？ ','当系统提示您充值成功后，建议您刷新下游戏页面，即可获得充值所得的金币。另外如果没有立即获得金币，请稍等几分钟，金币到账有一定的延迟时间是正常的情况，请您放心！如长时间金币都无法到账的话，请您及时与我们联系，我们会尽快为您进行查证处理的'),(112,3,19,'官网充值失败 ','    为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！\r\n具体地址如下http://bbs.uwan.com/forum-19-1.html\r\n格式如下： \r\n充值账号： （必填） \r\n充值服务器：（必填） \r\n充值时间： （必填） \r\n充值金额： （必填） \r\n充值类型： （必填） \r\n游戏充值订单号： （必填） \r\n银行交易订单号： （网银充值时须填写） \r\n银行交易流水号： （网银充值时须填写） \r\n卡类充值请提供充值卡类型及卡号。'),(113,3,19,'官网充值失败 ','&nbsp;&nbsp;&nbsp; 为了更快地为您进行充值方面的查询和处理，请您到我们的论坛充值服务区按照要求格式发帖，我们将有专人第一时间对您的情况进行查询和处理的！<br />\r\n具体地址如下<a href=\\\"http://bbs.uwan.com/forum-19-1.html\\\">http://bbs.uwan.com/forum-19-1.html</a><br />\r\n格式如下： <br />\r\n充值账号： （必填） <br />\r\n充值服务器：（必填） <br />\r\n充值时间： （必填） <br />\r\n充值金额： （必填） <br />\r\n充值类型： （必填） <br />\r\n游戏充值订单号： （必填） <br />\r\n银行交易订单号： （网银充值时须填写） <br />\r\n银行交易流水号： （网银充值时须填'),(114,3,19,' 能用手机短信方式充值吗？ ','现在游戏中是可以使用手机短信的方式进行充值的，使用手机短信充值时需要支付50%的手续费，详情建议您点击游戏内的“我要充值”来进行了解，感谢您的支持和理解！\r\n'),(115,3,19,'新手怎么领取金币？ ','尊敬的玩家，每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；<br />\r\n&nbsp;&nbsp;&nbsp; 请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。'),(116,3,19,'新手怎么领取金币？ ','每位新人在创建角色的前3天内可以到“城市”——“福利社”中的“投资人的援助”可以每天领取一次赠送金币，共3次。第一次20赠送金币，第二次30赠送金币，第三次50赠送金币；\r\n    请注意，如果玩家在某天没有领取赠送金币，则当天可领取的赠送金币数量推后一天，可领取的次数也相应减少一次，即第4天玩家无法领取赠送金币。想获得更多的金币您可以通过充值获得，参与游戏内的活动也能有机会获得，更多的金币和赠送金币的获得方式您可以在游戏里面查看或者与其他的玩家交流得到。\r\n'),(117,3,19,'赠送金币可以干什么？ ','赠送金币可以实现金币的部分功能，赠送金币可以在商城购买VIP卡、劲霸浓鱼汤、猛龙保安队、和解协议、空心爆米花、搬家公司、企业更名申请表、解冻剂、不松口果汁、保密协议、探索装备（探索装备需有限公司等级或以上才可以使用赠送金币购买）等部分道具，您可以点击该物品并选择用赠送金币支付。\r\n　　您也可以使用赠送金币，为能够进行加速的升级过程进行加速升级哦。赠送金币还能作为批量采购、批量停业和批量开业的手续费使用。\r\n'),(118,3,19,'拆除工厂后，售销经验还在吗？仓库等级会不会少？ ','工厂拆除后工厂的一切数据将被清空（包括技术经验和销售经验），除原料外其他物品信息保留。所以您的设备等级和仓库等级都会没有了，需要重新开始。  \r\n'),(119,3,19,'工厂仓库中的材料怎么用？ ','尊敬的玩家，工厂仓库中的材料是配合相应的图纸用于在工厂生产道具，工厂生产道具必须拥有图纸、材料都符合条件，首先您选择图纸，点击使用了图纸之后，图纸便会进入到工厂中图纸车间，这样您以后就可以在工厂中生产道具了。具体操作流程为：我的工厂-生产车间-生产道具。根据图纸的不同，会消耗相应所需的材料而生产出各种各样的道具来哦。\r\n'),(120,3,19,'如何获得销售经验？ ','销售经验必须通过向系统或者玩家销售工厂当前可生产的最高等级货物才能获得，并且必须是自行生产的货物。另外通过交易市场收购的货物再进行销售是无法获得销售经验的，并且将自己工厂所生产的货物运输到店铺里面使用也是不会获得销售经验的，请您留意！\r\n'),(121,3,20,'原油原料 ','原油可以通过建立油井来开采原油，油井可通过勘测油田和竞标油田的方式来获取油田进行搭建,另外,您也可以尝试通过交易市场收购原油原料,但由于原油原料比较稀缺,有时候交易市场也不一定有出售喔.   '),(122,3,20,'创建工厂需要什么条件 ','创建工厂需要满足以下条件：<br />\r\n1：公司等级到达小型公司（事业--公司信息--可以查看到公司等级）。<br />\r\n2：提升一名员工为工厂主任（该员工拥有产品天赋（初级），事业--公司架构--提升为工厂主任）。<br />\r\n3：创建费用：10000G币。<br />\r\n&nbsp;&nbsp;&nbsp; 当您满足了以上条件后，即可点击“创建工厂”来进行工厂的建设。'),(123,3,20,'工厂如何采购原材料 ',' 工厂每天会自动获得系统赠送的原料，另外也可以点击“城市”-“交易市场”-“我要收购”来收购其他玩家出售的原料，或者在商场用金币购买“物资箱”来使用获得原料，现在游戏当中还可以通过使用原料卡来获取原料，原料卡主要是通过参加法老的宝藏来获得。 '),(124,3,20,' 如何提高原料的获取速度 ','您可以通过提升人物属性—智慧属性、工厂对应行业销售经验来提高原料的获取速度，更多提升原料获得速度的途径，您可以通过将鼠标移动到工厂信息内的“原料产量”即可显示相关提示。'),(125,3,20,'工厂的销售经验有什么用 ','销售经验是跟您的工厂原料获取速度有关的哦，提升某一行业的销售经验，可以提升对应该行业原料的获取速度，增加销售经验是需要销售您当前工厂所能生产的最高级别的货物才能增加的。\r\n'),(126,3,20,'天然气有什么用？ ','将天然气运输到店铺里面使用可以增加店铺的收入，每8个小时使用一罐天然气，不同等级的天然气，其效果也是不一样的，另外天然气还可以挂单到交易市场进行出售。\r\n'),(127,3,20,'特产货物 ','您可以把仓库内符合行业的特产货物放在您的店铺进行销售哦。选择您需要进行销售的店铺，在系统货物/工厂货物右边点击“切换为该货物”，点击“从仓库运输”再按系统提示继续操作即可。     \r\n    您也可以把特产货物放到交易系统卖给其他玩家。点击界面右下方的“交易”，在右边一栏选择“我要出售”，再按系统指示完成操作即可。\r\n'),(128,3,20,'如何创建工厂 ','当您满足了创建工厂的所有条件之后，请您点击事业-创建工厂，接着选择工业区及街道，然后点击一块空地就可以选择工厂类型来创建您的工厂了！创建工厂的时候系统会提示您开设工厂的具体要求的。\r\n'),(129,3,20,'如何获得原料卡 ','尊敬的玩家，您可以通过探索法老的宝藏、勇攀高峰、援助贝尔、充值礼包、日充值奖励等途径获得原料卡；部分的活动奖励中也包含原料卡。更多的获得途径欢迎您在游戏中与其他玩家沟通获得，或者留意论坛公告。祝您游戏愉快！'),(130,3,20,' G币可以兑换为金币吗？ ','游戏内暂时是不提供G币兑换金币的功能，金币主要是通过充值来获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得哦！请您留意下相关情况，谢谢！\r\n'),(131,3,20,'如何获得金币？ ','金币主要是通过充值获得，另外与黑衣人（金币代表）谈判、旅游也有一定几率获得。如果雕像主人有设置敬仰奖励的话，您在敬仰时也是可以获得相应的金币，留下祝福后雕像主人还可以根据他自己的意愿赏金币给您。部分的活动（例如起点树活动）也是可以获得金币的，请您密切留意论坛公告。'),(132,3,20,'赠送金币怎么获得 ','新手进入游戏前三天可以到城市--福利社领取赠送金币，您的公司升级时，系统也会赠送公司升级大礼包，部分升级礼包里面也会有赠送金币，VIP每天登陆奖励有机会获得，兑奖资格券达到规定的数目可以兑换赠送金币。参加16小时活动：晨跑和吃早餐有几率获得的铜币和银币，当达到一定数量可以兑换赠送金币哦，部分活动也有一定几率获得赠送金币哦。'),(136,0,1,'标题','<p><img src=\"Upload/Faq/20100911/163006_67469.jpg\" alt=\"\" align=\"left\" border=\"0\" /><span style=\"font-weight:bold;\">he</span><span style=\"font-weight:bold;\">llo</span></p>\r\n<p><img src=\"Public/Admin/js/Libs/kindeditor/plugins/emoticons/9.gif\" alt=\"\" border=\"0\" /></p>\r\n<p><table style=\"width:100%;\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr><td> hello   </td>\r\n<td>hello <br />\r\n</td>\r\n</tr>\r\n<tr><td> </td>\r\n<td> </td>\r\n</tr>\r\n<tr><td> </td>\r\n<td> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</p>\r\n<hr />'),(137,0,2,'客服公告','客服公告'),(138,1,8,'<img src=\\\"http://service.uwan.com/Upload/PlayerFaq/20100914/183928_46724.jpg\\\" alt=\\\"\\\" border=\\\"0\\\" /><br />','');

/*Table structure for table `cndw_player_faq_log` */

DROP TABLE IF EXISTS `cndw_player_faq_log`;

CREATE TABLE `cndw_player_faq_log` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `player_faq_id` int(11) unsigned NOT NULL COMMENT 'faq_id',
  `faq_whether` tinyint(1) unsigned NOT NULL COMMENT '是否有帮助1:是,0:否',
  `faq_opinion` tinyint(1) unsigned NOT NULL COMMENT '原因',
  `content` varchar(100) default NULL COMMENT '其它详细',
  `date_create` int(10) unsigned NOT NULL COMMENT '创建日期',
  PRIMARY KEY  (`Id`),
  KEY `player_faq_id` (`player_faq_id`,`faq_whether`,`faq_opinion`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT='FAQ是否有用日志表';

/*Data for the table `cndw_player_faq_log` */

insert  into `cndw_player_faq_log`(`Id`,`player_faq_id`,`faq_whether`,`faq_opinion`,`content`,`date_create`) values (1,28,0,0,NULL,1283240359),(2,28,0,5,'不爽阿！',1283240392),(3,33,0,5,'sdsdfsfsdf',1283240622),(4,10,0,5,'dfxfdfsfdf',1283240710),(5,2,1,0,NULL,1283240715),(6,93,1,0,NULL,1283240833),(7,58,1,5,NULL,1283241575),(8,58,1,5,NULL,1283241575),(9,58,1,5,NULL,1283241575),(10,58,1,5,NULL,1283241575),(11,58,1,5,NULL,1283241575),(12,58,1,5,NULL,1283241575),(13,58,1,0,NULL,1283241578),(14,59,1,0,NULL,1283241581),(15,56,0,2,NULL,1283241585),(16,33,0,2,NULL,1283242718),(17,52,0,5,'rfrf',1283252070),(18,69,1,0,NULL,1283305361),(19,69,1,0,NULL,1283305363),(20,69,1,0,NULL,1283305459),(21,69,0,1,NULL,1283305710),(22,69,1,1,NULL,1283305720),(23,49,1,0,NULL,1283306774),(24,47,1,0,NULL,1283306877),(25,47,1,0,NULL,1283306881),(26,47,1,0,NULL,1283306881),(27,1,1,0,NULL,1283306924),(28,3,1,0,NULL,1283307180),(29,1,1,0,NULL,1283308712),(30,79,0,2,NULL,1283308717),(31,6,0,3,NULL,1283308762),(32,91,0,1,NULL,1283325949),(33,92,1,0,NULL,1283326051),(34,93,1,0,NULL,1283326065),(35,93,1,0,NULL,1283326065),(36,92,1,0,NULL,1283326067),(37,92,1,0,NULL,1283326073),(38,1,1,0,NULL,1283338470),(39,6,0,2,NULL,1283512258),(40,23,1,0,NULL,1283754182),(41,4,1,0,NULL,1283754341),(42,3,0,2,NULL,1283754420),(43,2,0,2,NULL,1283845807),(44,93,1,0,NULL,1283940627),(45,46,0,3,NULL,1284108547),(46,100,0,2,NULL,1284345214),(47,91,1,0,NULL,1284345221),(48,92,0,3,NULL,1284345225),(49,2,1,0,NULL,1284439058),(50,2,0,1,NULL,1284439077),(51,2,1,0,NULL,1284439090),(52,2,0,1,NULL,1284439212),(53,2,0,1,NULL,1284444671),(54,2,0,0,NULL,1284467545),(55,2,1,0,NULL,1284467555),(56,2,1,0,NULL,1284467801),(57,2,0,0,NULL,1284467806),(58,2,0,0,NULL,1284467875),(59,2,1,0,NULL,1284467884),(60,2,1,0,NULL,1284467970),(61,3,0,1,NULL,1284467991),(62,3,1,2,NULL,1284468054),(63,24,0,2,NULL,1284468068),(64,2,0,2,NULL,1284608127);

/*Table structure for table `cndw_player_kind_faq` */

DROP TABLE IF EXISTS `cndw_player_kind_faq`;

CREATE TABLE `cndw_player_kind_faq` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(1) unsigned NOT NULL COMMENT '游戏类型id',
  `name` varchar(50) default NULL COMMENT '分类',
  PRIMARY KEY  (`Id`),
  KEY `game_type_id` (`game_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `cndw_player_kind_faq` */

insert  into `cndw_player_kind_faq`(`Id`,`game_type_id`,`name`) values (7,1,'人物类要'),(8,1,'座驾类'),(3,1,'充值类我'),(4,1,'金币类朋'),(5,1,'工厂类'),(6,1,'商城类'),(1,0,'网络骗术'),(2,0,'公告'),(13,2,'艺人系统'),(14,2,'工作人员系统'),(15,2,'星探系统'),(16,2,'公司系统'),(17,2,'通告系统'),(18,3,'人物类'),(19,3,'充值类'),(20,3,'店铺类');

/*Table structure for table `cndw_quality` */

DROP TABLE IF EXISTS `cndw_quality`;

CREATE TABLE `cndw_quality` (
  `Id` int(11) NOT NULL auto_increment,
  `work_order_id` int(11) NOT NULL COMMENT '工单id',
  `quality_user_id` int(11) default NULL COMMENT '质检人id',
  `qa_id` int(11) NOT NULL COMMENT '回复id',
  `option_id` tinyint(1) default NULL COMMENT '质检选项',
  `status` tinyint(1) default '1' COMMENT '状态 1:未申诉.2:已申诉.3:申诉成功.4:申诉失败.5:同意质检',
  `scores` tinyint(3) NOT NULL COMMENT '所扣分数',
  `complain_content` text COMMENT '申诉',
  `quality_content` text COMMENT '质检内容',
  `reply_content` text COMMENT '回复内容',
  `reply_time` int(10) default NULL COMMENT '回复时间',
  `quality_time` int(10) unsigned NOT NULL COMMENT '质检时间',
  `complain_time` int(10) unsigned default NULL COMMENT '申诉时间',
  PRIMARY KEY  (`Id`),
  KEY `quality_user_id` (`quality_user_id`),
  KEY `qa_id` (`qa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='工单回复质检表';

/*Data for the table `cndw_quality` */

insert  into `cndw_quality`(`Id`,`work_order_id`,`quality_user_id`,`qa_id`,`option_id`,`status`,`scores`,`complain_content`,`quality_content`,`reply_content`,`reply_time`,`quality_time`,`complain_time`) values (1,3,25,4,4,1,-2,NULL,'哦mygod',NULL,NULL,1284348828,NULL),(2,1,25,5,1,1,0,NULL,'asdfasdfasdf',NULL,NULL,1284349235,NULL),(3,2,25,6,1,1,0,NULL,'sadfasdfsadfasfd',NULL,NULL,1284349375,NULL),(4,4,4,8,1,1,0,NULL,'dfasadfsasdfdsafsadf',NULL,NULL,1284349740,NULL),(5,9,4,24,1,1,0,NULL,'sdfsdfsdf',NULL,NULL,1284363829,NULL),(6,8,4,19,1,1,0,NULL,'15616',NULL,NULL,1284365436,NULL),(7,4,4,12,1,1,0,NULL,'',NULL,NULL,1284365614,NULL),(8,4,4,10,4,1,-1,NULL,'',NULL,NULL,1284365629,NULL),(9,6,4,33,4,1,-2,NULL,'扣分，问你死未！',NULL,NULL,1284368219,NULL),(10,4,4,9,4,1,-2,NULL,'忽忽反对恢复',NULL,NULL,1284368277,NULL),(11,48,4,87,1,1,0,NULL,'二有有有',NULL,NULL,1284618683,NULL);

/*Table structure for table `cndw_question_types` */

DROP TABLE IF EXISTS `cndw_question_types`;

CREATE TABLE `cndw_question_types` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(3) unsigned default NULL COMMENT '游戏类型id',
  `title` varchar(100) default NULL COMMENT '标题',
  `form_table` text COMMENT '表单',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='问题类型表';

/*Data for the table `cndw_question_types` */

insert  into `cndw_question_types`(`Id`,`game_type_id`,`title`,`form_table`) values (1,1,'账号被盗','a:3:{i:0;a:6:{s:4:\"type\";s:6:\"select\";s:8:\"required\";b:1;s:4:\"name\";s:5:\"mibao\";s:5:\"title\";s:9:\"密保卡\";s:11:\"description\";s:24:\"是否使用了密保卡\";s:7:\"options\";a:2:{i:0;s:3:\"否\";i:1;s:3:\"是\";}}i:1;a:5:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:12:\"user_account\";s:5:\"title\";s:12:\"游戏账号\";s:11:\"description\";s:27:\"请输入您的游戏账号\";}i:2;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}}'),(2,1,'角色/账号异常','a:0:{}'),(7,3,'盗号问题','a:8:{i:0;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:16:\"lastoneloginsite\";s:5:\"title\";s:18:\"最近登录地点\";}i:1;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:9:\"lossgoods\";s:5:\"title\";s:12:\"被盗物品\";}i:2;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:9:\"otherloss\";s:5:\"title\";s:12:\"其他损失\";}i:3;a:5:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:11:\"stolenplace\";s:5:\"title\";s:12:\"被盗地点\";s:11:\"description\";s:0:\"\";}i:4;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:10:\"stolentime\";s:5:\"title\";s:12:\"被盗时间\";}i:5;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:8:\"nickname\";s:5:\"title\";s:6:\"昵称\";}i:6;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:5:\"email\";s:5:\"title\";s:12:\"注册邮箱\";}i:7;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}}'),(3,1,'防沉迷系统','N;'),(4,1,'封号查询','N;'),(5,1,'商城/充值问题','N;'),(6,2,'测试',NULL),(8,3,'充值问题','a:8:{i:0;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}i:1;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:8:\"nickname\";s:5:\"title\";s:6:\"昵称\";}i:2;a:5:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:14:\"rechargeamount\";s:5:\"title\";s:12:\"充值金额\";s:11:\"description\";s:0:\"\";}i:3;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:12:\"rechargetime\";s:5:\"title\";s:12:\"充值时间\";}i:4;a:5:{s:4:\"type\";s:6:\"select\";s:8:\"required\";b:1;s:4:\"name\";s:14:\"rechargemethod\";s:5:\"title\";s:12:\"充值方式\";s:7:\"options\";a:13:{i:0;s:9:\"银行卡\";i:1;s:9:\"支付宝\";i:2;s:6:\"快钱\";i:3;s:9:\"财付通\";i:4;s:6:\"易宝\";i:5;s:9:\"神州行\";i:6;s:12:\"手机短信\";i:7;s:9:\"骏网卡\";i:8;s:9:\"盛大卡\";i:9;s:9:\"征途卡\";i:10;s:6:\"电信\";i:11;s:6:\"联通\";i:12;s:12:\"固话充值\";}}i:5;a:5:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:12:\"rechargeband\";s:5:\"title\";s:12:\"充值银行\";s:11:\"description\";s:38:\"如果使用银行卡充值,请填写.\";}i:6;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:15:\"rechargeaccount\";s:5:\"title\";s:19:\"充值卡号/账号\";}i:7;a:5:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:1;s:4:\"name\";s:19:\"rechargeordernumber\";s:5:\"title\";s:15:\"充值订单号\";s:11:\"description\";s:0:\"\";}}'),(9,3,'游戏BUG','a:2:{i:0;a:4:{s:4:\"type\";s:4:\"text\";s:8:\"required\";b:0;s:4:\"name\";s:9:\"foundtime\";s:5:\"title\";s:12:\"发现时间\";}i:1;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}}'),(10,3,'游戏建议',NULL),(11,3,'活动问题','a:1:{i:0;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}}'),(12,3,'道具丢失','a:1:{i:0;a:3:{s:4:\"type\";s:16:\"game_server_list\";s:8:\"required\";b:0;s:4:\"name\";s:14:\"game_server_id\";}}');

/*Table structure for table `cndw_roles` */

DROP TABLE IF EXISTS `cndw_roles`;

CREATE TABLE `cndw_roles` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `role_value` varchar(32) NOT NULL default '' COMMENT '角色值,唯一',
  `role_name` varchar(32) NOT NULL default '' COMMENT '角色名称',
  `description` text,
  `date_created` int(10) unsigned NOT NULL default '0' COMMENT '创建日期',
  `date_updated` int(10) unsigned NOT NULL default '0' COMMENT '更新日期',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色表';

/*Data for the table `cndw_roles` */

insert  into `cndw_roles`(`Id`,`role_value`,`role_name`,`description`,`date_created`,`date_updated`) values (1,'bto','商业大亨','优玩网-技术部-商业大亨团队',1282186121,1282186121),(2,'kefu','客服部','test',1282188618,1282188618),(3,'juese','角色名','角色名角色名',1283855759,1283855759),(4,'guest','来宾用户','来宾用户,代示没任何权限的用户，用于从PASSPORT初次登录过来的用户',1284607046,1284607046);

/*Table structure for table `cndw_rooms` */

DROP TABLE IF EXISTS `cndw_rooms`;

CREATE TABLE `cndw_rooms` (
  `Id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '房间名',
  `details` text COMMENT '房间详细信息,序列化对象数据',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `cndw_rooms` */

insert  into `cndw_rooms`(`Id`,`name`,`details`) values (6,'早班组',NULL),(7,'早班组',NULL);

/*Table structure for table `cndw_service_faq` */

DROP TABLE IF EXISTS `cndw_service_faq`;

CREATE TABLE `cndw_service_faq` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type_id` tinyint(1) NOT NULL COMMENT '游戏类型id',
  `kind_id` int(11) default '0' COMMENT '问题类型ID',
  `question` text COMMENT '问题',
  `answer` text COMMENT '答案',
  PRIMARY KEY  (`Id`),
  KEY `game_type` (`game_type_id`),
  KEY `kind_id` (`kind_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='玩家FAQ';

/*Data for the table `cndw_service_faq` */

insert  into `cndw_service_faq`(`Id`,`game_type_id`,`kind_id`,`question`,`answer`) values (6,1,7,'冰棒!<br />','没有冰棒!~~~<br />');

/*Table structure for table `cndw_service_kind_faq` */

DROP TABLE IF EXISTS `cndw_service_kind_faq`;

CREATE TABLE `cndw_service_kind_faq` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `parent_id` int(11) default '0' COMMENT '父类id',
  `game_type_id` tinyint(3) unsigned NOT NULL COMMENT '游戏类型',
  `name` varchar(50) default NULL COMMENT '分类',
  PRIMARY KEY  (`Id`),
  KEY `game_type_id` (`game_type_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `cndw_service_kind_faq` */

insert  into `cndw_service_kind_faq`(`Id`,`parent_id`,`game_type_id`,`name`) values (6,0,1,'人物类'),(7,0,1,'商城类'),(12,0,0,'公告'),(11,0,0,'网络骗术');

/*Table structure for table `cndw_sysconfig` */

DROP TABLE IF EXISTS `cndw_sysconfig`;

CREATE TABLE `cndw_sysconfig` (
  `Id` smallint(4) unsigned NOT NULL auto_increment,
  `config_name` varchar(20) default NULL,
  `title` varchar(50) default NULL,
  `config_value` text,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='系统设置配置表';

/*Data for the table `cndw_sysconfig` */

insert  into `cndw_sysconfig`(`Id`,`config_name`,`title`,`config_value`) values (1,'game_type','游戏类型','a:2:{i:1;a:3:{s:2:\"Id\";i:1;s:3:\"img\";s:44:\"/Public/front/default/images/gamelogo_01.gif\";s:4:\"name\";s:12:\"商业大亨\";}i:2;a:3:{s:2:\"Id\";i:3;s:3:\"img\";s:44:\"/Public/front/default/images/gamelogo_03.gif\";s:4:\"name\";s:9:\"富人国\";}}'),(2,'workorder_source','工单类型来源','a:7:{i:1;s:12:\"官网提问\";i:2;s:15:\"游戏内提问\";i:3;s:18:\"客服电话提问\";i:4;s:18:\"客服论坛提问\";i:5;s:14:\"客服QQ提问\";i:6;s:18:\"客服邮件提问\";i:7;s:6:\"其他\";}'),(3,'workorder_status','工单状态类型','a:3:{i:1;s:9:\"待处理\";i:2;s:9:\"处理中\";i:3;s:9:\"已处理\";}'),(4,'verify_level','查证处理等级','a:3:{i:1;s:6:\"一般\";i:2;s:6:\"优先\";i:3;s:6:\"紧急\";}'),(5,'player_evaluation','玩家评价','a:3:{i:1;a:1:{s:5:\"title\";s:6:\"好评\";}i:2;a:2:{s:5:\"title\";s:6:\"中评\";s:9:\"isDefault\";b:1;}i:3;a:2:{s:5:\"title\";s:6:\"差评\";s:11:\"Description\";a:4:{i:1;s:21:\"回复速度太慢！\";i:2;s:12:\"答非所问\";i:3;s:18:\"回复态度恶劣\";i:4;s:6:\"其他\";}}}'),(9,'quality_options','质检选项','a:8:{i:1;s:3:\"对\";i:2;s:6:\"推荐\";i:3;s:6:\"错字\";i:4;s:15:\"内容不完整\";i:5;s:15:\"内容不清晰\";i:6;s:12:\"内容错误\";i:7;s:12:\"建议优化\";i:8;s:6:\"其它\";}'),(6,'workorder_vip_level','工单vip等级条件','a:7:{i:0;i:0;i:1;i:1000;i:2;i:2000;i:3;i:3000;i:4;i:4000;i:5;i:5000;i:6;i:6000;}'),(8,'faq_opinion','faq意见选项','a:6:{i:0;s:15:\"请选择原因\";i:1;s:18:\"描述不够清晰\";i:2;s:21:\"内容太多不想看\";i:3;s:27:\"不是我想了解的问题\";i:4;s:15:\"内容不正确\";i:5;s:6:\"其他\";}'),(10,'quality_status','质检状态','a:5:{i:1;s:9:\"未申诉\";i:2;s:9:\"已申诉\";i:3;s:12:\"同意申诉\";i:4;s:12:\"拒绝申诉\";i:5;s:12:\"同意质检\";}'),(11,'verify_type','查证处理类型','a:3:{i:1;a:12:{i:1;s:12:\"账号问题\";i:2;s:12:\"充值问题\";i:3;s:12:\"商会问题\";i:4;s:12:\"地标问题\";i:5;s:12:\"股票问题\";i:6;s:12:\"媒体问题\";i:7;s:12:\"旅游问题\";i:8;s:15:\"游乐场问题\";i:9;s:15:\"议政厅问题\";i:10;s:12:\"交易问题\";i:11;s:12:\"员工问题\";i:12;s:9:\"其它BUG\";}i:2;a:3:{i:1;s:12:\"账号问题\";i:2;s:12:\"充值问题\";i:3;s:12:\"其它问题\";}i:3;a:12:{i:1;s:12:\"账号问题\";i:2;s:12:\"充值问题\";i:3;s:12:\"商会问题\";i:4;s:12:\"地标问题\";i:5;s:12:\"股票问题\";i:6;s:12:\"媒体问题\";i:7;s:12:\"旅游问题\";i:8;s:15:\"游乐场问题\";i:9;s:15:\"议政厅问题\";i:10;s:12:\"交易问题\";i:11;s:12:\"员工问题\";i:12;s:9:\"其它BUG\";}}'),(12,'verify_status','查证处理状态','a:8:{i:1;s:9:\"未审核\";i:2;s:9:\"待处理\";i:3;s:9:\"处理中\";i:4;s:19:\"已修复,待验收\";i:5;s:12:\"重新检查\";i:6;s:12:\"其它状况\";i:7;s:9:\"已完成\";i:8;s:15:\"已回馈玩家\";}');

/*Table structure for table `cndw_user` */

DROP TABLE IF EXISTS `cndw_user`;

CREATE TABLE `cndw_user` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `org_id` tinyint(3) default '0' COMMENT '组id',
  `department_id` int(11) unsigned NOT NULL COMMENT '部门id',
  `service_id` varchar(20) default NULL COMMENT '客服id',
  `roles` varchar(100) default NULL COMMENT '角色id数组方式例(sysadmin,main)',
  `user_name` varchar(20) NOT NULL default '' COMMENT '用户名',
  `password` varchar(50) NOT NULL default '' COMMENT '密码',
  `nick_name` varchar(20) NOT NULL default '' COMMENT '用户姓名',
  `date_created` int(10) unsigned NOT NULL default '0' COMMENT '创建日期',
  `date_updated` int(10) unsigned NOT NULL default '0' COMMENT '更新日期',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `cndw_user` */

insert  into `cndw_user`(`Id`,`org_id`,`department_id`,`service_id`,`roles`,`user_name`,`password`,`nick_name`,`date_created`,`date_updated`) values (5,1,1,NULL,'kefu','zl8522115','202cb962ac59075b964b07152d234b70','绯羽之恋1',1282876530,1283851388),(4,1,2,NULL,'bto','zlsky','e10adc3949ba59abbe56e057f20f883e','朱磊',1282876435,1282876435),(22,2,1,NULL,'juese','a','0cc175b9c0f1b6a831c399e269772661','a',1283868044,1283868044),(23,2,1,NULL,'juese','b','92eb5ffee6ae2fec3ad71c777531578f','b',1283868054,1283868054),(24,1,1,NULL,'juese','c','4a8a08f09d37b73795649038408b5f33','c',1283868064,1283868064),(25,3,1,'052','juese','d','8277e0910d750195b448797616e091ad','d',1283868070,1284623540),(47,0,1,NULL,'guest','chseason','2d7e42ccdae09073fdec7dc23e79dc78','chseason',1284618306,1284622428),(40,0,1,NULL,'guest','fox','f5c635942da1107215fdd24a944ddf6b','fox',1284618172,1284622431),(48,0,1,NULL,'juese','chengxi_c','f6e17bca0c72b006068dfe44fdc4656a','chengxi_c',1284618854,1284619445);

/*Table structure for table `cndw_user_priority_operator` */

DROP TABLE IF EXISTS `cndw_user_priority_operator`;

CREATE TABLE `cndw_user_priority_operator` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `game_type_id` tinyint(3) NOT NULL COMMENT '游戏类型id',
  `operator_id` smallint(4) NOT NULL COMMENT '运营商id',
  `priority_level` tinyint(2) NOT NULL default '1' COMMENT '优先级,默认1级',
  KEY `user_id` (`user_id`),
  KEY `operator_id` (`operator_id`),
  KEY `priority_level` (`priority_level`),
  KEY `game_type_id` (`game_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户与运营商的优先级';

/*Data for the table `cndw_user_priority_operator` */

insert  into `cndw_user_priority_operator`(`user_id`,`game_type_id`,`operator_id`,`priority_level`) values (5,1,1,3),(5,1,2,2),(4,1,1,2),(4,1,2,1),(22,1,3,2),(22,1,2,1),(22,1,1,3),(5,1,3,1),(23,1,3,3),(23,1,2,2),(23,1,1,1),(24,1,3,2),(24,1,2,3),(24,1,1,1),(25,1,3,3),(25,1,2,2),(25,1,1,2),(25,1,4,1),(48,3,4,1),(48,3,3,1);

/*Table structure for table `cndw_verify` */

DROP TABLE IF EXISTS `cndw_verify`;

CREATE TABLE `cndw_verify` (
  `Id` int(11) NOT NULL auto_increment,
  `work_order_id` int(11) default NULL COMMENT '工单关联id',
  `game_type_id` tinyint(3) NOT NULL COMMENT '游戏类型',
  `operator_id` smallint(4) NOT NULL COMMENT '运营商id',
  `game_server_id` int(11) NOT NULL COMMENT '所属服务器id',
  `department_id` tinyint(3) NOT NULL COMMENT '部门id',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `type` tinyint(2) NOT NULL COMMENT '问题类型',
  `level` tinyint(1) NOT NULL COMMENT '处理等级.',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `log` text COMMENT '日志,序列化.type:1日志.2留言',
  PRIMARY KEY  (`Id`),
  KEY `work_order_id` (`work_order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `cndw_verify` */

insert  into `cndw_verify`(`Id`,`work_order_id`,`game_type_id`,`operator_id`,`game_server_id`,`department_id`,`status`,`type`,`level`,`title`,`content`,`create_time`,`log`) values (1,1,1,1,10,1,7,1,1,'2','基本原则 要人',1284467303,'a:4:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:46:18\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：未审核\";}i:1;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:48:19\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：待处理\";}i:2;a:4:{s:4:\"time\";s:19:\"2010-09-15 16:59:52\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：已完成\";}i:3;a:4:{s:4:\"time\";s:19:\"2010-09-15 17:00:04\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:39:\"已经处理好了，你回复玩家吧\";}}'),(3,3,1,1,10,1,1,1,1,'asdfsadfsadf','asdfasdfasdf',1284520509,'a:1:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:15:09\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：未审核\";}}'),(4,3,1,1,10,1,1,3,1,'asfdadsf','dsfadsfaadfsadsfasfd',1284520515,'a:1:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:15:15\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：未审核\";}}'),(5,3,1,1,10,1,5,1,1,'','fassadfdfsadfsadfsadsfaasdffsaddsfafads',1284520521,'a:5:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:15:21\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：未审核\";}i:1;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:15:39\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:15:\"agadadfsdfssadf\";}i:2;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:16:16\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：处理中\";}i:3;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:56:31\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:27:\"修改状态：重新检查\";}i:4;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:56:35\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:5:\"hello\";}}'),(2,2,1,2,1,1,8,1,1,'asdfasdfasdf','asdfasdfasdfasdf',1284468691,'a:16:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:04\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：待处理\";}i:1;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:07\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:27:\"修改状态：重新检查\";}i:2;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:09\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:30:\"修改状态：已回馈玩家\";}i:3;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:11\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：未审核\";}i:4;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:13\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:10:\"dsfgafgafg\";}i:5;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:15\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:12:\"asdfasdfasdf\";}i:6;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:16\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:16:\"asdfasdfasdfasdf\";}i:7;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:18\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:12:\"asdfasdfasdf\";}i:8;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:19\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:8:\"asdfasdf\";}i:9;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:20\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:7:\"adfasdf\";}i:10;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:21\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:11:\"asdfasdfadf\";}i:11;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:25\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：待处理\";}i:12;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:28\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:7:\"adfasdf\";}i:13;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:30\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:7:\"adfasdf\";}i:14;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:34\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:30:\"修改状态：已回馈玩家\";}i:15;a:4:{s:4:\"time\";s:19:\"2010-09-15 10:45:55\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:16:\"fadssadfadfsadfs\";}}'),(6,4,1,1,11,2,2,2,2,'r3erere','3234re',1284523068,'a:1:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 11:57:48\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：待处理\";}}'),(7,NULL,1,2,2,2,3,2,1,'人我人我','要工工工工',1284532025,'a:1:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 14:27:05\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：处理中\";}}'),(8,NULL,1,1,10,1,5,1,1,'人我我我','夺要人',1284532117,'a:2:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 14:28:37\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：未审核\";}i:1;a:4:{s:4:\"time\";s:19:\"2010-09-15 14:28:53\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:27:\"修改状态：重新检查\";}}'),(9,16,1,1,11,1,7,1,1,'返还玫瑰bibi车','uid：1111 账号：11111 昵称：111111\r\n返还一辆玫瑰bibi车给这个玩家吧',1284538076,'a:4:{i:0;a:4:{s:4:\"time\";s:19:\"2010-09-15 16:07:56\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:31:\"添加此BUG.状态：未审核\";}i:1;a:4:{s:4:\"time\";s:19:\"2010-09-15 16:08:10\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：待处理\";}i:2;a:4:{s:4:\"time\";s:19:\"2010-09-15 17:01:08\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"1\";s:11:\"description\";s:24:\"修改状态：已完成\";}i:3;a:4:{s:4:\"time\";s:19:\"2010-09-15 17:01:36\";s:4:\"user\";s:13:\"朱磊[zlsky]\";s:4:\"type\";s:1:\"2\";s:11:\"description\";s:24:\"你可以回复玩家啦\";}}');

/*Table structure for table `cndw_work_order` */

DROP TABLE IF EXISTS `cndw_work_order`;

CREATE TABLE `cndw_work_order` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `game_type` tinyint(3) unsigned NOT NULL COMMENT '所属游戏ID',
  `question_type` smallint(4) unsigned NOT NULL COMMENT '问题类型ID',
  `title` varchar(50) default NULL COMMENT '标题',
  `source` tinyint(3) NOT NULL COMMENT '表单来源',
  `question_num` tinyint(3) unsigned default '0' COMMENT '提问数',
  `answer_num` tinyint(3) unsigned default '0' COMMENT '回复数',
  `status` tinyint(1) unsigned NOT NULL default '1' COMMENT '工单处理状态(默认未处理)',
  `money` int(11) unsigned NOT NULL default '0' COMMENT '用户充值数(默认0)',
  `game_server_id` smallint(4) unsigned default NULL COMMENT '工单所属于哪个服务器',
  `operator_id` tinyint(11) unsigned default NULL COMMENT '运营商id',
  `owner_user_id` int(11) unsigned default NULL COMMENT '工单属于哪个用户',
  `create_time` int(10) NOT NULL COMMENT '表单建立时间',
  `timeout` int(10) NOT NULL COMMENT '工单超时时间',
  `vip_level` tinyint(1) NOT NULL COMMENT 'vip等级',
  `quality_id` int(11) default '0' COMMENT '质检状态.0:未质检.其他正数字为user_id,负数为工单分配给了哪个用户,但未质检',
  `evaluation_status` tinyint(1) default '0' COMMENT '是否评价.0:未评价.其它已经评价',
  `user_account` varchar(50) default NULL COMMENT '玩家用户账号',
  `user_nickname` varchar(50) default NULL COMMENT '玩家呢称',
  PRIMARY KEY  (`Id`),
  KEY `game_type` (`game_type`),
  KEY `operator_id` (`operator_id`),
  KEY `owner_user_id` (`owner_user_id`),
  KEY `quality_id` (`quality_id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='工单表';

/*Data for the table `cndw_work_order` */

insert  into `cndw_work_order`(`Id`,`game_type`,`question_type`,`title`,`source`,`question_num`,`answer_num`,`status`,`money`,`game_server_id`,`operator_id`,`owner_user_id`,`create_time`,`timeout`,`vip_level`,`quality_id`,`evaluation_status`,`user_account`,`user_nickname`) values (1,1,1,'test',5,1,2,2,0,1,2,23,1284348365,0,0,-4,0,NULL,NULL),(2,1,1,'测试',1,1,1,1,0,2,9,NULL,1284348558,0,0,-4,0,'ccx1999@163.com',NULL),(3,1,1,'888',5,1,1,3,0,1,2,24,1284348774,0,0,-4,0,NULL,NULL),(4,1,1,'sdwqwqe',5,1,7,1,0,1,2,22,1284349707,0,0,4,0,NULL,NULL),(5,1,1,'5643654',5,1,0,1,0,1,2,23,1284355876,0,0,-4,0,NULL,NULL),(6,1,1,'5643654',5,1,1,1,0,1,2,24,1284355876,0,0,4,0,NULL,NULL),(7,1,1,'7777',5,1,0,1,0,1,2,22,1284356191,0,0,-4,0,NULL,NULL),(8,1,8,'恩恩发',1,3,4,1,0,1,9,NULL,1284356413,0,0,4,0,'464522789@qq.com',NULL),(9,1,1,'53443',5,1,1,1,0,1,2,23,1284357720,0,0,-4,0,NULL,NULL),(10,1,1,'asdfsadf',5,1,5,1,0,1,2,24,1284359095,0,0,-4,0,NULL,NULL),(11,1,1,'test',1,2,0,1,0,1,9,NULL,1284380392,0,0,-4,0,'ccx1999@163.com',NULL),(12,1,6,'TEST',1,1,0,1,0,NULL,9,NULL,1284522453,0,0,-4,0,'35659912@qq.com',NULL),(13,3,6,'TEST',1,1,0,1,0,NULL,9,NULL,1284522500,0,0,-4,0,'35659912@qq.com',NULL),(14,1,1,'test now',1,1,1,3,0,1,9,NULL,1284522695,0,0,-4,0,'464522789@qq.com',NULL),(15,1,1,'我有个账号被盗取了',1,1,0,1,0,1,9,NULL,1284536132,0,0,-4,0,'464522789@qq.com',NULL),(16,1,1,'我要怎么样呢？',1,5,5,3,0,2,9,NULL,1284536253,0,0,-4,1,'kidd10',NULL),(17,1,1,'555',1,1,0,1,0,1,9,NULL,1284606983,0,0,-4,0,'464522789@qq.com',NULL),(18,1,2,'444',1,1,0,1,0,NULL,9,NULL,1284607355,0,0,-4,0,'464522789@qq.com',NULL),(19,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607682,0,0,-4,0,'test','冰封'),(20,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607698,0,0,-4,0,'test','冰封'),(21,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607699,0,0,-4,0,'test','冰封'),(22,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607724,0,0,-4,0,'test','冰封'),(23,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607725,0,0,-4,0,'test','冰封'),(24,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607727,0,0,-4,0,'test','冰封'),(25,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607728,0,0,-4,0,'test','冰封'),(26,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607728,0,0,-4,0,'test','冰封'),(27,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284607728,0,0,-4,0,'test','冰封'),(28,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608003,0,0,-4,0,'test','冰封'),(29,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608004,0,0,-4,0,'test','冰封'),(30,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608004,0,0,-4,0,'test','冰封'),(31,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608004,0,0,-4,0,'test','冰封'),(32,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608005,0,0,-4,0,'test','冰封'),(33,1,0,'为什么不能用',2,1,0,1,0,NULL,3,NULL,1284608005,0,0,-4,0,'test','冰封'),(34,1,0,'为什么不能用',2,1,3,1,0,NULL,3,NULL,1284608006,0,0,-4,0,'test','冰封'),(35,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284610012,0,0,-4,0,'test','冰封'),(36,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616318,0,0,-4,0,'test','冰封'),(37,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616320,0,0,-4,0,'test','冰封'),(38,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616320,0,0,-4,0,'test','冰封'),(39,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(40,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(41,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(42,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(43,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(44,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616321,0,0,-4,0,'test','冰封'),(45,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616322,0,0,-4,0,'test','冰封'),(46,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616323,0,0,-4,0,'test','冰封'),(47,1,0,'为什么不能用',2,1,0,1,0,12,NULL,NULL,1284616323,0,0,-4,0,'test','冰封'),(48,1,0,'为什么不能用',2,1,2,1,0,12,NULL,NULL,1284616323,0,0,4,0,'test','冰封');

/*Table structure for table `cndw_work_order_detail` */

DROP TABLE IF EXISTS `cndw_work_order_detail`;

CREATE TABLE `cndw_work_order_detail` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `work_order_id` int(11) unsigned NOT NULL COMMENT '关联work_order的Id',
  `content` text COMMENT '工单详细信息',
  PRIMARY KEY  (`Id`),
  KEY `work_order_id` (`work_order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='工单信息表';

/*Data for the table `cndw_work_order_detail` */

insert  into `cndw_work_order_detail`(`Id`,`work_order_id`,`content`) values (1,1,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"a\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:4:\"test\";}}'),(2,2,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:5:\"12346\";s:14:\"game_server_id\";s:1:\"2\";}}'),(3,3,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:6:\"朱磊\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:5:\"99999\";}}'),(4,4,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"d\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:14:\"asdfasdfasdfas\";}}'),(5,5,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"c\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"1\";s:12:\"user_account\";s:6:\"545435\";}}'),(6,6,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"c\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"1\";s:12:\"user_account\";s:6:\"545435\";}}'),(7,7,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"d\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:4:\"7777\";}}'),(8,8,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:8:{s:14:\"game_server_id\";s:1:\"1\";s:8:\"nickname\";s:9:\"辅导费\";s:14:\"rechargeamount\";s:3:\"100\";s:12:\"rechargetime\";s:9:\"2010-9-13\";s:14:\"rechargemethod\";s:1:\"1\";s:12:\"rechargeband\";s:12:\"工商银行\";s:15:\"rechargeaccount\";s:19:\"6214544475565447554\";s:19:\"rechargeordernumber\";s:13:\"4848484845445\";}}'),(9,9,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:1:\"d\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:5:\"34343\";}}'),(10,10,'a:2:{s:9:\"user_data\";a:1:{s:9:\"nick_name\";s:6:\"朱磊\";}s:11:\"form_detail\";a:2:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:7:\"wwqeqwe\";}}'),(11,11,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"1\";s:12:\"user_account\";s:4:\"test\";s:14:\"game_server_id\";s:1:\"1\";}}'),(12,12,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(13,13,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(14,14,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:3:\"eee\";s:14:\"game_server_id\";s:1:\"1\";}}'),(15,15,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:27:\"我有个账号被盗取了\";s:14:\"game_server_id\";s:1:\"1\";}}'),(16,16,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"1\";s:12:\"user_account\";s:27:\"我的账号也被盗取了\";s:14:\"game_server_id\";s:1:\"2\";}}'),(17,17,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:3:{s:5:\"mibao\";s:1:\"0\";s:12:\"user_account\";s:6:\"456789\";s:14:\"game_server_id\";s:1:\"1\";}}'),(18,18,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(19,19,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(20,20,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(21,21,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(22,22,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(23,23,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(24,24,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(25,25,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(26,26,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(27,27,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(28,28,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(29,29,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(30,30,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(31,31,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(32,32,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(33,33,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(34,34,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(35,35,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(36,36,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(37,37,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(38,38,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(39,39,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(40,40,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(41,41,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(42,42,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(43,43,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(44,44,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(45,45,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(46,46,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(47,47,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}'),(48,48,'a:2:{s:9:\"user_data\";a:0:{}s:11:\"form_detail\";a:0:{}}');

/*Table structure for table `cndw_work_order_qa` */

DROP TABLE IF EXISTS `cndw_work_order_qa`;

CREATE TABLE `cndw_work_order_qa` (
  `Id` int(11) unsigned NOT NULL auto_increment,
  `work_order_id` int(11) unsigned NOT NULL COMMENT '关联work_order的Id',
  `reply_name` varchar(50) default NULL COMMENT '回复客服姓名',
  `qa` tinyint(1) default '0' COMMENT '0提问,1回复',
  `content` text COMMENT '问题详情',
  `create_time` int(10) NOT NULL,
  `is_quality` tinyint(1) NOT NULL default '0' COMMENT '0未质检,1质检过',
  `is_timeout` tinyint(1) NOT NULL COMMENT '是否超时.0未超时.1已超时',
  PRIMARY KEY  (`Id`),
  KEY `work_order_id` (`work_order_id`),
  KEY `reply_name` (`reply_name`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='工单的提问及回复表';

/*Data for the table `cndw_work_order_qa` */

insert  into `cndw_work_order_qa`(`Id`,`work_order_id`,`reply_name`,`qa`,`content`,`create_time`,`is_quality`,`is_timeout`) values (1,1,NULL,0,'test',1284348365,0,0),(2,2,NULL,0,'sfsf',0,0,0),(3,3,NULL,0,'0999',1284348774,0,0),(4,3,'c',1,'hello',1284348796,1,0),(5,1,'d',1,'dfasdfasdfasdfasdfasdf',1284349138,1,0),(6,2,'d',1,'fasafsdafdsafsd',1284349364,1,0),(7,4,NULL,0,'safdsadfsadfsadfsaf',1284349707,0,0),(8,4,'zlsky',1,'asdfasfdsadfasdf',1284349724,1,0),(9,4,'zlsky',1,'safddsafsadfasd',1284349726,1,0),(10,4,'zlsky',1,'sfadafdsadfs',1284351097,1,0),(11,4,'zlsky',1,'sfadadfsafdsafds',1284351099,0,0),(12,4,'zlsky',1,'fdsasadffadsadfs',1284351101,1,0),(13,4,'zlsky',1,'fsaddafsdfasadfsdfsa',1284351102,0,0),(14,4,'zlsky',1,'adfsfadsdfasdfsa',1284351105,0,0),(15,5,NULL,0,'5435345',1284355876,0,0),(16,6,NULL,0,'5435345',1284355876,0,0),(17,7,NULL,0,'77777',1284356191,0,0),(18,8,NULL,0,'我刚刚充值了100元，但是没有到账呢，快帮我解决！',0,0,0),(19,8,'zlsky',1,'你好！\r\n     请稍等！我们会尽快处理啦！',1284356631,1,0),(20,8,'zlsky',1,'',1284356638,0,0),(21,8,NULL,0,'补充内容',0,0,0),(22,8,NULL,0,'呵呵',0,0,0),(23,9,NULL,0,'324343',1284357720,0,0),(24,9,'zlsky',1,'hello\r\n',1284357821,1,0),(25,8,'zlsky',1,'ddd',1284358810,0,0),(26,8,'zlsky',1,'afdafsdfsad',1284358828,0,0),(27,10,NULL,0,'dxcvxzcvzxc',1284359095,0,0),(28,10,'zlsky',1,'wrwerwr',1284359199,0,0),(29,10,'zlsky',1,'夺',1284359353,0,0),(30,10,'zlsky',1,'wqwqwewqwwqweqwe',1284359359,0,0),(31,10,'zlsky',1,'asdf',1284359779,0,0),(32,10,'zlsky',1,'asdf',1284359786,0,0),(33,6,'zlsky',1,'cddcdc',1284367929,1,0),(34,11,NULL,0,'test',0,0,0),(35,11,NULL,0,'kiki',0,0,0),(36,1,'zlsky',1,'heoo',1284430093,0,0),(37,12,NULL,0,'TEST',0,0,0),(38,13,NULL,0,'test',0,0,0),(39,14,NULL,0,'eeeee',0,0,0),(40,14,'zlsky',1,'write back',1284522751,0,0),(41,15,NULL,0,'我有个账号被盗取了',0,0,0),(42,16,NULL,0,'GM帮我看看我的账号，我的账号被盗取了，怎么办，快帮我拿回来啊。',0,0,0),(43,16,'zlsky',1,'您好!\r\n    请问您被盗取了什么东西呢？告诉我，我帮您拿回来。',1284536391,0,0),(44,16,NULL,0,'我丢失了一辆玫瑰bibi车',0,0,0),(45,16,'zlsky',1,'您好！\r\n    真的吗？',1284536520,0,0),(46,16,NULL,0,'nnd，你查一下吧',0,0,0),(47,16,NULL,0,'...！',0,0,0),(48,16,'zlsky',1,'您好！\r\n    我帮您查啦，你等一下哈！',1284536730,0,0),(49,16,'zlsky',1,'您好！\r\n    帮你把玫瑰bibi车找回来了，请查看一下游戏啊！\r\n',1284541348,0,0),(50,16,NULL,0,'3q\r\n',0,0,0),(51,16,'zlsky',1,'不客气啦！',1284541423,0,0),(52,17,NULL,0,'3456789',0,0,0),(53,18,NULL,0,'5555',0,0,0),(54,19,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(55,20,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(56,21,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(57,22,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(58,23,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(59,24,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(60,25,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(61,26,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(62,27,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(63,28,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(64,29,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(65,30,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(66,31,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(67,32,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(68,33,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(69,34,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(70,34,'zlsky',1,'dvsdvssdvsdv',1284608780,0,0),(71,34,'zlsky',1,'dvsvdssdv',1284608784,0,0),(72,34,'zlsky',1,'sddsvsdv',1284608787,0,0),(73,35,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(74,36,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(75,37,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(76,38,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(77,39,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(78,40,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(79,41,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(80,42,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(81,43,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(82,44,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(83,45,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(84,46,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(85,47,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(86,48,NULL,0,'我的宝马不见了阿?帮我查查好吗?',0,0,0),(87,48,'zlsky',1,'工在人人人',1284617282,1,0),(88,48,'zlsky',1,'压顶 人人',1284618654,0,0);

/*Table structure for table `cndw_reply_qulity` */

DROP TABLE IF EXISTS `cndw_reply_qulity`;

/*!50001 DROP VIEW IF EXISTS `cndw_reply_qulity` */;
/*!50001 DROP TABLE IF EXISTS `cndw_reply_qulity` */;

/*!50001 CREATE TABLE  `cndw_reply_qulity`(
 `reply_name` varchar(50) ,
 `content` text ,
 `create_time` int(10) ,
 `Id` int(11) ,
 `work_order_id` int(11) ,
 `quality_user_id` int(11) ,
 `qa_id` int(11) ,
 `option_id` tinyint(1) ,
 `status` tinyint(1) ,
 `scores` tinyint(3) ,
 `complain_content` text ,
 `quality_content` text ,
 `reply_content` text ,
 `reply_time` int(10) ,
 `quality_time` int(10) unsigned ,
 `complain_time` int(10) unsigned 
)*/;

/*View structure for view cndw_reply_qulity */

/*!50001 DROP TABLE IF EXISTS `cndw_reply_qulity` */;
/*!50001 DROP VIEW IF EXISTS `cndw_reply_qulity` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `cndw_reply_qulity` AS (select `qa`.`reply_name` AS `reply_name`,`qa`.`content` AS `content`,`qa`.`create_time` AS `create_time`,`quality`.`Id` AS `Id`,`quality`.`work_order_id` AS `work_order_id`,`quality`.`quality_user_id` AS `quality_user_id`,`quality`.`qa_id` AS `qa_id`,`quality`.`option_id` AS `option_id`,`quality`.`status` AS `status`,`quality`.`scores` AS `scores`,`quality`.`complain_content` AS `complain_content`,`quality`.`quality_content` AS `quality_content`,`quality`.`reply_content` AS `reply_content`,`quality`.`reply_time` AS `reply_time`,`quality`.`quality_time` AS `quality_time`,`quality`.`complain_time` AS `complain_time` from (`cndw_work_order_qa` `qa` join `cndw_quality` `quality`) where (`qa`.`Id` = `quality`.`qa_id`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
