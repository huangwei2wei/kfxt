<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_BadWord_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$sendData["BadWord"]	=	explode(",", $_POST["BadWord"]);
			$PostData["data"]	=	json_encode($sendData);
			$post["data"]	=	json_encode($sendData);
			//			echo $PostData["data"];
			$getData = $this->_gameObject->getGetData();
			$data = $this->getResult("UpdateSystem/BadWord",$getData,$PostData);
			if($data["Result"]===0){
				$jumpUrl = $this->_url();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]	=	max(0,intval($_GET['page']-1));;
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] === 0){
			$this->_assign['dataList']=$data['BadWord'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>"50"));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		return $this->_assign;
	}

	private function _url(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'BadWord',$query);
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