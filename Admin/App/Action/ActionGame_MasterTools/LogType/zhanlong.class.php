<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LogType_zhanlong extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 1;
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($_REQUEST["op"]=="add"){
			$this->_add();
		}elseif($_REQUEST["op"]=="del"){
			$this->_del();
		}
		$this->_assign["dataList"]	=	$this->_f("logType_".$this->_gameObject->_gameId);
		$this->_assign["delete_url"]	=	Tools::url(CONTROL,'LogType',array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'op'=>"del"
			));
			return $this->_assign;
	}

	private function _del(){
		$typeArr = $this->_f("logType_".$this->_gameObject->_gameId);
		unset($typeArr[$_GET["id"]]);
		$this->_f("logType_".$this->_gameObject->_gameId,$typeArr);
		$this->jump('操作成功',1,Tools::url(CONTROL,'LogType',array(
			'zp'=>PACKAGE,
		'__game_id'=>$this->_gameObject->_gameId,
		)));
	}

	private function _add(){
		$typeArr = $this->_f("logType_".$this->_gameObject->_gameId);
		$typeArr[$_POST["type_id"]]	= array("cn"=>$_POST["chinese"],"en"=>$_POST["english"]);
		$this->_f("logType_".$this->_gameObject->_gameId,$typeArr);
		$this->jump('操作成功',1,Tools::url(CONTROL,'LogType',array(
			'zp'=>PACKAGE,
		'__game_id'=>$this->_gameObject->_gameId,
		)));
	}



}