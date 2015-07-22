<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_PowerIP_zhanlong extends Action_ActionBase{

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

		if(empty($_GET["page"])){
			$page = 1;
		}else{
			$page = $_GET["page"];
		}
		$page = ($page-1)*PAGE_SIZE;
		$data = $this->Select("select * from  t_allow_ip limit ".$page.",".PAGE_SIZE,$this->jdbc,$this->user,$this->pwd);
		$dataCount = $this->Select("select count(*) from  t_allow_ip",$this->jdbc,$this->user,$this->pwd);
		$this->_assign['dataList']=$data;
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$dataCount[0]['count(*)'],'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign['Del_Url']=$this->_urlDel();
		return $this->_assign;
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'PowerIP',$query);
	}

	private function _add(){
		$sql = "INSERT INTO t_allow_ip(`allow_ip`,`desc_text`) VALUES('".$_POST["Add_IP"]."', '".$_POST["desc"]."')";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}
	
	private function _del(){
	$sql = "delete from t_allow_ip where allow_ip='".$_GET["IP"]."'";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}
}