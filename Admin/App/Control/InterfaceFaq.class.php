<?php
Tools::import('Control_Interface');
/**
 * faq接口
 * @author php-朱磊
 */
class Control_InterfaceFaq extends ApiInterface {
	
	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;

	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;

	/**
	 * Model_StatsFaq
	 * @var Model_StatsFaq
	 */
	private $_modelStatsFaq;

	/**
	 * Help_SqlSearch
	 * @var Help_SqlSearch
	 */
	private $_helpSqlSearch;

	/**
	 * faq评价表
	 * @var Model_PlayerFaqLog
	 */
	private $_modelPlayerFaqLog;

	/**
	 * faq搜索引擎类
	 * @var Util_FaqSearch
	 */
	private $_utilFaqSearch;
	
	/**
	 * Cache_Faq
	 * @var Cache_Faq
	 */
	private $_cacheFaq;

	/**
	 * 语言
	 * @var array
	 */
	private $_lang;

	/**
	 * 当前语言
	 * @var string
	 */
	private $_curLang;

	public function __construct(){
		parent::__construct();
		$this->_lang=$this->_getGlobalData('lang');
		if (!$_REQUEST['lang'] || !array_key_exists($_REQUEST['lang'],$this->_lang)){
			$this->_curLang=1;//默认简体
		}else {
			$this->_curLang=$_REQUEST['lang'];
		}
	}

	/**
	 * faq分类
	 */
	public function actionType(){
		$gameTypeId=abs(intval($_REQUEST['game_id']));
		$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
		$dataList=$this->_modelPlayerKindFaq->findByGameTypeId($gameTypeId,$this->_curLang);
		$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_Type','data'=>$dataList));
	}

	/**
	 * faq列表
	 */
	public function actionList(){
		$this->_loadCore('Help_SqlSearch');
		$this->_helpSqlSearch=new Help_SqlSearch();
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$kindId=abs(intval($_GET['kind_id']));
		$page=$_GET['page']?abs(intval($_GET['page'])):abs(intval($_GET['p']));
		if (!$page)$page=1;
		$pageSize=abs(intval($_GET['ps']));
		if (!$pageSize)$pageSize=8;
		$this->_helpSqlSearch->set_tableName($this->_modelPlayerFaq->tName());
		$this->_helpSqlSearch->set_field('Id,ratio,game_type_id,kind_id,question');
		$this->_helpSqlSearch->set_conditions("kind_id={$kindId}");
		$this->_helpSqlSearch->set_conditions('status!=1');
		$this->_helpSqlSearch->setPageLimit($page,$pageSize);
		$sql=$this->_helpSqlSearch->createSql();
		$dataList=$this->_modelPlayerFaq->select($sql);
		if (false === $dataList)$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));//无数据返回出错.
		$count=$this->_modelPlayerFaq->findCount("kind_id={$kindId} and status!=1");
		$toalPgae=ceil($count/$pageSize);
		$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_List','data'=>array('page'=>array('total'=>$count,'total_page'=>$toalPgae,'page'=>$page,'page_size'=>$pageSize),'list'=>$dataList)));
	}

	/**
	 * faq详细
	 */
	public function actionDetail(){
		$id=abs(intval($_GET['id']));
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$data=$this->_modelPlayerFaq->findById($id);
		
		$data['answer']=$data['answer_g'];
		unset($data['answer_g'],$data['answer_s']);
		if(empty($data['answer'])){
			unset($data['answer']);
		}
		if($data){
			$this->_modelStatsFaq=$this->_getGlobalData('Model_StatsFaq','object');
			$statArr=array('game_type_id'=>$data['game_type_id'],'source'=>2,'kind_id'=>$data['kind_id'],'lang_id'=>$data['lang_id']);
			$this->_modelStatsFaq->add($statArr);	//增加统计量
			$this->_modelPlayerFaq->execute("update {$this->_modelPlayerFaq->tName()} set `ratio`=ratio+1 where Id={$id}"); //增加点击率
			$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_Detail','data'=>$data));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));//无数据返回出错
		}
	}

	/**
	 * 最高点击率faq
	 */
	public function actionHot(){
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$pageSize=abs(intval($_REQUEST['ps']));
		$gameTypeId=abs(intval($_REQUEST['game_id']));
		$dataList=$this->_modelPlayerFaq->findHotList($gameTypeId,$pageSize,$this->_curLang);
		if ($dataList !== false){
			$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_Hot','data'=>$dataList));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));
		}
	}

	/**
	 * searchFAQ
	 */
	public function actionSearch(){
		$this->_utilFaqSearch=$this->_getGlobalData('Util_FaqSearch','object');
		$keyWords=urldecode($_REQUEST['keywords']);
		if(empty($keyWords)){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'KeyWords Empty','data'=>null));
		}
		$gameTypeId=Tools::coerceInt($_REQUEST['game_id']);
		$page=Tools::coerceInt($_REQUEST['p']);
		if (!$page)$page=1;
		$pageSize=Tools::coerceInt($_REQUEST['ps']);
		if (!$pageSize)$pageSize=8;
		$this->_utilFaqSearch->setFaqStatus(1);	//设置不需要官网的
		$this->_utilFaqSearch->setLimit($page,$pageSize);
		$data=$this->_utilFaqSearch->search($keyWords,$gameTypeId,$this->_curLang);
		
		if (!is_array($data)){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));
		}
		$dataList = array();
		if(is_array($data['data'])){
			foreach ($data['data'] as $key=>&$list){
				if (is_array($list))unset($data['data'][$key]['answer_g'],$data['data'][$key]['answer_s']);
			}
			$dataList = $data['data'];
		}
		$toalPgae=ceil($data['info']['total']/$pageSize);
		$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_Search','data'=>array('page'=>array('total'=>$data['info']['total'],'total_page'=>$toalPgae,'page'=>$page,'page_size'=>$pageSize),'list'=>$dataList)));
	}

	/**
	 * faq评价
	 */
	public function actionEvaluate(){
		$this->_modelPlayerFaqLog=$this->_getGlobalData('Model_PlayerFaqLog','object');
		$id=abs(intval($_REQUEST['id']));
		$this->_cacheFaq=$this->_getGlobalData('Cache_Faq','object');
		$faq=$this->_cacheFaq->getId($id);
		if(!$faq){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'faq error','data'=>null));
			return;
		}
		$addArr=array();
		$addArr['game_type_id']=$faq['game_type_id'];
		$addArr['lang_id']=$faq['lang_id'];
		$addArr['source']=2;
		$addArr['date_create']=CURRENT_TIME;
		
		$addArr['player_faq_id']=$id;
		$addArr['faq_whether']=abs(intval($_REQUEST['faq_whether']));
		if ($addArr['faq_whether']==0){
			$addArr['faq_opinion']=abs(intval($_REQUEST['faq_opinion']));
			if ($addArr['faq_opinion']==0)$addArr['faq_opinion']=rand(1,4);
			if ($addArr['faq_opinion']==5){
				$addArr['content']=$_REQUEST['content'];
			}
		}
		if ($this->_modelPlayerFaqLog->add($addArr)){
			$this->_returnAjaxJson(array('status'=>1,'info'=>'InterfaceFaq_Evaluate','data'=>null));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'insert error','data'=>null));
		}
	}
	
	private function _writeLog(){
		$msg=array();
		foreach ($_GET as $key=>$value){
			array_push($msg,"GET {$key} ： {$value}");
		}
		foreach ($_POST as $key=>$value){
			array_push($msg,"POST {$key} ： {$value}");
		}
		Tools::addLog(Tools::formatLog($msg),true);
	}


}