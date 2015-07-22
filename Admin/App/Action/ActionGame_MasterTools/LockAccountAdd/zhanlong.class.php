<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountAdd_zhanlong extends Action_ActionBase{

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
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$getData["AccountName"]	= $_POST['AccountName'];
			$getData["HowLong"]	=	intval($_POST['HowLong']);
			$getData["Remove"]	=	intval(0);
			$data = $this->getResult($UrlAppend,$getData);
			if($data["Result"]===0){
				$arr = explode(",",$getData["AccountName"]);
				foreach($arr as $a){
					$this->_playersData[]=array(
						"playerId"=>0,
						"playerAccount"=>trim($a),
					);
				}
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}