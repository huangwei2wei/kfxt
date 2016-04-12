<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define ( 'WWW_PATH', str_replace ( '\\', '/',  dirname ( __FILE__ )  ) ); //定义主目录
require '../Admin/Libs/Core/App.class.php'; //引入文件
App::run();//运行mvc
