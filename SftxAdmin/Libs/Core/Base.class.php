<?php
/**
 * 基础类
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 */
abstract class Base {
	/**
	 * 全局变量
	 * @param array
	 */
	protected static $_global = array ();
	
	/**
	 * 载入核心类函数
	 * @param string $class
	 * @param boolean $isSingle 是否开启单例模式 
	 */
	static protected function _loadCore($class,$isSingle=FALSE) {
		$includePath = MVC_DIR . '/'.str_replace('_','/',$class).'.class.php';
		if (! file_exists ( $includePath ))throw new Error ( "_loadCore error: {$includePath}" );//@todo 错误类,未完成
		include_once $includePath;
	}
	
	/**
	 * 注册全局变量
	 * @param string $key key值
	 * @param array/object/int $data 信息
	 * @param string $type 注册全局类型,可以是object,cache(默认),string,int
	 */
	protected static function _registerGlobalData($key, $data, $type = 'cache') {
		$type = strtolower ( $type );
		if (! isset ( self::$_global [$type] [$key] )) {
			self::$_global [$type] [$key] = $data;
		}
	}
	
	/**
	 * 获取全局变量,注:如果获取对象不存在,将注册对象,以后在次获取都将会是同一对象,单例模式.
	 * @param key $key
	 * @param string $type 注册全局类型,可以是object,cache(默认),room,user
	 * @return object,cache,string,int
	 */
	protected static function _getGlobalData($key, $type = 'cache') {
		$type = strtolower ( $type );
		if (! isset ( self::$_global [$type] [$key] )) { //如果没有的话...
			switch ($type) {
				case 'object' :
					{
						Tools::import ( $key );
						self::$_global [$type] [$key] = new $key ();
						break;
					}
				case 'room' :
					{ //房间对象 $key为数据库里的Id
						self::$_global [$type] [$key] = self::_includeFile ( ROOMS_DIR . "/{$key}.serialize.php" );
						self::$_global [$type] [$key] = unserialize ( self::$_global [$type] [$key] );
						break;
					}
				case 'user' :
					{ //用户对象 $key为用户名
						self::$_global [$type] [$key] = self::_includeFile ( USERS_DIR . "/{$key}/Info.serialize.php" );
						self::$_global [$type] [$key] = unserialize ( self::$_global [$type] [$key] );
						break;
					}
				default :
					{
						self::$_global [$type] [$key] = self::_includeFile(CACHE_DIR . "/{$key}.cache.php");
					}
			}
		}
		return self::$_global [$type] [$key];
	}
	
	/**
	 * 引入文件,复写 include
	 * @param string $filePath 文件路径
	 */
	protected static function _includeFile($filePath) {
		if (file_exists ( $filePath )) {
			return include $filePath;
		} else {
			exit('文件不存在' . $filePath);
		}
	}
	
	/**
	 * 生成缓存函数
	 * @param array $array 生成数组
	 * @param string $filePath 文件路径
	 * @param string $return 变量名,默认为直接return
	 */
	static protected function _addCache($array, $filePath, $return = NULL) {
		if (! $array)
			return false;
		if (! $filePath)
			return false;
		if ($return == null) {
			$return = 'return ';
		} else {
			$return = "{$return} = ";
		}
		$str = "<?php\r\n";
		$str .= $return;
		$str .= var_export ( $array, true );
		$str .= "\r\n?>";
		if (self::_writeData ( $filePath, $str )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 写入文件,复写file_put_contents函数
	 * @param string $filename
	 * @param string $data
	 * @param flags int[optional]
	 * @param context resource[optional]
	 */
	static protected function _writeData($filename, $data, $flags = null, $context = null) {
		if (file_put_contents ( $filename, $data, $flags, $context )) {
			return true;
		} else {
			throw new Error ( '文件写入失败' . $filename );
		}
	}
	
	// 快速文件数据读取和保存 针对简单类型数据 字符串、数组
	static protected function _f($name,$value='',$path=CACHE_DIR,$effectiveTime=0) {
	    static $_cache = array();
	    $path = rtrim($path,'/').'/';
	    $filename   =   $path.$name.'.cache.php';
	    if('' !== $value) {
	        if(is_null($value)) {
	            // 删除缓存
	            return unlink($filename);
	        }else{
	            // 缓存数据
	            $dir   =  dirname($filename);
	            // 目录不存在则创建
	            if(!is_dir($dir))  mkdir($dir);
	            return file_put_contents($filename,"<?php\nreturn ".var_export($value,true).";\n?>");
	        }
	    }
	    if(isset($_cache[$name])) return $_cache[$name];
	    // 获取缓存数据
	    if(is_file($filename)) {
	    	if($effectiveTime && filemtime($filename)+intval($effectiveTime)<CURRENT_TIME){
		    	return false;
		    }
	        $value   =  include $filename;
	        $_cache[$name]   =   $value;
	    }else{
	        $value  =   false;
	    }
	    return $value;
	}
}