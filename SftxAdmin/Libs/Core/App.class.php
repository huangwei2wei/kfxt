<?php

/**
 * 框架应用程序初始化
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 */
class App {
	
	//control层参数
	private static $_control;
	//action动作参数
	private static $_action;
	
	/**
	 * 运行应用程序
	 */
	public static function run() {
		self::_init ();
		self::_analysisConfig ();
		self::_analysisUrl ();
		self::_initialize ();
		self::_loadAction ();
	}
	
	/**
	 * 用户验证
	 */
	private static function _initialize() {
		require_once MVC_DIR . '/Initialize.class.php'; //权限验证类
		$initialize = new Initialize ();
		$initialize->runGlobal ();
	}
	
	/**
	 * 载入动作,运行MVC
	 */
	private static function _loadAction() {
		try {
			self::$_action = 'action' . self::$_action;
			$controlFile = APP_PATH . '/Control/' . self::$_control . '.class.php';
			self::$_control = 'Control_' . self::$_control;
			if (! file_exists ( $controlFile ))
				//@todo 错误类未完成
				throw new Error ( '控制器不存在:' . $controlFile );
			require_once $controlFile;
			if (! class_exists ( self::$_control ))
				//@todo 错误类未完成
				throw new Error ( '指定的类名不存在:' . self::$_control );
			
			$instance = new self::$_control ();
			if (! method_exists ( $instance, self::$_action ))
				//@todo 错误类未完成
				throw new Error ( '指定类名:' . self::$_control . '不存在方法:' . self::$_action );
			$action = self::$_action;
			
			$instance->$action ();
		} catch ( Error $e ) {
			//@todo 错误类未完成
			echo $e->displayMsg ();
		}
	}
	
	/**
	 * 载入应用程序页面
	 */
	private static function _init() {
		header ( 'Content-type:text/html;charset=utf-8' ); //设置字符集
		date_default_timezone_set ( 'PRC' );
		session_start ();
		define ( 'MVC_DIR', dirname ( __FILE__ ) );
		define ( 'ROOT_PATH', str_replace ( '\\', '/', dirname ( dirname ( MVC_DIR ) ) ) );
		require_once MVC_DIR . '/Base.class.php'; //基础类
		require_once MVC_DIR . '/Control.class.php'; //控制层类
		require_once MVC_DIR . '/Model.class.php'; //数据库ORM
		require_once MVC_DIR . '/Error.class.php'; //引入错误类
		require_once MVC_DIR . '/Object.class.php'; //引入object基类
		require_once MVC_DIR . '/Tools.class.php'; //引入工具类
	}
	
	/**
	 * 载入配置常量
	 */
	private static function _analysisConfig() {
		$dbConfig=dirname(WWW_PATH) . "/config/sftxconfig.php";
		$dbConfig=require $dbConfig;
		$config = require_once MVC_DIR . '/Conf/Config.php'; //引入配置文件
		$config=array_merge($config,$dbConfig);
		foreach ( $config as $const => $value ) {
			define ( $const, $value );
		}
		define ( '__ROOT__', dirname ( 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] ) );
		define ( '__JS__', __ROOT__ . '/Public/sftxadmin/js' );
		define ( '__CSS__', __ROOT__ . '/Public/sftxadmin/css' );
		define ( '__IMG__', __ROOT__ . '/Public/sftxadmin/img' );
		define ( '__SWF__', __ROOT__ . '/Public/sftxadmin/swf' );
	}
	
	/**
	 * URL配置
	 *
	 */
	private static function _analysisUrl() {
		switch (URL_MODE) {
			case '1' :
				{
					self::$_control = $_GET ['c'] ? ucwords ( $_GET ['c'] ) : DEFAULT_CONTROL;
					self::$_action = $_GET ['a'] ? ucwords ( $_GET ['a'] ) : DEFAULT_ACTION;
					break;
				}
			case '2' : //@todo 未完成...
				{
					$path = trim ( $_SERVER ['PATH_INFO'], '/' );
					$paths = explode ( '/', $path );
					$_GET ['c'] = array_shift ( $paths );
					$_GET ['a'] = array_shift ( $paths );
					self::$_control = $_GET ['c'] ? ucwords ( $_GET ['c'] ) : DEFAULT_CONTROL;
					self::$_action = $_GET ['a'] ? ucwords ( $_GET ['a'] ) : DEFAULT_ACTION;
					break;
				}
		}
		$_GET ['c'] = self::$_control;
		$_GET ['a'] = self::$_action;
		define ( 'CONTROL', $_GET ['c'] );
		define ( 'ACTION', $_GET ['a'] );
	}

}