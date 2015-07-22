<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCard_HuanJL extends Action_ActionBase{
	const ITEM_TYPE = 'itemTypes';
	const BIND_TYPE = 'bindType';
	const GOLD_TYPE = 'goldType';
	private $_items = array();
	public function _init(){
		
		$this->_assign[self::BIND_TYPE] = Tools::gameConfig(self::BIND_TYPE,$this->_gameObject->_gameId);	//bindType
		$this->_assign[self::GOLD_TYPE] = Tools::gameConfig(self::GOLD_TYPE,$this->_gameObject->_gameId);	//goldType
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
			'classId'=>intval($_GET['classId']),
			'name'=>trim($_GET['className']),
			'ps'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		return array_filter($postData);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['submit']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		unset($getData['serverId']);	//这个接口不用serverId这个参数
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);
// 		print_r($postData);
// 		print_r($getData);
		if( !($data && $data['status'] ==1) ){
			$this->_assign['connectError'] = 'Error Info:'.$data['info'];
			return $this->_assign;
		}

		$itemsCon = array();
		if(is_array($tmpData = $data['data']) && is_array($tmpData = $tmpData['list'])){
			foreach($tmpData as &$sub){
				//$sub['type'] = '';
				$sub['URL_itemCardAppendApply'] = Tools::url(CONTROL,'ItemCardAppendApply',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'classId'=>$sub['id'],'bindType'=>$sub['bindType'],'type'=>$sub['type']));
				$sub['URL_batchList'] = Tools::url(CONTROL,'ItemCardShowBatch',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'classId'=>$sub['id'],));
				$sub['URL_ItemPackageEdit'] = Tools::url(CONTROL,'ItemPackageEdit',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'classId'=>$sub['id'],'submit'=>1));
				$sub['bindType'] = $this->_assign[self::BIND_TYPE][$sub['bindType']];
				$sub['goldType'] = $this->_assign[self::GOLD_TYPE][$sub['goldType']];
// 				$sub['goods'] = json_decode($sub['goods'],true);
				if(is_array($sub['goods'])){
					foreach($sub['goods'] as $item){
						$itemsCon[$item['goodsId']] = '';
					}
				}
			}
			
			$itemsCon = $this->_getItemsName($itemsCon);
			foreach($tmpData as &$sub){
				$sub['itemInfo'] = '';
				if(is_array($sub['goods'])){
					foreach($sub['goods'] as $item){
						$sub['itemInfo'] .= $itemsCon[$item['goodsId']]."(<font color='#FF0000'>{$item['num']}</font>),";
					}
				}
			}
			$this->_assign['dataList'] = $tmpData;
			if($data['data']['total']){
				$total = intval($data['data']['total']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Info:返回数据结构有误';
		}
		return $this->_assign;
	}
	
	private function _getItemsName($itemIds){
		if(empty($this->_items)){
			$this->_items = $this->partner('Item');
		}
		$items = array();
		if($itemIds){
			foreach ($this->_items as $sub){
				foreach($itemIds as $itemId =>$val){
					if(isset($sub[$itemId])){
						$items[$itemId] = $sub[$itemId];
						unset($itemIds[$itemId]);
					}
				}
			}
		}
		return $items;
	}
	

}

//"$data" = Array [3]	
//	data = Array [3]	
//		0 = Array [17]	
//			name = (string:4) 4444	
//			id = (int) 102	
//			type = (int) 1	
//			serverId = null	
//			classId = (int) 102	
//			goods = (string:109) [{"goodsId":106354,"num":5,"bind":1},{"goodsId":188006,"num":5,"bind":1},{"goodsId":101006,"num":6,"bind":1}]	
//			bindType = (int) 2	
//			counts = (int) 1	
//			playerId = null	
//			mailTitle = null	
//			mailContent = null	
//			batchId = (int) 0	
//			expire = (int) 0	
//			goldType = (int) 2	
//			goldValue = (int) 5	
//			timestamp = (int) 0	
//			sign = null	
//		1 = Array [17]	
//		2 = Array [17]	
//	status = (int) 1	
//	info = null	

