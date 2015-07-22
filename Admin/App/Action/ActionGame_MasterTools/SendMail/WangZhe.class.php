<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_WangZhe extends Action_ActionBase{
	protected $_userType;
	protected $_users;
	protected $_title;
	protected $_content;
	protected $_cause;
	
	public function _init(){
		$this->_assign['playerTypes'] = array(
			'1'=>'玩家ID',
			'2'=>'玩家账号',
			'3'=>'玩家昵称',
		);
	}
	public function getPostData($post=null){
		$this->_userType = intval($_POST['userType']);
		if(!in_array($this->_userType ,array(1,2,3))){
			$this->jump('玩家类型错误');
		}
		$this->_users = base64_encode(trim($_POST['users']));
		$this->_title =  base64_encode(trim($_POST['title']));
		$this->_content =  base64_encode(trim($_POST['content']));
		$this->_cause = trim($_POST['cause']);
		$postData = array(
			'playerType'=>$this->_userType,
			'playerIds'=>$this->_users,
			'title'=>$this->_title,
			'content'=>$this->_content,
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost() && $_REQUEST['sbm']){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			
			$postData = array_merge($postData,$getData);
			$data = $this->getResult($UrlAppend,$postData,null);
// 			print_r($postData);
// 			print_r($data);exit;
			if($data['status'] == 1){
				$this->jump('发送成功');
			}else{
				$errorInfo = '发送失败:';
				$this->jump($errorInfo.$data['info'],-1);
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