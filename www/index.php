<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//exit(print_r($_POST));
// 定义ThinkPHP框架路径
define ( 'WWW_PATH', str_replace ( '\\', '/',  dirname ( __FILE__ )  ) ); //定义主目录
define('THINK_PATH', '../ThinkPHP');
//定义项目名称和路径
define('APP_NAME', 'front');
define('APP_PATH', '../Front');
// 加载框架入口文件
require(THINK_PATH."/ThinkPHP.php");
//实例化一个网站应用实例
App::run();
?>