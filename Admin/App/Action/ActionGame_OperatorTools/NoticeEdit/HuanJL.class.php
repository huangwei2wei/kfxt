<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_HuanJL extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
// 		print_r($_POST);
// 		$gameConf = $this->_gameObject->getIfConf();
// 		$getData = $this->_gameObject->getGetData($gameConf['NoticeEdit']['get']);
// 		$postData = $this->_gameObject->getPostData($_GET['id']);
// 		if($post){
// 			$postData = array_merge($post,$postData);
// 		}
// 		$postData = array_filter($postData);
// 		$data = $this->_gameObject->getNotice($postData);
// 		if($data['data']){
// 			foreach($data['data'] as &$item){
// 				if($item['id']==$_GET['id']){
// 					$this->_assign['selected']	=	$item;
// 				}
// 			}
// 		}
		
		
		$noticeType = Tools::gameConfig('noticeType',$this->_gameObject->_gameId);
		$this->_assign['noticeType'] = $noticeType;
		$this->_assign['forbid'] = array('0'=>'不屏蔽','1'=>'屏蔽');
		 
		if(isset($_GET['id'])){
			$getData  = $this->_gameObject->getGetData(array('type'=>'placardlist'));
			$data = $this->getResult($UrlAppend,$getData,array('id'=>$_GET['id']));
			if($data['status'] == 1){
				$this->_assign['info'] = $data['data']['list'][0];
			}
		}
		if($this->_isPost() && !empty($_POST["subbutton"])){

			$postData = array(
				'id'	=>trim($_GET["id"]),
				'title'=>trim($_POST['title']),
				'delay'=>intval($_POST['IntervalTime']),
				'forbid'=>intval($_POST['forbid']),
				'type'=>intval($_POST['type']),
				'begin'=>strtotime(trim($_POST['begin'])),
				'end'=>strtotime(trim($_POST['end'])),
			);
			
			
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			print_r($postData);
// 			print_r($data);exit;
			if($data['status'] == 1){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		return $this->_assign;
	}
	
	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id']
		);
		return Tools::url(CONTROL,'Notice',$query);
	}
	
}