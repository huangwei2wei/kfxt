<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AddShopProduce_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$items = $this->_f("18_ActionGame_MasterTools_Define_zhanlong",'',CACHE_DIR);
		$this->_assign["items"]	=	$this->_f("18_ActionGame_MasterTools_Item_zhanlong",'',CACHE_DIR);
		if($this->_isPost()){
			if(count($_POST["attribute"])>0){
				$AttachAttribute = "";
				foreach($_POST["attribute"] as $k=>$a){
					$AttachAttribute .= $a."|".$_POST["attribute_mun"][$k].";";
				}
			}
			if(count($_POST["NeedJob"])>0){
				$NeedJob = "";
				foreach($_POST["NeedJob"] as $b){
					$NeedJob .= $b.";";
				}
			}
			if(count($_POST["PriceSell"])>0){
				$PriceSell = "";
				foreach($_POST["PriceSell"] as $b){
					$PriceSell .= $b."|".$_POST["PriceSell".$b].";";
				}
			}

			if(count($_POST["Original"])>0){
				$Original = "";
				foreach($_POST["Original"] as $b){
					$Original .= $b."|".$_POST["Original".$b].";";
				}
			}
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'SellID'=>intval($_POST['SellID']),
				'OrderLevel'=>intval($_POST['OrderLevel']),
				'ShopLableID'=>intval($_POST['ShopLableID']),
				'NeedJob'=>trim($NeedJob),
				'ShopLableName'=>trim($_POST['ShopLableName']),
				'ItemID'=>intval($_POST["ItemID"]),
				'AttachAttribute'=>$AttachAttribute,
				'ImproveLevel'=>intval($_POST['ImproveLevel']),
				'MaturityTime'=>$_POST['MaturityTime'],
				'TimeDurable'=>intval($_POST['TimeDurable']),
				'PriceSell'=>trim($PriceSell),
				'Original'=>trim($Original),
				'IsBuyBind'=>intval($_POST['IsBuyBind']),
				'IsHot'=>intval($_POST['IsHot']),
				'IsDiscout'=>intval($_POST['IsDiscout']),
				'IsTimeLoss'=>intval($_POST['IsTimeLoss']),
				'SaleType'=>intval($_POST['SaleType']),
				'SellText'=>trim($_POST['SellText']),
				'SaleTime'=>trim($_POST['SaleTime']),
				'ItemNum'=>intval($_POST['ItemNum']),
				'ItemName'=>trim($_POST['ItemName']),
				'IconFile'=>trim($_POST['IconFile']),
				'Remove'=>intval(0),
			);

			foreach($_POST["CurrencyType"] as $a){
				$postData['CurrencyType']	.=	$a.";";
			}



			if($post){
				$postData = array_merge($post,$postData);
			}
			$SendData["data"]	=	json_encode($postData);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$SendData);
			if($data["Result"]===0){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		if(!empty($_GET["SellID"])){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]		=	max(0,intval($_GET["WorldID"]));
			$getData["SellID"]		=	intval($_GET['SellID']);
			$dataIndex = $this->getResult("QuerySystem/MallItemList",$getData);
			$a = array();
			foreach($dataIndex["Column"] as $k=>$item){
				$a[$item]	=	$dataIndex["MallItemList"][$k];
			}
			$a["CurrencyType"]=explode(";",$a["CurrencyType"]);
			foreach($a["CurrencyType"] as $v){
				$a["CurrencyType"][$v]=$v;
			}
			$a["PriceSell"]=explode(";",$a["PriceSell"]);
			foreach($a["PriceSell"] as $v){
				$arr = explode("|",$v);
				$c["a"] = $arr[0];
				$c["b"] = $arr[1];
				$dataarr[$c["a"]]=$c;
			}
			$a["PriceSell"] = $dataarr;
			
			$a["Original"]=explode(";",$a["Original"]);
			foreach($a["Original"] as $v){
				$arr = explode("|",$v);
				$c["a"] = $arr[0];
				$c["b"] = $arr[1];
				$dataarra[$c["a"]]=$c;
			}
			$a["Original"] = $dataarra;
			
			$a["NeedJob"]=explode(";",$a["NeedJob"]);
				
			foreach($a["NeedJob"] as $v){
				$a["NeedJob"][$v]=$v;
			}
			$this->_assign["data"]	=	$a;
		}
		$this->_assign["items"]	=	$this->_f("18_ActionGame_MasterTools_Item_zhanlong",'',CACHE_DIR);

		$this->_assign["gold"]	=	$items["AllDefine"][1]["Detail"];
		$this->_assign["attribute"]	=	$items["AllDefine"][2]["Detail"];
		return $this->_assign;
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ShopProduce',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}