<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Patch_XiYou extends Action_ActionBase{

	public function _init(){
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
	}
	public function getPostData($post=null){

		$postData = array(
			'params'=>$_POST['params'],
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		
		if(empty($_REQUEST['server_id'])){
			return $this->_assign;
		}
		if($this->_isAjax()){
			$postData = $this->getPostData($post);
			$postData = $this->_gameObject->getPostData($postData);
			$postData = array_merge($get,$postData);
			$data = $this->getResult($UrlAppend,$get,$postData);
			
// 			print_r($postData);exit;
			
// 			$postData = $this->getPostData($post);
// 			$getData = $this->_gameObject->getGetData($get);
// 			$data = $this->getResult($UrlAppend,$getData,$postData);
			
			if($data['status'] == 1){
				$this->ajaxReturn(array('status'=>1,'info'=>'操作成功！','data'=>null));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>$data['info'],'data'=>null));
			}
		}
		
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['userTypeSelect'] = 1;
		$this->_assign['users'] = $playerIds;
		return $this->_assign;
	}
	
}