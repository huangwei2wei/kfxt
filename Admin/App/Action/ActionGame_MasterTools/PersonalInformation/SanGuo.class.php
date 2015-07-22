<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PersonalInformation_SanGuo extends Action_ActionBase{
	protected $_param;
	protected $_cause;
	
	public function _init(){}
	
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id']	=	$_REQUEST['server_id'];
		if($this->_isPost()){
			$postData['user_id']	=	$_POST['playerId'];
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$sex		=	array(
					'1'	=>	'女',
					'2'	=>	'男'
				);
				$country	=	array(
					'1'	=>	'蜀',
					'2'	=>	'魏',
					'3'	=>	'吴',
				);
				if($data['data']['is_special_vip']==1){
					$data['data']['is_special_vip']	=	'是';
				}else{
					$data['data']['is_special_vip']	=	'否';
				}
				$data['data']['energy_add_time']	=	date('Y-m-d H:i:s',$data['data']['energy_add_time']);
				$data['data']['sex']		=	$sex[$data['data']['sex']];
				$data['data']['country']	=	$country[$data['data']['country']];
				$this->_assign['data']	=	$data['data'];
				$this->_assign['id']	=	$_POST['playerId'];
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['msg'],-1);
			}
			$this->_assign['editurl']	=	$this->	_urlEdituser($postData['user_id']);
		}else{
			$this->_assign['editurl']	=	$this->	_urlEdituser();
		}
		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
//	public function _getPersonalInformation($id){
//		$data = $this->getResult($UrlAppend,$get,$postData);
//	}
	
	private function _urlEdituser($id=NULL){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'		=>$id,
		);
		return Tools::url(CONTROL,'UpdatePersonalInformation',$query);
	}
}