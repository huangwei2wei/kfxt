<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_GongFu extends Action_ActionBase{
	
	private $_playerLogTypes = array();
	
	public function _init(){
		$this->_assign['URL_LogTypeUpdate']= $this->_urlLogTypeUpdate();
		$this->_playerLogTypes = $this->partner('PlayerLogType');	//从日志类型Action接口中拿数据
		$this->_assign['playerLogTypes'] = json_encode($this->_playerLogTypes);
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
			//'playerId'=>intval($_GET['playerId']),
			//'account'=>trim($_GET['account']),
			'playerType'=>0,//模式传0
			'player'=>'',//默认为空
			'name'=>trim($_GET['name']),
			'param'=>trim($_GET['keywords']),
			'objectId'=>intval($_GET['rootid']),
			'eventId'=>intval($_GET['typeid']),
			'beginTime'=>trim($_GET['StartTime']),
			'endTime'=>trim($_GET['EndTime']),
			'ip'=>trim($_GET['ip']),
		);
		$player = array(
			1=>trim($_GET['playerId']),
			2=>trim($_GET['account']),
			3=>trim($_GET['name']),
		);
		$player = array_filter($player);
		if($player){
			$postData['playerType'] = key($player);
			$postData['player'] = current($player);
		}
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
//		$data ='{"data":{"logs":[{"id":3,"addTime":1321414404,"level":99,"goldTicke":0,"asset":3983621,"exp":6463276,"gold":4,"params":"0","objectId":3,"eventId":30003,"playerId":231,"ip":"192.168.13.68"},{"id":4,"addTime":1321414455,"level":99,"goldTicke":0,"asset":3983618,"exp":6463276,"gold":4,"params":"皆天剑法$@1010201$@0$@3$@0$@0$@2","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":5,"addTime":1321414456,"level":99,"goldTicke":0,"asset":3983614,"exp":6463276,"gold":4,"params":"皆天剑法$@1010202$@0$@4$@0$@0$@3","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":6,"addTime":1321414456,"level":99,"goldTicke":0,"asset":3983604,"exp":6463276,"gold":4,"params":"皆天剑法$@1010301$@0$@10$@0$@0$@4","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":7,"addTime":1321414456,"level":99,"goldTicke":0,"asset":3983594,"exp":6463276,"gold":4,"params":"皆天剑法$@1010302$@0$@10$@0$@0$@5","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":8,"addTime":1321414456,"level":99,"goldTicke":0,"asset":3983584,"exp":6463276,"gold":4,"params":"皆天剑法$@1010303$@0$@10$@0$@0$@6","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":9,"addTime":1321414456,"level":99,"goldTicke":0,"asset":3983564,"exp":6463276,"gold":4,"params":"皆天剑法$@1010401$@0$@20$@0$@0$@7","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":10,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3983534,"exp":6463276,"gold":4,"params":"皆天剑法$@1010402$@0$@30$@0$@0$@8","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":11,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3983494,"exp":6463276,"gold":4,"params":"皆天剑法$@1010403$@0$@40$@0$@0$@9","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":12,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3983434,"exp":6463276,"gold":4,"params":"皆天剑法$@1010404$@0$@60$@0$@0$@10","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":13,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3983344,"exp":6463276,"gold":4,"params":"皆天剑法$@1010501$@0$@90$@0$@0$@11","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":14,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3983204,"exp":6463276,"gold":4,"params":"皆天剑法$@1010502$@0$@140$@0$@0$@12","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":15,"addTime":1321414457,"level":99,"goldTicke":0,"asset":3982994,"exp":6463276,"gold":4,"params":"皆天剑法$@1010503$@0$@210$@0$@0$@13","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":16,"addTime":1321414458,"level":99,"goldTicke":0,"asset":3982674,"exp":6463276,"gold":4,"params":"皆天剑法$@1010504$@0$@320$@0$@0$@14","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":17,"addTime":1321414458,"level":99,"goldTicke":0,"asset":3982204,"exp":6463276,"gold":4,"params":"皆天剑法$@1010505$@0$@470$@0$@0$@15","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":18,"addTime":1321414458,"level":99,"goldTicke":0,"asset":3981494,"exp":6463276,"gold":4,"params":"皆天剑法$@1010601$@0$@710$@0$@0$@16","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":19,"addTime":1321414458,"level":99,"goldTicke":0,"asset":3980424,"exp":6463276,"gold":4,"params":"皆天剑法$@1010602$@0$@1070$@0$@0$@17","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":20,"addTime":1321414458,"level":99,"goldTicke":0,"asset":3978824,"exp":6463276,"gold":4,"params":"皆天剑法$@1010603$@0$@1600$@0$@0$@18","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":21,"addTime":1321414459,"level":99,"goldTicke":0,"asset":3976424,"exp":6463276,"gold":4,"params":"皆天剑法$@1010604$@0$@2400$@0$@0$@19","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"},{"id":22,"addTime":1321414459,"level":99,"goldTicke":0,"asset":3972824,"exp":6463276,"gold":4,"params":"皆天剑法$@1010605$@0$@3600$@0$@0$@20","objectId":3,"eventId":30001,"playerId":231,"ip":"192.168.13.68"}],"totals":54},"status":1,"info":null}';
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1' && is_array($data['data']['logs'])){
			
			$this->_assign['playerName'] = $data['data']['playerName'];
			$this->_assign['playerAccount'] = $data['data']['playerAccount'];
			foreach($data['data']['logs'] as &$sub){
				$sub['addTime'] = date('Y-m-d H:i:s',$sub['addTime']);
				$sub['rootType'] = $playerLogTypes [$sub['objectId']] ['rootTypeName'];
				$sub['subType'] = $playerLogTypes [$sub['objectId']] ['subTypeList'] [$sub['eventId']] ['subTypeName'];
				$des = $playerLogTypes [$sub['objectId']] ['subTypeList'] [$sub['eventId']]['des'];
				$params = explode('$@',$sub['params']); //参数的分隔符是  $@
				$paramsCount = count($params);
				for($i = 0; $i < $paramsCount ;$i++){
					$des = str_replace('{'.$i.'}','<font color="#FF0000">'.array_shift($params).'</font>',$des);
				}
				$sub['params'] = $des;
			}
			$this->_assign['dataList'] = $data['data']['logs'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['totals'],'perpage'=>PAGE_SIZE));
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