<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeSync_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		switch ($_GET['doaction']){
			case 'serverSync' :{
				return $this->_noticeServerSync($UrlAppend,$get,$post);
			}
			default:{
				return $this->_noticeIndex($UrlAppend,$get,$post);
			}
		}
	}

	public function _noticeServerSync($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isAjax()){
			$arr = explode(",",$_REQUEST["sysnValue"]);
			if(is_array($arr)){
				$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
				$getData = $this->_gameObject->getGetData($get);
				foreach($arr as $k=>$v){
					if($_GET["ac"]!="del"){
						$getData["WorldID"]		=	max(0,intval($serverList[$_REQUEST["server_id"]]['ordinal']));
						$getData["OrderID"]		=	intval($v);
						$dataIndex 				= 	$this->getResult($UrlAppend,$getData);
						$data					=	$dataIndex["Broadcast"][0];
						$data["DateTime"]		=	$data["DataTime"];
						$data["Remove"]			=	intval(0);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface 		= 	$this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/Broadcast",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}else{
						$data["Remove"]			=	intval(1);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["OrderID"]		=	intval($v);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/Broadcast",$getData,$SendData);
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
			$getData["WorldID"]		=	$_POST["WorldID"];
			$getData["Page"]		=	$_POST["Page"];
			$data = $this->getResult($UrlAppend,$getData);
			$orderIds = $_POST['data'];
			foreach($data['Broadcast'] as $k=>$val){
				if(!in_array($val['OrderID'],$orderIds)){
					unset($data['Broadcast'][$k]);
				}
			}

			$this->_assign['dataList']=$data['Broadcast'];
			$this->_assign['tplServerSelect'] = "ActionGame_OperatorTools/AllNotice/ServerSelect.html";
			$this->_assign['sysnValue'] = implode(",",$_POST['data']);
		}

		$this->_assign["doaction"] = "serverSync";
		return $this->_assign;
	}

	private function _noticeIndex($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		$mergerData=array(
		       'WorldID' => max(0,intval($_GET['WorldID'])),
		       'Page' => max(0,intval($_GET['page']-1)),
		);
		$getData = $this->_gameObject->getGetData($get);

		$getData = array_merge($getData,$mergerData);
		$this->_assign['GET']	=	$_POST;

		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			foreach ($data['Broadcast'] as $key=>&$sub){
				$sub["URL_del"]	=	$this->_urlNoticeDel($sub["OrderID"],$sub["WorldID"]);
				$sub["URL_edit"]	=	$this->_urlNoticeEdit();
			}
			$this->_assign['dataList']=$data['Broadcast'];
			$_SERVER ['REQUEST_URI'].="&WorldID=".$getData['WorldID'];  //翻页时用到
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
			$this->_assign['WorldID'] =	$getData["WorldID"];
			$this->_assign['Page'] = $getData['Page'];

		}
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
		$WorldID = $serverList[$_REQUEST['server_id']]['ordinal'];
		$this->_assign['_WorldID'] = $WorldID;
		$this->_assign['syncDel'] = Tools::url(CONTROL,'NoticeSync',$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			"doaction"=>"serverSync",
			"ac"=>"del",
			"page"=>$_GET["page"],
		));
		return $this->_assign;
	}

	private function _urlNoticeDel($id,$WorldID){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'OrderID'=>$id,
			'WorldID'=>$WorldID,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}

	private function _urlNoticeEdit(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeEdit',$query);
	}

	private function _urlNoticeAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeAdd',$query);
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