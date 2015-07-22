<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccount_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["doaction"]=="detail"){
			$this->_operateDetail(1);
			exit(); 
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=array();
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		$postData['Per']	=	20;
		if($_GET['page']){
			$postData['Page']	=	$_GET['page'];
		}else{
			$postData['Page']	=	1;
		}
		
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1' && $data['data']){
			foreach($data['data']['list'] as &$msg){
				$msg['urldel']			=	$this->_urldel($msg['user_id']);
				$msg['URL_Detail']		=	$this->_detailurl($msg['user_id']);
			}
			$this->_assign['dataList']=$data['data']['list'];
			
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['totle_page'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox']=$helpPage->show();
		}
		$this->_assign['urladd']	=	$this->_urladd();
		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
	private function _urladd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccountAdd',$query);
	}
	
	private function _urldel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'		=>	$id
		);
		return Tools::url(CONTROL,'LockAccountDel',$query);
	}
	
	private function _detailurl($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'game_user_id'		=>	$id,
			'doaction'			=>	"detail"
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
	
	private function _operateDetail($operateType=0){		
		if ($_REQUEST['server_id']){
			$jsonData = array('status'=>0,'info'=>'server id error','data'=>NULL);
			$gameUserId = $_GET['game_user_id'];
			$operateType = intval($operateType);
			$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
			$dataList = $this->_modelGameOperateLog->getDetail($_REQUEST['server_id'],$gameUserId,$operateType);
			$jsonData = array('status'=>1,'info'=>NULL,'data'=>$dataList);
		}else{
			$jsonData = array('status'=>0,'info'=>'server id error','data'=>NULL);
		}
		$this->ajaxReturn($jsonData);
	}
//"$data" = Array [3]	
//	data = (boolean) true	
//	status = (int) 1	
//	info = null	
	
	
}