<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_PayAdd_zhanlong extends Action_ActionBase{

	private $jdbc;
	private $user;
	private $pwd;

	public function _init(){
		$this->jdbc = "jdbc:mysql://dbproxy.app100646209.twsapp.com:8000/zlsg_qq_trans?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=10.182.32.11:3325&tgw=tgw_l7_forward";
		$this->user = "dataroot";
		$this->pwd  = "qq@7%ssAjk3D";
		$this->_assign["GET"]	=	$_GET;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){

		if($this->_isPost()){
			if($_GET["world_id"]!=""){
				$sql = "UPDATE t_pay SET net_host='".$_POST["net_host"]."',net_port='".$_POST["net_port"]."',_text='".$_POST["_text"]."' WHERE world_id='".$_GET["world_id"]."'";
			}else{
				$sql = "INSERT INTO t_pay(`world_id`,`net_host`,`net_port`,`_text`) VALUES('".$_POST["world_id"]."', '".$_POST["net_host"]."', '".$_POST["net_port"]."', '".$_POST["_text"]."')";
			}
			if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		if($_GET["world_id"]!=""){
			$a = $this->Select("select * from  t_pay where world_id='".$_GET["world_id"]."'",$this->jdbc,$this->user,$this->pwd);
			$this->_assign['data']=$a[0];
		}
		return $this->_assign;
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ServicerMaintain',$query);
	}

	private function _urlModify(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ServicerMaintainSync',$query);
	}
}