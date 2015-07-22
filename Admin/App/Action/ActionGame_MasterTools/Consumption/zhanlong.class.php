<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Consumption_zhanlong extends Action_ActionBase{

	public $_jdbc;
	public $operator_id;

	public $dataIp;

	public $dataBase;
	public function _init(){
		$serverList = $this->_getGlobalData('server/server_list_18');
		$dataInfo 	= 	$serverList[$_REQUEST['server_id']]["data_url"];
		$dataInfo	=	explode(";",$dataInfo);
		$this->operator_id	= 	$serverList[$_REQUEST['server_id']]["operator_id"];
		$this->dataIp 		= $dataInfo[0];
		$this->dataBase 	= $dataInfo[1];
		$this->_jdbc 		= "jdbc:mysql://s82.app24599.qqopenapp.com:8010/".$dataInfo[1]."?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=".$dataInfo[0]."&tgw=tgw_l7_forward";
		$query = array(
			'zp'		=>PACKAGE,
			'__game_id'	=>$this->_gameObject->_gameId,
			'server_id'	=>$_REQUEST['server_id'],
			'playerType'=>1,
		);
		$ShortcutUrl = array(
			'SendMail'		=>Tools::url(CONTROL,'SendMail',$query),
			'SilenceAdd'	=>Tools::url(CONTROL,'SilenceAdd',$query),
			'LockAccountAdd'=>Tools::url(CONTROL,'LockAccountAdd',$query),
			'PointDel'		=>Tools::url(CONTROL,'PointDel',$query),
		);
		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}

		if(!$_REQUEST['sbm']){
			return $this->_assign;
		}
		$itemlist 	= $this->_f("zlsg_t_name_item");
		$serverList = $this->_getGlobalData('server/server_list_18');
		$dataInfo 	= $serverList[$_REQUEST['server_id']]["data_url"];
		if(empty($_GET["player"])){
			$this->jump("玩家不能为空",-1);
		}else{
			if($_GET["playerType"]==1){
				$where = "iRoleId='".$_GET["player"]."'";
			}else{
				$where = "iUin='".$_GET["player"]."'";
			}
		}

		if($_GET["WorldId"]!=""){
			$where .= "and iWorldId='".$_GET["WorldId"]."'";
		}

		if($_GET["beginTime"]!=""){
			$where .= "and dtEventTime>'".$_GET["beginTime"]."'";
		}

		if($_GET["endTime"]!=""){
			$where .= "and dtEventTime<'".$_GET["endTime"]."'";
		}
		if(!empty($_GET["page"])){
			$page = $_GET["page"];
		}else{
			$page = 1;
		}
		$min = 10*($page-1);
		$status = 0;
		if(in_array($this->operator_id,array(83,84,126))){
			$this->_assign['is_tw'] = 0;
			$sqlcount = "select count(*) from ConsumeQQPay where ".$where;
			$sql = "select * from ConsumeQQPay where ".$where." order by dtEventTime desc limit ".$min.",200";
			$data 	= $this->Select($sql,$this->_jdbc);
			$datacount 	= $this->Select($sqlcount,$this->_jdbc);
			$datalist = array();
			$i=0;
			foreach($data as $k=>&$v){
				$i++;
				$arr = explode("*",$v["vPayItem"]);
				if($itemlist[$arr[0]]!=""){
					$name = $itemlist[$arr[0]];
				}else{
					$name=$arr[0];
				}
				$pice = $arr[1];
				$mun 	=	$arr[2];
				$v["vPayItem"] = "道具：".$name.";单价：".$pice.";数量：".$mun;
				$datalist[] = $v;
				if($i>10){
					break;
				}
			}
			$status = 1;
		}else{
			$this->_assign['is_tw'] = 1;
			$sqlcount = "select count(*) from CashLog where ".$where;
			$sql = "select * from CashLog where ".$where." order by dtEventTime desc limit ".$min.",200";
			$data 	= $this->twSelect($sql,$this->dataBase,$this->dataIp,"dataviewer","#@5r5%22");
			$datacount 	= $this->twSelect($sqlcount,$this->dataBase,$this->dataIp,"dataviewer","#@5r5%22");
			
			$itemlist = $this->_f("zlsg_t_name_item");
			$scenelist = $this->_f("zlsg_t_name_scene");
			$e =	$this->_f("logType_".$this->_gameObject->_gameId);
			$datalist = array();
			$i=0;
			foreach($data as $k=>&$v){
				$i++;
				$v["iSceneID"]	=	$scenelist[$v["iSceneID"]];
				$v["iObjectID"]	=	$itemlist[$v["iObjectID"]];
				$v["iDoingEvent"]	=	$e[$v["iDoingEvent"]]["cn"];
				$datalist[] = $v;
				if($i>10){
					break;
				}
			}
			$status = 1;
		}
		
		$info = null;
		if(is_array($data)){
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$datacount[0]["count(*)"],'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $datalist;
			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			//			$this->_assign['connectError'] = 'Error Message:'.$data;
			//			$info = $data;
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