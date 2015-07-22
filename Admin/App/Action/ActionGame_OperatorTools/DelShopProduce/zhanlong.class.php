<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_DelShopProduce_zhanlong extends Action_ActionBase{

	private $_utilMsg;

	public function _init(){
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$postData = array(
			"Remove"=>intval(1),
			'SellID'=>intval($_REQUEST['SellID']),
			'WorldID'=>intval($_REQUEST['WorldID']),
		);
		$getData = $this->_gameObject->getGetData($get);
		$SendData["data"]	=	json_encode($postData);
		//		echo $SendData["data"];
		//		die();
		$data = $this->getResult($UrlAppend,$getData,$SendData);
		if($data["Result"]===0){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ShopProduce',$query);
	}
}