<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_GongFu extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		$getData = $this->_gameObject->getGetData($get);
		$postData=array();
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		$data = $this->_gameObject->getNotice($postData);	//如果游戏有自定义接口,可以使用
//		if($data){
//			return $data;
//		}
//		$data = '{"data":[{"endTime":0,"url":"","beginTime":0,"id":1,"title":"欢迎访问游戏!!!!","initialDelay":60},{"endTime":0,"url":"","beginTime":0,"id":2,"title":"欢迎参与内测,抓虫拿大奖励,送iphone4S,ipad!.每日登录3万金币.","initialDelay":77},{"endTime":0,"url":"","beginTime":0,"id":4,"title":"精心打造年度玩家最喜爱的游戏","initialDelay":90},{"endTime":0,"url":"","beginTime":0,"id":5,"title":"欢迎充值啊!","initialDelay":130},{"endTime":0,"url":"","beginTime":0,"id":6,"title":"收入决定成败","initialDelay":77},{"endTime":0,"url":"","beginTime":0,"id":7,"title":"简单快乐.","initialDelay":100}],"status":1,"info":null}';
		//$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1' && $data['data']){
			foreach ($data['data'] as &$sub){
				$sub['title_t']	=	$sub['title'];
				$sub['title']	=	strip_tags($sub['title']);
				$sub['beginTime_t']	=	$sub['beginTime'];
				$sub['beginTime'] 	= $sub['beginTime']?date('Y-m-d H:i:s',$sub['beginTime']):'无限制';
				$sub['endTime_t']	=	$sub['endTime'];
				$sub['endTime'] 	= $sub['endTime']?date('Y-m-d H:i:s',$sub['endTime']):'无限制';
				$sub['URL_del'] 	= $this->_urlNoticeDel($sub['id']);
				$sub['URL_edit'] 	= $this->_urlNoticeEdit($sub['id']);
			}
			$this->_assign['dataList']=$data['data'];
		}
//		print_r($data['data']);
		return $this->_assign;
	}
	
	private function _urlNoticeDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}
	
	private function _urlNoticeEdit($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeEdit',$query);
	}
	
	private function _urlNoticeAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeAdd',$query);
	}
	
//"$data" = Array [3]	
//	data = Array [6]	
//		0 = Array [6]	
//			endTime = (int) 0	
//			url = (string:0) 	
//			beginTime = (int) 0	
//			id = (int) 1	
//			title = (string:10) 欢迎访问游戏!!!!	
//			initialDelay = (int) 60	
//		1 = Array [6]	
//		2 = Array [6]	
//		3 = Array [6]	
//		4 = Array [6]	
//		5 = Array [6]	
//	status = (int) 1	
//	info = null
	
}