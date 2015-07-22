<?php
/**
 * FAQ统计
 * @author php-朱磊
 *
 */
class Control_StatsFaq extends Stats {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_PlayerFaqLog
	 * @var Model_PlayerFaqLog
	 */
	private $_modelPlayerFaqLog;
	
	/**
	 * Model_StatsFaq
	 * @var Model_StatsFaq
	 */
	private $_modelStatsFaq;
	
	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 评价统计
	 */
	public function actionEv(){
		

		

		
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$sources=array('1'=>'官网','2'=>'游戏');
		$selected=array();//选中数组
		$selected['lang']=$_POST['lang'];
		$selected['source']=Tools::coerceInt($_POST['source']);
		$selected['start_time']=$_POST['start_time'];
		$selected['end_time']=$_POST['end_time'];
		$selected['game_type_id']=Tools::coerceInt($_POST['game_type_id']);
		if ($this->_isPost()){
			$this->_modelPlayerFaqLog=$this->_getGlobalData('Model_PlayerFaqLog','object');
			if (empty($_POST['start_time']) || empty($_POST['end_time']))$this->_utilMsg->showMsg('请选择开始时间与结束时间',-1,2);
			$stats=$this->_modelPlayerFaqLog->stats($_POST['source'],$_POST['game_type_id'],array('start'=>$_POST['start_time'],'end'=>$_POST['end_time']),$_POST['lang']);
		}
		
		$this->_view->assign('dataList',$stats);
		$sources['']='所有';
		$gameTypes['']='所有';
		$lang=$this->_getGlobalData('lang');	//语言
		$this->_view->assign('land',$lang);
		$this->_view->assign('sources',$sources);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('selected',$selected);
		$this->_utilMsg->createPackageNavBar();
		if ($_POST['xls']){//导出excel
			Tools::import('Util_ExportExcel');
			$this->_utilExportExcel=new Util_ExportExcel('faq统计','Excel/StatsFaq',$stats);
			$this->_utilExportExcel->outPutExcel();
		}else {
			$this->_view->display ();
		}
	}
	
	/**
	 * 点击率统计
	 */
	public function actionRatio(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$sources=$this->_getGlobalData('workorder_source');
		$lang=$this->_getGlobalData('lang');
		$statsType=array('day'=>'按天','hour'=>'按小时');
		$selected=array();//统计数组
		$selected['stats_type']=$_POST['stats_type']?$_POST['stats_type']:'day';	//统计类型默认天
		$selected['game_type_id']=$_POST['game_type_id'];
		$selected['source']=$_POST['source'];
		$selected['start_time']=$_POST['start_time'];
		$selected['end_time']=$_POST['end_time'];
		$selected['lang_id']=$_POST['lang_id'];
		if ($this->_isPost()){
			$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
			$kindList=$this->_modelPlayerKindFaq->findListAll($_POST['game_type_id'],$_POST['lang_id']);
			$this->_modelStatsFaq=$this->_getGlobalData('Model_StatsFaq','object');
			if (empty($_POST['start_time']) || empty($_POST['end_time']))$this->_utilMsg->showMsg('请选择开始时间与结束时间',-1,2);
			
			if ($_POST['stats_type']=='day'){//按天
				$this->_view->assign('allDay',Tools::getdateArr($_POST['start_time'],$_POST['end_time']));
				$stats=$this->_modelStatsFaq->statsDay(array('start_time'=>$_POST['start_time'],'end_time'=>$_POST['end_time']),$_POST['game_type_id'],$_POST['source'],$_POST['lang_id']);		
			}else {//按小时
				$this->_view->assign('allHour',range(0,23));
				$stats=$this->_modelStatsFaq->statsHour(array('start_time'=>$_POST['start_time'],'end_time'=>$_POST['end_time']),$_POST['game_type_id'],$_POST['source'],$_POST['lang_id']);
			}
			
		}else{
			$selected["start_time"]	=	date("Y-m-d",time())." 00:00:00";
			$selected["end_time"]	=	date("Y-m-d",time())." 23:59:59";
		}
		$this->_view->assign('kindList',$kindList);
		$this->_view->assign('dataList',$stats);
		$gameTypes['']='所有';
		$sources['']='所有';
		$lang['']='所有';
		$this->_view->assign('selected',$selected);
		$this->_view->assign('statsType',$statsType);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('sources',$sources);
		$this->_view->assign('lang',$lang);
		$this->_utilMsg->createPackageNavBar();
		
		if ($_POST['xls']){//导出excel
			Tools::import('Util_ExportExcel');
			$this->_utilExportExcel=new Util_ExportExcel('faq统计','Excel/StatsFaq',$stats);
			$this->_utilExportExcel->outPutExcel();
		}else {
			$this->_view->display ();
		}
	}
	
}