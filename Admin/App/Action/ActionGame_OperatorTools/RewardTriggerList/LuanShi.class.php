<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_RewardTriggerList_LuanShi extends Action_ActionBase{
	public function _init(){
		$this->_assign['exchangeTypes'] = Tools::gameConfig('exchangeTypes',$this->_gameObject->_gameId);	//itemTypes
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'ps'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
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
		$this->_assign['server_id'] = $_REQUEST['server_id'];
	
			$postData=$this->getPostData($post);
// 			print_r($postData);
			$getData = $this->_gameObject->getGetData($get);
 			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			print_r($data);
// 			exit;
			
 			if($data['status'] == 1 && is_array($data['data']['list'])){
 				$list = $data['data']['list'];
//  				print_r($list);exit;
				foreach($list as &$sub){
					$sub['del_url'] = Tools::url(CONTROL,'RewardTriggerDel',array('id'=>$sub['id'],'zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'doaction'=>'delById'));
					$sub['del_urlByTitle'] = Tools::url(CONTROL,'RewardTriggerDel',array('title'=>$sub['title'],'zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'doaction'=>'delByTitle'));
					foreach ($sub['award'] as $k=>$v){
						$award .= $v['cname'].'('.$v['num'].') ';
					}
					$sub['award'] = $award;
					$award = '';
				} 
				$this->_assign['dataList'] = $list;
				
// 				$this->_loadCore('Help_Page');//载入分页工具
// 				$helpPage=new Help_Page(array('total'=>intval($data['data']['total']),'perpage'=>PAGE_SIZE));
// 				$this->_assign['pageBox'] = $helpPage->show();
			}
		
		return $this->_assign;
	}
}