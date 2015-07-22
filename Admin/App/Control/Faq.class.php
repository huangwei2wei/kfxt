<?php
/**
 * FAQ模块
 * @author php-朱磊
 *
 */
class Control_Faq extends Control {

	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;

	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;
	
	/**
	 * Model_GameFaq
	 * @var Model_GameFaq
	 */
	private $_modelGameFaq;
	
	/**
	 * Model_GameKindFaq
	 * @var Model_GameKindFaq
	 */
	private $_modelGameKindFaq;

	/**
	 * Model_ServiceFaq
	 * @var Model_ServiceFaq
	 */
	private $_modelServiceFaq;

	/**
	 * Model_ServiceKindFaq
	 * @var Model_ServiceKindFaq
	 */
	private $_modelServiceKindFaq;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Model_PlayerFaqKeywords
	 * @var Model_PlayerFaqKeywords
	 */
	private $_modelPlayerFaqKeywords;
	
	/**
	 * Model_FaqLog
	 * @var Model_FaqLog
	 */
	private $_modelFaqLog;
	
	/**
	 * faq分类
	 * @var array
	 */
	private $_faqKind;
	
	/**
	 * 语言
	 * @var array
	 */
	private $_lang;
	
	/**
	 * 简繁互转
	 * @var Util_Utf8s2f
	 */
	private $_utilUtf8s2f;
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_modelPlayerFaq = $this->_getGlobalData ( 'Model_PlayerFaq', 'object' );
		$this->_modelPlayerKindFaq = $this->_getGlobalData ( 'Model_PlayerKindFaq', 'object' );
		$this->_modelServiceFaq = $this->_getGlobalData ( 'Model_ServiceFaq', 'object' );
		$this->_modelServiceKindFaq = $this->_getGlobalData ( 'Model_ServiceKindFaq', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );

		#------更改faq主分类------#
		$this->_faqKind = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$this->_faqKind [0] = '网上银行';
		
		#------更改faq主分类------#
	}

	private function _createUrl() {
		$this->_url ['Faq_ServiceAdd'] = Tools::url ( CONTROL, 'ServiceAdd' );
		$this->_url ['Faq_ServiceKindAdd'] = Tools::url ( CONTROL, 'ServiceKindAdd' );
		$this->_url ['Faq_ServiceCreateCache'] = Tools::url ( CONTROL, 'ServiceCreateCache' );
		
		
		$this->_url ['Faq_Player_ratio']=Tools::url(CONTROL,'Player',array('doaction'=>'ratio'));//修改FAQ点击率
		$this->_url ['Faq_Player_recount']=Tools::url(CONTROL,'PlayerKind',array('doaction'=>'recount'));	//更新FAQ分类count
		
		$this->_url ['Faq_Player_add_cn']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>1));	//增加faq
		$this->_url ['Faq_Player_add_en']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>2));	//增加faq
		$this->_url ['Faq_Player_add_sk']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>3));	//增加faq
		$this->_url ['Faq_Player_add_jp']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>4));	//增加faq
		$this->_url ['Faq_Player_add_vn']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>5));	//增加faq
		$this->_url ['Faq_Player_add_tc']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>6));	//增加faq	
		$this->_url ['Faq_Player_add_thai']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>7));	//增加faq	
		$this->_url ['Faq_Player_add_india']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>8));	//增加faq	
		$this->_url ['Faq_Player_add_Indonesia']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>9));
		$this->_url ['Faq_Player_add_m_cn']=Tools::url(CONTROL,'Player',array('doaction'=>'add','lang'=>10));
		
		$this->_url ['Faq_Player_kindindex_cn']=Tools::url(CONTROL,'PlayerKind',array('lang'=>1));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_en']=Tools::url(CONTROL,'PlayerKind',array('lang'=>2));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_sk']=Tools::url(CONTROL,'PlayerKind',array('lang'=>3));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_jp']=Tools::url(CONTROL,'PlayerKind',array('lang'=>4));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_vn']=Tools::url(CONTROL,'PlayerKind',array('lang'=>5));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_tc']=Tools::url(CONTROL,'PlayerKind',array('lang'=>6));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_thai']=Tools::url(CONTROL,'PlayerKind',array('lang'=>7));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_india']=Tools::url(CONTROL,'PlayerKind',array('lang'=>8));	//faq分类显示页面
		$this->_url ['Faq_Player_kindindex_Indonesia']=Tools::url(CONTROL,'PlayerKind',array('lang'=>9));
		$this->_url ['Faq_Player_kindindex_m_cn']=Tools::url(CONTROL,'PlayerKind',array('lang'=>10));
		$this->_url ['Faq_Player_hotkeywords']=Tools::url(CONTROL,'Player',array('doaction'=>'hotkeywords'));	//火热关键字
		$this->_url ['Faq_Player_evManage']=Tools::url(CONTROL,'Player',array('doaction'=>'ev_manage'));//FAQ评价管理
		$this->_url ['Faq_Player_Sort']=Tools::url(CONTROL,'Player',array('doaction'=>'sort'));	//排序
		
		
		
		$this->_url ['uploadImgUrl'] = Tools::url ( 'Default', 'ImgUpload' );
		$this->_url ['updateGameFapImgUrl']=Tools::url('Default','ImgUpload',array('type'=>'GameFaq'));
		$this->_view->assign ( 'url', $this->_url );
	}
	#--------------------------------------客服中心FAQ--------------------------------------#
	
	
	/**
	 * FAQ分类管理
	 */
	public function actionPlayerKind(){
		$this->_lang=$this->_getGlobalData('lang');
		$_REQUEST['lang']=$_REQUEST['lang']?$_REQUEST['lang']:1;	//默认简体:1
		$this->_view->assign('curLang',$_REQUEST['lang']);
		switch ($_GET['doaction']){
			case 'add' :{//增加
				$this->_kindAdd();
				return ;
			}
			case 'edit' :{//编辑 
				$this->_kindEdit();
				return ;
			}
			case 'del' :{//删除 
				$this->_kindDel();
				return ;
			}
			case 'recount' :{//更新 count
				$this->_kindRecount();
				return ;
			}
			default:{//默认显示页面
				$this->_kindIndex();
				return ;
			}
		}
	}
	
	private function _kindRecount(){
		$kindId=Tools::coerceInt($_GET['Id']);
		$data=$this->_modelPlayerKindFaq->recount($kindId);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	} 
	
	public function actionPlayer(){
		$this->_lang=$this->_getGlobalData('lang');
		$_REQUEST['lang']=$_REQUEST['lang']?$_REQUEST['lang']:1;	//默认简体:1
		$this->_view->assign('curLang',$_REQUEST['lang']);
		switch ($_GET['doaction']){
			case 'add' :{//增加FAQ
				$this->_playerAdd();
				return ;
			}
			case 'edit' :{//编辑FAQ
				$this->_playerEdit();
				return ;	
			}
			case 'del' :{//删除FAQ
				$this->_playerDel();
				return ;
			}
			case 'hotkeywords' :{//热门关键字管理
				$this->_hotKeyWords();
				return ;
			}
			case 'ratio' :{//点击率
				$this->_ratio();
				return ;
			}
			case 'evaluation' :{//详细
				$this->_evaluation();
				return ;
			}
			case 'ev_manage' :{	//FAQ评价管理
				$this->_evManage();
				return ;
			}
			case 'sort' :{
				$this->_playerSort();
				return ;
			}
			default:{
				return ;
			}
		}
	}
	
	/**
	 * 排序
	 */
	private function _playerSort(){
		$info=$this->_modelPlayerKindFaq->sort($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _playerAdd(){
		if ($this->_isPost ()) {
			$_POST['lang_id']=$_REQUEST['lang']?$_REQUEST['lang']:1;	//默认中文 
			$_POST['question'] = trim($_POST['question']);
			if ($this->_modelPlayerFaq->add ( $_POST )) {
				$faqCount = $this->_modelPlayerFaq->findCount("kind_id={$_POST['kind_id']} and status !=1");
				$this->_modelPlayerKindFaq->update(array('count'=>$faqCount),"Id={$_POST['kind_id']}");	//数量加1
				$this->_utilMsg->showMsg ( false,1,Tools::url(CONTROL,ACTION,array('doaction'=>'add','game_type_id'=>$_POST['game_type_id'],'kind_id'=>$_POST['kind_id'],'lang'=>$_REQUEST['lang'])) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_ERROR',__CLASS__), - 2 );
			}
		} else {
			$selectedArr=array('game_type_id'=>$_GET['game_type_id'],'kind_id'=>$_GET['kind_id']);
			$gameTypes = $this->_faqKind;
			$kindList = $this->_modelPlayerKindFaq->findAll ();
			$this->_view->assign ( 'gameType',  $this->_faqKind );
			$this->_view->assign ( 'kindList', json_encode($kindList) );
			$this->_utilMsg->createNavBar();
			$this->_view->assign('faqStatus',$this->_modelPlayerFaq->getFaqStatus());
			$this->_view->set_tpl(array('body'=>'Faq/PlayerAdd.html'));
			$this->_view->assign('selectedArr',$selectedArr);
			$this->_view->display ();
		}
	}
	
	private function _playerEdit(){
		if ($this->_isPost ()) {
			$updateArr = array ('game_type_id'=>intval($_POST['game_type_id']),'kind_id'=>intval($_POST['kind_id']),'question' =>trim($_POST ['question']), 'answer_g' => $_POST ['answer_g'],'answer_s' => $_POST ['answer_s'],'status'=>intval($_POST['status']),'check_status'=>0 );
			if ($this->_modelPlayerFaq->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( false, 1, Tools::url ( CONTROL, 'PlayerIndex', array ('kind_id' => $_POST ['kind_id'], 'game_type_id' => $_POST ['game_type_id'],'lang'=>$_REQUEST['lang'] ) ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('EDIT_ERROR',__CLASS__), - 2 );
			}
		} else {
			$faqList = $this->_modelPlayerFaq->findById ( $_GET ['Id'] );
			$faqList ['question'] = str_replace ( "\\", '', $faqList ['question'] );
			$faqList ['answer_g'] = str_replace ( "\\", '', $faqList ['answer_g'] );
			$faqList ['answer_s'] = str_replace ( "\\", '', $faqList ['answer_s'] );
			$gameTypes = $this->_faqKind;
			$kindList = $this->_modelPlayerKindFaq->findAll ();
			$this->_view->assign ( 'gameType', $this->_faqKind );
			$this->_view->assign ( 'kindList', json_encode($kindList) );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_view->assign ( 'faqList', $faqList );
			$this->_view->assign('faqStatus',$this->_modelPlayerFaq->getFaqStatus());
			$this->_view->set_tpl(array('body'=>'Faq/PlayerEdit.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	private function _playerDel(){
		if ($this->_modelPlayerFaq->deleteById ( $_GET ['Id'] )) {
			$faqCount = $this->_modelPlayerFaq->findCount("kind_id={$_GET['kind_id']} and status !=1");
			$this->_modelPlayerKindFaq->update(array('count'=>$faqCount),"Id={$_GET['kind_id']}");	//数量减1
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('DEL_ERROR',__CLASS__),-2);
		}
	}
	
	private function _hotKeyWords(){
		$this->_modelPlayerFaqKeywords=$this->_getGlobalData('Model_PlayerFaqKeywords','object');
		$data=$this->_modelPlayerFaqKeywords->add($_POST);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}
	
	private function _kindAdd(){
		if ($this->_modelPlayerKindFaq->add ( array ('game_type_id' => $_GET ['game_type_id'], 'name' => $_GET ['data'],'lang_id'=>$_REQUEST['lang'] ) )) {
			$this->_returnAjaxJson( array ('status' => 1 ) );
		}
	}
	
	private function _kindEdit(){
		if ($this->_modelPlayerKindFaq->update ( array ('name' => $_GET ['data'] ), "Id={$_GET['Id']}" )) {
			$this->_returnAjaxJson ( array ('status' => 1 ) );
		}
	}
	
	private function _kindDel(){
		if ($this->_modelPlayerKindFaq->deleteById ( $_GET ['Id'] )) {
			$this->_modelPlayerFaq->deleteByKindId($_GET['Id']);	//删除所有这个分类的FAQ
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('DEL_ERROR',__CLASS__), - 2 );
		}
	}
	
	private function _kindIndex(){
		$this->_modelPlayerFaqKeywords=$this->_getGlobalData('Model_PlayerFaqKeywords','object');
		$dataList = array ();
		foreach ( $this->_faqKind as $key => $value ) {
			$childList = $this->_modelPlayerKindFaq->findByGameTypeId ( $key,$_REQUEST['lang'] );
			if (count ( $childList )) {
				foreach ( $childList as &$childValue ) {
					$childValue ['url_edit'] = Tools::url ( CONTROL, 'PlayerKind', array ('Id' => $childValue ['Id'],'doaction'=>'edit','lang'=>$_REQUEST['lang'] ) );
					$childValue ['url_del'] = Tools::url ( CONTROL, 'PlayerKind', array ('Id' => $childValue ['Id'] ,'doaction'=>'del','lang'=>$_REQUEST['lang']) );
					$childValue ['url_recount']=Tools::url(CONTROL,'PlayerKind',array('Id'=>$childValue['Id'],'doaction'=>'recount'));
					$childValue ['url_faq_copy_type']=Tools::url(CONTROL,'FaqCopy',array('Id'=>$childValue['Id'],'doaction'=>'faqtype'));
				}
			}
			$dataList [] = array (
							'game_type' => $value,
							'game_type_id' => $key,
							'url_add' => Tools::url ( CONTROL, 'PlayerKind', array ('game_type_id' => $key ,'doaction'=>'add' ,'lang'=>$_REQUEST['lang']) ), 
							'url_faq_copy_game' => Tools::url ( CONTROL, 'FaqCopy', array ('game_type_id' => $key , 'doaction'=>'game' ,'lang'=>$_REQUEST['lang']) ), 
							'childList' => $childList ? $childList : null );
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_view->assign('keywords',$this->_modelPlayerFaqKeywords->findAll());
		$this->_view->set_tpl(array('body'=>'Faq/PlayerKindIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	private function _ratio(){
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$data=$this->_modelPlayerFaq->ratioEdit($_POST);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}
	
	/**
	 * 评率
	 */
	private function _evaluation(){
		 $id=Tools::coerceInt($_GET['Id']);
		 $this->_modelFaqLog=$this->_getGlobalData('Model_FaqLog','object');
		 $dataList=$this->_modelFaqLog->getFaqEvaluation($id);
		 $dataList['good']=$dataList['good']?$dataList['good']:0;
		 $dataList['bad']=$dataList['bad']?$dataList['bad']:0;
		 if ($dataList['opinion']){
		 	$faqOpinion=$this->_getGlobalData('faq_opinion');
		 	$faqOpinion=array_flip($faqOpinion);
		 	foreach ($faqOpinion as $key=>&$value){
		 		$value=intval($dataList['opinion'][$value]);
		 	}
		 	$dataList['faq_opinion']=$faqOpinion;
		 }
		 $this->_returnAjaxJson(array('status'=>1,'msg'=>null,'data'=>$dataList));
	}
	
	/**
	 * 评价管理
	 */
	private function _evManage(){
		$kind=$this->_modelPlayerKindFaq->findAll();
		$kind=Model::getTtwoArrConvertOneArr($kind,'Id','name');
		$faqOpinion=$this->_getGlobalData('faq_opinion');
		$lang=$this->_getGlobalData('lang');
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$source=Tools::getLang('EVMANAGE_SOURCE',__CLASS__);
		$whether=Tools::getLang('EVMANAGE_WHETHER',__CLASS__);
		$this->_modelFaqLog=$this->_getGlobalData('Model_FaqLog','object');
		$selected=array();
		$selected['game_type_id']=$_GET['game_type_id'];
		$selected['source']=$_GET['source'];
		$selected['whether']=$_GET['whether'];
		$selected['time']=array('start'=>$_GET['start_time'],'end'=>$_GET['end_time']);
		$selected['faq_id']=$_GET['faq_id'];
		$selected['faq_opinion']=$_GET['faq_opinion'];
		$selected['lang']=$_GET['lang'];
		$data=$this->_modelFaqLog->findPage($selected,$_GET['page']);
		foreach ($data['dataList'] as &$list){
			$list['word_game_type_id']=$gameTypes[$list['game_type_id']];
			$list['word_lang_id']=$lang[$list['lang_id']];
			$list['word_kind_id']=$kind[$list['kind_id']];
			$list['date_create']=date('Y-m-d H:i:s',$list['date_create']);
			$list['word_faq_whether']=$whether[$list['faq_whether']];
			$list['url_detail']=Tools::url(CONTROL,ACTION,array('doaction'=>'edit','Id'=>$list['player_faq_id'],'disabled'=>1));
			if (!$list['faq_whether'])$list['opinion']=$list['content']?$list['content']:$faqOpinion[$list['faq_opinion']];
		}
		$this->_loadCore('Help_Page');
		$faqOpinion['']=Tools::getLang('ALL','Common');
		$helpPage=new Help_Page(array('total'=>$data['total'],'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign('dataList',$data['dataList']);
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('source',$source);
		$this->_view->assign('whether',$whether);
		$this->_view->assign('faqOpinion',$faqOpinion);
		$this->_view->assign('lang',$lang);
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Faq/PlayerEvManage.html'));
		$this->_view->display();
	}
	

	/**
	 * faq显示页面
	 */
	public function actionPlayerIndex(){
		$this->_lang=$this->_getGlobalData('lang');	//多语言
		$this->_faqKind [''] = Tools::getLang('ALL','Common');
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelPlayerFaq->tName());
		
		$_GET['lang']=$_GET['lang']?$_GET['lang']:1;//默认简体:1
		$helpSqlSearch->set_conditions("lang_id='{$_GET['lang']}'");	//默认语言
		$this->_view->assign('selectedLang',$_GET['lang']);
		
		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
			
			$faqKind=$this->_modelPlayerKindFaq->findByGameTypeId($_GET['game_type_id'],$_GET['lang']);
			$kindList=array();
			foreach ($faqKind as $value){
				$kindList[$value['Id']]=$value['name']."({$value['count']})";
			}
			
			$kindList['']=Tools::getLang('ALL','Common');
			$this->_view->assign('kindList',$kindList);
			if ($_GET['kind_id']){
				$helpSqlSearch->set_conditions("kind_id={$_GET['kind_id']}");
				$this->_view->assign('selectedkindId',$_GET['kind_id']);
			}
			
		}

		if ($_GET['status']!=''){
			$helpSqlSearch->set_conditions("status={$_GET['status']}");
			$this->_view->assign('selectedFaqStatus',$_GET['status']);
		}
		if ($_GET['question']!=''){
			$helpSqlSearch->set_conditions("question like '%{$_GET['question']}%'");
			$this->_view->assign('selectedQuestion',$_GET['question']);
		}
		if ($_GET['answer']!=''){
			$helpSqlSearch->set_conditions("(answer_g like '%{$_GET['answer']}%' or answer_s like '%{$_GET['answer']}%')");
			$this->_view->assign('selectedAnswer',$_GET['answer']);
		}
		
		if($_GET['starttime']!=''){
			$helpSqlSearch->set_conditions("(time>".strtotime($_GET['starttime']).")");
			$this->_view->assign('selectedstarttime',$_GET['starttime']);
		}
		if($_GET['endtime']!=''){
			$helpSqlSearch->set_conditions("(time<".strtotime($_GET['endtime']).")");
			$this->_view->assign('selectedendtime',$_GET['endtime']);
		}
		$faqStatus=$this->_modelPlayerFaq->getFaqStatus();
		$conditions=$helpSqlSearch->get_conditions();
		
		$by=($_GET['by']=='asc')?'desc':'asc';
		$this->_view->assign('curBy',$by);
		if (in_array($_GET['order'],array('ratio','time'))){
			$helpSqlSearch->set_orderBy("{$_GET['order']} {$by}");
		}
		
		$helpSqlSearch->setPageLimit($_GET['page']);
		$sql=$helpSqlSearch->createSql();
		//print_r($sql);
		$dataList=$this->_modelPlayerFaq->select($sql);
		if ($dataList){
			$users=$this->_getGlobalData('user');
			foreach ($dataList as &$list){
				$list['word_game_type_id']=$this->_faqKind[$list['game_type_id']];
				$list['question']=strip_tags($list['question']);
				$list['answer_s']=strip_tags($list['answer_s']);
				if($list["check_status"]!=0){
					$list["check_status"]	=	date("Y-m-d H:i:s",$list["check_status"]);
				}
				$list['answer_g']=strip_tags($list['answer_g']);
				$list['word_status']=$faqStatus[$list['status']];
				$list['word_user_id']=$users[$list['user_id']]['nick_name'];
				$list['time']=date('Y-m-d H:i:s',$list['time']);
				$list['url_edit']=Tools::url(CONTROL,'Player',array('doaction'=>'edit','lang'=>$_REQUEST['lang'],'game_type_id'=>$list['game_type_id'],'kind_id'=>$list['kind_id'],'Id'=>$list['Id']));
				$list['url_del']=Tools::url(CONTROL,'Player',array('doaction'=>'del','lang'=>$_REQUEST['lang'],'game_type_id'=>$list['game_type_id'],'kind_id'=>$list['kind_id'],'Id'=>$list['Id']));
			}
			//print_r($dataList["0"]);
			$this->_view->assign('dataList',$dataList);
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelPlayerFaq->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_view->assign("ajaxurl",Tools::url(CONTROL,'checkfaq'));
//		print_r($this->_lang);
		$this->_view->assign('lang',$this->_lang);
		$this->_view->assign('faqStatus',$this->_modelPlayerFaq->getFaqStatus());
		$this->_view->assign('game_type',$this->_faqKind);
		$this->_view->assign ( 'gameTypeKind', $this->_faqKind );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/*
	 * faq检测
	 * */
	public function actioncheckfaq(){
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		if($this->_modelPlayerFaq->checkfaq($_GET["id"])){
			$returnDate = array('status'=>1,'info'=>NULL,'data'=>date("Y-m-d H:i:s",CURRENT_TIME));
		}else{
			$returnDate = array('status'=>0,'info'=>'数据库操作失败','data'=>NULL);
		}
		$this->_returnAjaxJson($returnDate);
		
	}
	#--------------------------------------客服中心FAQ--------------------------------------#


	#--------------------------------------客服FAQ--------------------------------------#
	
	/**
	 * 显示页面
	 */
	public function actionServiceIndex(){
		switch ($_GET['doaction']){
			case 'list' :{
				return ;
			}
			default:{
				$this->_utilMsg->createNavBar();
				$this->_view->set_tpl(array('body'=>'Faq/ServiceIndex.html'));
				$this->_view->display ();
				return ;
			}
		}
	}
	
	/**
	 * 编辑功能
	 */
	public function actionService(){
		switch ($_GET['doaction']){
			
		}
	}
	
	
	/**
	 * 分类管理 
	 */
	public function actionServiceKind(){
		switch ($_GET['doaction']){
			
		}
	}
	
	/**
	 * 复制FAQ
	 */
	public function actionFaqCopy(){
		switch ($_GET['doaction']){
			case 'game' :{
				$this->_faqCopyFromGame();
				break ;
			}
			case 'faqtype' :{				
			}
			default:{
				$this->_faqCopyFromFaqType();
			}
		}
	}
	
	/**
	 * 从整一个游戏复制FAQ
	 */
	private function _faqCopyFromGame(){
//		$gameTypeId = $_REQUEST['game_type_id'];
//		$lang = $_REQUEST['lang'];
//		$dataList=$this->_modelPlayerKindFaq->findTableData(NUll,$gameTypeId,$lang);
		
		$this->_view->display();
	}
	
	/**
	 * 从整一个FAQ类型复制FAQ
	 */
	private function _faqCopyFromFaqType(){
		$this->_lang=$this->_getGlobalData('lang');	//多语言
		$kindId = intval($_REQUEST['Id']);
		$lang_id = intval($_REQUEST['lang']);
		$is_syn = intval($_REQUEST['is_syn']);
		if($this->_isPost()){
							
			if(!$lang_id){
				$this->_utilMsg->showMsg('语言必须',-1);
			}
			
			if(!$kindId){
				$this->_utilMsg->showMsg('类型不能为空',-1);
			}
			
			//不能由其他语言复制至简体中文
			if($lang_id == 1){
				$this->_utilMsg->showMsg('不允许复制至此简体中文！',-1);
			}
			
			$checkLangId=$this->_modelPlayerKindFaq->findLangIdByKindId($kindId);
			if($lang_id == $checkLangId){
				$this->_utilMsg->showMsg('不允许复制至本语言！',-1);
			}

			if($is_syn && $lang_id!=6){
				$this->_utilMsg->showMsg('暂时只允许同步繁体中文！',-1);
			}
			
			$translateTo = intval($_REQUEST['translateTo'])?array('field'=>'Utf8_F2S'):array('field'=>'Utf8_S2F');

			$typeData=$this->_modelPlayerKindFaq->findTableData($kindId);
			$typeKeyValue = array(
				'lang_id'=>$lang_id,
				'name'=>$translateTo,	//is array
				'copy_from'=>array('field'=>'Id'),
				'Id'=>NULL,
			);
			$newTypeData = $this->_changeDataVal($typeData,$typeKeyValue);
			$newTypeData = array_shift($newTypeData);
			
			$ExistData = $this->_modelPlayerKindFaq->findCopyKind($newTypeData['copy_from'],$lang_id);
			if(!$ExistData){
				$this->_modelPlayerKindFaq->add($newTypeData);
				$CopyKindId = $this->_modelPlayerKindFaq->findLastId();
			}else{
				$CopyKindId = $ExistData['Id'];
				if($is_syn){
					$this->_modelPlayerKindFaq->update($newTypeData,"Id={$CopyKindId}");	//同步
				}
			}
			
			$faqKeyValue = array(	
				'check_user_id'=>null,			
				'kind_id'=>$CopyKindId,
				'lang_id'=>$lang_id,
				'question'=>$translateTo,	//is array
				'answer_g'=>$translateTo,	//is array
				'answer_s'=>$translateTo,	//is array
				'copy_from'=>array('field'=>'Id'),
				'Id'=>null,
			);
			$faqData = $this->_modelPlayerFaq->findByKindId($kindId);			
			$ExistFaqIds = $this->_modelPlayerFaq->findExistCopyFaqIds($faqData,$lang_id);
			$ExistFaqForSyn = array();	//用于更新
			foreach($faqData as $key => $faqDatasub){
				if(in_array($faqDatasub['Id'],$ExistFaqIds)){
					$ExistFaqForSyn[] = $faqData[$key];
					unset($faqData[$key]);
				}
			}
			$newFaqData = $this->_changeDataVal($faqData,$faqKeyValue);
			
			foreach($newFaqData as $subNewFaqData){
				$this->_modelPlayerFaq->add($subNewFaqData);
			}
			
			if($is_syn){
				$ExistFaqForSyn = $this->_changeDataVal($ExistFaqForSyn,$faqKeyValue);
				foreach($ExistFaqForSyn as $sub){
					$this->_modelPlayerFaq->update($sub,"copy_from = {$sub['copy_from']} and lang_id = {$lang_id}");
				}
			}
			
			//更新复制后FAQ类型的数量
			$KindAmount = $this->_modelPlayerFaq->findCount("lang_id={$lang_id} and kind_id={$CopyKindId}");
			$this->_modelPlayerKindFaq->update(array('count'=>$KindAmount),"Id={$CopyKindId}");
			
			$locationHref = Tools::url(CONTROL,'PlayerKind',array('lang'=>$lang_id));
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$locationHref);
		}
		$this->_view->assign('lang',$this->_lang);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 改变传入的数组$data的值，
	 * $keyValue键名：需要修改的$data的key，$keyValue键值：修改$data某key对应的值
	 * 如果$keyValue键值为NULL，则删除$data的字段。
	 * 如果$keyValue键值为是数组,则翻译.array('mode'=>'Utf8_S2F')简翻繁,array('mode'=>'Utf8_F2S')繁翻简
	 * @param $data
	 * @param $keyValue
	 */
	private function _changeDataVal($data=array(),$keyValue=array()){
		$this->_utilUtf8s2f = $this->_getGlobalData('Util_Utf8s2f','object');
		if(is_array($data) && count($data) && is_array($keyValue) && count($keyValue)){

			//将为NULL的值都放在最后才执行，因为前面的值可能还需要使用，不能这么快unset掉
			$tmpNull = array();
			foreach ($keyValue as $key => $value){
				if(NULL == $value){
					$tmpNull[$key] = $value;
					unset($keyValue[$key]);
				}				
			}
			$keyValue = array_merge($keyValue,$tmpNull);
			unset($tmpNull);
			
			foreach($data as &$sub){
				foreach($keyValue as $key =>$value){
					if(array_key_exists($key,$sub)){
						if(is_array($value)){
							switch ($value['field']){
								case 'Utf8_S2F':{
									$sub[$key] = $this->_utilUtf8s2f->Utf8_S2F($sub[$key]);
									break;
								}
								case 'Utf8_F2S':{
									$sub[$key] = $this->_utilUtf8s2f->Utf8_F2S($sub[$key]);
									break;
								}
								default :{
									$sub[$key] = $sub[$value['field']];
								}
							}
						}
						elseif(NULL === $value){
							unset($sub[$key]);
						}else{
							$sub[$key] = $value;
						}
					}
				}
			}
		}
		return $data;
	}
	
	/**
	 * 修复FAQ分类下的FAQ数量不对的接口
	 */
	public function actionXiuFu(){
		$game_id = intval($_GET['game_id']);
		if(!$game_id){
			echo 'field';
			exit();
		}
		$faqKindTable = $this->_modelPlayerKindFaq->tName();
		$kindIds = $this->_modelPlayerKindFaq->select("select Id from {$faqKindTable} where game_type_id = {$game_id}");
		foreach($kindIds as $key =>$val){
			$faqCount = $this->_modelPlayerFaq->findCount("kind_id={$val['Id']} and status !=1");
			$this->_modelPlayerKindFaq->update(array('count'=>$faqCount),"Id={$val['Id']}");
		}
		echo 'ok';
	}
	
	public function actionFAQCount(){
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$faq = $this->_getGlobalData ( 'Model_PlayerFaq', 'object' );
		$users = $this->_modelUser->findSetOrgByUser ();
		if($users){
			$userarr	=	array();
			foreach($users as $u){
				if($u['org_id']==13){
					if($_POST['userid']){
						if(in_array($u['Id'],$_POST['userid'])){
							$u['is_post']	=	1;
						}
					}
					$userarr[]	=	$u;
				}
			}
		}
		if($this->_isPost()){
			if(count($_POST['userid'])>0){
				$this->_view->assign ('gamecount',$faq->FaqCount($_POST));
			}
			
		}
		$this->_view->assign ( 'user',$userarr);
		$this->_view->assign ( 'js','QualityCheck/Index.js.html' );
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
}