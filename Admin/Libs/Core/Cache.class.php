<?php
/**
 * 缓存类
 * @author php-朱磊
 *
 */
class Cache extends Base {
	
	/**
	 * 操作句柄
	 * @var string
	 */
	protected $_handler = array ();
	
	/**
	 * 读入次数
	 * @var int
	 */
	protected $_qCount = 0;
	
	/**
	 * 写入次数.
	 * @var int
	 */
	protected $_wCount = 0;
	
	/**
	 * 返回缓存句柄
	 * @param string $type
	 */
	protected function _getHandler($type) {
		if (! is_object ( $this->_handler [$type] )) {
			$cacheClass = "Cache_{$type}";
			$this->_loadCore ( $cacheClass );
			$this->_handler [$type] = new $cacheClass ();
		}
		return $this->_handler [$type];
	}
	
	/**
	 * 获取数据
	 * @param string $key
	 * @param string $type
	 */
	public function get($key, $type = CACHE_TYPE) {
		$type = ucwords ( $type );
		$this->_qCount ++;
		$key = CACHE_PREFIX . $key;
		return $this->_getHandler ( $type )->get ( $key );
	}
	
	/**
	 * 设置key
	 * @param string $key
	 * @param string $value
	 * @param int $expire
	 * @param string $type
	 */
	public function set($key, $value, $expire, $type = CACHE_TYPE) {
		$type = ucwords ( $type );
		$this->_wCount ++;
		$key = CACHE_PREFIX . $key;
		return $this->_getHandler ( $type )->set ( $key, $value, $expire );
	}
	
	/**
	 * 删除key
	 * @param string $key
	 * @param string $type
	 */
	public function rm($key, $type = CACHE_TYPE) {
		$key = CACHE_PREFIX . $key;
		return $this->_getHandler ( $type )->rm ( $key );
	}
	
	/**
	 * 清除缓存
	 * @param string $type
	 */
	public function clear($type) {
		$type = ucwords ( $type );
		return $this->_getHandler ( $type )->clear ( $type );
	}
	
	/**
	 * @return the $_qCount
	 */
	public function get_qCount() {
		return $this->_qCount;
	}
	
	/**
	 * @return the $_wCount
	 */
	public function get_wCount() {
		return $this->_wCount;
	}

}