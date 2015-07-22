<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PersonalInformation_MingXing extends Action_ActionBase{

	public function _init(){}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['nickName'] = urlencode(trim($_GET['nickName']));
		$postData['openId'] = trim($_GET['openId']);
		$postData = array_filter($postData);
		if(!$postData){
			$this->jump('参数缺少',-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=$this->getPostData($post);
		$sendData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$sendData);
		if(is_array($data) && $data['status']==1){
			$this->_assign['data'] = $data['data'];
		}
		return $this->_assign;
	}
}
