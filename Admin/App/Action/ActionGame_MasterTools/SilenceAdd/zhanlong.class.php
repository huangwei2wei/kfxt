<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_zhanlong extends Action_ActionBase{

	protected $_cause;
	protected $_serverId;

	protected $_playersData=array();
	protected $_endTime;

	public function _init(){
		$this->_cause = trim($_POST['cause']);
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_endTime = trim($_POST['HowLong']);
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$getData["PlayerID"]	=	$_POST['PlayerID'];
			$getData["WorldID"]	=	intval($_POST['WorldID']);
			$getData["HowLong"]		=	intval($_POST['HowLong']);
			$getData["PlayerName"]	=	urlencode(trim($_POST['PlayerName']));
			$getData["Remove"]	=	intval(0);
			$data = $this->getResult($UrlAppend,$getData);
			if($data["Result"]===0){
				if(!empty($getData["PlayerID"])){
					$arr = explode(",",$getData["PlayerID"]);
					foreach($arr as $a){
						$this->_playersData[]=array(
						'playerId'=>$a,
						);
					}
				}else{
					$arr = explode(",",$getData["PlayerName"]);
					foreach($arr as $a){
						$this->_playersData[]=array(
						'playerNickname'=>$a,
						);
					}
				}
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		return $this->_assign;
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}