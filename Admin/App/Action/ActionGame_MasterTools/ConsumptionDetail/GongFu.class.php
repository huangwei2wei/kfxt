<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ConsumptionDetail_GongFu extends Action_ActionBase{
	public function _init(){
		$this->_assign['exchangeTypes'] = Tools::gameConfig('exchangeTypes',$this->_gameObject->_gameId);	//itemTypes
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
			'playerType'=>$_GET['type'],
			'player'=>$_GET['player'],
			'beginTime'=>strtotime( trim($_GET['beginTime'])) ,
			'endTime'=>strtotime( trim($_GET['endTime'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		if(!$postData['player']){
			$this->jump('用户不能为空!',-1);
		}
// 		if(!$postData['beginTime']){
// 			$this->jump('开始时间不能空！',-1);
// 		}
// 		if(!$postData['endTime']){
// 			$this->jump('结束时间不能空!',-1);
// 		}
		
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($_REQUEST['sbm']){
			$postData=$this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
// 			$getData = array_merge($getData,$postData);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			
// 			print_r($getData);
// 			print_r(array_merge($getData,$postData));
// 			print_r($data);
// 			exit;
			$pageMoneyTotal = 0;
 			if($data['status'] == 1){
//  			$PlayerLogType = $this->partner('PlayerLogType');
 				
				foreach($data['data']['logs'] as &$sub){
					$sub['playerId'] = $this->_d2s($sub['playerId']);
				}
				
				$this->_assign['dataList'] = $data['data']['logs'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($data['data']['totals']),'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}
		return $this->_assign;
	}
}