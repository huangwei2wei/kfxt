<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_StarDream extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		$getData = $this->_gameObject->getGetData($get);
		if( $this->_isPost() && !empty($_POST["subbutton"]) ){
			$getData = $this->_gameObject->getGetData($get);
			$postData = array(
				'id'=>intval($_POST['id']),
				'startTime'	=>intval(strtotime($_POST['startTime'])),
				'endTime'		=>intval(strtotime($_POST['endTime'])),
				'intervalTime'	=>intval($_POST['intervalTime']),
				'content'=>base64_encode(trim($_POST['content'])),
			);
			
			$data = $this->_gameObject->result('NoticeEdit',$getData,$postData);
			if($data['status'] == 1){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		
		
		$noticeInfo = $this->_gameObject->result('Notice',$getData,array('id'=>intval($_POST['id']),'page'=>1,'pageSize'=>20));
		if( $noticeInfo['status']=='1' ){
			$this->_assign['selected'] = array(
				'id'=>$noticeInfo['data']['list']['id'],
				'content'=>$noticeInfo['data']['list']['content'],
				'beginTime'=>date('Y-m-d H:i:s',$noticeInfo['data']['list']['startTime']),
				'endTime'=>date('Y-m-d H:i:s',$noticeInfo['data']['list']['endTime']),
				'spaceTime'=>$noticeInfo['data']['list']['spaceTime'],
			);
		} else {
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$noticeInfo['info'],-1);
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