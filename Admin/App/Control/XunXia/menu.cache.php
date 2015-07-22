<?php
return array(
	'XunXiaNotice'=>array(
		'name'=>'公告管理',
		'display'=>true,
		'child'=>array(
			'Index'=>array('name'=>'公告显示列表','display'=>true,),
			'Add'=>array('name'=>'增加公告','display'=>true),
			'AllAdd'=>array('name'=>'全服发公告','display'=>true),
			'Edit'=>array('name'=>'编辑公告','display'=>false),
			'Del'=>array('name'=>'删除公告','display'=>false),
		),
	),
	'XunXiaUser'=>array(
		'name'=>'游戏用户管理',
		'display'=>true,
		'child'=>array(	
			'UserQuery'=>array('name'=>'用户查询','display'=>true),
			'Index'=>array('name'=>'用户管理','display'=>true),
			'GameLogin'=>array('name'=>'游戏登录','display'=>true),
			'OperationLog'=>array('name'=>'新用户操作日志','display'=>true),
			'UserGoldLog'=>array('name'=>'用户元宝操作日志','display'=>true),
			'UserSilverLog'=>array('name'=>'用户铜钱操作日志','display'=>true),
			'UserFoodLog'=>array('name'=>'用户血量操作日志','display'=>true),
			'OperLog'=>array('name'=>'用户操作日志','display'=>true),
			'DepositList'=>array('name'=>'官网充值查询','display'=>true),
			'DepositListQQ'=>array('name'=>'腾讯充值查询','display'=>true),
			'LockUser'=>array('name'=>'全服封OpenId','display'=>false),
			
		),
	),
	'XunXiaSysManage'=>array(
		'name'=>'GM管理工具',
		'display'=>true,
		'child'=>array(
			'SendMsg'=>array('name'=>'群发短信','display'=>true),
			'IpIndex'=>array('name'=>'封IP列表','display'=>true),
			'IpAdd'=>array('name'=>'增加封IP','display'=>false),
			'IpDel'=>array('name'=>'解除封IP','display'=>false),
	
			'SearchDj'=>array('name'=>'查询装备','display'=>true,),
				
			'LockIP'=>array('name'=>'新封IP管理','display'=>true),
			'ResUserIndex'=>array('name'=>'封号显示列表','display'=>true),
			'ResUserAdd'=>array('name'=>'增加封号','display'=>false),
			'ResUserDel'=>array('name'=>'解除封号','display'=>false),
			'LockUserByBatch'=>array('name'=>'全服封号','display'=>true),
			'TalkUserIndex'=>array('name'=>'禁言显示列表','display'=>true),
			'TalkUserAdd'=>array('name'=>'增加禁言','display'=>false),
			'TalkUserDel'=>array('name'=>'解除禁言','display'=>false),
			'GiftCard'=>array('name'=>'道具卡管理','display'=>true),
			'Serverlist'=>array('name'=>'服务器管理','display'=>true,),
//			'GiftCardList'=>array('name'=>'道具卡列表','display'=>true),
			'ComplementActivityKey'=>array('name'=>'补活动钥匙','display'=>true),
		),
	),
);