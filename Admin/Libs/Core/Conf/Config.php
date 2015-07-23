<?php
define('MAGIC_QUOTES',get_magic_quotes_gpc());					//是否开始魔术引号
define('URL_MODE',1);											//URL模式, 1普通模式, 2 PATH_INFO模式
define('DEFAULT_CONTROL','Index');								//默认调用的控制器
define('DEFAULT_ACTION','Index');								//默认执行的方法
define('DEFAULT_PACKAGE','Default');							//默认模块包
define('APP_PATH',ROOT_PATH . '/App');							//应用程序目录
define('LANG_PATH',APP_PATH.'/Lang/'.$_COOKIE['kefu_lang']);	//语言包文件夹,默认简体
define('LANG_ID',intval($_COOKIE['kefu_lang'])?intval($_COOKIE['kefu_lang']):1);	//默认langID,默认1:简体

define('LIB_PATH',ROOT_PATH . '/Libs');							//第三方插件文件夹
define('UPDATE_DIR',WWW_PATH . '/Upload');						//上传目录

define('RUNTIME_DIR',ROOT_PATH.'/RunTime');						//Runtime路径
define('CACHE_DIR',RUNTIME_DIR . '/Cache');						//缓存目录
define('ROOMS_DIR',RUNTIME_DIR . '/Rooms');						//房间文件缓存目录
define('USERS_DIR',RUNTIME_DIR . '/Users');						//用户缓存文件
define('WORKORDER_DIR',RUNTIME_DIR . '/WorkOrder');				//工单缓存文件
define('EXCEL_DIR',RUNTIME_DIR . '/Excel');						//用户上传excel文件存放位置

define('CURRENT_TIME',$_SERVER['REQUEST_TIME']);				//当前时间
define('PAGE_SIZE',20);											//常规分页数
define('DEBUG',true);											//是否开启debug模式

define('TEMPLATE','Smarty');									//模板配置
define('TEMPLATE_TYPE','html');									//模板类型名 html,pthml,tpl,htm
define('TEMPLATE_THEME','default');								//模板默认主题
define('TEMPlATE_LEFT_DELIMITER','<!--{');						//模板左边标识符
define('TEMPLATE_RIGHT_DELIMITER','}-->');						//模板右边标识符
define('TEMPLATE_DEFALUT_DISPLAY_PAGE','Default/Main');			//模板默认标识符

define('ERROR_UNEXTPECTED','内部错误');							//内部错误

define('SESSION_USER_KEY','SERVICE_USER');						//用户sessionKEY
define('RBAC_ROLES','RBAC_ROLES');								//用户session角色KEY
define('RBAC_EVERYONE','RBAC_EVERYONE');						//RBAC_EVERYONE 表示任何用户（不管该用户是否具有角色信息）
define('RBAC_HAS_ROLE','RBAC_HAS_ROLE');						//RBAC_HAS_ROLE 表示具有任何角色的用户
define('RBAC_NO_ROLE','RBAC_NO_ROLE');							//RBAC_NO_ROLE 表示不具有任何角色的用户
define('RBAC_NULL','RBAC_NULL');								//RBAC_NULL 表示该设置没有值

define('TAKE_KEY','cndw_kefu');									//service唯一key
define('KEY_SPACE','zl85@#cn*dw');								//用户登录加密密钥
define('PASSPORT_URL','/admin.php?c=Index&a=Index');				//passport网站
define('FRONT_WORKORDER_TIMEOUT',60*60*20);						//前台工单超时时间,默认2小时.
define('DEFAULT_FRG_SERVER_ID',11);								//默认富人国做副本服务器ID

define('FRG_SYS_KEY','!@$$DSDGldj*73@sls-(3');					//统一系统KEY

define('CACHE_TYPE','Eaccelerator');							//配置 File,Memcache,Apc,Eaccelerator等等...
define('CACHE_PREFIX','kefu');									//缓存key前缀.

define('MasterAccount','admin,huangwei');						//超级管理员账号,请用","号隔开
