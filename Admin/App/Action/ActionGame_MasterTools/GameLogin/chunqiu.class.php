<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_chunqiu extends Action_ActionBase{
	const SEPARATOR = "\n";

	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime;
	protected $_cause;
	protected $_playersData=array();
	public function _init(){

	}
	public function getPostData($post=null){
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$url = $this->_getUrl($this->_serverId);
			$account = trim($_POST['User']);
			$queryvars = array(
					'do' => 'gmlogin',
					'account' => $account,
					'time' => time()
			);
			ksort($queryvars);
			$sign_str = http_build_query($queryvars);
			$sign_str .= "IGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDMz3OCs5esvsp";
			$queryvars['sign'] = md5($sign_str);
			$url = explode(":",$url);
			$url = "http://".$url[0]."/".$UrlAppend."?".http_build_query($queryvars);
			header('Location: '.$url);
			$this->stop();
		}
		$gameId = $this->_gameObject->_gameId;
		$this->_assign['serverlist'] = json_encode($this->_getGlobalData('server/server_list_'.$gameId));
		return $this->_assign;
	}


	private function _urlSilence(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

	private function _getPlayers(){
		$_POST['users'] = trim($_POST['users']);
		if(isset($_POST['separator']) && $_POST['separator'] && $_POST['separator']!=self::SEPARATOR){
			$_POST['users'] = str_replace($_POST['separator'],self::SEPARATOR,$_POST['users']);
		}
		return $_POST['users'];
	}
}