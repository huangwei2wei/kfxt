<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_XiYou extends Action_ActionBase{
	private $_backpackSearchType = array();
	private static $_goods = null;
	public function _init(){
		$this->_assign['URL_itemsDel'] = $this->_urlItemsDel();
		$this->_backpackSearchType = Tools::gameConfig('backpackSearchType',$this->_gameObject->_gameId);
		$this->_assign['backpackSearchType'] = $this->_backpackSearchType;
		$this->_assign['userType'] = Tools::gameConfig('userType',$this->_gameObject->_gameId);
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($this->_isPost()){
			$postData = $this->_gameObject->getPostData($post);
			if(!$_POST['user']){
				$this->jump('用户不能为空',-1);
			}
			$postData['user'] = $_POST['user'];
			$postData['userType'] = $_POST['userType'];
			$postData['type'] = max(1,trim($_POST['backpackSearchType']));
			$data = $this->getResult($UrlAppend,$get,$postData);
			
// 			var_dump($data);exit;
			
			if($data['status'] == '1' ){
				if($data['data']){
					$goods = $data['data']['list'];
					foreach ($goods as &$v){
						$v['goodsName'] = $this->_getGoodsName($v['goodsId']);
					}
					$this->_assign['dataList'] = $goods;
					$this->_assign['UserID'] = $data['data']['UserID'];
					$this->_assign['UserName'] = $data['data']['UserName'];
					$this->_assign['NickName'] = $data['data']['NickName'];
				}else{
					$this->jump('没有此用户',-1);
				}
			}else{
				$this->jump('查询失败'.$data['info'],-1);
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
	private function _urlItemsDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ItemDel',$query);
	}
	private function _getGoodsName($goodsId){
		if($this->_goods == null){
			$this->_goods = $this->partner('Item');
		}
		if(is_array($this->_goods)){
			foreach ($this->_goods as $subK=>$subV ){
				foreach ($subV['subList'] as $k => $v){
					if ($k == $goodsId){
						return $v;
					}
				}
			}
		}
	}
	
}