<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_MingXing extends Action_ActionBase{


	public function main($UrlAppend=null,$get=null,$post=null){

		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$this->_assign["logType"]	=	$this->_logType($get);
		if(!$_REQUEST['submit']){
			return $this->_assign;
		}

		$getData = $this->_gameObject->getGetData($get);
		$getData["Category"]	=	$_GET["Category"];
		$getData["unixStartTime"]	=	strtotime($_GET["unixStartTime"]);
		$getData["unixEndTime"]	=	strtotime($_GET["unixEndTime"]);
		$getData["skey"]	=	urlencode($_GET["skey"]);
		$getData["pageSize"] = 20;
		$getData["page"] = $_GET['page'];
		$getData = array_filter($getData);
		if($_GET["userId"]!=""){
			$getData["userId"]	=	$_GET["userId"];
		}

		if($_GET["nickName"]!=""){
			$getData["nickName"]	=	$_GET["nickName"];
		}

		if($_GET["openId"]!=""){
			$getData["openId"]	=	$_GET["openId"];
		}

		$postData = $this->getPostData($post);		
		$data = $this->getResult($UrlAppend,$getData,$postData);
		$msg = '';
		if($data["status"]==0){
			$this->_assign["connectError"] = $data['info'];
		}else{
			foreach($data["data"]["comments"] as $key=>&$msg){
				$data["data"]["comments"][$key]	=	urldecode($msg);
			}
			foreach($data["data"]["list"] as &$_msg){
				foreach($_msg as &$son){
					$son	=	urldecode($son);
				}
			}
			$this->_assign["contenttype"] = $data["data"]["comments"];
			$this->_assign["dataList"] = $data["data"]["list"];
		}

		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>20));
		$this->_assign['pageBox'] = $helpPage->show();
		
		return $this->_assign;
	}

	private function _logType($get){
		$get['action']	=	"getLogList";
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult("",$getData,null);
		if($data["status"]==1){
			$logType	=	array();
			foreach($data["data"] as $key=>$msg){
				$logType[$key]	=	urldecode($msg);
			}
			return $logType;
		}else{
			return array("false"=>"读取失败");
		}

	}




}