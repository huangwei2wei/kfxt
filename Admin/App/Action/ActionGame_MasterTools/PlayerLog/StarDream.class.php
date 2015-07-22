<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_StarDream extends Action_ActionBase{
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		$getGetData = $this->_gameObject->getGetData($get);
		$data = $this->_gameObject->result('PlayerLogType',$getGetData,null);
		if($data['status']==1){
			foreach ($data['data']['list'] as $key=>$value){
				$this->_assign['RootType'][$key]=$value['rootTypeName'];
			}
			$this->_assign['SubLogType'] = json_encode($data['data']['list']);//subTypeList
		} else {
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		
		if($_GET['submit']){
			$postData = array(
				'user'=>$_GET['user'],
				'userType'=>$_GET['userType'],
				'page'=>isset($_GET['page'])?intval($_GET['page']):1,
			);
			if($_GET['endTime']){
				$postData['endTime'] = strtotime( trim($_GET['endTime']) );
			}
			if($_GET['beginTime']){
				$postData['beginTime'] = strtotime( trim($_GET['beginTime']) );
				$postData['endTime'] = $postData['endTime']?$postData['endTime']:time();
			}
			if($_GET['rootid']){
				$postData['objectId'] = intval( trim($_GET['rootid']) );
			}
			if($_GET['typeid']){
				$postData['eventId'] = intval( trim($_GET['typeid']) );
			}
			$data = $this->_gameObject->result('PlayerLog',$getGetData,$postData);
			if($data['status'] == 1){
				
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_assign['pageBox'] = $helpPage->show();
				
			} else {
				$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			}
			$this->_assign['GET'] = $_GET;
		}
		
		return $this->_assign;
	}

}