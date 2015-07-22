<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_MallLogList_LuanShi extends Action_ActionBase{

	
	public function _init(){
//		$this->_assign['wearType'] = $this->_wearType;
//		$this->_assign['URL_EquipmentUpgrade'] = Tools::url(CONTROL,'EquipmentUpgrade',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
//		$this->_assign['URL_EquipmentDel'] = Tools::url(CONTROL,'EquipmentDel',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
	}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['playerId'] = trim($_GET['playerId']);
		$postData['pname'] = trim($_GET['pname']);
		$postData['prolename'] = trim($_GET['prolename']);
		$postData['pageSize'] = PAGE_SIZE;
		$postData['page'] = intval(trim($_GET['page']));
		$postData = array_filter($postData,'strlen');
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=$this->getPostData($post);
		$sendData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$sendData,null);
// 		print_r($data);exit;
		$realCostSum = 0;
		if(is_array($data) && $data['status']==1 ){
			if($data['data']['list']){
				foreach($data['data']['list'] as &$list){
					$list["toolMallName"]	=	$data['data']['mallTool'][$list['toolId']]['toolMallName'];
					$realCostSum += $list['realCost'];
				}
				$this->_assign['realCostSum'] = $realCostSum;
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();	
			}
		}else{
			$this->jump('服务器返回错误:'.$data['info'],-1);
		}
		return $this->_assign;
	}
}
