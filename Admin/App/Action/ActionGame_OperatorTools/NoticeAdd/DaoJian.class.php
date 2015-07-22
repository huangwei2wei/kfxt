<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeAdd_DaoJian extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
// 		$noticeType = Tools::gameConfig('noticeType',$this->_gameObject->_gameId);
		$noticeType = array(0=>'跑马灯', 1=>'游戏信息公告', 2=>'喇叭', 3=> '跑马灯+聊天(紧急公告)',4=>' 聊天公告');
		$this->_assign['noticeType'] = $noticeType;
		 
		if($this->_isPost() && !empty($_POST["subbutton"])){
			$postData = array(
				'title'=>trim($_POST['title']),
				'contents'=>trim($_POST['contents']),
				'intervals'=>intval($_POST['intervals']),
				'noticeType'=>intval($_POST['noticeType']),
				'startTime'=> trim($_POST['startTime']),
				'endTime'=> trim($_POST['endTime']),
				'op'=>0,
				'playMode'=>1,
				'status'=>1,
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
//			print_r($data);exit;
			if($data['status'] == 0){
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