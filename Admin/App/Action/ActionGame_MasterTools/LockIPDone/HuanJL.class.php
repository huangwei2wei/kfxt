<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockIPDone_HuanJL extends Action_ActionBase{
	protected $_param;
	protected $_cause;
	
	public function _init(){}
	
	public function getPostData($post=null){
		$this->_param = trim($_POST['param']);
		$postData = array(
			'ips'=>$this->_param,
			'ForbidTime'=>$_POST['endTime']
		);
		$this->_cause = trim($_POST['cause']);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $getData = $this->_gameObject->getGetData($get);
			$postData = $this->getPostData();
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1 && count($data['data'])>0){
				$this->jump('操作成功');
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}elseif ($_GET['del']){
			$getData = $getData = $this->_gameObject->getGetData($get);
			$getData['type']='ipdel';
			$postData = array(
					'ips'=>$_GET['ip'],
			);
			var_dump($UrlAppend);
			print_r($getData);
			
			$data = $this->getResult($UrlAppend,$getData,$postData);
			print_r($postData);
			print_r($data);exit;
			
			
			if($data['status'] == 1 && count($data['data'])>0){
				$this->jump('操作成功');
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		return $this->_assign;
// 		echo 'aa';
// 		$v = View::getInstance ();
// 		$v -> set_tpl(array('body'=>'aa/aa'));
// 		$v -> display();
	}
	
}