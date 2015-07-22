<?php
class Help_Memcache {
	/**
	 * Memcache
	 * @var Memcache
	 */
	private $_memcache;
	
	/**
	 * Help_Memcache
	 * @var Help_Memcache
	 */
	private static $_Instance;
	
	private function __construct(){
		$this->_memcache->connect(MEMCACHE_ADDRESS,MEMCACHE_PORT);		//连接memcache
		
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
	
	/**
	 * 增加值
	 * @param int $key	key值
	 * @param unknown_type $var  存储的数据
	 * @param int $expire  缓存时间
	 * @return boolean
	 */
	public function add(int $key,$var,int $expire=30){
		return $this->_memcache->add($key,$var,MEMCACHE_COMPRESSED,$expire);
	}
	
	/**
	 * 设置值
	 * @param string $key key值
	 * @param unknown_type $var 存储的数据
	 * @param int $expire 缓存时间
	 * @return boolean
	 */
	public function set(string $key,$var,int $expire=30){
		return $this->_memcache->set($key,$var,MEMCACHE_COMPRESSED,$expire);
	}
	
	/**
	 * 替换值
	 * @param string $key key值
	 * @param unknown_type $var 存储的数据
	 * @param int $expire 缓存时间
	 * @return boolean
	 */
	public function replace(string $key,$var,int $expire=30){
		return $this->_memcache->replace($key,$var,MEMCACHE_COMPRESSED,$expire);
	}
	
	public function get($key){
		return $this->_memcache->get($key);
	}
	
	/**
	 * 关闭服务器连接
	 * @return void
	 */
	public function close(){
		$this->_memcache->close();
	}
	
	/**
	 * 清除所有缓存
	 */
	public function flush(){
		return $this->_memcache->flush();
	}
	
	public function delete($key){
		return $this->_memcache->delete($key);
	}
	
	
	
	
	
}