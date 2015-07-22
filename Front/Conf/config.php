<?php
$dbConf=include '../config/config.php';
$frontConf=array(
	'DEBUG_MODE'=>true,
	'APP_DEBUG' => true,
	'URL_MODEL' => 3,
	'UPLOAD_PATH' => '../www/Upload/',	//上传目录
	'WE_OPERATOR' => 9,//官方ID
	'HAVE_DO' => 3,//已处理状态
	'TMPL_ACTION_ERROR' => 'Public::error',//错误模板
	'LOGIN_URL' => 'https://login.uwan.com/UserCenter/Login.php?back=',	//用户登录URL
	'SEND_ORDER_URL'=>'http://service.uwan.com/admin.php',	//发送工单URL
	'USER_CREATE_SERVER_URL'=>'http://www.uwan.com/Api/Serverlist/service_sys.php',		//平台接口,用户查找用户在哪个服务器上有角色.u=用户账号 g,1=商业大亨,2=富人国
	'SEND_KEY'=>'cndw_kefu',	//发送工单KEY
	'CURRENT_TIME'=>$_SERVER['REQUEST_TIME'],	//系统当前时间
	'TMPL_CACHE_ON'=>false,//每次都要编译模板

	#------缓存配置------#
	'DATA_CACHE_TYPE'=>'Eaccelerator',

	#------异常错误------#
	//'SHOW_ERROR_MSG' =>true,
	//'ERROR_MESSAGE' => '发生错误！',
	//'ERROR_PAGE' => 'http://localhost.uwan.com/index.php?s=/Index/error',
	'FAQ_BANK_ID'=>0,
	'LANG_ID'=>1,	//语言配置,默认简体1


	#------富人国配置------#
	'SYS_KEY'=>'!@$$DSDGldj*73@sls-(3',//统一系统KEY
	'FRG_GAME_ID'=>2,	//富 人国游戏ID
	'SERVICE_COOKIE_GAME_ID'=>'service_cookie_game_id',	//客服中心使用cookie记录玩家最后使用的游戏id（如FAQ）
);
return array_merge($dbConf,$frontConf);
?>