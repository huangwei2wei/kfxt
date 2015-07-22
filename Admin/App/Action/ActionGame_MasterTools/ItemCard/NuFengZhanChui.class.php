<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCard_NuFengZhanChui extends Action_ActionBase{

	private static $_goods = array();
	private $cardType = array();
	
	public function _init(){
		$this->cardType = Tools::gameConfig('cardType',$this->_gameObject->_gameId);
// 		$this->_goods = $this -> _getItemsName($this->_goods);
		$this->_assign['cardType'] = $this->cardType;
		$this->_assign['URL_itemCardApply'] = Tools::url(
			CONTROL,
			'ItemCardApply',
			array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_itemCardQuery'] = Tools::url(
			CONTROL,
			'ItemCardQuery',
			array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
	}
	public function getPostData($post=null){
		$postData = array(
			'cardId'=>$_GET['cardId'],
			'cardName'=>trim($_GET['cardName']),
			'pageSize'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		if ($post){
			$postData = array ($postData,$post);
		}
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		switch ($_GET['do']){
			case 'failure':
				$this->_failure();
				break;
			default:
				return $this->_getCardlist($UrlAppend,$get,$post);
		}
	}
	private function _failure(){
		$postData = array('cardId'=>$_GET['cardId']);
		$postData = $this->_gameObject->getPostData($postData);
		$data = $this->getResult('itemCard/inValidCard',null,$postData);
		if($data['status'] == 1){
			$this->jump('操作成功',-1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}
	
	private function _getCardlist($UrlAppend=null,$get=null,$post=null){
		$postData = $this->getPostData($post);
		$postData = $this->_gameObject->getPostData($postData);
		$sendData = array_merge($postData,$get);
		$data = $this->_gameObject->getResult($UrlAppend,$sendData);
// 		print_r($postData);
// 		print_r($data);exit;


		if( $data['status'] ==1 ){
			$list = $data['data']['list'];
			if(is_array($list)){
				foreach($list as &$sub){
					$sub['URL_itemCardFailure'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'cardId'=>$sub['cardId'],'do'=>'failure'));
					$sub['goods'] = $this->_getGoodsInfo($sub['goods']);
					$sub['type'] = $this->cardType[$sub['type']];
				}
				$this->_assign['dataList'] = $list;
				$total = intval($data['data']['total']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Info:'.$data['info'];
			return $this->_assign;
		}
		return $this->_assign;
	}
	
	//获取道具列表
	private function _getItemsName($itemIds){
		if($itemIds == null){
			$itemIds = $this->partner('Item');
		}
		return $itemIds;
	}
	//解析物品
	private function _getGoodsInfo($goods){
		$goodsList = explode('&',$goods);
		$goodsInfo = '';
		if(is_array($goodsList)){
			foreach ($goodsList as $goods){
				$g = explode('_', $goods);
				$goodsInfo .= $this->_goods[$g[0]]['subList'][$g[1]].'('.$g[2].')  ';
			}
		}
		return $goodsInfo;
	}

}
