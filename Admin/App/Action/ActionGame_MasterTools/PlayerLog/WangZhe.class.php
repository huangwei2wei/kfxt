<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_WangZhe extends Action_ActionBase{
	
	private $_playerLogTypes = array();
	
	public function _init(){
		$this->_assign['URL_LogTypeUpdate']= $this->_urlLogTypeUpdate();
		$this->_playerLogTypes = $this->partner('PlayerLogType');	//从日志类型Action接口中拿数据
		$this->_assign['playerLogTypes'] = json_encode($this->_playerLogTypes);
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
			'playerId' => base64_encode($_GET['player']),
			'playerType' => trim($_GET['playerType']),
			'keywords'=>trim($_GET['keywords']),
			'bigType'=>intval($_GET['rootid']),
			'smallType'=>intval($_GET['typeid']),
			'startTime'=>urlencode(trim($_GET['StartTime']))?urlencode(trim($_GET['StartTime'])):0,
			'endTime'=>urlencode(trim($_GET['EndTime']))?urlencode(trim($_GET['EndTime'])):0,
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		$this->_assign['playerType'] = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称',);
		if(!$_REQUEST['server_id'] || !$_REQUEST['submit']){
			return $this->_assign;
		}
		$playerLogTypes = $this->_playerLogTypes;
		$getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$getData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$getData,null);
		
// 		print_r($getData);
// 		print_r($data);
// 		exit;
		
		if($data['status'] == '1'){
			$this->_assign['playerName'] = $data['data']['playerName'];
			$this->_assign['playerAccount'] = $data['data']['playerAccount'];
			foreach($data['data']['list'] as &$sub){
				$sub['playerType'] = $playerLogTypes[$sub['typeId']]['rootTypeName'].'-->'.$playerLogTypes[$sub['typeId']]['subTypeList'][$sub['subTypeId']]['subTypeName'];
// 				$sub['addTime'] = date('Y-m-d H:i:s',$sub['addTime']);
// 				$sub['rootType'] = $playerLogTypes [$sub['objectId']] ['rootTypeName'];
// 				$sub['subType'] = $playerLogTypes [$sub['SerialType']] ['subTypeList'] [$sub['Oper']] ['subTypeName'];
// 				$des = $playerLogTypes [$sub['objectId']] ['subTypeList'] [$sub['eventId']]['des'];
// 				$params = explode('$@',$sub['params']); //参数的分隔符是  $@
// 				$paramsCount = count($params);
// 				for($i = 0; $i < $paramsCount ;$i++){
// 					$des = str_replace('{'.$i.'}','<font color="#FF0000">'.array_shift($params).'</font>',$des);
// 				}
// 				$sub['params'] = $des;
			}
			 
			$this->_assign['dataList'] = $data['data']['list'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['totalNum'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		return $this->_assign;
	}
	
	private function _urlLogTypeUpdate(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'timeout'=>1,
		);
		return Tools::url(CONTROL,'PlayerLogType',$query);
	}
}