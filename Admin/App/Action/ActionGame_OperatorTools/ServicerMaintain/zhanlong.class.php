<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ServicerMaintain_zhanlong extends Action_ActionBase{

	private $jdbc;
	private $user;
	private $pwd;

	public function _init(){
		$this->jdbc = "jdbc:mysql://dbproxy.app100646209.twsapp.com:8000/zlsg_qq_web?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=10.182.32.11:3325&tgw=tgw_l7_forward";
		$this->user = "dataroot";
		$this->pwd  = "qq@7%ssAjk3D";
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($_POST["op"]=="add"){
			$this->_add();
		}elseif($_GET["op"]=="del"){
			$this->_del();
		}

		if($_GET["ac"]=="stop"){
			$this->_allStop();
		}elseif($_GET["ac"]=="action"){
			$this->_allAction();
		}

		if(empty($_GET["page"])){
			$page = 1;
		}else{
			$page = $_GET["page"];
		}
		$page = ($page-1)*50;
		$data = $this->Select("select * from  t_maintain order by world_id asc limit ".$page.","."50",$this->jdbc,$this->user,$this->pwd);
		$dataWeb = $this->Select("select * from  t_web_list",$this->jdbc,$this->user,$this->pwd);
		foreach($data as $k=>&$v){
			foreach($dataWeb as $sk=>&$sv){
				if($sv["world_id"]==$v["world_id"]){
					
					$v["server_name"]	=	$sv["world_name"];
				}
			}
			if($v["maintain_state"]=="1"){
				$v["maintain_state"] = "停机";
			}else{
				$v["maintain_state"] = "正常";
				
			}
		}

		$dataCount = $this->Select("select count(*) from  t_maintain",$this->jdbc,$this->user,$this->pwd);
		$this->_assign['dataList']=$data;
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$dataCount[0]['count(*)'],'perpage'=>50));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign['stop_Url']=$this->_urlAllStop();
		$this->_assign['action_Url']=$this->_urlAllAction();
		$this->_assign['Del_Url']=$this->_urlDel();
		$this->_assign['modify_Url']=$this->_urlModify();
		return $this->_assign;
	}

	private function _urlAllStop(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ac'=>"stop"
			);
			return Tools::url(CONTROL,'ServicerMaintain',$query);
	}

	private function _urlAllAction(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ac'=>"action"
			);
			return Tools::url(CONTROL,'ServicerMaintain',$query);
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


	private function _allStop(){
		if($this->_isPost()){
			$idstr = implode(",",$_POST["world_id"]);
		}else{
			$idstr = $_GET["world_id"];
		}
	
		$sql ="UPDATE t_maintain SET maintain_state='1' WHERE world_id in(".$idstr.")";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	private function _allAction(){
		if($this->_isPost()){
			$idstr = implode(",",$_POST["world_id"]);
		}else{
			$idstr = $_GET["world_id"];
		}
		$sql ="UPDATE t_maintain SET maintain_state='0' WHERE world_id in(".$idstr.")";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	private function _add(){
		$sql = "INSERT INTO t_maintain(`world_id`,`maintain_state`,`maintain_text`,`maintain_link`) VALUES('".$_POST["world_id"]."', '".$_POST["maintain_state"]."', '".$_POST["maintain_text"]."', '".$_POST["maintain_link"]."')";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	private function _del(){
		$sql = "delete from t_maintain where world_id='".$_GET["world_id"]."'";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}
}