<?php
/**
 * faq接口
 * @author php-朱磊
 */
class Control_InterfaceFaq extends Control {
	
	/**
	 * key
	 * @var string
	 */
	private $_key = TAKE_KEY;
	
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
	 * Help_SqlSearch
	 * @var Help_SqlSearch
	 */
	private $_helpSqlSearch;
	
	/**
	 * faq评价表
	 * @var Model_PlayerFaqLog
	 */
	private $_modelPlayerFaqLog;
	
	public function __construct(){
		if (! $this->_initialize ())//如果不通过验证将退出返回出错数据
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'ERROR', 'data' => null ) );
	}
	
	/**
	 * 是否通过验证
	 */
	private function _initialize() {
		$sign = $_REQUEST ['_sign'];
		$verifyCode = $_REQUEST ['_verifycode'];
		if (isset ( $sign ) && isset ( $verifyCode )) {
			if (md5 ( $this->_key . $verifyCode ) == $sign) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * faq分类
	 */
	public function actionType(){
		$gameTypeId=abs(intval($_GET['game_id']));
		$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
		$dataList=$this->_modelPlayerKindFaq->findByGameTypeId($gameTypeId);
		$this->_returnAjaxJson(array('status'=>1,'info'=>'','data'=>$dataList));
	}
	
	/**
	 * faq列表
	 */
	public function actionList(){
		$this->_loadCore('Help_SqlSearch');
		$this->_helpSqlSearch=new Help_SqlSearch();
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$kindId=abs(intval($_GET['kind_id']));
		$page=abs(intval($_GET['page']));
		if (!$page)$page=1;
		$pageSize=abs(intval($_GET['ps']));
		if (!$pageSize)$pageSize=8;
		$this->_helpSqlSearch->set_tableName($this->_modelPlayerFaq->tName());
		$this->_helpSqlSearch->set_field('Id,ratio,game_type_id,kind_id,question');
		$this->_helpSqlSearch->set_conditions("kind_id={$kindId}");
		$this->_helpSqlSearch->setPageLimit($page,$pageSize);
		$sql=$this->_helpSqlSearch->createSql();
		$dataList=$this->_modelPlayerFaq->select($sql);
		if (!$dataList)$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));//无数据返回出错.
		$count=$this->_modelPlayerFaq->findCount("kind_id={$kindId}");
		$toalPgae=ceil($count/$pageSize);
		$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>array('page'=>array('total'=>$count,'total_page'=>$toalPgae,'page'=>$page,'page_size'=>$pageSize),'list'=>$dataList)));
	}
	
	/**
	 * faq详细
	 */
	public function actionDetail(){
		$id=abs(intval($_GET['id']));
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$data=$this->_modelPlayerFaq->findById($id);
		if($data){
			if (strpos("\\",$data['question']))$data['question']=str_replace('\\','',$data['question']);
			if (strpos("\\",$data['answer']))$data['answer']=str_replace('\\','',$data['answer']);
			$this->_modelPlayerFaq->update(array('ratio'=>'ratio+1'),"Id={$id}");	//增加点击率
			$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>$data));
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
		if (!$pageSize)$this->_returnAjaxJson(array('status'=>0,'info'=>'pageSize null','data'=>null));
		if (!$gameTypeId)$this->_returnAjaxJson(array('status'=>0,'info'=>'game_id null','data'=>null));
		$dataList=$this->_modelPlayerFaq->findHotList($gameTypeId,$pageSize);
		if ($dataList){
			$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>$dataList));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));
		}
	}
	
	/**
	 * searchFAQ
	 */
	public function actionSearch(){
		$this->_loadCore('Help_SqlSearch');
		$this->_helpSqlSearch=new Help_SqlSearch();
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$gameTypeId=abs(intval($_REQUEST['game_id']));
		$keyWords=$_REQUEST['keywords'];
		$page=abs(intval($_REQUEST['p']));
		if (!$page)$page=1;
		$pageSize=abs(intval($_REQUEST['ps']));
		if (!$pageSize)$pageSize=8;
		$this->_helpSqlSearch->set_tableName($this->_modelPlayerFaq->tName());
		$this->_helpSqlSearch->set_field('Id,ratio,game_type_id,kind_id,question');
		$this->_helpSqlSearch->set_conditions("game_type_id={$gameTypeId}");
		$this->_helpSqlSearch->set_conditions("question like '%{$keyWords}%'");
		$this->_helpSqlSearch->setPageLimit($page,$pageSize);
		$conditions=$this->_helpSqlSearch->get_conditions();
		$sql=$this->_helpSqlSearch->createSql();
		$dataList=$this->_modelPlayerFaq->select($sql);
		if (!$dataList)$this->_returnAjaxJson(array('status'=>0,'info'=>'NO_FIND','data'=>null));//无数据返回出错.
		$count=$this->_modelPlayerFaq->findCount($conditions);
		$toalPgae=ceil($count/$pageSize);
		$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>array('page'=>array('total'=>$count,'total_page'=>$toalPgae,'page'=>$page,'page_size'=>$pageSize),'list'=>$dataList)));
	}
	
	/**
	 * faq评价
	 */
	public function actionEvaluate(){
		$this->_modelPlayerFaqLog=$this->_getGlobalData('Model_PlayerFaqLog','object');
		$addArr=array();
		$addArr['date_create']=CURRENT_TIME;
		$id=abs(intval($_POST['id']));
		$addArr['player_faq_id']=$id;
		$addArr['faq_whether']=abs(intval($_POST['faq_whether']));
		if ($addArr['faq_whether']==0){
			$addArr['faq_opinion']=abs(intval($_POST['faq_opinion']));
			if ($addArr['faq_opinion']==5){
				$addArr['content']=$_POST['content'];
			}
		}
		if ($this->_modelPlayerFaqLog->add($addArr)){
			$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>null));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'insert error','data'=>null));
		}
	}
	
	
}