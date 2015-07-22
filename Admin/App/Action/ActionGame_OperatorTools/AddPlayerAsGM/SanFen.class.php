<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AddPlayerAsGM_SanFen extends Action_ActionBase{

	public function _init(){
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
	}
	public function getPostData($post=null){

		$postData = array(
			'playerId'=>$_POST['playerId'],
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isAjax()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$doAction = $_POST['doAction'];
			switch ($doAction){
				case 1:
					$UrlAppend = 'addPlayerAsGM.jsp';
					break;
				case 0:
					$UrlAppend = 'deletePlayerAsGM.jsp';
					break;
			}
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			echo $UrlAppend;print_r($postData);print_r($data);exit;
			
			if($data['status'] == 1){
				$this->ajaxReturn(array('status'=>1,'info'=>'操作成功！','data'=>null));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>$data['info'],'data'=>null));
			}
		}
		
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['userTypeSelect'] = 1;
		$this->_assign['users'] = $playerIds;
		return $this->_assign;
	}
	
}