<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivationCodeList_LuanShi extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData['page'] = $_GET['page']?$_GET['page']:1;
		$postData['cardType'] = $_POST['cardType'];
		$data = $this->getResult($UrlAppend,$getData,$postData);
print_r($postData);
		if($data['status'] == 1){
			$this->_assign['cardList'] = $data['data']['cardList'];
			$cardTypes = array();
			foreach ($data['data']['cardTypes'] as $v){
				$cardTypesp[$v['id']] = $v['typeName'];
			}
			$this->_assign['cardTypesp'] = $cardTypesp;
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['Del_Url'] = $this->_urlDel();
		$this->_assign['URL_Add'] = $this->_urlAdd();
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivationCodeAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivationCodeDel',$query);
	}

}