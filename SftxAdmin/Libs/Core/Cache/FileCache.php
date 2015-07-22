<?php
class Cache_FileCache {
	/**
	 * Cache_FileCache
	 * @var Cache_FileCache
	 */
	private static $_Instance=null;
	
	private function __construct(){
		
	}
	
	/**
	 * @return Help_Memcache
	 */
	public static function getInstance() {
		if (self::$_Instance == null) {
			self::$_Instance = new self ();
		}
		return self::$_Instance;
	}
	
}