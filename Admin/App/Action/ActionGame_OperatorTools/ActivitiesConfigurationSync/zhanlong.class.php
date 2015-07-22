<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesConfigurationSync_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
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
						$getData["TableID"]		=	intval($v);
						$dataIndex 				= 	$this->getResult("QuerySystem/ClassTable",$getData);
						$data					=	$dataIndex["ClassTable"][0];
						$data["Remove"]			=	intval(0);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/ClassTable",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}else{
						$data["Remove"]			=	intval(1);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["TableID"]		=	intval($v);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/ClassTable",$getData,$SendData);
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
			$data = $this->_f("zlsg_ActivitiesConfigurationSync".$_REQUEST['server_id']);
			foreach ($data as $key=>$val){
				if(!in_array($val['TableID'], $_POST['TableID'])){
					unset($data[$key]);
				}
			}
			$this->_assign['dataList']=$data;
			$this->_assign['tplServerSelect'] = "ActionGame_OperatorTools/AllNotice/ServerSelect.html";
			$this->_assign['doaction']='serverSync';
			$this->_assign['sysnValue'] = implode(",",$_POST['TableID']);
				
		}
		return $this->_assign;
	}

	public function _activitiesIndex($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["doaction"]=="updateCard"){
			$datalist = $this->updateCard(0);
			$this->_f("zlsg_ActivitiesConfigurationSync".$_REQUEST['server_id'],$datalist);
			$this->jump('操作成功',1);

		}
		$this->_assign['update'] = Tools::url(CONTROL,'ActivitiesConfigurationSync',$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				"doaction"=>"updateCard",
		));

		$getData = $this->_gameObject->getGetData($get);

		$_GET["WorldID"] = $this->_getServerID();
		$this->_assign["_GET"] = $_GET;
		$this->_assign['dataList'] = $this->_f("zlsg_ActivitiesConfigurationSync".$_REQUEST['server_id']);
		$this->_assign['dataListcount'] = count($this->_f("zlsg_ActivitiesConfigurationSync".$_REQUEST['server_id']));
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
		$WorldID = $serverList[$_REQUEST['server_id']]['ordinal'];
		$this->_assign['_WorldID'] = $WorldID;
		$this->_assign['syncDel'] = Tools::url(CONTROL,'ActivitiesConfigurationSync',$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			"doaction"=>"serverSync",
			"ac"=>"del",
			"page"=>$_GET["page"],
		));
		$this->_assign['serverSync'] = Tools::url(CONTROL,'ActivitiesConfigurationSync',$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			"doaction"=>"serverSync",
			"page"=>$_GET["page"],
		));
		//http://hl.uwan.com/admin.php?c=OperatorTools&a=ActivitiesConfigurationSync&zp=ActionGame&__game_id=18&server_id=1116&doaction=serverSync&page=
		//http://hl.uwan.com/admin.php?c=OperatorTools&a=ActivitiesSync&zp=ActionGame&doaction=serverSync&__game_id=18&page=1
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

		$data = $this->getResult("QuerySystem/ClassTable",$getData);
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
		$MallItemList	=	$data["ClassTable"];
		$datalist		=	array();
		$i = 0;
		$a = 0;
		return $MallItemList;
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