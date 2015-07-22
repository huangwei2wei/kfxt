<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_RechargeRecord_MingXing extends Action_ActionBase{
	
	private $_chargeType = array('1'=>'openId','2'=>'昵称','3'=>'玩家Id');
	
	public function _init(){
		$this->_assign['chargeType'] = $this->_chargeType;
	}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['orderId'] = trim($_GET['orderId']);
		$postData['type'] = intval($_GET['type']);
		$postData['value'] = urlencode(trim($_GET['value']));
		$postData['pageIndex'] = max(1,intval($_GET['page']));
		$postData['pageSize'] = PAGE_SIZE;
		$validate = array(
			'type'=>array(array('array_key_exists','###',$this->_chargeType),'玩家类型错误'),
		);
		$check = Tools::arrValidate($postData,$validate);
		if($check !== true){
			$this->jump($check,-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=$this->getPostData($post);
		$sendData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$sendData);
		if(is_array($data) && $data['status']==1 && $data['data']['list']){
			$this->_assign['dataList'] = $data['data']['list'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		return $this->_assign;
	}
}
