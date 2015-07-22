<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_TradeManage_Xiayi extends Action_ActionBase{

	public function _init(){

	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost() && $_REQUEST['sbm']){
			
			$postData = array(
				'flag'=>intval($_POST['trade_flag']),
			);
			
			$IfConf = $this->_gameObject->getIfConf();
			$data = $this->_gameObject->result($this->_getUrl(),$postData,$IfConf['EditTrade']['UrlAppend']);
			$data = json_decode($data,true);
			if($data['status'] == 1){
				$this->jump('状态修改成功',1);
			} else {
				$this->jump('操作失败',-1);
			}
			
		} else {
			
			$data = $this->_gameObject->result($this->_getUrl(),array(),$UrlAppend);
			$data = json_decode($data,true);
			if($data['status'] == 1){
				$this->_assign['trade_flag'] = $data['flag'];
			} else {
				$this->_assign['error'] = '出错';
			}
		}
		$this->_assign['flagArr'] = array(0=>'开启',1=>'关闭');
		return $this->_assign;
	}

}