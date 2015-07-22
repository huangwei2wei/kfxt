<?php
define('FAQ_ID','faq_id_');
/**
 * Cache_Faq
 * @author php-朱磊
 *
 */
class Cache_Faq extends Cache {
	
	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;
	
	/**
	 * 缓存获取指定faq
	 * @param $id
	 * @param $expire
	 */
	public function getId($id,$expire=NULL){
		if ($expire==null)$expire=3600*24;	//默认缓存24小时
		$key=FAQ_ID.$id;
		$result=$this->get(FAQ_ID.$id);
		if (!$result){
			$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
			$result=$this->_modelPlayerFaq->findById($id);
			$this->set($key,$result,$expire);
		}
		return $result;
	}
	
	/**
	 * 分页api,
	 */
	public function pageList(){
		
	}
	
	
}