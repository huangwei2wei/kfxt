<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_SendEmailMultiserver_SanFen extends Action_ActionBase{
	protected $_userType;
	protected $_users;
	protected $_title;
	protected $_content;
	protected $_cause;
	
	public function _init(){
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
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
		$this->_title = trim($_POST['title']);
		$this->_content = $_POST['content'];
		$this->_cause = trim($_POST['cause']);
		$postData = array(
			'userType'=>$this->_userType,
			'users'=>$this->_users,
			'title'=>$this->_title,
			'content'=>$this->_content,
			'isAllUser'=>trim($_POST['isAllUser']),
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
		if($this->_isAjax()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
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