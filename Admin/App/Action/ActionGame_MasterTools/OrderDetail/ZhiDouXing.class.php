<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_OrderDetail_ZhiDouXing extends Action_ActionBase{

	private $_playerLogTypes = array();

	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
		    'playerId'=>intval($_GET['playerId']),
		    'account'=>trim($_GET['account']),
			'type'=>trim($_GET['type']),//模式传0
			'page'=>trim($_GET['page']),
			'player'=>'',//默认为空
			'account'=>trim($_GET['account']),
			'playerName'=>trim($_GET['playerName']),
			'keyword'=>trim($_GET['keyword']),
			'objectId'=>intval($_GET['rootid']),
			'eventId'=>intval($_GET['typeid']),
			'startTime'=>trim(strtotime($_GET['startTime'])),
			'endTime'=>trim(strtotime($_GET['endTime'])),
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
		
		if($data['status'] == '1' && is_array($data['data'])){
			$this->_assign['playerName'] = $data['data']['playerName'];
			$this->_assign['playerAccount'] = $data['data']['playerAccount'];
			foreach($data['data'] as &$sub){
				$sub['createTime'] = date('Y-m-d H:i:s',$sub['createTime']/1000);
			}
			$this->_assign['dataList'] = $data['data'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['total'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		
		return $this->_assign;
	}

	private function _logType(){
		return array(
		"All"=>"所有",
		"MapController"=>"地图",
		"SoldierController"=>"士兵",
		"PlayerController"=>"玩家",
		"GMPlayerController"=>"系统",
		"AssistanceController"=>"协助",
		"WarController"=>"战争",
		"MissionController"=>"任务",
		"GoodsController"=>"道具",
		"ShopController"=>"门店",
		"ZoneController"=>"地区",
		);
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