<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Define_zhanlong extends Action_ActionBase{

	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新

	public function _init(){}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		//		$effectiveTime = $this->_effectiveTime;
		//		if($_REQUEST['timeout']){
		//			$effectiveTime = -1;
		//		}
		$fileCacheName = $this->getFileCacheName();
		//		$items = $this->_f($fileCacheName,'',CACHE_DIR,$effectiveTime);
		//		if(false !== $items){
		//			return $items;
		//		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Define"]	=	intval(0);
		$data = $this->getResult($UrlAppend,$getData);
		//		print_r($data);
		//		die();
		if($data){
			$updateSuccess = 1;
			$this->_f($fileCacheName,$data);
		}
		if($this->_isAjax()){	//ajax的更新提示
			$this->ajaxReturn(array('status'=>$updateSuccess,'info'=>null,'data'=>null));
		}elseif($updateSuccess){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败',-1);
		}
	}

	private function _getData($UrlAppend=null,$get=null,$post=null){
		$getData = $get;
		$getData['serverId'] = $this->_gameObject->getServerId();
		$data = $this->getResult($UrlAppend,$getData);
		$itemsData = array();
		if($data['status'] != '1'){
			return $itemsData;
		}
		if(is_array($data['data'])){
			foreach($data['data'] as $key => $sub){
				foreach($sub as $val){
					$itemsData[$key][$val[0]] = $val[1];
				}
			}
		}
		return $itemsData;
	}
}