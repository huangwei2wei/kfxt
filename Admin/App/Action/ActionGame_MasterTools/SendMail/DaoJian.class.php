<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_DaoJian extends Action_ActionBase{
	protected $_userType;
	protected $_users;
	protected $_title;
	protected $_content;
	protected $_cause;
	protected $_endTime;
	
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
		$this->_users = trim($_POST['users']);
		$this->_title =  trim($_POST['title']);
		$this->_content =  trim($_POST['content']);
		$this->_cause = trim($_POST['cause']);
		$this->_endTime = $_POST['endTime'];
		
		$postData = array(
			'title'=>$this->_title,
			'content'=>$this->_content,
			'sender'=>'系统邮件',
			'players'=>$this->_users,
			'playerType'=>$this->_userType,
			'endTime' => $this->_endTime,
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
			
			//$getData = array_merge($getData,$postData);
			$data = $this->getResult($UrlAppend,$getData,$postData);
//	print_r($postData);
//	var_dump($data);
//	exit;
			if($data['status'] == 0){
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