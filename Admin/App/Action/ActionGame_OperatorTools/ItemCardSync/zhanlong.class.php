<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ItemCardSync_zhanlong extends Action_ActionBase{
	public function _init(){
		$type = array(
			"WorldID"	=>"区ID",
			"PackID"	=>"礼包ID",
			"PackType"	=>"礼包类型",
			"PackName"  =>"礼包名",
			"Describes" =>"礼包描述",
			"ItemList"  =>"礼包内容",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		switch ($_GET['doaction']){
			case 'serverSync' :{
				return $this->_libaoServerSync($UrlAppend,$get,$post);
			}
			default:{
				return $this->_libaoIndex($UrlAppend,$get,$post);
			}
		}
	}

	private function _libaoIndex($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["doaction"]=="updateCard"){
			$datalist = $this->updateCard(0);
			$this->_f("zlsg_ItemCardSync".$_REQUEST['server_id'],$datalist);
			$this->jump('操作成功',1);
			
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
		       'Page' => max(0,intval($_GET['page']-1)),
		);

		$getData	=	array_merge($getData,$postData);
		if($post){
			$postData = array_merge($post,$postData);
		}

		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["ItemPackList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['ItemPackList'] as $key=>&$sub){
				$datalist[$a][$Column[$i]]	=	$sub;
				if($Column[$i]=="区ID"){
					$datalist[$a]["WorldID"] = $sub;
				}
				if($Column[$i]=="礼包ID"){
					$datalist[$a]["PackID"] = $sub;
				}
				$i++;
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}
			//print_r($datalist);
			$this->_assign['WorldID'] = $WorldID;
			$this->_assign['Column'] = $Column;
			$this->_assign['postData'] = $postData;
			$this->_assign["len"]	=	count($Column)+1;
			$this->_assign['dataList'] = $this->_f("zlsg_ItemCardSync".$_REQUEST['server_id']);
			$this->_assign['dataListcount'] = count($this->_f("zlsg_ItemCardSync".$_REQUEST['server_id']));
			$_SERVER ['REQUEST_URI'].="&WorldID=".$postData['WorldID']."&page=".$_GET["page"];  //翻页时用到
		}
		$this->_assign['syncDel'] = Tools::url(CONTROL,'ItemCardSync',$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				"doaction"=>"serverSync",
				"ac"=>"del",
				"page"=>$_GET["page"],
		));
		$this->_assign['update'] = Tools::url(CONTROL,'ItemCardSync',$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				"doaction"=>"updateCard",
		));
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

		$data = $this->getResult("QuerySystem/ItemPackList",$getData);
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
		$Column = $data["Column"];
		$MallItemList	=	$data["ItemPackList"];
		$datalist		=	array();
		$i = 0;
		$a = 0;
		foreach ($data['ItemPackList'] as $key=>&$sub){
			$datalist[$a][$Column[$i]]	=	$sub;
			if($Column[$i]=="区ID"){
				$datalist[$a]["WorldID"] = $sub;
			}
			if($Column[$i]=="礼包ID"){
				$datalist[$a]["PackID"] = $sub;
			}
			$i++;
			if($i>=count($Column)){
				$i=0;
				$a++;
			}
		}
		return $datalist;
	}

	private function _libaoServerSync($UrlAppend=NULL,$get=NULL,$post=NULL){
		if ($this->_isAjax()){
			$arr = explode(",",$_REQUEST["sysnValue"]);
			if(is_array($arr)){
				$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
				$getData = $this->_gameObject->getGetData($get);
				foreach($arr as $k=>$v){
					if($_GET["ac"]!="del"){
						$getData["WorldID"]		=	max(0,intval($serverList[$_REQUEST["server_id"]]['ordinal']));
						$getData["PackID"]		=	intval($v);
						$dataIndex 				= 	$this->getResult($UrlAppend,$getData);

						$PackData = array();
						foreach($dataIndex["Column"] as $sk=>$sv){
							$PackData[$sv]	=	$dataIndex["ItemPackList"][$sk];
						}
						$data					=	$PackData;
						$data["Remove"]			=	intval(0);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface 		= 	$this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/ItemPack",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}else{
						$data["Remove"]			=	intval(1);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["PackID"]		=	intval($v);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/ItemPack",$getData,$SendData);
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

		}else {
			if (!$_REQUEST['server_id'])$this->_getGlobalData('Util_Msg','object')->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Id'])$this->_getGlobalData('Util_Msg','object')->showMsg(Tools::getLang('PLZ_SLT_PACK_FOR_SYN',__CLASS__),-1);
			$selectedIds=array_unique($_POST['Id']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];

			$getData = $this->_gameObject->getGetData($get);
			$postData=array(
				'WorldID'=>max(0,intval($_POST['WorldID'])),
				'Page'=>max(0,intval($_POST['Page']-1)),
			);
			$getData = array_merge($getData,$postData);

			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result'] == '0'){
				$Column = $data["Column"];
				$datalist		=	array();
				$i = 0;
				$a = 0;
				foreach ($data['ItemPackList'] as $key=>&$sub){
					$datalist[$a][$Column[$i]]	=	$sub;
					if($Column[$i]=="区ID"){
						$datalist[$a]["WorldID"] = $sub;
					}elseif($Column[$i]=="礼包ID"){
						$datalist[$a]["PackID"] = $sub;
					}elseif($Column[$i]=="礼包获取类型"){
						$datalist[$a]["PackType"] = $sub;
					}elseif($Column[$i]=="礼包名"){
						$datalist[$a]["PackName"] = $sub;
					}elseif($Column[$i]=="礼包描述"){
						$datalist[$a]["Describes"] = $sub;
					}elseif($Column[$i]=="礼包内容"){
						$datalist[$a]["ItemList"] = $sub;
					}
					$i++;
					if($i>=count($Column)){
						$i=0;
						$a++;
					}
				}
				$datalist = $this->_f("zlsg_ItemCardSync".$_REQUEST['server_id']);
				foreach ($datalist as $key=>$value){
					if(!in_array($value['礼包ID'],$selectedIds)){
						unset($datalist[$key]);
					}
				}
				$this->_assign['sysnValue'] = implode(",",$_POST['Id']);
				$this->_assign['Column']=$Column;
				$this->_assign['doaction']='Sync';
				$this->_assign['dataList']=$datalist;
				$this->_assign["PackName"]  = "礼包名";
				$this->_assign["Describes"] = "礼包描述";		  
				//	$this->_assign('serverName',$serverName);
				//		$this->_createServerList();
				$this->_assign['tplServerSelect'] = "ActionGame_OperatorTools/AllNotice/ServerSelect.html";
				//		$this->_set_tpl(array('body'=>'OperationFRG/LibaoServerSyn.html'));
			}

			return $this->_assign;
		}
	}

	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Item',$query);
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ItemCardSync',$query);
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