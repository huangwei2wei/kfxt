<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_zhanlong extends Action_ActionBase{

//		public $_Applytype = 51;
	public $_Applytype = 42;
	public function _init(){
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
	}

	private function _upload() {
		$this->_loadCore('Help_FileUpload');
		$uploadPath = UPDATE_DIR . '/zlsg/' . date ( 'Ymd', CURRENT_TIME );
		$helpFileUpload=new Help_FileUpload($_FILES['Excel'],$uploadPath);
		$helpFileUpload->setBaseUrl(__ROOT__.'/Upload/zlsg/'.date('Ymd',CURRENT_TIME));
		$helpFileUpload->singleUpload();
		return $helpFileUpload->getSaveInfo();
	}

	public function addApply($data=array()){
		$cause = "操作原因:<br>";
		$cause .= "区ID:".$data["WorldID"]."<br>";
		$cause .= "是否发送给全服所有人:".$data["IsAllPlayer"]."<br>";
		$cause .= "收件人ID:".$data["PlayerID"]."<br>";
		$cause .= "标题:".$data["Title"]."<br>";
		$cause .= "内容:".$data["Content"]."<br>";
		$cause .= "发送类型:".$data["IsPack"]."<br>";
		$cause .= "道具或礼包的ID:".$data["ItemID"]."<br>";
		$cause .= "数量:".$data["ItemNum"]."<br>";
		$cause .= "是否绑定:".$data["IsBinded"]."<br>";
		$cause .= "道具到期时间计时:".$data["TimeDurableType"]."<br>";
		$cause .= "时间耐久度:".$data["TimeDurable"]."<br>";
		$cause .= "强化等级:".$data["ImproveLevel"]."<br>";
		$cause .= "附加属性:".$data["AttachAttribute"]."<br>";
		$cause .= "钱:".$data["Money"]."<br>";
		$cause .= "经验:".$data["Exp"]."<br>";
		$getData = $this->_gameObject->getGetData();
		$SendData["data"]	=	json_encode($data);
		foreach($this->_serverList as $k=>$v){
			if($v["ordinal"]==$data["WorldID"]){
				$serverId	=	$k;
			}
		}
		if($serverId ==""){
			return "not find server";
		}
		$applyData = array(
				'type'=>$this->_Applytype,	//从Game拿id
				'server_id'=>$serverId,
				'operator_id'=>$this->_serverList[$serverId]['operator_id'],
				'game_type'=>$this->_serverList[$serverId]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>str_replace(array("\r\n","\n",),'',$cause),
				'send_type'=>1,	//2	http
				'send_data'=>array(
					'url_append'=>"UpdatePlayer/Mail",
					'post_data'=>$SendData,
					'get_data'=>$getData,
					'call'=>array(
						'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
						'cal_local_method'=>'ApplySend',
						'params'	=>array('data'=>$SendData,"server_id"=>$serverId,"UrlAppend"=>"UpdatePlayer/Mail"),
		),
		),
				'receiver_object'=>array($serverId=>''),
				'player_type'=>0,
				'player_info'=>$data["PlayerID"],
		);

		$modelApply = $this->_getGlobalData('Model_Apply','object');
		$modelApply->AddApply($applyData);
		return "申请成功";
	}

	public function _batchDeal(){
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		$file = $this->_upload();
		$data->read($file["path"]);
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$sendData = array(
				'WorldID'=>intval($data->sheets[0]['cells'][$i][1]),
				'IsAllPlayer'=>intval(0),
				'PlayerID'=>trim($data->sheets[0]['cells'][$i][2]),
				'Title'=>trim($data->sheets[0]['cells'][$i][3]),
				'Content'=>trim($data->sheets[0]['cells'][$i][4]),
				'IsPack'=>intval($data->sheets[0]['cells'][$i][5]),
				'ItemID'=>intval($data->sheets[0]['cells'][$i][6]),
				'ItemNum'=>intval($data->sheets[0]['cells'][$i][7]),
				'IsBinded'=>intval($data->sheets[0]['cells'][$i][8]),
				'TimeDurableType'=>intval($data->sheets[0]['cells'][$i][9]),
				'TimeDurable'=>intval(strtotime($data->sheets[0]['cells'][$i][10])),
				'ImproveLevel'=>intval($data->sheets[0]['cells'][$i][11]),
				'AttachAttribute'=>trim($data->sheets[0]['cells'][$i][12]),
				'Money'=>trim($data->sheets[0]['cells'][$i][13]),
				'Exp'=>intval($data->sheets[0]['cells'][$i][14]),
			);
			$returnData .= "[".$i."]".$this->addApply($sendData)."<br/>";
		}
		$URL_CsIndex = Tools::url('Apply','CsIndex');
		$URL_CsAll = Tools::url('Apply','CsAll');
		$showMsg = $returnData;
		$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
		$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
		$this->jump($showMsg,1,1,false);
		die($returnData);
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["ac"]=="batch"){
			$this->_batchDeal();
			return NULL;
		}
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		if($_POST["IsSendEmain"]==1){
			$getData = $this->_gameObject->getGetData($get);
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'PlayerID'=>trim($_POST['Receiver']),
				'PlayerName'=>trim($_POST['ReceiveName']),
				'Title'=>trim($_POST['Title']),
				'Content'=>trim($_POST['Content']),
			);
			$SendData["data"]	=	json_encode($postData);
			$data = $this->getResult($UrlAppend,$getData,$SendData);
			if($data["Result"]===0){
				$this->jump('操作成功',1);
			}else{
				$errorInfo = '操作失败';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$this->_utilRbac = $this->_getGlobalData ('Util_Rbac', 'object' );
		$userClass=$this->_utilRbac->getUserClass();
		if($userClass["_departmentId"]==2||$userClass["_departmentId"]==4){
			$this->_assign["allServer"]	=	1;
		}
		if($this->_isPost()){
			$modelApply = $this->_getGlobalData('Model_Apply','object');
//			print_r($_POST);
//			die();
			foreach($_POST["IsPack"] as $key=>$value){
				$AttachAttribute = "";
				if(count($_POST["attribute"][$key])>0){
					$AttachAttribute = "";
					foreach($_POST["attribute"][$key] as $k=>$a){
						$AttachAttribute .= $a."|".$_POST["attribute_mun"][$key][$k].";";
					}
				}
				$Money = "";
				if(count($_POST["Money"][$key])>0){
					foreach($_POST["Money"][$key] as $k=>$b){
						$Money .= $b."|".$_POST["Money".$b][$key].";";
					}
				}
				if($_POST["ItemNum"][$key]>999){
					$_POST["ItemNum"][$key]=999;
				}
				$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'IsAllPlayer'=>intval($_POST['IsAllPlayer']),
				'PlayerID'=>trim($_POST['Receiver']),
				'PlayerName'=>trim($_POST['ReceiveName']),
				'Title'=>trim($_POST['Title']),
				'Content'=>trim($_POST['Content']),
				'IsPack'=>intval($_POST['IsPack'][$key]),
				'ItemID'=>intval($_POST['ItemID'][$key]),
				'ItemNum'=>intval($_POST['ItemNum'][$key]),
				'IsBinded'=>intval($_POST['IsBinded'][$key]),
				'TimeDurableType'=>intval($_POST['TimeDurableType'][$key]),
				'TimeDurable'=>intval(strtotime($_POST['TimeDurable'][$key])),
				'ImproveLevel'=>intval($_POST['ImproveLevel'][$key]),
				'AttachAttribute'=>trim($AttachAttribute),
				'Money'=>trim($Money),
				'Exp'=>intval($_POST['Exp'][$key]),
				);
				$cause = "操作原因：".$_POST["cause"]."<br>";
				$cause .= "区ID:".$postData["WorldID"]."<br>";
				$cause .= "是否发送给全服所有人:".$postData["IsAllPlayer"]."<br>";
				$cause .= "收件人ID:".$postData["PlayerID"]."<br>";
				$cause .= "收件人昵称:".$postData["PlayerName"]."<br>";
				$cause .= "标题:".$postData["Title"]."<br>";
				$cause .= "内容:".$postData["Content"]."<br>";
				$cause .= "发送类型:".$postData["IsPack"]."<br>";
				$cause .= "道具或礼包的ID:".$postData["ItemID"]."<br>";
				$cause .= "数量:".$postData["ItemNum"]."<br>";
				$cause .= "是否绑定:".$postData["IsBinded"]."<br>";
				$cause .= "道具到期时间计时:".$postData["TimeDurableType"]."<br>";
				$cause .= "时间耐久度:".$postData["TimeDurable"]."<br>";
				$cause .= "强化等级:".$postData["ImproveLevel"]."<br>";
				$cause .= "附加属性:".$AttachAttribute."<br>";
				$cause .= "钱:".$Money."<br>";
				$cause .= "经验:".$postData["Exp"]."<br>";
				if($post){
					$postData = array_merge($post,$postData);
				}
				$SendData["data"]	=	json_encode($postData);
				$getData = $this->_gameObject->getGetData($get);
				$serverId	=	$_REQUEST["server_id"];
				$applyData = array(
			'type'=>$this->_Applytype,	//从Game拿id
			'server_id'=>$serverId,
			'operator_id'=>$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>str_replace(array("\r\n","\n",),'',$cause),
			'send_type'=>1,	//2	http
			'send_data'=>array(
				'url_append'=>$UrlAppend,
				'post_data'=>$SendData,
				'get_data'=>$getData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
					'cal_local_method'=>'ApplySend',
					'params'	=>array('data'=>$SendData,"server_id"=>$_REQUEST['server_id'],"UrlAppend"=>$UrlAppend),
				),
				),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>0,
			'player_info'=>$postData["PlayerID"],
				);
				$modelApply->AddApply($applyData);
			}
			$URL_CsIndex = Tools::url('Apply','CsIndex');
			$URL_CsAll = Tools::url('Apply','CsAll');
			$showMsg = '申请成功,等待审核...<br>';
			$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
			$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
			$this->jump($showMsg,1,1,false);
		}
		$items = $this->_f("18_ActionGame_MasterTools_Item_zhanlong",'',CACHE_DIR);
		$this->_assign["items"]	=	$items;
		$items = $this->_f("18_ActionGame_MasterTools_Define_zhanlong",'',CACHE_DIR);
		$this->_assign["gold"]	=	$items["AllDefine"][1]["Detail"];
		$this->_assign["attribute"]	=	$items["AllDefine"][2]["Detail"];
		$this->_assign["Item_url"]	=	$this->_urlitems();
		$this->_assign["batch_url"]	=	$this->_urlBatch();
		return $this->_assign;
	}

	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Define',$query);
	}

	private function _urlBatch(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ac'=>"batch"
			);
			return Tools::url(CONTROL,'SendMail',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}