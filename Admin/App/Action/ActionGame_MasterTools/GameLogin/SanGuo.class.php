<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_SanGuo extends Action_ActionBase{
	protected $_operatorId;	//运营商ID
	protected $_ordinal;		//服号
	protected $_serverId;		//服务器ID
	protected $_playerAccount;//玩家账号
	protected $_cause;		//操作原因

	public function _init(){}
	public function getPostData($post=null){
		$gameId = $this->_gameObject->_gameId;
		$this->_operatorId = intval($_POST['operator_id']);
		$this->_ordinal = intval($_POST['ordinal']);
		$this->_serverId = $this->_getServerId($gameId,$this->_operatorId,$this->_ordinal);
		$this->_playerAccount = trim($_POST['playerAccount']);
		$this->_cause = $_POST['cause'];
		return array(
			'user_id'=>$this->_playerAccount,
		);
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$_REQUEST['server_id']	=	$this->_serverId;
			$data = $this->getResult($UrlAppend,$getData,$postData);
			
			if($data['status'] == '1'){
				header('Location: '.$data['data']['url_1']);
				$this->stop();
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['msg'],-1);
			}
		}
	}
	
	/**private function _getServerId($gameId,$operatorId,$ordinal){
		$modelGameSerList = $this->_getGlobalData('Model_GameSerList','object');
		$sql = "select Id from {$modelGameSerList->tName()} where game_type_id={$gameId} and operator_id={$operatorId} and ordinal={$ordinal}";
		$server = $modelGameSerList->select($sql,1);
		if(!$server){
			$this->stop('服务器不存在',-1);
		}
		return $server['Id'];
	}**/
}