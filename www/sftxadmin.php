<?php
define ( 'WWW_PATH', str_replace ( '\\', '/',  dirname ( __FILE__ )  ) ); //定义主目录
require '../SftxAdmin/Libs/Core/App.class.php';	//引入文件
App::run();//运行mvc
