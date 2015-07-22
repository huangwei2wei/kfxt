<?php
/**
 * faq搜索类
 * @author php-朱磊
 *
 */
class Util_FaqSearch extends Base {
	/**
	 * SphinxClient
	 * @var SphinxClient
	 */
	private $_sphinx;
	
	/**
	 * Cache
	 * @var Cache_Faq
	 */
	private $_CacheFaq;
	
	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;
	
	/**
	 * faq状态3:通用,2:游戏内,3:官网
	 * @var int
	 */
	private $_faqStatus=null;
	
	public function __construct(){
		$this->_initSphinx();
		$this->_initCache();
	}
	
	/**
	 * 设置状态
	 * @param int $status
	 */
	public function setFaqStatus($status){
		$this->_faqStatus=$status;
	}
	
	/**
	 * 初始化缓存
	 */
	private function _initCache(){
		$this->_CacheFaq=$this->_getGlobalData('Cache_Faq','object');
	}
	
	/**
	 * 初始化搜索引擎
	 */
	private function _initSphinx(){
		$this->_loadCore('Help_SphinxClient');
		$this->_sphinx=new SphinxClient();
		$this->_sphinx->SetServer(SPHINX_HOST,SPHINX_PORT);
		$this->_sphinx->SetConnectTimeout(5);	//连接时间
		$this->_sphinx->SetArrayResult(true);
		$this->_sphinx->SetMaxQueryTime(10);		//设置最大超时时间
		$this->_sphinx->SetMatchMode(SPH_MATCH_ANY);	//匹配模式
	}
	
	/**
	 * 设置limit
	 * @param int $page    第几页
	 * @param int $pageSize 每页多少条
	 */
	public function setLimit($page,$pageSize=PAGE_SIZE){
		$page=abs(intval($page));
		$pageSize=abs(intval($pageSize));
		if ($page!=0)$page--;
		$begin=$page*$pageSize;
		$this->_sphinx->SetLimits($begin,$pageSize);
	}
	
	/**
	 * 搜索faq
	 * @param string $keywords 搜索的字符串
	 * @param int $gameId	游戏ID
	 * @param int $langId  语言
	 * @param int $kindId	分类ID
	 */
	public function search($keywords,$gameId=null,$langId=NULL,$kindId=null){
		if (is_numeric($langId))$this->_sphinx->SetFilter('lang_id',array($langId),false);
		if (is_numeric($gameId))$this->_sphinx->SetFilter('game_type_id',array($gameId),false);
		if (is_numeric($kindId))$this->_sphinx->SetFilter('kind_id',array($kindId),false);
		if (is_numeric($this->_faqStatus))$this->_sphinx->SetFilter('status',array($this->_faqStatus),true);
		$result=$this->_sphinx->Query($keywords);
		$retResult=array(
			'data'=>$this->_getResult($result['matches']),
			'info'=>array('total'=>$result['total'],'total_found'=>$result['total_found'],'time'=>$result['time'],'words'=>$result['words']));
		return $retResult;
	}
	
	
	/**
	 * 翻译结果集,变成可用的数据
	 * @param array $ids
	 */
	private function _getResult($ids){
		if (!count($ids))return false;
		$resultArr=array();
		foreach ($ids as $id){
			array_push($resultArr,$this->_CacheFaq->getId($id['id']));			
		}
		return $resultArr;
	}
	
	
	
	
	
	
}