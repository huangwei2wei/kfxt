<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_MailList_WangZhe extends Action_ActionBase{

	
	public function _init(){
		$this->_assign['mailType'] = $this->getMailType();
		$this->_assign['playerType'] = $this->getPlayType();
//		$this->_assign['wearType'] = $this->_wearType;
//		$this->_assign['URL_EquipmentUpgrade'] = Tools::url(CONTROL,'EquipmentUpgrade',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
//		$this->_assign['URL_EquipmentDel'] = Tools::url(CONTROL,'EquipmentDel',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
	}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['playerType'] = trim($_GET['playerType']);
		$postData['playerId'] = base64_encode(trim($_GET['player']));
		$postData['mailType'] = trim($_GET['mailType']);
		$postData['isDeleted'] = trim($_GET['isDeleted']);
		$postData['isRead'] = trim($_GET['isRead']);
		$postData['page'] = max(1,intval($_GET['page']));
		$postData['pageSize'] = PAGE_SIZE;
// 		$postData = array_filter($postData,'strlen');
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=$this->getPostData($post);
		$sendData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$sendData,null);
		
// 		print_r($sendData);
// 		print_r($data);
// 		exit;
		if($data['status']==1){
			if($data['data']['list']){
				foreach($data['data']['list'] as &$_msg){
					$_msg["title"]	=	urldecode($_msg["title"]);
					$_msg["content"]	=	urldecode($_msg["content"]);
					$_msg['addTime'] = date('Y-m-d H:i:s',$_msg['addTime']); 
					if($_msg['getTime'] > 0){
						$_msg['getTime'] = date('Y-m-d H:i:s',$_msg['getTime']);
					}else{
						$_msg['getTime'] ='--';
					}
					if($_msg['attachment']){
						$_msg['attachment'] = '是';
					}else{
						$_msg['attachment'] = '否';
					}
				}
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['totalNum'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();	
			}
		}else{
			$this->jump('服务器返回错误:'.$data['info'],-1);
		}
		return $this->_assign;
	}
//  获取信件类型
	private function getMailType(){
		return array(1=>'收件箱',
					 2=>'发件箱',
					 3=>'存件箱'
				);
	}
// 	获取玩家类型
	private function getPlayType(){
		return array(1=>'用户Id',
					 2=>'用户账号',
					 3=>'用户呢称 ',
				);
	}

}
