<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_Default_1_1 extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!isset($_REQUEST['server_id'])){
			return array();
		}

		if(isset($_GET['id'])){
			$get  =  $this->_gameObject->getGetData($get);
			$get = array_merge($get,array('id'=>$_GET['id']));
			$data = $this->getResult('getNoticeList.php',$get);
				
			if($data['status'] == 1){
				$this->_assign['info'] = $data['data']['list'][0];
			}
		}
		if($this->_isPost() && !empty($_POST["subbutton"])){
			$data = array(
				'id'	=>trim($_GET["id"]),
				'message'=>trim($_POST['message']),
				'intervalTime'=>intval($_POST['intervalTime']),
				'startTime'=>strtotime(trim($_POST['startTime'])),
				'endTime'=>strtotime(trim($_POST['endTime'])),
			);
			$get  =  $this->_gameObject->getGetData($get);
			$postData = $data;
			$data = $this->getResult($UrlAppend,$get,$postData);
			if($data['status'] == 1){
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
			'server_id'=>$_REQUEST['server_id']
		);
		return Tools::url(CONTROL,'Notice',$query);
	}

}