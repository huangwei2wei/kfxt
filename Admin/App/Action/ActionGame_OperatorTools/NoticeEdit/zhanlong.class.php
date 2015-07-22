<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_zhanlong extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost() && $_POST["sub"]){
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'OrderID'=>intval($_POST['OrderID']),
				'Timeout'=>intval($_POST['Timeout']),
				'DateTime'=>trim($_POST['DataTime']),
				'Msg'=>trim($_POST['Msg']),
				'Place'=>trim($_POST['Place']),
				'Remove'=>intval(0),
			);
			
			if($post){
				$postData = array_merge($post,$postData);
			}
			$SendData["data"]	=	json_encode($postData);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$SendData);
//			print_r($data);
//			die();
			if($data["Result"]===0){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$this->_assign['POST'] = $_POST;
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