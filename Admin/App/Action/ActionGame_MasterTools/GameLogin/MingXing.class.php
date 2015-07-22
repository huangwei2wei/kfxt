<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_MingXing extends Action_ActionBase{
	protected $_operatorId;	//运营商ID
	protected $_ordinal;		//服号
	protected $_serverId;		//服务器ID
	protected $_nickName;//玩家账号
	protected $_openId;//玩家账号
	protected $_cause;		//操作原因

	public function _init(){
		//前端可选用玩家的什么信息登录游戏
		$this->_assign['loginIdentifier'] = array(
			'nickName'=>'玩家昵称',
			'openId'=>'openId',
		);
	}
	public function getPostData($post=null){
		$gameId = $this->_gameObject->_gameId;
		$this->_operatorId = intval($_POST['operator_id']);
		$this->_ordinal = intval($_POST['ordinal']);
		$this->_serverId = intval($_POST['server_id']);//$this->_getServerId($gameId,$this->_operatorId,$this->_ordinal);
		$this->_nickName = trim($_POST['nickName']);
		$this->_openId = trim($_POST['openId']);
		$this->_cause = $_POST['cause'];
		$postData =  array(
			'nickName'=>urlencode($this->_nickName),
			'openId'=>$this->_openId,
		);
		$postData = array_filter($postData);
		if(empty($postData)){
			$this->jump('请输入玩家',-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$sendData = array_merge($getData,$postData);
			$data = $this->getResult($UrlAppend,$sendData);
			if(is_array($data)){
				if($data['status'] != 1){
					$this->stop('返回错误：'.$data['info'],-1);
				}
				$url = $this->_getUrl($this->_operatorId,true);
				$url = rtrim($url,'/').'/';
				$url .= ltrim($data['info'],'/');
				header('Location: '.$url);
				$this->stop();
			}
			$this->jump('操作失败',-1);
		}
		$gameId = $this->_gameObject->_gameId;
		$this->_assign['serverlist'] = json_encode($this->_getGlobalData('server/server_list_'.$gameId));
		return $this->_assign;
		
	}
	
}
