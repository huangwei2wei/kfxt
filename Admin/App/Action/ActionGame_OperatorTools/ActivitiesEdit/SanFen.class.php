<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesEdit_SanFen extends Action_ActionBase{
	public function _init(){
// 		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
// 		print_r($_POST);exit;
		if($this->_isPost()){
			$postData = array(
				'id'=>intval($_POST['id']),
				'activation '=>intval($_POST['activation']),
				'beginTime'=>trim($_POST['beginTime']),
				'endTime'=>trim($_POST['endTime']),
			);
			
			$getData = $this->_gameObject->getGetData($get);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			print_r($postData);
// 			print_r($data);exit;
			if($data['status']==1){
				if($data['data']== true){
					$jumpUrl = $this->_url();
					$this->jump('操作成功',1,$jumpUrl);
				}
				$this->jump($data['info'],-1);
			}else{
				$this->jump($data['info'],-1);
			}
		}else{
			$activation = array(1=>'是',0=>'否');
			$get['id'] = $_GET['getOneById'];
			$getData = $this->_gameObject->getGetData($get);
			$postData=array();
			if($post){
				$postData = array_merge($post,$postData);
			}
			$data = $this->getResult('getActivity.jsp',$getData,$postData);
			$this->_assign['data'] = $data['data'][0];
			$this->_assign['activation'] = $activation;
		}
		return $this->_assign;
	}

	private function _url($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'ActivitiesList',$query);
	}

}