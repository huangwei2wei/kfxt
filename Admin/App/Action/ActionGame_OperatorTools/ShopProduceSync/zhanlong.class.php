<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ShopProduceSync_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$type = array(
			"WorldID"	=>"区ID",
			"SellID"	=>"商品ID",
			"OrderLevel"	=>"排序等级",
			"NeedJob"=>"适用职业",
			"ShopLableID"=>"标签页ID",
			"ShopLableName"=>"标签页名",
			"ItemID"=>"道具ID",
			"ItemNum"=>"道具数量",
			"ItemName"=>"道具名称",
			"IconFile"=>"图标连接",
			"ImproveLevel"	=>"强化等级",
			"AttachAttribute"	=>"强化属性",
			"MaturityTime"	=>"过期时间",
			"TimeDurable"	=>"时间耐久度",
			"CurrencyType"	=>"货币类型",
			"PriceSell"=>"售卖价格",
			"Original"=>"原价",
			"IsBuyBind"=>"是否买入即绑定",
			"IsHot"=>"是否热卖",
			"IsDiscout"	=>"是否打折",
			"IsTimeLoss"	=>"是否剩余时间（下线计时）",
			"SaleType"=>"售卖类型",
			"SaleTime"	=>"售卖时间",
			"SellText"	=>"商品描述",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		switch ($_GET['doaction']){
			case 'serverSync' :{
				return $this->_shopProduceServerSync($UrlAppend,$get,$post);
			}
			default:{
				return $this->_shopProduceIndex($UrlAppend,$get,$post);
			}
		}
	}

	public function _shopProduceServerSync($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($this->_isAjax()){
			$arr = explode(",",$_REQUEST["sysnValue"]);
			if(is_array($arr)){
				$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
				$getData = $this->_gameObject->getGetData($get);
				foreach($arr as $k=>$v){
					if($_GET["ac"]!="del"){
						$getData["WorldID"]		=	max(0,intval($serverList[$_REQUEST["server_id"]]['ordinal']));
						$getData["SellID"]		=	intval($v);
						$dataIndex 				= 	$this->getResult($UrlAppend,$getData);
						foreach($dataIndex["Column"] as $sk=>$sv){
							$PackData[$sv]	=	$dataIndex["MallItemList"][$sk];
							if($sv=="Price"){
								$PackData["PriceSell"]	=$dataIndex["MallItemList"][$sk];
							}
						}
						$data					=	$PackData;
						$data["Remove"]			=	intval(0);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface 		= 	$this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/MallItem",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}else{
						$data["Remove"]			=	intval(1);
						$data["WorldID"]    	=   intval($serverList[$_REQUEST["server"]]['ordinal']);
						$data["SellID"]		=	intval($v);
						$SendData["data"]		=	json_encode($data);
						$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
						$r = $utilHttpInterface->result($serverList[$_REQUEST["server"]]['server_url'],"UpdateSystem/MallItem",$getData,$SendData);
						$r = json_decode($r,true);
						if($r["Result"]===0){
						}else{
							$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
						}
					}
				}
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>"data is null"));
			}
			$this->ajaxReturn(array('status'=>1,'msg'=>"succeed"));

		}else{
			$getData = $this->_gameObject->getGetData($get);

			$getData["Page"]		=	max(0,intval($_POST['Page']-1));
			$getData["WorldID"]		=	max(0,intval($_POST['WorldID']));
			$sellIds = $_POST['data'];

			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result'] == '0'){
				$Column = $data["Column"];
				$MallItemList	=	$data["MallItemList"];
				$datalist		=	array();
				$i = 0;$a = 0;

				foreach ($data['MallItemList'] as $key=>&$sub){
					//					if($Column[$i]=="商品描述"){
					//						$sub = strip_tags($sub);
					//					}
					$datalist[$a][$Column[$i]]		=	$sub;

					if($Column[$i]=="区ID"){
						$datalist[$a]["WorldID"] 	= 	$sub;
					}
					if($Column[$i]=="商品ID"){
						$datalist[$a]["SellID"] 	= 	$sub;
					}
					$i++;
					if($i>=count($Column)){
						$i=0;
						$a++;
					}
				}
				$datalist=$this->_f("zlsg_ShopProduceSync".$_REQUEST['server_id']);
				foreach ($datalist as $k=>$val){
					if(!in_array($val['SellID'],$sellIds)){
						unset($datalist[$k]);
					}
				}

				$this->_assign['Column']=$Column;
				$this->_assign["len"]	=	count($Column)+1;
				$this->_assign['dataList']=$datalist;
				$this->_assign['tplServerSelect'] = "ActionGame_OperatorTools/AllNotice/ServerSelect.html";
				$this->_assign['sysnValue'] = implode(",",$_POST['data']);
			}
		}
		$this->_assign['docation'] = 'serverSync';
		return $this->_assign;
	}


	private function updateCard($page){
		if(empty($page)){
			$page=0;
		}
		$getData = $this->_gameObject->getGetData($get);
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
		$WorldID = $serverList[$_REQUEST['server_id']]['ordinal'];
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		$postData=array(
		       'WorldID' => max(0,intval($_GET['WorldID'])),
		       'Page' => max(0,intval($page)),
		);

		$getData	=	array_merge($getData,$postData);
		if($post){
			$postData = array_merge($post,$postData);
		}

		$data = $this->getResult("QuerySystem/MallItemList",$getData);
		$datalist = array();
		if($data['Result'] == '0'){
			$arr1 = array();
			$arr2 = array();
			$arr1 = $this->getList($data);
			$count=$data['Count']/20;
			if($page<($count-1)){
				$arr2 = $this->updateCard($page+1);
			}
			$datalist = array_merge($arr1,$arr2);
		}else{
			$this->jump("错误：".$data['Result'],-1);	
		}
		return $datalist;
	}


	private function getList($data){
		$Column = $data["Column"];
		$MallItemList	=	$data["MallItemList"];
		$datalist		=	array();
		$i = 0;
		$a = 0;
		//			MallItemList

		foreach ($data['MallItemList'] as $key=>&$sub){
			if($Column[$i]=="商品描述"){
				$sub = strip_tags($sub);
			}
			$datalist[$a][$Column[$i]]		=	$sub;

			if($Column[$i]=="区ID"){
				$datalist[$a]["WorldID"] 	= 	$sub;
			}
			if($Column[$i]=="商品ID"){
				$datalist[$a]["SellID"] 	= 	$sub;
			}
			$i++;
			//				"WorldID"	=>"区ID",
			//			"SellID"	=>"商品ID",
			if($i>=count($Column)){
				$i=0;
				$a++;
			}
		}
		return $datalist;
	}


	public function _shopProduceIndex($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["doaction"]=="updateCard"){
			$datalist = $this->updateCard(0);
			$this->_f("zlsg_ShopProduceSync".$_REQUEST['server_id'],$datalist);
			$this->jump('操作成功',1);

		}
		$this->_assign['update'] = Tools::url(CONTROL,'ShopProduceSync',$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				"doaction"=>"updateCard",
		));

		$_GET["WorldID"] = $this->_getServerID();
		$this->_assign["_GET"] = $_GET;
		$mergerData=array(
		       'WorldID' => max(0,intval($_GET['WorldID'])),
		       'Page' => max(0,intval($_GET['page']-1)),
		);
		$getData = $this->_gameObject->getGetData($get);
		$getData = array_merge($getData,$mergerData);

		$data = $this->getResult($UrlAppend,$getData);

		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["MallItemList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			//			MallItemList

			foreach ($data['MallItemList'] as $key=>&$sub){
				if($Column[$i]=="商品描述"){
					$sub = strip_tags($sub);
				}
				$datalist[$a][$Column[$i]]		=	$sub;

				if($Column[$i]=="区ID"){
					$datalist[$a]["WorldID"] 	= 	$sub;
				}
				if($Column[$i]=="商品ID"){
					$datalist[$a]["SellID"] 	= 	$sub;
				}
				$i++;
				//				"WorldID"	=>"区ID",
				//			"SellID"	=>"商品ID",
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}

			$this->_assign['Add_Url']=$this->_urlAdd();
			$this->_assign['Del_Url']=$this->_urlDel();
			$this->_assign['Column']=$Column;
			$this->_assign["len"]	=	count($Column)+1;
			$this->_assign['dataList']=$datalist;
			$_SERVER ['REQUEST_URI'].="&WorldID=".$getData['WorldID']; 
			$this->_assign['dataList'] = $this->_f("zlsg_ShopProduceSync".$_REQUEST['server_id']);
			$this->_assign['dataListcount'] = count($this->_f("zlsg_ShopProduceSync".$_REQUEST['server_id']));
		}
		$this->_assign['WorldID'] = $mergerData['WorldID'];
		$this->_assign['Page'] = $mergerData['Page'];
		$this->_assign['syncDel'] = Tools::url(CONTROL,'ShopProduceSync',$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			"doaction"=>"serverSync",
			"ac"=>"del",
			"page"=>$_GET["page"],
		));
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'AddShopProduce',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'DelShopProduce',$query);
	}

	/**
	 * 并发时生成的消息
	 * @param array $data back_result
	 * @return string
	 */
	private function _getMultiMsg($data){
		$serverList=$this->_getGlobalData('gameser_list');
		$sendStatusMsgs='';
		foreach ($data as $key=>$value){
			if ($value['Result']===0){
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_SUCCESS','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
			}else {
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_FAILURE','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
			}
		}
		return $sendStatusMsgs;
	}

}