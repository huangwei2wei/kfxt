<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCard_chunqiu extends Action_ActionBase{
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
			'className'=>trim($_GET['className']),
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
		);
		return array_filter($postData);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}


		if($_GET["Card_info"]){
			$data = $this->_gameObject->result($this->_getUrl(),array("card_type"=>$_GET["Card_info"]),"60027");
			$data = json_decode($data,true);
			foreach($data["card_info"] as &$item){
				$item["end_time"] = date("Y-m-d H:i:s",$item["end_time"]);
				$item["create_time"] = date("Y-m-d H:i:s",$item["create_time"]);
			}
			$this->_assign["data"] = $data["card_info"];
			$this->_assign["GET"] = $_GET;
			return $this->_assign;
		}
		//		$postData = $this->getPostData($post);
		//		$getData = $this->_gameObject->getGetData($get);
		$data = $this->_gameObject->result($this->_getUrl(),"",$UrlAppend);
		$data = base64_encode($data);
		$data = base64_decode($data);
		$data = json_decode($data,true);
//		print_r($data);
		$server_list = $this->_getGlobalData('server/server_list_23');
		$url = $server_list[$_REQUEST['server_id']]['server_url'];
		$url = explode(":",$server_list[$_REQUEST['server_id']]['server_url']);
		if($data['status'] ==1){
			foreach($data["card_list"] as &$item){
				$item["desc"]	=	$item["item_data"]["desc"];
				$item["card_info"]=Tools::url(
					CONTROL,
					'ItemCard',
					array(
						'zp'=>PACKAGE,
						'__game_id'=>$this->_gameObject->_gameId,
						'server_id'=>$_REQUEST['server_id'],
						"Card_info"=>$item["card_type"])
						
				);
				$item["url"] = "http://".$url[0]."".$item["url"];
				foreach($item["item_data"]["item_list"] as &$a){
					$item["content"]	.=	"ID:".$a["item_id"]."数量:".$a["number"]."是否绑定：".$a["bind"].";";
				}
			}
			
			$data["card_list"] = array_reverse($data["card_list"]);
			$this->_assign["data"] = $data["card_list"];
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

