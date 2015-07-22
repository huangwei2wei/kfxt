<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_Default_1  extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		$this->_assign["items"]	=	$this->_f($this->getFileCacheName("ActionGame_MasterTools_Item_Default_1"));
		$this->_assign["updatecache"] = Tools::url(CONTROL,'Item',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));

		if($_GET["User"]){
			$getData = $this->_gameObject->getGetData($get);
			$getData["User"] = urlencode($_GET["User"]);
			$getData["userType"] = $_GET["userType"];
			$getData["Page"] = max(1,intval($_GET['page']));
			$data = $this->getResult($UrlAppend,$getData);
			$assList = array();
			foreach ($data["List"] as $k=>$v){
				if( isset($assList[$v['pid']])){
					$assList[$v['pid']]['nums'] += $v['nums'];//合并重复信息
				} else {
					$assList[$v['pid']] = $v;
				}
			}
			$this->_assign["List"] = $assList;
			$this->_assign["Column"] = $data["Column"];
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
			$this->_assign['GET'] = $_GET;
			$this->_assign["submitUrl"] = Tools::url(CONTROL,'SendUserPackage',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
		}
		

		return $this->_assign;
	}

	private function _urlNoticeDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'NoticeID'=>$id,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}

	private function _urlNoticeEdit($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'NoticeID'=>$id,
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