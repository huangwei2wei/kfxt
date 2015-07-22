<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_OnLine_SanFen extends Action_ActionBase{

	public function main($UrlAppend=null,$get=null,$post=null){

		$getData = $this->_gameObject->getGetData($get);
	 
		$data = $this->getResult($UrlAppend,$getData,null);
 
		$this->_assign['data'] = $data;
		
// 		if($data['status'] == '1'){
// 			$this->_assign['data'] =$data['data'];
// 		}else{
// 			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
// 		}
		return $this->_assign;
	}
	
}