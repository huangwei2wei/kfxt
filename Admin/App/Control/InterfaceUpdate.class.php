<?php
Tools::import('Control_Interface');
/**
 * 系统更新表class接口
 * @author php-朱磊
 *
 */
class Control_InterfaceUpdate extends ApiInterface {

	
	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;
	
	public function __construct(){
		exit('forbidden');//屏蔽
		parent::__construct();
	}
	
	public function actionUpdateSql(){
		$this->_modelSysconfig=$this->_getGlobalData('Model_Sysconfig','object');
		//在<<<EOF和EOF之间输入sql语句;
		$sql=<<<EOF
		
EOF;
		$sql = trim($sql);
		if(empty($sql)){
			return;
		}
		$sql=explode(';',$sql);
		foreach ($sql as $list){
			$this->_modelSysconfig->execute($list);
		}
	}

}



/*
20110107
		CREATE TABLE `cndw`.`cndw_log` (
		`Id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`user_id` INT( 10 ) NULL COMMENT '操作人',
		`time` INT( 10 ) NOT NULL COMMENT '操作时间',
		`control` VARCHAR( 50 ) NOT NULL COMMENT '操作控制器',
		`action` VARCHAR( 50 ) NOT NULL COMMENT '操作动作',
		`doaction` VARCHAR( 50 ) NULL COMMENT '子操作类型',
		`msg` VARCHAR( 255 ) NULL COMMENT '操作留言'
		) ENGINE = MYISAM COMMENT = '系统日志表';
		
		REPLACE INTO `cndw_sysconfig` (`Id`, `config_name`, `title`, `config_value`) VALUES(19, 'lang', '语言配置选项', 'a:4:{i:1;s:6:"中文";i:2;s:6:"英文";i:3;s:6:"韩文";i:4;s:6:"日本";}');
		ALTER TABLE `cndw_player_kind_faq` ADD `lang_id` TINYINT( 1 ) NULL DEFAULT '1' COMMENT '语言ID,默认中文' AFTER `game_type_id`;
		ALTER TABLE `cndw_player_faq` ADD `lang_id` TINYINT( 1 ) NULL DEFAULT '1' COMMENT '语言,默认简体中文' AFTER `game_type_id`;
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(26, 3, '建议/投诉举报', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(27, 3, '账号和密码问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(20, 3, '登录问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(21, 3, '系统问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(22, 3, '充值问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(23, 3, '游戏问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(24, 3, '游戏建议', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(25, 3, 'BUG问题', NULL);
		REPLACE INTO `cndw_question_types` (`Id`, `game_type_id`, `title`, `form_table`) VALUES(28, 3, '其它问题', NULL);

20110112
		ALTER TABLE `cndw_stats_faq` ADD `lang_id` TINYINT( 1 ) NULL DEFAULT '1' COMMENT '语言ID,默认简体' AFTER `game_type_id` ;
		ALTER TABLE `cndw_player_faq` ADD `time` INT( 10 ) NOT NULL COMMENT '最后编辑时间' AFTER `status` ,
		ADD `user_id` INT( 10 ) NOT NULL COMMENT '最后编辑人' AFTER `time` ;
		ALTER TABLE `cndw_player_faq_log` ADD `source` TINYINT( 1 ) NOT NULL COMMENT '来源:1官网,2:游戏' AFTER `faq_opinion` ;
		ALTER TABLE `cndw_gameser_list` ADD `time_zone` TINYINT( 3 ) NULL DEFAULT '0' COMMENT '时区设置' AFTER `marking` ;
		
20110117
		ALTER TABLE `cndw_log_201101` ADD `ip` INT( 11 ) NOT NULL COMMENT 'ip' AFTER `time`;
		ALTER TABLE `cndw_player_faq_log` ADD `game_type_id` TINYINT( 1 ) NOT NULL COMMENT '所属游戏' AFTER `source` ;

20110125
		ALTER TABLE `cndw_user` DROP `game_type_id` ;
		ALTER TABLE `cndw_user` ADD `act` TEXT NULL COMMENT '用户指定的权限,以","号隔开' AFTER `roles`;
		ALTER TABLE `cndw_act` CHANGE `allow` `allow` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色';
		ALTER TABLE `cndw_quality` ADD `reply_user_id` INT( 10 ) NOT NULL COMMENT '回复人userid' AFTER `qa_id` ;
		ALTER TABLE `cndw_quality` ADD `again_status` TINYINT( 1 ) NULL COMMENT '复检状态,0未通过,1通过' AFTER `again_user_id` ;
		
		CREATE TABLE IF NOT EXISTS `cndw_quality_document` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `create_user_id` int(11) NOT NULL COMMENT '记录人',
		  `create_time` int(10) NOT NULL COMMENT '记录时间',
		  `org_id` int(11) NOT NULL COMMENT '组别ID',
		  `reply_user_id` int(11) NOT NULL COMMENT '被质检人id',
		  `source` tinytext NOT NULL COMMENT '来源',
		  `quality_status` tinyint(1) NOT NULL COMMENT '质检状态',
		  `quality_user_id` int(11) NOT NULL COMMENT '质检人id',
		  `scores` int(10) NOT NULL COMMENT '所扣分数',
		  `feedback` tinyint(1) NOT NULL COMMENT '是否反馈.0:否.1:是',
		  `work_order_id` int(11) DEFAULT NULL COMMENT '工单id',
		  `qa_id` int(11) DEFAULT NULL COMMENT '回复ID',
		  `title` varchar(200) NOT NULL COMMENT '标题',
		  `content` text NOT NULL COMMENT '内容',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
		
		ALTER TABLE `cndw_apply_data_frg` ADD `operator_id` INT( 10 ) NULL COMMENT '运营商ID' AFTER `post_data` ;
		ALTER TABLE `cndw_gold_card` ADD `operator_id` INT( 10 ) NOT NULL COMMENT '运营商id';
20110128
		ALTER TABLE `cndw_rooms` ADD `game_type_id` INT( 10 ) NULL COMMENT '房间所属游戏' AFTER `Id` ,
		ADD `operator_id` INT( 10 ) NULL COMMENT '房间所属运营商' AFTER `game_type_id` 

20110216
		CREATE TABLE `cndw_auto_orderqueue` (
		`Id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`game_type_id` TINYINT( 11 ) NOT NULL COMMENT '游戏类型ID',
		`operator_id` INT( 11 ) NOT NULL COMMENT '运营商id',
		`vip_level` TINYINT( 1 ) NOT NULL COMMENT 'vip等级',
		`room_id` INT( 11 ) NOT NULL COMMENT '所属房间id',
		`work_order_id` INT( 11 ) NOT NULL COMMENT '工单id',
		`create_time` INT NOT NULL COMMENT '加入时间'
		) ENGINE = MEMORY COMMENT = '自动分配工单队列';
		
20110221
		CREATE TABLE IF NOT EXISTS `cndw_program_datework` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `start_time` int(10) NOT NULL COMMENT '开始时间',
		  `end_time` int(10) NOT NULL COMMENT '预计完成时间',
		  `actual_time` int(10) DEFAULT NULL COMMENT '实际完成时间',
		  `content` text NOT NULL COMMENT '工作内容',
		  `user_id` int(11) NOT NULL COMMENT '添加人',
		  `group_id` tinyint(2) NOT NULL COMMENT '组ID',
		  PRIMARY KEY (`Id`),
		  KEY `user_id` (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='技术管理每日工作计划' AUTO_INCREMENT=1 ;

		CREATE TABLE IF NOT EXISTS `cndw_program_task` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `assign_user_id` int(11) NOT NULL COMMENT '分配人',
		  `accept_user_id` int(11) NOT NULL COMMENT '接受人',
		  `difficulty` tinyint(2) NOT NULL COMMENT '难度',
		  `precast_hour` int(10) NOT NULL COMMENT '预计完成时间',
		  `actual_hour` int(10) DEFAULT NULL COMMENT '实际完成时间',
		  `start_time` int(10) DEFAULT NULL COMMENT '开始时间',
		  `end_time` int(10) DEFAULT NULL COMMENT '完成时间',
		  `timeout_cause` text COMMENT '廷时原因',
		  `efficiency_scorce` int(11) DEFAULT NULL COMMENT '质量得分',
		  `quality_scorce` float DEFAULT NULL COMMENT '效率得分',
		  `efficiency_level` tinyint(1) DEFAULT NULL COMMENT '质量等级.1:优秀,2:良好,3:及格,4:差',
		  `task_content` text NOT NULL COMMENT '任务描述',
		  `bug_scorce` int(10) DEFAULT NULL COMMENT 'BUG评分',
		  PRIMARY KEY (`Id`),
		  KEY `assign_user_id` (`assign_user_id`),
		  KEY `accept_user_id` (`accept_user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='技术管理任务效绩表' AUTO_INCREMENT=1 ;
		update cndw_gameser_list set marking=replace(marking,'_','|') where game_type_id in (1,2);
		ALTER TABLE `cndw_gameser_list` DROP `send_msg_url` ;

20110223
		CREATE TABLE IF NOT EXISTS `cndw_order_log` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '工单ID',
		  `game_type_id` tinyint(3) NOT NULL COMMENT '游戏ID',
		  `operator_id` smallint(4) NOT NULL COMMENT '运营商id',
		  `server_id` int(10) NOT NULL COMMENT '服务器ID',
		  `runtime` int(10) NOT NULL COMMENT '执行时间',
		  `last_run_time` int(10) NOT NULL COMMENT '上一次执行的时间',
		  `log` text NOT NULL COMMENT '日志',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='工单日志处理表' AUTO_INCREMENT=1 ;
		
20110308
		ALTER TABLE `cndw_work_order` ADD `evaluation_desc` TINYINT( 1 ) NULL COMMENT '差评时状态' AFTER `evaluation_status`;
		ALTER TABLE `cndw_work_order` ADD `game_user_id` INT( 10 ) NULL COMMENT '玩家ID' AFTER `user_nickname`;
20110314
		ALTER TABLE `cndw_player_kind_faq` ADD `sort_id` INT( 10 ) NULL DEFAULT '0' COMMENT '排序id' AFTER `name`;
		update cndw_gameser_list set marking=CONCAT('dwcn|',marking) where game_type_id=1 and operator_id=9;
		update cndw_gameser_list set marking=CONCAT('gamefrg|',marking) where game_type_id=2 and operator_id=9;

20110317
		ALTER TABLE `cndw_verify` ADD `finish_user_id` INT( 10 ) NULL COMMENT '跟进user_id' AFTER `user_id` ;
		UPDATE cndw_verify SET finish_user_id = user_id;
		ALTER TABLE `cndw_work_order` ADD `is_verify` TINYINT NOT NULL COMMENT '是否需要查证' AFTER `status` ;
		ALTER TABLE `cndw_work_order` CHANGE `user_account` `user_account` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '玩家用户账号';
		
		
		CREATE TABLE IF NOT EXISTS `cndw_ly_worker` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(250) NOT NULL COMMENT '标题',
		  `content` text NOT NULL COMMENT '内容',
		  `game_type_id` tinyint(2) NOT NULL COMMENT '游戏ID',
		  `type` tinyint(2) NOT NULL COMMENT '类型',
		  `progress` tinyint(2) NOT NULL COMMENT '进度',
		  `edit_time` int(10) NOT NULL COMMENT '创建时间',
		  `user_id` int(11) NOT NULL COMMENT '编辑人',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='联运工作交接表' AUTO_INCREMENT=1 ;


		ALTER TABLE `cndw_menu` ADD `name_2` VARCHAR( 50 ) NULL COMMENT '英文名称' AFTER `name` ;
		ALTER TABLE `cndw_question_types` ADD `title_2` VARCHAR( 50 ) NULL COMMENT '英文类型名称' AFTER `title` ;
		ALTER TABLE `cndw_sysconfig` ADD `config_value_2` TEXT NULL COMMENT '配置值,英文' AFTER `config_value` ;

*/