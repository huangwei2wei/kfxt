<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_SysLog_Default extends Action_ActionBase{

	public function _init(){
		$this->_assign["GET"]	=	$_GET;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($_GET["ac"]=="ajax"){
			$this->_descAjax();
		}
		$userList = $this->_f("user_all");
		if($_REQUEST["Acuser"]!=""){
			foreach($userList as $k=>$v){
				if($v["nick_name"]==$_REQUEST["Acuser"]){
					$acuser = $k;
				}
			}
		}

		$dataLog = $this->_modelLog->getLogData($this->_gameObject->_gameId,$_REQUEST["page"],$_REQUEST["Actype"],$acuser,$_REQUEST["begin"],$_REQUEST["end"]);
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$menu=$this->_utilRbac->getUserMoudleMenu("ActionGame");
		$game	=	$this->_getGlobalData("game_type");
		foreach($dataLog["data"] as $k=>&$v){
			$v["time"]		=	$v["actime"];
			$v["user"]		=	$v["acuser"];
			$v["game_id"]	=	$game[$v["game_id"]]["name"];
			$v["acuser"]	=	$userList[$v["acuser"]]["nick_name"];
			$v["actime"]	=	date("Y-m-d H:i:s",$v["actime"]);
			if($menu[$v["control"]]["child"][$v["action"]]["name"]!=""){
				$v["action"]			=	$menu[$v["control"]]["child"][$v["action"]]["name"];
			}
			$v["control"]			=	$menu[$v["control"]]["name"];
		}
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$dataLog["count"][0]['count(Id)'],'perpage'=>30));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign["dataLog"]	=	$dataLog["data"];
		$this->_assign["all_user"]	=	$userList;
		$this->_assign["ac_type"]	=	$menu;
		$this->_assign["ajax_url"]	=	$this->_urlajax();
		return $this->_assign;
	}

	private function _descAjax(){
		$data = $this->_modelLogDesc->findByActimeAndAcuser($_REQUEST["actime"],$_REQUEST["acuser"]);

		$reData["state"] = intval(1);
		$d = "";
		foreach($data as $k=>$v){
			$d .="请求数据：<br>".base64_decode($v["subData"])."<br/><br/>";
			$d .="返回数据：<br>".base64_decode($v["returnData"])."<Br/><br/>";
		}
		echo $d;
		die();
	}

	private function _urlajax(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ac'		=>"ajax",
		);
		return Tools::url(CONTROL,'SysLog',$query);
	}
}