<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_PayList_zhanlong extends Action_ActionBase{

	private $jdbc;
	private $user;
	private $pwd;

	public function _init(){
		$this->jdbc = "jdbc:mysql://dbproxy.app100646209.twsapp.com:8000/zlsg_qq_trans?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=10.182.32.11:3325&tgw=tgw_l7_forward";
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
		$page = ($page-1)*50;
		$data = $this->Select("select * from  t_pay order by world_id asc limit ".$page.","."50",$this->jdbc,$this->user,$this->pwd);
		$dataCount = $this->Select("select count(*) from  t_pay",$this->jdbc,$this->user,$this->pwd);
//		print_r($data);
		$this->_assign['dataList']=$data;
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$dataCount[0]['count(*)'],'perpage'=>50));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign['Del_Url']=$this->_urlDel();
		$this->_assign['modify_Url']=$this->_urlModify();
		return $this->_assign;
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'PayList',$query);
	}

	private function _urlModify(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'PayAdd',$query);
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
		$sql = "delete from t_pay where world_id='".$_GET["world_id"]."'";
		if($this->Select($sql,$this->jdbc,$this->user,$this->pwd,true)){
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}
}