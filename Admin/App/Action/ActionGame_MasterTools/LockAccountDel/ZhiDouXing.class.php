<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountDel_ZhiDouXing extends Action_ActionBase{

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
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$getData["account"]	=$_GET["account"];
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == 1){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}
}