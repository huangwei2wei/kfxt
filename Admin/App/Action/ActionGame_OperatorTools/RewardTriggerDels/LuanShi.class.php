<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_RewardTriggerDels_LuanShi extends Action_ActionBase{
	
	private $_utilMsg;
	
	public function _init(){
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if(!$_POST['title']){
			$this->jump('title error',-1);
		}
		$postData = array(
			'title'=>$_POST['title'],
			'doaction'=>'delByTitle',
		);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == 1){
			$this->ajaxReturn(array('status'=>1,'info'=>'删除成功！','data'=>null));
		}else{
			$this->ajaxReturn(array('status'=>0,'info'=>$data['info'],'data'=>null));
		}
	}
}