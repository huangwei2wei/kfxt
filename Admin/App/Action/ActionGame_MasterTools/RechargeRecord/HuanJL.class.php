<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_HuanJL extends Action_ActionBase{
	public function _init(){
		$this->_assign['exchangeTypes'] = Tools::gameConfig('exchangeTypes',$this->_gameObject->_gameId);	//itemTypes
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'ps'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
			'playerType'=>0,//模式传0
			'player'=>'',//默认为空
			'State'=>trim($_GET['exchangeType']),
			'OrderID'=>trim($_GET['transactionId']),
			'beginTime'=>strtotime(trim($_GET['loginBeginTime'])),
			'endTime'=>strtotime(trim($_GET['loginEndTime'])),
		);
		$player = array(
			1=>trim($_GET['playerId']),
			2=>trim($_GET['playerAccount']),
			3=>trim($_GET['playerName']),
		);
		$player = array_filter($player,'strlen');
		if($player){
			$postData['playerType'] = key($player);
			$postData['player'] = current($player);
		}
		if($post){
			$postData = array_merge($post,$postData);
		}
// 		$postData = array_filter($postData,'strlen');
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($_REQUEST['sbm']){
			$postData=$this->getPostData($post);
// 			print_r($postData);
			$getData = $this->_gameObject->getGetData($get);
 			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			print_r($data);
			$pageMoneyTotal = 0;
 			if($data['status'] == 1 && is_array($data['data']['list'])){
				foreach($data['data']['list'] as &$sub){
// 					$sub['playerId'] = $this->_d2s($sub['playerId']);
					$pageMoneyTotal += $sub['RMB'];
				}
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_assign['pageMoneyTotal'] = $pageMoneyTotal;
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($data['data']['total']),'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}
		return $this->_assign;
	}
}