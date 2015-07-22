<?php
/**
 * 用户遗失账号
 * @author php-兴源
 *
 */
class Control_LostPassword extends Control{
	
	public function __construct(){
		$this->_createView();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	function actionIndex(){
		$this->_modelLostPassword=$this->_getGlobalData('Model_LostPassword','object');
		$status = $this->_modelLostPassword->getStatus();
		$this->_view->assign('statusSelect',$status);
		$status[''] = Tools::getLang('ALL','Common');
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');
		$this->_utilMsg->createNavBar();
		$where=array();
		if($this->_isPost()){
			$selected['status'] = $_POST['status'];
			if(intval($_POST['status'])>0)$where['status'] = $_POST['status'];
		}
		$order = 'status asc,Id desc';
		$dataList = $this->_modelLostPassword->getOrder($where,1,$order);
		$this->_loadCore ( 'Help_Page' );
		$currUrl = Tools::url ( CONTROL, 'EventList', $selected );
		$helpPage = new Help_Page ( array ('total' => $this->_modelLostPassword->getCount ( $where ), 'perpage' => PAGE_SIZE ,'url' => $currUrl) );
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('serverList',$serverList);
		$this->_view->assign('chargeType',$this->_modelLostPassword->getChargeType());
		$this->_view->assign('status',$this->_modelLostPassword->getStatus());
		$this->_view->assign('status',$status);
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('selected',$selected);
		$this->_view->display();
	}
	
	function actionChangeStatus(){
		$Id = intval($_REQUEST['Id']);
		$this->_modelLostPassword=$this->_getGlobalData('Model_LostPassword','object');
		$isExist=array_key_exists($_REQUEST['status'],$this->_modelLostPassword->getStatus()); 
		$data = array('status'=>0,'info'=>'修改失败');
		if($Id > 0 && $isExist){
			$this->_modelLostPassword=$this->_getGlobalData('Model_LostPassword','object');
			$tag = $this->_modelLostPassword->update(array('status'=>$_REQUEST['status']),'Id='.$Id);
			if($tag)$data = array('status'=>1,'info'=>'修改成功');
		}
		$this->_returnAjaxJson($data);
	}
	

}