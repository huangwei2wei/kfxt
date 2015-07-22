<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_WebAdd_zhanlong extends Action_ActionBase{

	private $jdbc;
	private $user;
	private $pwd;

	public function _init(){
		$this->jdbc = "jdbc:mysql://dbproxy.app100646209.twsapp.com:8000/zlsg_qq_web?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=10.182.32.11:3325&tgw=tgw_l7_forward";
		$this->user = "dataroot";
		$this->pwd  = "qq@7%ssAjk3D";
		$this->_assign["GET"]	=	$_GET;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){

		if($this->_isAjax()){
			$sql = "UPDATE t_web_list SET resource_version='".$_POST["resource_version"]."' WHERE world_id in({$_POST["server"]})";
			$this->Select($sql,$this->jdbc,$this->user,$this->pwd,true);
			die();
		}
		if($this->_isPost()){
			if($_GET["world_id"]!=""){
				$sql = "UPDATE t_web_list SET server_ip='".$_POST["server_ip"]."',server_port='".$_POST["server_port"]."',platform_ip='".$_POST["platform_ip"]."',platform_port='".$_POST["platform_port"]."',language_pack='".$_POST["language_pack"]."',resource_url='".$_POST["resource_url"]."',resource_version='".$_POST["resource_version"]."',world_name='".$_POST["world_name"]."' WHERE world_id='".$_GET["world_id"]."'";
			}else{
				$sql = "INSERT INTO t_web_list(`server_ip`,`server_port`,`platform_ip`,`platform_port`,`language_pack`,`resource_url`,`resource_version`,`world_name`,`world_id`) VALUES('".$_POST["server_ip"]."', '".$_POST["server_port"]."', '".$_POST["platform_ip"]."', '".$_POST["platform_port"]."', '".$_POST["language_pack"]."', '".$_POST["resource_url"]."', '".$_POST["resource_version"]."', '".$_POST["world_name"]."', '".$_POST["world_id"]."')";
			}
			if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){

			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		
		if($_GET["world_id"]!=""){
			$server=$this->Select("select world_id,world_name from  t_web_list",$this->jdbc,$this->user,$this->pwd);
			$a = $this->Select("select * from  t_web_list where world_id='".$_GET["world_id"]."'",$this->jdbc,$this->user,$this->pwd);
			$this->_assign['data']=$a[0];
			$this->_assign['server']=$server;
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