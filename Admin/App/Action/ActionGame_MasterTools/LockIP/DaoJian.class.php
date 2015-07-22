<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockIp_DaoJian extends Action_ActionBase{

	public function _init(){
		$this->_assign['URL_lockIPDone'] = $this->_lockIPDone();
	}
	public function getPostData($post=null){
		$postData = array(
				'ps'=>PAGE_SIZE,
				'page'=>max(1,intval($_GET['page'])),
				);
		if($post){
			$postData = array_merge($postData,$post);
		}
		return $postData;
	}
	
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$data = $this->getResult($UrlAppend,$getData,$postData);
print_r($data); 
		if($data['result'] == '0'){
				$dataList = $data['data'];
				if(count($dataList) >0){
					foreach ($dataList as &$v){
						$v['del_url'] = $this->_DelLockIP($v['IP']);
					}
				}
				$this->_assign['dataList'] = $dataList;
		}else{
			$this->jump('接口调用失败',0,-1);
		}
		
		$total = intval($data['data']['total']);
		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();

		return $this->_assign;
	}
	
	private function _lockIPDone(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockIPDone',$query);
	}
	
	private function _DelLockIP($ip){
		$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				'ip' => $ip,
				'del' => 1,
		);
		return Tools::url(CONTROL,'DelLockIP',$query);
	}
}