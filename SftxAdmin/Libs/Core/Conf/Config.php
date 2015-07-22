<?php

return array(
	'URL_MODE'					=> 1,					//URL模式, 1普通模式, 2 PATH_INFO模式
	'DEFAULT_CONTROL'			=> 'Index',				//默认调用的控制器
	'DEFAULT_ACTION'			=> 'Index',				//默认执行的方法
	'APP_PATH'					=> ROOT_PATH . '/App',	//应用程序目录
	'LIB_PATH'					=> ROOT_PATH . '/Libs',	//第三方插件文件夹
	'CACHE_DIR'					=> ROOT_PATH . '/RunTime/Cache',	//缓存目录
	'UPDATE_DIR'				=> WWW_PATH . '/Upload',	//上传目录
	'ROOMS_DIR'					=> ROOT_PATH . '/RunTime/Rooms',	//房间文件缓存目录
	'USERS_DIR'					=> ROOT_PATH . '/RunTime/Users',		//用户缓存文件
	'WORKORDER_DIR'				=> ROOT_PATH . '/RunTime/WorkOrder',	//工单缓存文件
	'EXCEL_DIR'					=> ROOT_PATH . '/RunTime/Excel',		//用户上传excel文件存放位置
	'CURRENT_TIME'				=> $_SERVER['REQUEST_TIME'],	//当前时间,
	'PAGE_SIZE'					=> 20,					//常规分页数
	'DEBUG'						=> true,		//是否开启debug模式


	'TEMPLATE'					=> 'Smarty',			//模板配置
	'TEMPLATE_TYPE'			    => 'html',				//模板类型名 html,pthml,tpl,htm
	'TEMPLATE_THEME'			=> 'default',			//模板默认主题
	'TEMPlATE_LEFT_DELIMITER'	=> '<!--{',				//模板左边标识符
	'TEMPLATE_RIGHT_DELIMITER'	=> '}-->',				//模板右边标识符
	'TEMPLATE_DEFALUT_DISPLAY_PAGE' => 'Default/Main',	//smarty模板默认显示页面

	'ERROR_UNEXTPECTED'			=> '内部错误',			//内部错误

	'DB_HOST'					=> 'localhost',			//mysql主机
	'DB_USER'					=> 'root',				//mysql用户
	'DB_PWD'					=> 'root',			//数据库密码
	'DB_CHAR'					=> 'utf8',				//数据库字符集
	'DB_NAME'					=> 'cndw',				//数据库名
	'DB_TYPE'					=> 'mysql',				//数据库连接方式(pdo|mysql)
	'DB_PREFIX'					=> 'cndw_',				//数据库前缀

	//rbac权限
	'SESSION_USER_KEY'			=>'SERVICE_USER',				//用户sessionKEY
	'RBAC_ROLES'				=>'RBAC_ROLES',			//用户session角色KEY
	'RBAC_EVERYONE'				=>'RBAC_EVERYONE',		//RBAC_EVERYONE 表示任何用户（不管该用户是否具有角色信息）
	'RBAC_HAS_ROLE'				=>'RBAC_HAS_ROLE',		//RBAC_HAS_ROLE 表示具有任何角色的用户
	'RBAC_NO_ROLE'				=>'RBAC_NO_ROLE',		//RBAC_NO_ROLE 表示不具有任何角色的用户
	'RBAC_NULL'					=>'RBAC_NULL',			//RBAC_NULL 表示该设置没有值

	//Memcached配置
//	'MEMCACHE_ADDRESS'			=>'127.0.0.1',			//memcacheIP
//	'MEMCACHE_PORT'				=>'11211',				//memcache端口号

	//service配置
	'TAKE_KEY'					=>'cndw_kefu',			//service唯一key
	'KEY_SPACE'					=>'zl85@#cn*dw',		//用户登录加密密钥
//	'PASSPORT_URL'				=>Tools::url('Index','Login'),	//登录URL
//	'FRONT_WORKORDER_TIMEOUT'	=>60*60*2,				//前台工单超时时间,默认2小时.
    'DEFAULT_FRG_SERVER_ID'     =>1,                   //默认富人国做副本服务器ID

);