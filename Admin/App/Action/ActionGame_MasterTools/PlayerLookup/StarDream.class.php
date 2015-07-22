<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_StarDream extends Action_ActionBase{
	
	public function _init(){
		
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData = array();
		$postData['page'] = isset($_GET['page'])?intval($_GET['page']):1;
		$postData['pageSize'] = PAGE_SIZE;
		
		if( $_GET['playerId'] ){
			$postData['userType'] = 0;
			$postData['user'] = $_GET['playerId'];
		} else if( $_GET['accountName'] ){
			$postData['userType'] = 1;
			$postData['user'] = $_GET['accountName'];
		} else if( $_GET['playerName'] ){
			$postData['userType'] = 2;
			$postData['user'] = $_GET['playerName'];
		}
		if($_GET['regEndTime']){
			$postData['regEndTime'] = strtotime( trim($_GET['regEndTime']) );
		}
		if($_GET['regBeginTime']){
			$postData['regBeginTime'] = strtotime( trim($_GET['regBeginTime']) );
			$postData['regEndTime'] = $postData['regEndTime']?$postData['regEndTime']:time();
		}
		if($_GET['loginEndTime']){
			$postData['loginEndTime'] = strtotime( trim($_GET['loginEndTime']) );
		}
		if($_GET['loginStartTime']){
			$postData['loginStartTime'] = strtotime( trim($_GET['loginStartTime']) );
			$postData['loginEndTime'] = $postData['loginEndTime']?$postData['loginEndTime']:time();
		}
		
		$data = $this->_gameObject->result('PlayerLookup',$getData,$postData);
		if($data['status'] == 1){
			
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $data['data']['list'];
			$this->_assign['pageBox'] = $helpPage->show();
			
			$status = 1;
		}else{
			$status = 0;
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$status,'info'=>$data['info'],'data'=>$this->_assign));
		}
		return $this->_assign;
	}
	
	private function _vocationId($vocationId=0){
		
	} 
	
	
}