<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_QShopProduce_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$type = array(
			"WorldID"	=>"区ID",
			"SellID"	=>"商品ID",
			"OrderLevel"	=>"排序等级",
			"ItemID"=>"道具ID",
			"ItemName"=>"道具名",
			"ItemNum"=>"道具数量",
			"IconFile"=>"图标url",
			"NeedJob"	=>"适用职业",
			"PriceSell"	=>"当前价格",
		
			"Origin"=>"原价",
			"IsHot"=>"是否热卖",
			"IsDiscout"=>"是否打折",
			"SaleType"	=>"售卖类型",
			"SaleTime"	=>"售卖时间",
			"SellText"	=>"商品描述",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page']));
		$getData["WorldID"]		=	max(0,intval($_POST["WorldID"]));
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["CashItemList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['CashItemList'] as $key=>&$sub){
				if($Column[$i]=="SellText"){
					$sub = strip_tags($sub);
				}
				$datalist[$a][$Column[$i]]	=	$sub;
				$i++;
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}
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
		return Tools::url(CONTROL,'AddQShopProduce',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'DelQShopProduce',$query);
	}

}