<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_MailList_MingXing extends Action_ActionBase{

	
	public function _init(){
//		$this->_assign['wearType'] = $this->_wearType;
//		$this->_assign['URL_EquipmentUpgrade'] = Tools::url(CONTROL,'EquipmentUpgrade',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
//		$this->_assign['URL_EquipmentDel'] = Tools::url(CONTROL,'EquipmentDel',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
	}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['nickName'] = urlencode(trim($_GET['nickName']));
		$postData['openId'] = trim($_GET['openId']);
		$postData['curPage'] = max(1,intval($_GET['page']));
		$postData['pageSize'] = PAGE_SIZE;
		$postData = array_filter($postData,'strlen');
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
		if(is_array($data) && $data['status']==1 ){
			if($data['data']['list']){
				foreach($data['data']['list'] as &$_msg){
					$_msg["title"]	=	urldecode($_msg["title"]);
					$_msg["content"]	=	urldecode($_msg["content"]);
					$_msg['time'] = date('Y-m-d H:i:s',$_msg['time']);
				}
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();	
			}
		}else{
			$this->jump('服务器返回错误:'.$data['info'],-1);
		}
		return $this->_assign;
	}
}
