<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_zhanlong extends Action_ActionBase{

	public function _init(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'playerType'=>1,
		);
		$ShortcutUrl = array(
			'SendMail'=>Tools::url(CONTROL,'SendMail',$query),
			'SilenceAdd'=>Tools::url(CONTROL,'SilenceAdd',$query),
			'LockAccountAdd'=>Tools::url(CONTROL,'LockAccountAdd',$query),
			'PointDel'=>Tools::url(CONTROL,'PointDel',$query),
		);
		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$uslarr = $this->_getGlobalData('server/server_list_18');
		if(!$_REQUEST['sbm']){
			return $this->_assign;
		}

		$getData				 	= $this->_gameObject->getGetData($get);
		$getData["a"]				=	"Recharge";
		$getData["pageSize"]		=	PAGE_SIZE;
		$getData["pageCount"]		=	max(0,intval($_GET['page']-1));
		if(!empty($_GET["player"])){
			$getData["player"]	=	$_GET["player"];
			$getData["playerType"]	=	$_GET["playerType"];
		}

		if(!empty($_GET["keywords"])){
			$getData["orderid"]	=	$_GET["keywords"];
		}

		if(!empty($_GET["beginTime"])){
			$getData["StartTime"]	=	$_GET["beginTime"];
		}

		if(!empty($_GET["endTime"])){
			$getData["EndTime"]	=	$_GET["endTime"];
		}
		
		$getData["server"] = "s".$uslarr[$_REQUEST["server_id"]]["ordinal"];
		$data = $utilHttpInterface->result($uslarr[$_REQUEST["server_id"]]["data_url"].$UrlAppend,"",$getData,"");
		$data = json_decode($data,true);
		
		$status = 0;
		$info = null;
		if(is_array($data)){
			$status = 1;
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data["totals"],'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $data["List"];
			$this->_assign['pageBox'] = $helpPage->show();

		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data;
			$info = $data;
		}
			
		return $this->_assign;
	}

	private function _vocationId($vocationId=0){
		static $vocation = false;	//首次执行，放进内存保存
		if($vocation === false){
			$vocation = Tools::gameConfig('vocationId',$this->_gameObject->_gameId);
			//vocation 职业 :1武者 ,2气宗 ,3药师
		}
		return $vocation[$vocationId];

	}


}