<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ShopProduceEdit_SanFen extends Action_ActionBase{
	public function _init(){
// 		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		if($this->_isPost()){
			unset($_POST['sbm']);
			$postData = $_POST;
			
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
			$ys = array(1=>'是',0=>'否');
			$get['id'] = $_GET['getOneById'];
			$getData = $this->_gameObject->getGetData($get);
			$postData=array();
			if($post){
				$postData = array_merge($post,$postData);
			}
			$data = $this->getResult('getGoods.jsp',$getData,$postData);
		 	$data = $data['data'][0];
		 	$data ['fashion'] = $data ['fashion'] == ''?0:1;
		 	$data ['hot'] = $data ['hot'] == ''?0:1;
		 	$data ['horse'] = $data ['horse'] == ''?0:1;
		 	$data ['gift'] = $data ['gift'] == ''?0:1;
		 	$data ['vip'] = $data ['vip'] == ''?0:1;
		 	$data ['mystical'] = $data ['mystical'] == ''?0:1;
		 	$data ['purchase'] = $data ['purchase'] == ''?0:1;
		 	$data ['forbid'] = $data ['forbid'] == ''?0:1;
			$this->_assign['data'] = $data;
			$this->_assign['ys'] = $ys;
		}
		return $this->_assign;
	}

	private function _url(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ShopProduce',$query);
	}

}