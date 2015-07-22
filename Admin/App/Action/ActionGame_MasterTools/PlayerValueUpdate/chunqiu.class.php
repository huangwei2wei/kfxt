<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerValueUpdate_chunqiu extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$postData = array(
				'type'=>intval($_POST['type']),
				'id'=>trim($_POST['id']),
				'name'=>base64_encode($_POST['name']),
				'user_id'=>$_POST['user_id'],
				'flag'=>intval($_POST['flag']),
				'level'=>intval($_POST['level']),
				'exp'=>intval($_POST['exp']),
				'evil'=>intval($_POST['evil']),
				'scene_id'=>intval($_POST['scene_id']),
				'pos_x'=>intval($_POST['pos_x']),
				'pos_y'=>intval($_POST['pos_y']),
			);
			$SendData["data"]	=	json_encode($postData);
			$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
			$data = json_decode($data,true);
			if($data['status'] == 1){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'PlayerValueUpdate',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}