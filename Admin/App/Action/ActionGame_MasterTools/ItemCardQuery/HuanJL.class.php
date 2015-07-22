<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardQuery_HuanJL extends Action_ActionBase{
	const BIND_TYPE = 'bindType';
	const GOLD_TYPE = 'goldType';
	private $_items = array();
	public function _init(){
		
	}
	
	public function getPostData($post=null){
		return array(
			'secreKey'=>trim($_POST['secretKey']),
		);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$this->_isPost()){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);
		if($data['status'] != 1){
			$this->_assign['connectError'] = 'Error Info:'.$data['info'];
			return $this->_assign;
		}
		$cardInfo = $data['data'];
		$itemsCon = array();
		if(is_array($cardInfo['goods'])){
			foreach($cardInfo['goods'] as $item){
				$itemsCon[$item['goodsId']] = '';
			}
			$itemsCon = $this->_getItemsName($itemsCon);
			$cardInfo['itemInfo'] = '';
			foreach($cardInfo['goods'] as $item){
				$cardInfo['itemInfo'] .= $itemsCon[$item['goodsId']]."(<font color='#FF0000'>{$item['num']}</font>),";
			}
			
// 			$bindTypes = Tools::gameConfig(self::BIND_TYPE,$this->_gameObject->_gameId);	//bindType
// 			$goldTypes = Tools::gameConfig(self::GOLD_TYPE,$this->_gameObject->_gameId);	//goldType
// 			$cardInfo['bindType'] = $bindTypes[$cardInfo['bindType']];
// 			$cardInfo['goldType'] = $goldTypes[$cardInfo['goldType']];
// 			$cardInfo['addTime'] = date('Y-m-d H:i:s',$cardInfo['addTime']);
// 			$cardInfo['exchangeTime'] = ($tmp=intval($cardInfo['exchangeTime']))?date('Y-m-d H:i:s',$tmp):'--';
		}
		$this->_assign['cardInfo'] = $cardInfo;
		return $this->_assign;
	}
		
	private function _getItemsName($itemIds){
		if(empty($this->_items)){
			$this->_items = $this->partner('Item');
		}
		$items = array();
		foreach ($this->_items as $sub){
			foreach($itemIds as $itemId =>$val){
				if(isset($sub[$itemId])){
					$items[$itemId] = $sub[$itemId];
					unset($itemIds[$itemId]);
				}
			}
		}
		return $items;
	}
//"$data" = Array [3]	
//	data = Array [17]	
//		name = (string:7) wanjia1	
//		counts = (int) 1	
//		serverId = (string:2) S1	
//		playerId = (int) 1	
//		bindType = (int) 2	
//		secretKey = (string:36) 77e8281a-0e91-4cdc-a188-10b945f4896e	
//		classId = (int) 112	
//		addTime = (int) 1323423956	
//		playerName = null	
//		playerAccount = null	
//		exchangeTime = (int) 0	
//		goods = (string:109) [{"goodsId":102509,"num":3,"bind":1},{"goodsId":102516,"num":4,"bind":1},{"goodsId":102569,"num":5,"bind":1}]	
//		goldType = (int) 0	
//		goldValue = (int) 0	
//		expire = (int) 0	
//		timestamp = (int) 1323424066	
//		sign = (string:32) f556a134990cf533fe64ae4028cc8e72	
//	status = (int) 1	
//	info = null	
	
}