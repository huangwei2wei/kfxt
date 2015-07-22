<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_GongFu extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData(array('action'=>'get'));
		$postData=array();
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		$data = $this->_gameObject->getNotice($postData);
		if($data['data']){
			foreach($data['data'] as &$item){
				if($item['id']==$_GET['id']){
					$this->_assign['selected']	=	$item;
				}
			}
		}
		
		if($this->_isPost() && !empty($_POST["subbutton"])){
			$postData = array(
				'id'	=>trim($_GET["id"]),
				'title'=>trim($_POST['title']),
				'url'=>trim($_POST['url']),
				'delay'=>intval($_POST['initialDelay']),
				'begin'=>trim($_POST['beginTime']),
				'end'=>trim($_POST['endTime']),
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);	
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