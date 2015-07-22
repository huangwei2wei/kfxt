<?php
return array(
	'XianHunUser'=>array(
		'name'=>'幻世仙征GM工具',
		'display'=>true,
		'child'=>array(
			'UserQueryDb'=>array('name'=>'用户查询','display'=>true,),
			'MailQuery'=>array('name'=>'邮箱查询','display'=>true,),
			'OperationLog'=>array('name'=>'玩家操作日志','display'=>true,),	
			'DepositList'=>array('name'=>'充值查询','display'=>true,),	
			'UserDetail'=>array('name'=>'用户详细查询','display'=>true,),
			'GameLogin'=>array('name'=>'游戏登录','display'=>true,),		
			'SendMail'=>array('name'=>'发邮件','display'=>true,),
			'SendMail'=>array('name'=>'发邮件','display'=>true,),
			'SendMail'=>array('name'=>'发邮件','display'=>true,),
			'LockIp'=>array('name'=>'IP封锁管理','display'=>true,),
			'LockUser'=>array('name'=>'封号管理','display'=>true,),
			'LockUserAdd'=>array('name'=>'添加封号','display'=>false,),
			'ForbiddenChat'=>array('name'=>'禁言管理','display'=>true,),
			'ForbiddenChatAdd'=>array('name'=>'添加禁言','display'=>false,),
			'Index'=>array('name'=>'在线用户','display'=>true,),
			'ItemsUpdate'=>array('name'=>'从服务器更新道具','display'=>false,),
			//'SendItems'=>array('name'=>'道具发放','display'=>false,),
			'SendItemsToKnapsack'=>array('name'=>'道具发放至背包','display'=>false,),
			'UserQuery'=>array('name'=>'旧用户查询','display'=>false,),
			'KnapSack'=>array('name'=>'扣除背包装备申请','display'=>true,),
			'Dress'=>array('name'=>'扣除身穿装备申请','display'=>true,),	
			'Warehouse'=>array('name'=>'扣除仓库装备申请','display'=>true,),
			'ItemsApply'=>array('name'=>'道具发放申请','display'=>true,),
			'ItemsPackage'=>array('name'=>'礼包卡号管理','display'=>false,),
			'Modification'=>array('name'=>'玩家修改数值','display'=>true,),
			'SoulModification'=>array('name'=>'宠物修改数值','display'=>true,),
			'PlayerMoneyLog'=>array('name'=>'玩家消耗查询','display'=>true,),
			'PlayerValueUpdate'=>array('name'=>'修改玩家数值','name_en'=>'VIP Player Setting','display'=>true),
		),
	),
	'XianHunOperator'=>array(
		'name'=>'幻世仙征运营工具',
		'display'=>true,
		'child'=>array(
			'Announcement'=>array('name'=>'公告','display'=>true,),
			'Synchronous'=>array('name'=>'发送多服公告','display'=>true,),
			'AllNotice'=>array('name'=>'多服公告管理','display'=>true,),
			'SendMailToAll'=>array('name'=>'全服发邮件','display'=>true,),
			'SendItemsToAllApply'=>array('name'=>'全服发礼品','display'=>true,),
			'FilteredWords'=>array('name'=>'禁用字','display'=>true,),
			'GameNotice'=>array('name'=>'活动公告','display'=>true,),
			'UpdateNotice'=>array('name'=>'更新公告','display'=>true,),
			'Bulletin'=>array('name'=>'文字公告','display'=>true),
			'ItemsPackage'=>array('c'=>'XianHunUser','name'=>'礼包卡号管理','display'=>true,),//换目录位置
			'Version'=>array('name'=>'版本号管理','display'=>true),
			'Serverlist'=>array('name'=>'服务器管理','display'=>true,),
			'GroupSendemail'=>array('name'=>'给在线玩家群发邮件','display'=>true,),
			'ServerStatus'=>array('name'=>'服务器状态','display'=>true,),
			'Modification'=>array('name'=>'玩家修改数值','display'=>true,),//换目录位置
		),
		
			
	)	


);