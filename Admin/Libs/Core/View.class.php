<?php

/**
 * 显示基础类
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 *
 */
class View extends Base {

	/**
	 * 模板句柄,目前为Smarty
	 *
	 * @var unknown_type
	 */
	private static $_Instance = null;

	private function __construct(){

	}

	/**
	 * 单例模式,返回模板实例
	 *
	 * @return Object
	 */
	public static function getInstance() {
		if (is_null ( self::$_Instance )) {
			switch (TEMPLATE)
			{
				case 'Smarty' :	{
					self::_loadCore('View_Smarty');
					self::$_Instance=new View_Smarty();
					return self::$_Instance;
					break;
				}
				default :{
					return FALSE;
					break;
				}
			}
		} else {
			return self::$_Instance;
		}

	}

}