<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GetOperatorServer_GetOperatorServer10 extends Action_ActionBase{

	public function _init(){
		$this->_assign['URL_check'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'doaction'=>'check'));
		$this->_assign['URL_update'] = Tools::url('OperatorTools','ServerManagement',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'doaction'=>'add'));
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
//		if(0){
//			$utilMHttpInterface = $this->_getGlobalData('Util_HttpMInterface','object');
//			$utilMHttpInterface ->addHttp($_REQUEST['server_id'],$UrlAppend,$get,$post);
//			$utilMHttpInterface ->send();
//			$data = $utilMHttpInterface ->getResult();
//		}
//		$str = '{"status":1,"info":null,"data":{"servers":[{"classId":1,"serverId":0,"ip":"192.168.12.165","url":"192.168.12.165","addTime":0,"name":"192.168.12.165","id":4,"type":1,"port":7777,"sign":null,"timestamp":0},{"classId":1,"serverId":3,"ip":"192.168.14.173","url":"192.168.14.173","addTime":0,"name":"测试组专业测试服","id":3,"type":1,"port":7777,"sign":null,"timestamp":0},{"classId":1,"serverId":1,"ip":"192.168.14.14","url":"192.168.14.14","addTime":0,"name":"14.14开发机","id":1,"type":1,"port":7777,"sign":null,"timestamp":0},{"classId":1,"serverId":2,"ip":"192.168.12.205","url":"192.168.12.205","addTime":0,"name":"林子本地服务器","id":2,"type":1,"port":7777,"sign":null,"timestamp":0}],"serverClasses":[{"uniqueId":"guanwang","url":" http://192.168.14.14/kungfucross/","name":"官网","id":1,"sign":null,"timestamp":0},{"uniqueId":"wanghongzhou","url":" http://192.168.12.165:8080/kungfucross/","name":"汪洪州","id":7,"sign":null,"timestamp":0}]}}';
//		$data = json_decode($str,true);
		switch($_GET['doaction']){
			case 'check':
				$this->_serverCheck();
				break;
			case 'update':
				
				break;
			default:
				$this->_serverList($UrlAppend,$get,$post);
				break;
		}
		return $this->_assign;
	}
	
	private function _serverList($UrlAppend=null,$get=null,$post=null){
		$data = $this->getResultOpt($UrlAppend,$get);
		
		if($data['status'] == 1){
			$servers = array();
			if($data['data']['serverClasses']){
				foreach($data['data']['serverClasses'] as $sub){
					$servers[$sub['id']] = $sub;
					$servers[$sub['id']]['list'] = array();
				}
			}
			if($data['data']['servers']){
				foreach($data['data']['servers'] as $sub){
					$servers[$sub['classId']]['list'][$sub['id']] = $sub;
				}
			}
			$this->_assign['dataList'] =  $servers;
		}
		//return $this->_assign;
	}
	
	private function _serverCheck(){
		$marking = trim($_GET['marking']);
		$returnData = array('status'=>'0','info'=>null,'data'=>0);
		if(empty($marking)){
			$returnData['info'] = '缺少参数:marking';
			$this->ajaxReturn($returnData);
		}
		$modelGameSerList =  $this->_getGlobalData('Model_GameSerList','object');
		$sql = "select *  from ".$modelGameSerList->tName()." where game_type_id = {$this->_gameObject->_gameId} and marking = '{$marking}'";
		$data = $modelGameSerList->select($sql,1);
		if($data){
			$returnData['info'] = '<font color="#00FF00">已存在</font>';
			$returnData['data'] = 1;
		}else{
			$returnData['info'] = '<font color="#FF0000">未同步</font>';
		}
		$returnData['status'] = 1;
		$this->ajaxReturn($returnData);
	}
}