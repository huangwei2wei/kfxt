<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ServicerMaintainSync_zhanlong extends Action_ActionBase{

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

		if($this->_isPost()&&count($_POST["world_id"])<1){
			$idstr = implode(",",$_POST["world_ids"]);
			$sql = "UPDATE t_maintain SET maintain_text='".$_POST["maintain_text"]."',maintain_state='".$_POST["maintain_state"]."',maintain_link='".$_POST["maintain_link"]."' WHERE world_id in(".$idstr.")";
			if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
				$this->jump('操作成功',1);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		if($_GET["world_id"]!=""){
			$a = $this->Select("select * from  t_maintain  where world_id='".$_GET["world_id"]."'",$this->jdbc,$this->user,$this->pwd);
			$this->_assign['data']=$a[0];
		}
		$data = $this->Select("select * from  t_maintain",$this->jdbc,$this->user,$this->pwd);
		if(count($_POST["world_id"])>1){
			foreach($data as $k=>&$v){
				if(in_array($v["world_id"],$_POST["world_id"])){
					$v["ac"]	=	1;

				}

			}
		}
		$this->_assign['dataList']=$data;
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