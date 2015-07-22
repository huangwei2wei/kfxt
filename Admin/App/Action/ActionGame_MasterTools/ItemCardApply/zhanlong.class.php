<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardApply_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'PackID'=>intval($_POST['PackID']),
				'PackType'=>intval($_POST['PackType']),
				'PackName'=>trim($_POST['PackName']),
				'ItemList'=>trim($_POST['ItemList']),
				'Describes'=>trim($_POST['Describes']),
				'Remove'=>intval(0),
			);
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
		if($_GET["PackID"]!=""){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]		=	max(0,intval($_GET["WorldID"]));
			$getData["PackID"]		=	intval($_GET['PackID']);
			$dataIndex = $this->getResult("QuerySystem/ItemPackList",$getData);
			$a = array();
			foreach($dataIndex["Column"] as $k=>$item){
				$a[$item]	=	$dataIndex["ItemPackList"][$k];
			}
			$this->_assign["data"]	=	$a;
		}
		
		$items = $this->_f("18_ActionGame_MasterTools_Item_zhanlong",'',CACHE_DIR);
		$this->_assign["items"]	=	$items;
		
		$this->_assign["Item_url"]	=	$this->_urlitems();
		return $this->_assign;
	}

	
	
	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Item',$query);
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ItemCard',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}