<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ServerState_WangZhe extends Action_ActionBase{

	public function main($UrlAppend=null,$get=null,$post=null){
		$endTime = strtotime(trim($_GET['endTime']));
		$startTime = strtotime(trim($_GET['startTime']));
		$time = array ('endTime'=>$endTime,'startTime'=>$startTime);
		
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,null);
		if($data['status'] == '1'){
			$info = json_decode(json_decode($data['data']));
			$this->_assign['dataList'] = $info->list;
			
// 			$this->_loadCore('Help_Page');//载入分页工具
// 			$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
// 			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		return $this->_assign;
	}
	
}