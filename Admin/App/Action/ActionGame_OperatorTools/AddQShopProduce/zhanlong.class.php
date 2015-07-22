<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AddQShopProduce_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'SellID'=>intval($_POST['SellID']),
				'OrderLevel'=>intval($_POST['OrderLevel']),
				'NeedJob'=>trim($_POST['NeedJob']),
				'ItemID'=>intval($_POST["ItemID"]),
				'ItemName'=>trim($_POST['ItemName']),
				'ItemNum'=>intval($_POST["ItemNum"]),
				'IconFile'=>trim($_POST['IconFile']),
				'PriceSell'=>trim($_POST['PriceSell']),
				'Original'=>trim($_POST['Original']),
				'IsHot'=>intval($_POST['IsHot']),
				'IsDiscout'=>intval($_POST['IsDiscout']),
				'IsDiscout'=>intval($_POST['SaleType']),
				'SellText'=>trim($_POST['SellText']),
				'SaleTime'=>trim($_POST['SaleTime']),
				'Remove'=>intval(0),
			);

			if($post){
				$postData = array_merge($post,$postData);
			}
			$SendData["data"]	=	json_encode($postData);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$SendData);
			if($data["Result"]===0){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'QShopProduce',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}