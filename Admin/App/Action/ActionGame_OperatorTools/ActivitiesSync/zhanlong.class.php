<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesSync_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$type = array(
			"WorldID"	=>"区ID",
			"ClassID"	=>"活动ID",
			"ClassType"	=>"活动类型",
			"ClassCheck"=>"检测方式",
			"ClassValue"=>"检测值",
			"TriggerType"=>"触发方式",
			"IsLoop"=>"是否循环",
			"MaxCount"	=>"最多循环次数",
			"MinLevel"	=>"最小等级",
			"MaxLevel"=>"最大等级",
			"NeedPrivilege"=>"需要特权类型",
			"ConditionType"	=>"条件类型",
			"ConditionValue"	=>"条件值",
			"UniqueIndex"	=>"只可参与一次性索引值",
			"EventType"=>"事件类型",
			"EventID"	=>"事件ID",
			"Fparameter"	=>"浮点参数",
			"WorldTimeBegin"	=>"开服后检测的开始时间",
		   	"WorldTimeEnd"	=>"开服后检测的截止时间",
			"DataTime"=>"起效时间段",
			"ItemGetType"	=>"道具获得类型",
			"ItemPack"	=>"礼包序列",
			"ClassName"	=>"活动名称",
			"Describes"	=>"活动描述",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		switch ($_GET['doaction']){
			case 'serverSync';
			return $this->_activitiesSync($UrlAppend,$get,$post);
			default:
				return $this->_activitiesIndex($UrlAppend,$get,$post);
		}
	}

	public function _activitiesSync($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isAjax()){
			$arr = explode(",",$_REQUEST["sysnValue"]);
			if(is_array($arr)){
				$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
				$getData = $this->_gameObject->getGetData($get);
				foreach($arr as $k=>$v){
					if($_GET["ac"]!="del"){
						$getData["WorldID"]		=	max(0,intval($serverList[$_REQUEST["server_id"]]['ordinal']));
						$getData["ClassID"]		=	intval($v);
						$dataIndex 				= 	$this->getResult($UrlAppend,$getData);
						$PackData = array();
						foreach($dataIndex["Column"] as $sk=>$sv){
							$PackData[$sv]	=	$dataIndex["ClassList"][$sk];
						}
						$data					=	$PackData;

						$data["Remove"]			=	intval(0);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["DataTime"]		=	$data["DateTime"];
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface 		= 	$this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/Class",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}else{
						$data["Remove"]			=	intval(1);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["ClassID"]		=	intval($v);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/Class",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}
				}
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>"data is null"));
			}
			$this->ajaxReturn(array('status'=>1,'msg'=>"succeed"));
		}else{
			$getData = $this->_gameObject->getGetData($get);
			$getData["Page"]		=	max(0,intval($_POST['Page']-1));
			$getData["WorldID"]		=	max(0,intval($_POST['WorldID']));
			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result'] == '0'){
				$Column = $data["Column"];
				$MallItemList	=	$data["MallItemList"];
				$datalist		=	array();
				$i = 0;
				$a = 0;
				foreach ($data['ClassList'] as $key=>&$sub){
					if($Column[$i]=="SellText"){
						$sub = strip_tags($sub);
					}
					if($Column[$i]=="区ID"){
						$datalist[$a]["WorldID"] = $sub;
					}
					if($Column[$i]=="活动ID"){
						$datalist[$a]["ClassID"] = $sub;
					}
					$datalist[$a][$Column[$i]]	=	$sub;
					$i++;
					if($i>=count($Column)){
						$i=0;
						$a++;
					}
				}
				$datalist = $this->_f("zlsg_ActivitiesSync".$_REQUEST['server_id']);
				foreach ($datalist as $key=>$val){
					if(!in_array($val['ClassID'], $_POST['ClassID'])){
						unset($datalist[$key]);
					}
				}

				$this->_assign['Column']=$Column;
				$this->_assign["len"]	=	count($Column)+1;
				$this->_assign['dataList']=$datalist;
				$this->_assign['sysnValue'] = implode(",",$_POST['ClassID']);
				$this->_assign['tplServerSelect'] = "ActionGame_OperatorTools/AllNotice/ServerSelect.html";
				$this->_assign['doaction']='serverSync';
			}
		}
		return $this->_assign;
	}

	private function updateCard($page){
		if(empty($page)){
			$page=0;
		}
		$getData = $this->_gameObject->getGetData($get);
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
		$WorldID = $serverList[$_REQUEST['server_id']]['ordinal'];
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		$postData=array(
		       'WorldID' => max(0,intval($_GET['WorldID'])),
		       'Page' => max(0,intval($page)),
		);

		$getData	=	array_merge($getData,$postData);
		if($post){
			$postData = array_merge($post,$postData);
		}

		$data = $this->getResult("QuerySystem/ClassList",$getData);
		$datalist = array();
		if($data['Result'] == '0'){
			$arr1 = array();
			$arr2 = array();
			$arr1 = $this->getList($data);
			$count=$data['Count']/20;
			if($page<($count-1)){
				$arr2 = $this->updateCard($page+1);
			}
			$datalist = array_merge($arr1,$arr2);
		}else{
			$this->jump("错误：".$data['Result'],-1);	
		}
		return $datalist;
	}


	private function getList($data){

		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["MallItemList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['ClassList'] as $key=>&$sub){
				if($Column[$i]=="SellText"){
					$sub = strip_tags($sub);
				}
				if($Column[$i]=="区ID"){
					$datalist[$a]["WorldID"] = $sub;
				}
				if($Column[$i]=="活动ID"){
					$datalist[$a]["ClassID"] = $sub;
				}
				$datalist[$a][$Column[$i]]	=	$sub;
				$i++;
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}
		}
		return $datalist;
	}

	public function _activitiesIndex($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["doaction"]=="updateCard"){
			$datalist = $this->updateCard(0);
			$this->_f("zlsg_ActivitiesSync".$_REQUEST['server_id'],$datalist);
			$this->jump('操作成功',1);

		}
		$this->_assign['update'] = Tools::url(CONTROL,'ActivitiesSync',$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				"doaction"=>"updateCard",
		));
		$getData = $this->_gameObject->getGetData($get);

		$_GET["WorldID"] = $this->_getServerID();
		$this->_assign["_GET"] = $_GET;
		$mergerData=array(
		       'WorldID' => max(0,intval($_GET['WorldID'])),
		       'Page' => max(0,intval($_GET['page']-1)),
		);
		$getData = array_merge($getData,$mergerData);
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["MallItemList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['ClassList'] as $key=>&$sub){
				if($Column[$i]=="SellText"){
					$sub = strip_tags($sub);
				}
				if($Column[$i]=="区ID"){
					$datalist[$a]["WorldID"] = $sub;
				}
				if($Column[$i]=="活动ID"){
					$datalist[$a]["ClassID"] = $sub;
				}
				//			"WorldID"	=>"区ID",
				//			"ClassID"	=>"活动ID",
				$datalist[$a][$Column[$i]]	=	$sub;
				$i++;
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}
			$this->_assign['Column']=$Column;
			$this->_assign["len"]	=	count($Column)+1;
			$this->_assign['dataList']=$datalist;
			$this->_assign['postData'] = array('Page'=>max(0,intval($_GET['page'])-1),'WorldID'=>max(0,intval($_POST["WorldID"])));
			$_SERVER ['REQUEST_URI'].="&WorldID=".$getData['WorldID'];  //翻页时用到
		}
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
		$WorldID = $serverList[$_REQUEST['server_id']]['ordinal'];
		$this->_assign['_WorldID'] = $WorldID;
		$this->_assign['dataList'] = $this->_f("zlsg_ActivitiesSync".$_REQUEST['server_id']);
		$this->_assign['dataListcount'] = count($this->_f("zlsg_ActivitiesSync".$_REQUEST['server_id']));
		$this->_assign['syncDel'] = Tools::url(CONTROL,'ActivitiesSync',$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			"doaction"=>"serverSync",
			"ac"=>"del",
			"page"=>$_GET["page"],
		));
		return $this->_assign;
	}

	/**
	 * 并发时生成的消息
	 * @param array $data back_result
	 * @return string
	 */
	private function _getMultiMsg($data){
		$serverList=$this->_getGlobalData('gameser_list');
		$sendStatusMsgs='';
		foreach ($data as $key=>$value){
			if ($value['Result']===0){
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_SUCCESS','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
			}else {
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_FAILURE','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
			}
		}
		return $sendStatusMsgs;
	}

}