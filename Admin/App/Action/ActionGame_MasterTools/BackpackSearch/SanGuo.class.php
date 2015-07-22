<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$postData=array();
			$postData['Per']	=	PAGE_SIZE;
			if($_GET['page']){
				$postData['Page']	=	$_GET['page'];
			}else{
				$postData['Page']	=	1;
			}
			if(!$_POST['playerId']){
				$this->jump('用户ID不能为空',-1);
			}else{
				$postData['user_id']	=	$_POST['playerId'];
			}

			$category	=	array(
				'12'	=>	"军队",
				'13'	=>	"计谋",
				'14'	=>	"装备",
				'18'	=>	"能量",
				'19'	=>	"建筑",
				'20'	=>	"普通礼物",
				'21'	=>	"地契",
				'22'	=>	"商品",
			);
			$categorydata	=	$this->getResult($UrlAppend,array('a'=>'getGameData'),NULL);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == '1' && $data['data']){
				$this->_assign['post']	=	$_POST;
				foreach($data['data']['list'] as &$_msg){
					$_msg['obj_id']		=	$categorydata['data'][$_msg['category']][$_msg['obj_id']]['name'];
					$_msg['category']	=	$category[$_msg['category']];
				}
				$this->_assign['dataList']=$data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['totle_page'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}
		
		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
	private function _urladd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccountAdd',$query);
	}
	
	private function _urldel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'		=>	$id
		);
		return Tools::url(CONTROL,'LockAccountDel',$query);
	}
	
	
}