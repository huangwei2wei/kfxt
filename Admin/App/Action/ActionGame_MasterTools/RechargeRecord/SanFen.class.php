<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_SanFen extends Action_ActionBase{
	private $_state ;
	public function _init(){
		$this->_state = array(1=>'成功',0=>'失败');
// 		$this->_assign['exchangeTypes'] = array(0=>'全部',1=>'正常充值',2=>'卡密充值');	//itemTypes
		$this->_assign['state'] = $this->_state;
		$this->_assign['playerType'] = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称');
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
			'playerType'=>trim($_GET['playerType']),//模式传0
			'player'=>trim($_GET['player']),
			'state'=>trim($_GET['state']),
// 			'exchangeType'=>trim($_GET['exchangeType']),
			'transactionId'=>trim($_GET['transactionId']),
			'beginTime'=>strtotime(trim($_GET['loginBeginTime'])),
			'endTime'=>strtotime(trim($_GET['loginEndTime'])),
		);
		if(empty($_GET['player'])){
			$postData['playerType'] = 0;
		}
		if($post){
			$postData = array_merge($post,$postData);
		}
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
 			$data = $this->getResult($UrlAppend,$getData,$postData);
//  		print_r($postData);print_r($data);exit;
			$pageMoneyTotal = 0;
 			if($data['status'] == 1){
 				$state = array(1=>'成功',2=>'失败');
//  				$item = $this->partner('Item');
//  				print_r($item);
				foreach($data['data'][0]['recharges'] as &$sub){
					$payitem = explode('*',$sub['payitem']);
					$sub['amt'] = $payitem[1]*$payitem[2];
					$sub['playerId'] = $this->_d2s($sub['playerId']);
					$sub['ts'] = date("Y-m-d H:i:s",$sub['ts']);
					$sub['state'] = $this->_state[$sub['state']];
					$sub['rmb'] = $sub['price']*$sub['num'];
					$pageMoneyTotal += $sub['rmb'];
				}
				$this->_assign['dataList'] = $data['data'][0]['recharges'];
				$this->_assign['pageMoneyTotal'] = $pageMoneyTotal;
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($data['data'][1]['totals']),'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}else{
				$this->jump($data['info'],-1);
			}
		}
		return $this->_assign;
	}
}