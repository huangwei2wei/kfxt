<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AllNoticeAdd_Default extends Action_ActionBase{


	public function _init(){}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		switch($_GET['doaction']){
			case 'ajax':{
				return $this->_ajaxallnotice();
			}
//			case 'del':{
//				return $this->_delallnotice();
//			}
			default :{
				return $this->_allnoticeIndex();
			}
		}
		
	}
	
	public function _allnoticeIndex(){
//		$senddata	=	array(
//			'game_type'	=>	$this->_gameObject->_gameId,
//			'page'		=>  $_GET['page']?$_GET['page']:'1',
//			'zp'		=>	PACKAGE,
//		);
//		if($this->_isPost ()){
//			if($_POST['start_time']){
//				$senddata['start_time']	=	$_POST['start_time'];
//			}
//			
//			if($_POST['end_time']){
//				$senddata['end_time']	=	$_POST['end_time'];
//			}
//			if($_POST['content']){
//				$senddata['content']	=	$_POST['content'];
//			}
//		}
//		$model	=	$this->_getGlobalData('Model_Notice','object');
//		$data	=	$model->getNoticelist($senddata);
//		
//		if($data['dataList']){
//			foreach($data['dataList'] as &$value){
//				$value['url_edit']=Tools::url(CONTROL,'NoticeEdit',array('zp'=>PACKAGE,'server_id'=>$value['server_id'],'id'=>$$value['main_id'],'__game_id'=>$value['game_type']));
//			}
//			$this->_assign['datalist']		=	$data['dataList'];
//			$this->_assign['pageBox']	=	$data['pageBox'];
//			
//		}
//		$this->_assign['post']				=	$_POST;
		$this->_assign['ajax']				=	Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'doaction'=>'ajax','__game_id'=>$this->_gameObject->_gameId));
//		$this->_assign['del']				=	Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'doaction'=>'del','__game_id'=>$this->_gameObject->_gameId));
		$this->_assign['tplServerSelect']	=	"ActionGame_OperatorTools/AllNotice/ServerSelect.html";
		return $this->_assign;
	}
	
	private function _ajaxallnotice(){
		$data	=	array(
			'server_id'	=>	$_REQUEST['server_id'],
			'game_type'	=>	$this->_gameObject->_gameId,
			"title"		=>	$_REQUEST['title'],
			"url"		=>	$_REQUEST['url'],
			"delay"		=>	$_REQUEST['interval'],
			"begin"		=>	$_REQUEST['begin'],
			"end"		=>	$_REQUEST['end'],
		);
		
		if($this->_gameObject->addNotice($data)){
			$this->ajaxReturn(array('status'=>1,'msg'=>'添加成功，'));
		}else{
			$this->ajaxReturn(array('status'=>0,'msg'=>'添加失败，'));
		}
	}
	
//	private function _delallnotice(){
//		$model	=	$model=$this->_getGlobalData('Model_Notice','object');
//		if($_POST['ids']){
//			foreach($_POST['ids'] as $_msg){
//				if(!$model->deleteNotice($_msg,$this->_gameObject->_gameId)){
//					$error	.=	'{id:'.$_msg.'}';
//				}
//			}
//			if(empty($error)){
//				$this->jump('删除成功',-1);
//			}else{
//				$this->jump($error."失败",-1);
//			}
//		}
//	}
	

}