<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_FunOnOrOff_XiYou extends Action_ActionBase{
 
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		switch ($_GET['do']){
			case 'updateFunction':
				return $this->updateFunction($UrlAppend,$get,$post);
				break;
			case 'getFunction':
				return $this->getFunction($UrlAppend,$get,$post);
				break;
			default:
				return null;
		}
	}
	private function getFunction($UrlAppend=null,$get=null,$post=null){
		$data = array(
					'id'=>$_GET['id'],
				);
		$postData = $this->_gameObject->getPostData($post);
		$postData = array_merge($data,$postData);
		$data = $this->getResult($UrlAppend,$get,$postData);
	    if($data['status'] == 1){
	    	$result = array(
	    			'status' => 1,
	    			'id' => $data['data']['id'],
	    			'isClose' => $data['data']['isClose']
	    			);
	    	exit( json_encode($result));
	    }else{
	    	$result = array(
	    			'status' => 0,
	    			'id' => $data['info'],
	    	);
	    	exit( json_encode($result));
	    }
	}
	
	private function updateFunction($UrlAppend=null,$get=null,$post=null){
		$data=array(
				'id'=>$_GET['id'],
				'isClose'=>$_GET['isClose'],
		);
		$postData = $this->_gameObject->getPostData($post);
		$postData = array_merge($data,$postData);
		$data = $this->getResult('server/updateFunction',$get,$postData);
// 		print_r($postData);
		if($data['status'] == 1){
			$result = array(
					'status' => 1,
					'info' => '操作成功',
			);
			exit( json_encode($result));
		}else{
			$result = array(
					'status' => 0,
					'info' => $data['info'],
			);
			exit( json_encode($result));
		}
	}
}


