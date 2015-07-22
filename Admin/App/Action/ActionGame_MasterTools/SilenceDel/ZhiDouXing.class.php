<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceDel_ZhiDouXing extends Action_ActionBase{
	
	public function _init(){
		
	}
	public function getPostData($post=null){
		return array(
			'account'=>$_REQUEST['account'],
		);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
// 		print_r($UrlAppend);
// 		print_r($getData);
// 		print_r($postData);
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);exit;
		if($data['status'] == 1){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}	
}