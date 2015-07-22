<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemLog_zhanlong extends Action_ActionBase{

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
				$this->_assign['cache'] = Tools::url(CONTROL,'ItemLog',$query);
				$ajax = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ajax'	=>"1"
			);
			$this->_assign['ajax'] = Tools::url(CONTROL,'ItemLog',$ajax);
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["cache"]=="1"){
			$this->_cache();
		}
		elseif($_GET["ajax"]=="1"){
			$this->_ajax();
		}
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
		if(empty($_GET["player"])&&empty($_GET["iEventId"])){
			$this->jump("玩家或事件不能为空",-1);
		}
		if(!empty($_GET["iEventId"])){
			$where = "iEventId='".$_GET["iEventId"]."'";
		}else{
			$where = "iRoleId='".$_GET["player"]."'";
			if($_GET["itemName"]!=""){
				foreach($itemlist as $k=>$v){
					if($v==$_GET["itemName"]){
						$where .=" and iItemID='".$k."'";
					}
				}
			}

			if($_GET["beginTime"]!=""){
				$where .= "and dtEventTime>'".$_GET["beginTime"]."'";
			}

			if($_GET["endTime"]!=""){
				$where .= "and dtEventTime<'".$_GET["endTime"]."'";
			}
		}
		$rwhere = $where;
		if($_GET["iDoingEvent"]!=999){
			$where .= "and iDoingEvent='".$_GET["iDoingEvent"]."'";
		}
		if(!empty($_GET["page"])){
			$page = $_GET["page"];
		}else{
			$page = 1;
		}
		$min = 10*($page-1);
		$sql = "select * from Item where ".$where." order by dtEventTime desc limit ".$min.","."2000";
		if(in_array($this->operator_id,array(83,84,126))){
			$data 	= $this->Select($sql,$this->_jdbc);
		}else{
			$data 	= $this->twSelect($sql,$this->dataBase,$this->dataIp,"dataviewer","#@5r5%22");
		}
		$Job = $this->_gameObject->getJob();
		$dataList = array();
		$i = 0;
		$item =$this->_f("zlsg_t_name_item");
		$items = $this->_f("18_ActionGame_MasterTools_Define_zhanlong",'',CACHE_DIR);
		$attribute	=	$items["AllDefine"][2]["Detail"];
		foreach($data as $k=>&$v){
			if(!empty($this->iDoingEvent[$v["iDoingEvent"]]["cn"])){
				$v["iDoingEvent"]=$this->iDoingEvent[$v["iDoingEvent"]]["cn"];
			}
			if(!empty($item[$v["iItemID"]])){
				$v["iItemID"] =	$item[$v["iItemID"]];
			}
			$v["iIsBinded"]==1?$v["iIsBinded"]="是":$v["iIsBinded"]="否";
			$v["iIsMaturity"]==1?$v["iIsMaturity"]="是":$v["iIsMaturity"]="否";
			$v["iIsDurable"]==1?$v["iIsDurable"]="是":$v["iIsDurable"]="否";
			$v["iIsLoss"]==1?$v["iIsLoss"]="是":$v["iIsLoss"]="否";

			if($v["iAttachType1"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType1"]]["Note"]."/".$v["iAttachRatio1"].")<br>";
			}
			if($v["iAttachType2"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType2"]]["Note"]."/".$v["iAttachRatio2"].")<br>";
			}
			if($v["iAttachType3"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType3"]]["Note"]."/".$v["iAttachRatio3"].")<br>";
			}
			if($v["iAttachType4"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType4"]]["Note"]."/".$v["iAttachRatio4"].")<br>";
			}
			if($v["iAttachType5"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType5"]]["Note"]."/".$v["iAttachRatio5"].")<br>";
			}
			if($v["iAttachType6"]!=0){
				$v["Attach"]	.="(".$attribute[$v["iAttachType6"]]["Note"]."/".$v["iAttachRatio6"].")<br>";
			}
			$i++;
			$dataList[]=$v;
			if($i>10){
				break;
			}
		}
		//		print_r($dataList);
		if(is_array($data)){
			$this->_loadCore('Help_Page');//载入分页工具
			if($page==1){
				$_GET["total"]=count($data);
			}
			$helpPage=new Help_Page(array('total'=>$_GET["total"],'perpage'=>10,"url"=>Tools::url(CONTROL,'ServicerMaintainSync',$_GET)));
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
		if(count($data)>0){
			foreach($data as $k=>$v){
				$name = $v["iRoleId"]."_".$v["iDoingEvent"]."_".$v["iDoingObject"]."_".$v["iLinkID"];
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
				//				print_r($v);
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
			foreach($dataArr as $v){
				echo $v["desc"]."<br/>";
				echo "===============================<br/>";
			}
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