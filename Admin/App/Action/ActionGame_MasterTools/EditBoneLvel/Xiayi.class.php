<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_EditBoneLvel_Xiayi extends Action_ActionBase{
	protected $_userType;
	protected $_user;
	protected $_bone_id;
	protected $_bone_level;

	public function _init(){
		$this->_assign['playerTypes'] = array(
			'1'=>'玩家ID',
			'2'=>'玩家账号',
			'3'=>'玩家昵称',
		);
	}
	public function getPostData($post=null){
		$this->_userType = intval($_POST['userType']);
		if(!in_array($this->_userType ,array(1,2,3)) || intval($_POST['bone_id'])==0 || intval($_POST['bone_level']) ==0 ){
			$this->jump('参数有误错误');
		}
		$this->_user = trim($_POST['user']);
		$this->_bone_id = intval(trim($_POST['bone_id']));
		$this->_bone_level = intval($_POST['bone_level']);
		
		$postData = array(
			'userType'=>$this->_userType,
			'user'=>$this->_user,
			'bone_id'=>$this->_bone_id,
			'bone_level'=>$this->_bone_level,
		);
		if($postData["userType"]==3){
			$postData["user"]		=	base64_encode($postData["user"]);
		}else{
			$postData["user"]		=	$postData["user"];
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
			
			$case = $this->_gameObject->_userTypeArr[$_POST['userType']].':'.$_POST['user'].'<br>骨头编号:'.$_POST['bone_id'].'<br>骨头等级:'.$_POST['bone_level'];
			$applyData = array(
				'type'=>62,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>intval($postData['userType']),//1为id，2为账号3为昵称
				'user'=>$_POST['user'],//值，
			);
			
			$re = $this->_gameObject->applyAction($applyData);
			if($re[0]==1){
				$this->jump($re[1],1,1,false);
			} else {
				$this->jump($re[1],-1);
			}
			return $this->_assign;
		}
		return $this->_assign;
	}

}