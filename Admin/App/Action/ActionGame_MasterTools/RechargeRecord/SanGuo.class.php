<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($_GET['playerId']){
			$getData = $this->_gameObject->getGetData($get);
			$postData=array();
			if($post){
				$postData = array_merge($post,$postData);
			}
			$postData = array_filter($postData);
			$postData['Per']	=	PAGE_SIZE;
			if($_GET['page']){
				$postData['Page']	=	$_GET['page'];
			}else{
				$postData['Page']	=	1;
			}
			if(!$_GET['playerId']){
				$this->jump('用户ID不能为空',-1);
			}else{
				$postData['user_id']	=	$_GET['playerId'];
			}
			if($_GET['year']){
				$postData['Year']	=	$_GET['year'];
			}
			
			if($_GET['month']){
				$postData['Month']	=	$_GET['month'];
			}
			
			if($_GET['day']){
				$postData['Day']	=	$_GET['day'];
			}

			
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == '1' && $data['data']){
				$this->_assign['dataList']=$data['data']['list'];
				
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['totle_page'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox']=$helpPage->show();
			}
		}
		
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
	
	
}