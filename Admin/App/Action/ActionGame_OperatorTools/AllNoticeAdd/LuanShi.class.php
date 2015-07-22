<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AllNoticeAdd_LuanShi extends Action_ActionBase{
	public function _init(){
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
// 		$noticeType = Tools::gameConfig('noticeType',$this->_gameObject->_gameId);
// 		$this->_assign['noticeType'] = $noticeType;
		if($this->_isAjax()){
			$postData = array(
				'content'=>trim($_POST['title']),
				'interval'=>intval($_POST['IntervalTime']),
				'url'=>trim($_POST['url']),
				'startTime'=>trim($_POST['begin']),
				'endTime'=>trim($_POST['end']),
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$this->ajaxReturn(array('status'=>1,'info'=>'操作成功！','data'=>null));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>$data['info'],'data'=>null));
			}
		}
		return $this->_assign;
	}
	
	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id']
		);
		return Tools::url(CONTROL,'Notice',$query);
	}
	
}