<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_GetIngotType_SanFen extends Action_ActionBase{
	
	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新
	
	public function _init(){}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$effectiveTime = $this->_effectiveTime;
		if($_REQUEST['timeout']){
			$effectiveTime = -1;
		}
		$fileCacheName = $this->getFileCacheName();
		$items = $this->_f($fileCacheName,'',CACHE_DIR,$effectiveTime);
		if(false !== $items){
			return $items;
		}
		$get =  $this->_gameObject->getGetData();
		$items = $this->_getData($UrlAppend,$get,$post);
		$updateSuccess = 0;
		if($items){
			$updateSuccess = 1;
			$this->_f($fileCacheName,$items);
		}
		if($_REQUEST['timeout']){
			if($this->_isAjax()){	//ajax的更新提示
				$this->ajaxReturn(array('status'=>$updateSuccess,'info'=>null,'data'=>null));
			}elseif($updateSuccess){
				$this->jump('操作成功',1);
			}else{
				$this->jump('操作失败',-1);
			}
		}
		return $items;
	}
	
	private function _getData($UrlAppend=null,$get=null,$post=null){
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$post);
		if($data['status'] != '1'){
			return false;
		}
		$playerLogType = array();
		if($data['data'][1]['type']){
			foreach($data['data'][1]['type'] as $rootTypeId => $rootTypeName){
				$playerLogType[$rootTypeId]['rootTypeName'] = $rootTypeName;
			}
		}
		if($data['data'][0]['event']){
			foreach($data['data'][0]['event'] as $k => $v){
				foreach($v as $subk => $subv){
					$playerLogType[$k]['subTypeList'][$subk] = array(
						'subTypeName'=>$subv
					);
				}
			}
		}
		return $playerLogType;
	}
}