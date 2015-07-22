<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AllNoticeAdd_StarDream extends Action_ActionBase{


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
		$this->_assign['ajax']				=	Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'doaction'=>'ajax','__game_id'=>$this->_gameObject->_gameId));
//		$this->_assign['del']				=	Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'doaction'=>'del','__game_id'=>$this->_gameObject->_gameId));
		$this->_assign['tplServerSelect']	=	"ActionGame_OperatorTools/AllNotice/ServerSelect.html";
		return $this->_assign;
	}
	
	private function _ajaxallnotice(){
		$getData = $this->_gameObject->getGetData($get);
		$postData = array(
			'server_id'	=>	$_REQUEST['server_id'],
			'startTime'	=>intval(strtotime($_REQUEST['startTime'])),
			'endTime'		=>intval(strtotime($_REQUEST['endTime'])),
			'intervalTime'	=>intval($_REQUEST['intervalTime']),
			'content'=>base64_encode(trim($_REQUEST['content'])),
		);
			
		$data = $this->_gameObject->result('NoticeAdd',$getData,$postData);
		if($data['status'] == 1){
			$this->ajaxReturn(array('status'=>1,'msg'=>'添加成功，'));
		}else{
			$this->ajaxReturn(array('status'=>0,'msg'=>'添加失败，'.$data['info']));
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