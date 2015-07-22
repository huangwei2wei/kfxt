<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_Default_1 extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost() && $_POST["submit"]){
			$getData 	= $this->_gameObject->getGetData($get);
			$getData["Content"] 	= trim($_REQUEST["Content"]);
			$getData["NoticeID"] 	= intval($_REQUEST["NoticeID"]);
			$getData["beginTime"] 	= intval(strtotime($_REQUEST["beginTime"]));
			$getData["endTime"] 	= intval(strtotime($_REQUEST["endTime"]));
			$getData["Delay"] 	= intval($_REQUEST["Delay"]);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data["states"]=="1"){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$this->_assign["POST"] = $_POST;
//		print_r($this->_assign);
		return $this->_assign;
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Notice',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}