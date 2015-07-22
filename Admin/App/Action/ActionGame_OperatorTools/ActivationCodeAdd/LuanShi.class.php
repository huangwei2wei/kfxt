<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivationCodeAdd_LuanShi extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$postData = array(
				'cardType'=>intval($_POST['cardType']),
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data["Result"]===0){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}else {
			$getData = $this->_gameObject->getGetData(array('m'=>'Admin','c'=>'CardType','a'=>'CardTypeList'));
			$data = $this->getResult('php/interface.php',$getData,null);
			if ($data && is_array($data)){
				$cardTypeList = array();
				foreach ($data['data']['cardTypes'] as $v){
					$cardTypeList[$v['id']] = $v['typeName'];
				}
				$this->_assign["cardTypeList"]	=	$cardTypeList;
			}else{
				$this->_assign['errorConn'] = Tools::getLang('CONNECT_SERVER_ERROR','Common');
			}
		}
		
		$this->_assign["Item_url"]	=	$this->_urlitems();
		return $this->_assign;
	}

	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url("MasterTools",'Define',$query);
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesList',$query);
	}


}