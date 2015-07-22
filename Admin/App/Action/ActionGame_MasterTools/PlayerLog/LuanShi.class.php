<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_LuanShi extends Action_ActionBase{
	
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
			//'playerId'=>intval($_GET['playerId']),
			//'account'=>trim($_GET['account']),
			'UserId' => trim($_GET['playerId']),
			'UserName' => trim($_GET['account']),
			'VUserName' => trim($_GET['name']),
			'keyWord'=>trim($_GET['keywords']),
				
			'RootId'=>intval($_GET['rootid']),
			'TypeId'=>intval($_GET['typeid']),
				
			'StartTime'=>strtotime(trim($_GET['StartTime'])),
			'EndTime'=>strtotime(trim($_GET['EndTime'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['submit']){
			return $this->_assign;
		}
		$playerLogTypes = $this->_playerLogTypes;
		$getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);exit;
		if($data['status'] == '1'){
			foreach($data['data']['list'] as &$sub){
				$sub['prolename'] = $data['data']['player']['prolename'];
				$sub['pname'] = $data['data']['player']['pname'];
				$sub['playerType'] = $playerLogTypes[$sub['rootId']]['rootTypeName'].'-->'.$playerLogTypes[$sub['rootId']]['subTypeList'][$sub['typeId']]['subTypeName'];
			}
			$this->_assign['dataList'] = $data['data']['list'];
			
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
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
	
//"$logTypes" = Array [8]	
//	3 = Array [2]	
//		rootTypeName = (string:2) 人物	
//		subTypeList = Array [7]	
//			30001 = Array [2]	
//				subTypeName = (string:7) 学习/升级武功	
//				des = (string:57) 学习/升级武功{0}，武功ID{1}，花费武晶石{2}，银两{3}，金币{4},道具{5}一本，武功达到第{6}级	
//			30002 = Array [2]	
//			30003 = Array [2]	
//			30004 = Array [2]	
//			30005 = Array [2]	
//			30006 = Array [2]	
//			30007 = Array [2]	
//	2 = Array [2]	
//	1 = Array [2]	
//	7 = Array [2]	
//	6 = Array [2]	
//	5 = Array [2]	
//	4 = Array [2]	
//	8 = Array [2]	
	
	
//"$data" = Array [3]	
//	data = Array [2]	
//		logs = Array [20]	
//			0 = Array [12]	
//				id = (int) 3	
//				addTime = (int) 1321414404	
//				level = (int) 99	
//				goldTicke = (int) 0	
//				asset = (int) 3983621	
//				exp = (int) 6463276	
//				gold = (int) 4	
//				params = (string:1) 0	
//				objectId = (int) 3	
//				eventId = (int) 30003	
//				playerId = (int) 231	
//				ip = (string:13) 192.168.13.68	
//			1 = Array [12]	
//			2 = Array [12]	
//			3 = Array [12]	
//			4 = Array [12]	
//			5 = Array [12]	
//			6 = Array [12]	
//			7 = Array [12]	
//			8 = Array [12]	
//			9 = Array [12]	
//			10 = Array [12]	
//			11 = Array [12]	
//			12 = Array [12]	
//			13 = Array [12]	
//			14 = Array [12]	
//			15 = Array [12]	
//			16 = Array [12]	
//			17 = Array [12]	
//			18 = Array [12]	
//			19 = Array [12]	
//		totals = (int) 54	
//	status = (int) 1	
//	info = null	
	
	
}