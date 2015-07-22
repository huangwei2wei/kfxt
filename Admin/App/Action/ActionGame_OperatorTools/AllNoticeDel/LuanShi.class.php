<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AllNoticeDel_LuanShi  extends Action_ActionBase{
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
	
		if(!$_REQUEST['title']){
			$this->jump('title error',-1);
		}
		$postData = array(
			'content'=>$_POST['title'],
			'doaction'=>'delByContent',
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