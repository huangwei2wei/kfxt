<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ShopProduce_zhanlong extends Action_ActionBase{
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
			"IsTimeLoss"	=>"是否下线后还记时间流失",
			"SaleType"=>"售卖类型",
			"SaleTime"	=>"售卖时间",
			"SellText"	=>"商品描述",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["WorldID"]==""&&$_POST["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
			$_POST["WorldID"] = $this->_getServerID();
		}
		$_GET["WorldID"] = $_POST["WorldID"];
		$this->_assign["_GET"] = $_GET;
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page'])-1);
		$getData["WorldID"]		=	max(0,intval($_POST["WorldID"]));
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
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['Add_Url']=$this->_urlAdd();
		$this->_assign['Del_Url']=$this->_urlDel();
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

}