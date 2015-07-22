<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_OnLine_WangZhe extends Action_ActionBase{

	public function main($UrlAppend=null,$get=null,$post=null){

		$getData = $this->_gameObject->getGetData($get);
	 
		$data = $this->getResult($UrlAppend,$getData,null);
 
		
		if($data['status'] == '1'){
			$this->_assign['data'] =$data['data'];
// 			$this->_loadCore('Help_Page');//载入分页工具
// 			$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
// 			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		return $this->_assign;
	}
	
}