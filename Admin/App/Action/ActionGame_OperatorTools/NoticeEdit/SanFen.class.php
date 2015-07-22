<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_SanFen extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		$noticeType = Tools::gameConfig('noticeType',$this->_gameObject->_gameId);
		$this->_assign['noticeType'] = $noticeType;
		$this->_assign['forbid'] = array('0'=>'不屏蔽','1'=>'屏蔽');
		 
		if(isset($_GET['getOneById'])){
			$getData  = $this->_gameObject->getGetData(array('type'=>'placardlist'));
			$data = $this->getResult('getNotice.jsp',$getData,array('id'=>$_GET['getOneById']));
			
			if($data['status'] == 1){
				$this->_assign['info'] = $data['data'][0]['players'][0];
			}
		}
		if($this->_isPost() && !empty($_POST["subbutton"])){
			$postData = array(
				'id'	=>trim($_GET["getOneById"]),
				'title'=>trim($_POST['title']),
				'delay'=>intval($_POST['IntervalTime']),
				'begin'=>trim($_POST['begin']),
				'end'=>trim($_POST['end']),
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $this->_gameObject->getGetData($get);
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