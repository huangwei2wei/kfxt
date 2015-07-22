<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceDel_chunqiu extends Action_ActionBase{

	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime = '';
	protected $_cause;
	protected $_playersData=array();
	protected $_silenceDel = true;

	public function _init(){


	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData["userType"]=1;
		$postData["users"]=$_GET["users"];
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = json_decode($data,true);
		if($data['status'] == 1){
			$arr = explode(",",$postData["users"]);
			$typelist = array('playerId','playerAccount','playerNickname');
			foreach($arr as $a){
				$b = array('playerId'=>0,'playerAccount'=>'','playerNickname'=>'');
				$b[ $typelist[ $postData['userType'] ] ] = trim($a);
					
				$this->_playersData[] = $b;
			}
			
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['error'],-1);
		}
	}
}