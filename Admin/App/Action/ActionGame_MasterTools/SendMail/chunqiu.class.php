<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_chunqiu extends Action_ActionBase{
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
		$this->_users = trim($_POST['users']);
		$this->_title = base64_encode(trim($_POST['title']));
		$this->_content = base64_encode($_POST['content']);
		$this->_cause = trim($_POST['cause']);
		
		$postData = array(
			'userType'=>$this->_userType,
			'users'=>$this->_users,
			'title'=>$this->_title,
			'content'=>$this->_content,
		);
		if($postData["userType"]==3){
			$postData["users"]		=	base64_encode($postData["users"]);
		}else{
			$postData["users"]		=	$postData["users"];
		}
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
			
			$postData["is_common"] = intval($_POST["is_common"]);
			$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
			$data = json_decode($data,true);
			if($data['status']==1){
				$this->jump('发送成功');
			}else{
				$errorInfo = $data['error'];
				$this->jump($errorInfo,-1);
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