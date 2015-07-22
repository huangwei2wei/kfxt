<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_StarDream extends Action_ActionBase{
	public function _init(){
		$this->_assign['exchangeTypes'] = Tools::gameConfig('exchangeTypes',$this->_gameObject->_gameId);	//itemTypes
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'user'=>$_GET['user'],
			'userType'=>$_GET['userType'],
			'page'=>isset($_GET['page'])?intval($_GET['page']):1,
			'pageSize'=>PAGE_SIZE,
		);
		
		if($_GET['orderId']){
			$postData['orderID'] = trim($_GET['orderID']);
		}
		if($_GET['endTime']){
			$postData['endTime'] = strtotime( trim($_GET['endTime']) );
		}
		if($_GET['beginTime']){
			$postData['beginTime'] = strtotime( trim($_GET['beginTime']) );
			$postData['endTime'] = $postData['endTime']?$postData['endTime']:time();
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($_REQUEST['sbm']){
			
			$getGetData = $this->_gameObject->getGetData($get);
			$postData=$this->getPostData($post);
			$data = $this->_gameObject->result('RechargeRecord',$getGetData,$postData);
			if( $data['status'] == 1 ){
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($data['data']['total']),'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			} else {
				$this->_assign['ConnectErrorInfo'] = $data['info'];
			}
			
			$this->_assign['select'] = $postData;
		}
		$this->_assign['userType'] = array(0=>'玩家ID',1=>'玩家账号',2=>'玩家昵称');
		$this->_assign['exchangeTypes'] = array(0=>'所有',1=>'失败',2=>'成功');
		return $this->_assign;
	}
}