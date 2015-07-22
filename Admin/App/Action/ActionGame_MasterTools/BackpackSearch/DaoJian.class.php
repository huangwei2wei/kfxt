<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_DaoJian extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_itemsDel'] = $this->_urlItemsDel();
// 		$this->_assign['itemTypes'] = Tools::gameConfig('itemTypes',$this->_gameObject->_gameId);
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$postData=array(
					'backpack' =>0,
					'pageSize'=>300,
					'pageNow' =>1
					);
			if(!$_POST['user']){
				$this->jump('用户不能为空',-1);
			}
			
			$postData['players'] = $_POST['user'];
			$postData['playerType'] = $_POST['userType'];
			
			if($post){
				$postData = array_merge($postData,$post);
			}
			$data = $this->getResult($UrlAppend,$getData,$postData);
//			print_r($postData);
//			print_r($postData);
//			print_r($data);
//			exit;
			if($data['status'] ==  0){
				if($data['data']){
					$this->_assign['dataList'] = $data['data'];
//					$this->_assign['UserID'] = $data['data']['UserID'];
//					$this->_assign['UserName'] = $data['data']['UserName'];
//					$this->_assign['NickName'] = $data['data']['NickName'];
				}else{
					$this->jump('没有此用户',-1);
				}
			}else{
				$this->jump('查询失败'.$data['info'],-1);
			}
		}
		$this->_assign['userType'] = array(
				1=>'玩家id',
				2=>'玩家账号',
				3=>'玩家昵称',
				);
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
	private function _urlItemsDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ItemDel',$query);
	}
	
	
}