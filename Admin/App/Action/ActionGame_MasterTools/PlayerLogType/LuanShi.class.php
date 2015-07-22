<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLogType_LuanShi extends Action_ActionBase{
	
	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新
	
	public function _init(){
		
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$effectiveTime = $this->_effectiveTime;
		if($_REQUEST['timeout']){
			$effectiveTime = -1;
		}
		$fileCacheName = $this->getFileCacheName();
// 		echo $fileCacheName;exit;
		$playerLogType = $this->_f($fileCacheName,'',CACHE_DIR,$effectiveTime);
		if(false !== $playerLogType){
			return $playerLogType;
		}
		$playerLogType = $this->_makePlayerLogType($UrlAppend,$get,$post);
		$updateSuccess = 0;
		if($playerLogType){
			$updateSuccess = 1;
			$this->_f($fileCacheName,$playerLogType);
		}
		if($_REQUEST['timeout']){
			if($this->_isAjax()){	//ajax的更新提示
				$this->ajaxReturn(array('status'=>$updateSuccess,'info'=>NULL,'data'=>NULL));
			}elseif($updateSuccess){
				$this->jump('操作成功',1);
			}else{
				$this->jump('操作失败',-1);
			}
		}
		return $playerLogType;
	}
	
	
	private function _makePlayerLogType($UrlAppend=NULL,$get=NULL,$post=NULL){
	 
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$post);
// 		print_r($getData);
// 		print_r($data);exit;
		if($data['status'] != '1'){
			return false;
		}
		$playerLogType = array();
		if($data['data']){
			foreach($data['data'] as $rootTypeId => $rootType){
				$playerLogType[$rootTypeId]['rootTypeName'] = $rootType['name'];
				
				foreach($rootType['list'] as $k => $v){
						$playerLogType[$rootTypeId]['subTypeList'][$k] = array(
								'subTypeName'=>$v
						);
				}
				
			}
		}
		
		return $playerLogType;
	}

}