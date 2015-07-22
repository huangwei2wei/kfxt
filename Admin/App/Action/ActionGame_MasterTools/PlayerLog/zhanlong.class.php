<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_zhanlong extends Action_ActionBase{

	public $_jdbc;

	public $iDoingEvent;

	public $iDoingObject;

	public $operator_id;

	public $dataIp;

	public $dataBase;

	public function _init(){
		$serverList = $this->_getGlobalData('server/server_list_18');

		$dataInfo 	= 	$serverList[$_REQUEST['server_id']]["data_url"];
		$dataInfo	=	explode(";",$dataInfo);
		$this->operator_id= 	$serverList[$_REQUEST['server_id']]["operator_id"];
		$this->dataIp = $dataInfo[0];
		$this->dataBase = $dataInfo[1];
		$this->_jdbc = "jdbc:mysql://s82.app24599.qqopenapp.com:8010/".$dataInfo[1]."?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=".$dataInfo[0]."&tgw=tgw_l7_forward";
		$this->iDoingEvent = $this->getiDoingEvent();
		$this->iDoingObject	=	$this->getiDoingObject();
		$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				'cache'	=>"1"
				);
				$this->_assign['cache'] = Tools::url(CONTROL,'PlayerLog',$query);
				$ajax = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ajax'	=>"1"
			);
			$this->_assign['ajax'] = Tools::url(CONTROL,'PlayerLog',$ajax);
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["cache"]=="1"){
			$this->_cache();
		}elseif($_GET["ajax"]=="1"){
			$this->_ajax();
		}
		$this->_assign['moneytype']=$this->getMoney();
		$this->_assign['tableArr']=$this->_getDataTable();
		if ($_GET["iDoingEvent"]==""){
			$this->_assign['MiDoingEvent']	=	999;
		}else{
			$this->_assign['MiDoingEvent']	=	$_GET["iDoingEvent"];
		}
		$itemlist = $this->_f("zlsg_t_name_item");
		$scenelist = $this->_f("zlsg_t_name_scene");
		$iDoingEvent = $this->getiDoingEvent();
		$this->_assign["iDoingEvent"]	=	$iDoingEvent;



		if(!$_REQUEST['sbm']){
			return $this->_assign;
		}
		if(empty($_GET["player"])){
			$this->jump("玩家不能为空",-1);
		}
		$where = "iRoleId='".$_GET["player"]."'";


		if($_GET["beginTime"]!=""){
			$where .= "and dtEventTime>'".$_GET["beginTime"]."'";
		}


		if($_GET["endTime"]!=""){
			$where .= "and dtEventTime<'".$_GET["endTime"]."'";
		}
		$rwhere = $where;
		if($_GET["Table"]=="Doing_Money"){
			$where .= " and iObjectID='".$_GET["moneytype"]."'";
		}
		if($_GET["iDoingEvent"]!=999){
			$where .= "and iDoingEvent='".$_GET["iDoingEvent"]."'";
		}
		if(!empty($_GET["page"])){
			$page = $_GET["page"];
		}else{
			$page = 1;
		}
		$min = 10*($page-1);

		$sql = "select * from Doing_Stream where ".$where." order by dtEventTime desc limit ".$min.","."2000";

		$upsql 	= "select * from RoleLevelUp where ".$rwhere;
		if(in_array($this->operator_id,array(83,84,126))){
			$data 	= $this->Select($sql,$this->_jdbc);
		}else{
			//			twSelect($sql,$url,$user="",$pwd="",$is_ac=false);
			$data 	= $this->twSelect($sql,$this->dataBase,$this->dataIp,"dataviewer","#@5r5%22");
		}

		$Job = $this->_gameObject->getJob();
		$dataList = array();
		$i = 0;
		foreach($data as $k=>&$v){
			$now=0;
			$r=array();
			if($scenelist[$v["iSceneID"]]!=""){
				$v["iSceneID"]	=	$scenelist[$v["iSceneID"]];
			}
			$i++;
			$sub = array();
			$v["desc"]	=	$this->iDoingEvent[$v["iDoingEvent"]]["cn"];
			$v["iJobId"]	=	$Job[$v["iJobId"]];
			//			$sub["iDoingEvent"] 	=  	$this->iDoingEvent[$v["iDoingEvent"]]["cn"];
			//			$sub["iDoingObject"]	=	$this->iDoingObject[$v["iDoingObject"]]["des"];
			//			$sub["dtEventTime"] 	= 	date("Y-m-d h:i:s",strtotime($v["dtEventTime"]));

			//			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iObjectID"])){
			//				$sub["iObjectID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iObjectID"][$v["iObjectID"]];
			//			}
			//			if($v["iLinkID"]!=0){
			//				if(is_array($this->iDoingObject[$v["iDoingObject"]]["iLinkID"])){
			//					$sub["iLinkID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iLinkID"][$v["iLinkID"]];
			//				}else{
			//					$sub["iLinkID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iLinkID"].$v["iLinkID"];
			//				}
			//			}
			//
			//			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iToValue"])){
			//				if($this->iDoingObject[$v["iDoingObject"]]["iToValue"][$v["iToValue"]]!=""){
			//					$sub["iToValue"]	=	" -> ".$this->iDoingObject[$v["iDoingObject"]]["iToValue"][$v["iToValue"]];
			//				}else{
			//					$sub["iToValue"]	=	$v["iToValue"];
			//				}
			//			}else{
			//				if($this->iDoingObject[$v["iDoingObject"]]["iToValue"].$v["iToValue"]!=""){
			//					$sub["iToValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iToValue"].$v["iToValue"];
			//				}
			//
			//			}
			//
			//			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iFromValue"])){
			//				if(empty($this->iDoingObject[$v["iDoingObject"]]["iFromValue"][$v["iFromValue"]])){
			//					$sub["iFromValue"]	=	$v["iFromValue"];
			//				}else{
			//					$sub["iFromValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iFromValue"][$v["iFromValue"]];
			//				}
			//			}else{
			//				$sub["iFromValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iFromValue"].$v["iFromValue"];
			//			}
			//			$sub["iChangeValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iChangeValue"].$v["iChangeValue"];
			//
			//			$v["desc"]	=	"操作事件:".$sub["iDoingEvent"];
			//			if($v["iLinkID"]){
			//				$v["desc"].="[".$sub["iLinkID"]."]";
			//			}
			//			$v["desc"] .= "<br/>操作对象：".$sub["iDoingObject"];
			//
			//			if($v["iObjectID"]!=0){
			//				$v["desc"] .= "/".$sub["iObjectID"];
			//			}
			//			$v["desc"] .= ":";
			//			if($v["iFromValue"]!=0){
			//				$v["desc"] .= $sub["iFromValue"];
			//			}
			//			if($v["iToValue"]!=0){
			//				$v["desc"] .= $sub["iToValue"];
			//			}
			//			if($v["iChangeValue"]!=0){
			//				$v["desc"] .= ",".$sub["iChangeValue"];
			//			}
			$dataList[] = $v;
			if($i>20){
				break;
			}
		}
		if(is_array($data)){
			$this->_loadCore('Help_Page');//载入分页工具
			if($page==1){
				$_GET["total"]=count($data);
			}
			$helpPage=new Help_Page(array('total'=>$_GET["total"],'perpage'=>20,"url"=>Tools::url(CONTROL,'ServicerMaintainSync',$_GET)));
			///admin.php?server_id=1105&zp=ActionGame&__game_id=18&c=MasterTools&a=PlayerLog&Table=Doing_Money&iDoingEvent=999&player=16&playerType=1&beginTime=&endTime=&sbm=%E6%9F%A5%E8%AF%A2&page=2
			$this->_assign['dataList'] = $dataList;
			$this->_assign['pageBox'] = $helpPage->show();
		}

		return $this->_assign;
	}


	public function _dataChange($data){
		return $data;
	}

	public function _ajax(){
		$sql = "SELECT * FROM Doing where iEventId='".$_POST["iEventId"]."'";
		$table = $this->_getDataTable();
		foreach($table as $k=>&$v){
			$sql.=" UNION ALL SELECT * FROM ".$k." where iEventId='".$_POST["iEventId"]."'";
		}
		$data = $this->Select($sql,$this->_jdbc);
		$dataList = array();
		foreach($data as $k=>$v){
			$name = $v["iDoingEvent"]."_".$v["iDoingObject"]."_".$v["iLinkID"];
			if(!is_array($dataList[$name])){
				$dataList[$name] = $v;
				$dataList[$name]["count"] = 1;
			}else{
				if($dataList[$name]["iFromValue"]>=$v["iFromValue"]||$dataList[$name]["iFromValue"]==""){
					$dataList[$name]["iFromValue"]=$v["iFromValue"];
				}
				if($dataList[$name]["iToValue"]<=$v["iToValue"]||$dataList[$name]["iToValue"]==""){
					$dataList[$name]["iToValue"]=$v["iToValue"];
				}
				$dataList[$name]["count"]++;
				$dataList[$name]["iChangeValue"] +=$v["iChangeValue"];
			}
		}
		foreach($dataList as $k=>&$v){
			if($scenelist[$v["iSceneID"]]!=""){
				$v["iSceneID"]	=	$scenelist[$v["iSceneID"]];
			}
			$sub = array();
			$sub["iDoingEvent"] =  $this->iDoingEvent[$v["iDoingEvent"]]["cn"];
			$sub["iDoingObject"]	=	$this->iDoingObject[$v["iDoingObject"]]["des"];
			$sub["dtEventTime"] = date("Y-m-d h:i:s",strtotime($v["dtEventTime"]));
			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iObjectID"])){
				$sub["iObjectID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iObjectID"][$v["iObjectID"]];
			}
			if($v["iLinkID"]!=0){
				if(is_array($this->iDoingObject[$v["iDoingObject"]]["iLinkID"])){
					//					$item = $this->Select("select * from Item where iEventId='".$v["iEventId"]."' and iItemID='".$v["iLinkID"]."'",$this->_jdbc);
					//					if(count($item)>0){
					//						$itemDesc = "物品数量：".$item["iNumber"];
					//						if($item["iIsBinded"]==1){$itemDesc.="/是否绑定：是";}else{$itemDesc.="/是否绑定：否";}
					//						if($item["iIsMaturity"]==1){$itemDesc.="/是否到期：是";}else{$itemDesc.="/是否到期：否";}
					//						if($item["iIsDurable"]==1){$itemDesc.="/是否到耐久时间：是";}else{$itemDesc.="/是否到耐久时间：否";}
					//						if($item["iIsLoss"]==1){$itemDesc.="/是否到流失时间：是";}else{$itemDesc.="/是否到流失时间：否";}
					//						$itemDesc.="/时间耐久度:".$item["iTimeDurable"];
					//						$itemDesc.="/强化等级:".$item["iImproveLevel"];
					//						$itemDesc.="/历史强化最高等级:".$item["iImproveLevelMax"];
					//						$itemDesc.="/装备强化次数:".$item["iImproveCount"];
					//						$itemDesc.="/装备晋级次数:".$item["iUpgradeCount"];
					//
					//					}
					$sub["iLinkID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iLinkID"][$v["iLinkID"]];
				}else{
					$sub["iLinkID"]	=	$this->iDoingObject[$v["iDoingObject"]]["iLinkID"].$v["iLinkID"];
				}
			}

			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iToValue"])){
				if($this->iDoingObject[$v["iDoingObject"]]["iToValue"][$v["iToValue"]]!=""){
					$sub["iToValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iToValue"][$v["iToValue"]];
				}else{
					$sub["iToValue"]	=	$v["iToValue"];
				}
			}else{
				$sub["iToValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iToValue"].$v["iToValue"];
			}

			if(is_array($this->iDoingObject[$v["iDoingObject"]]["iFromValue"])){
				if(empty($this->iDoingObject[$v["iDoingObject"]]["iFromValue"][$v["iFromValue"]])){
					$sub["iFromValue"]	=	$v["iFromValue"];
				}else{
					$sub["iFromValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iFromValue"][$v["iFromValue"]];
				}

			}else{
				$sub["iFromValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iFromValue"].$v["iFromValue"];
			}
			$sub["iChangeValue"]	=	$this->iDoingObject[$v["iDoingObject"]]["iChangeValue"].$v["iChangeValue"];
			$v["desc"]  = "操作玩家：".$v["iRoleId"]."<br/>";
			$v["desc"]	.=	"操作事件:".$sub["iDoingEvent"];
			if($v["iLinkID"]!=0){
				$v["desc"].="[".$sub["iLinkID"]."]";
			}
			if($v["count"]){
				$v["desc"].="[次数：".$v["count"]."]";
			}
			$v["desc"] .= "<br/>操作对象：".$sub["iDoingObject"];

			if($v["iObjectID"]!=0){
				$v["desc"] .= "/".$sub["iObjectID"];
			}
			$v["desc"] .= ":";
			if($v["iFromValue"]!=0){
				$v["desc"] .= $sub["iFromValue"];
			}
			if($v["iToValue"]!=0){
				$v["desc"] .= $sub["iToValue"];
			}
			if($v["iChangeValue"]!=0){
				$v["desc"] .= ",".$sub["iChangeValue"];
			}
			$dataArr[] = $v;
		}
		if(count($dataArr)<1){
			die("没找到相关记录");
		}
		foreach($dataArr as $v){
			echo $v["desc"]."<br/>";
			echo "===============================<br/>";
		}
		die();
	}

	private function _cache(){
		$jdbc = "jdbc:mysql://s82.app24599.qqopenapp.com:8010/zlsg_qq_dip?characterEncoding=utf8&connectTimeout=30000&extrakey=kkyt678&extra=10.182.32.11:3325&tgw=tgw_l7_forward";
		$item = $this->Select("select*from t_name_item",$jdbc);
		$itemlist = array();
		foreach($item as $ik=>$iv){
			$itemlist[$iv["_id"]]	=	$iv["_name"];
		}
		$this->_f("zlsg_t_name_item",$itemlist);
		$scene = $this->Select("select*from t_name_scene",$jdbc);
		foreach($scene as $ik=>$iv){
			$scenelist[$iv["_id"]]	=	$iv["_name"];
		}

		$this->_f("zlsg_t_name_scene",$scenelist);

		$quest = $this->Select("select*from t_name_quest",$jdbc);
		foreach($quest as $ik=>$iv){
			$questlist[$iv["_id"]]	=	$iv["_name"];
		}
		$this->_f("zlsg_t_name_quest",$questlist);

		$quest = $this->Select("select*from t_name_potential",$jdbc);
		foreach($quest as $ik=>$iv){
			$questlist[$iv["_id"]]	=	$iv["_name"];
		}
		$this->_f("zlsg_t_name_potential",$questlist);

		$quest = $this->Select("select*from t_name_skill",$jdbc);
		foreach($quest as $ik=>$iv){
			$questlist[$iv["_id"]]	=	$iv["_name"];
		}
		$this->_f("zlsg_t_name_skill",$questlist);
	}


	public function getiDoingEvent(){
		return $this->_f("logType_".$this->_gameObject->_gameId);
	}

	public function getiDoingObject(){
		return $this->_gameObject->getiDoingObject();
	}

	public function _getDataTable(){
		return $this->_gameObject->_getDataTable();
	}


}