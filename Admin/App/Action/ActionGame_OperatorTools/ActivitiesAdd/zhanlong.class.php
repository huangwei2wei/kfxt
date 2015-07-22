<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesAdd_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		if($this->_isPost()){
//			$NeedPrivilege .= "";
//			$NeedPrivilege .=$_POST["NeedPrivilege_privilege"];
//			if($_POST["NeedPrivilege_privilege"]=="1"){
//				$NeedPrivilege .="_".$_POST["NeedPrivilege_VIP"];
//			}
//			$NeedPrivilege .="_".$_POST["NeedPrivilege_condition"];
//			if($_POST["NeedPrivilege_E"]!=""||$_POST["NeedPrivilege_Y"]!=""){
//				$NeedPrivilege .="_".$_POST["NeedPrivilege_E"].$_POST["NeedPrivilege_Y"];
//			}
			$postData = array(
				'WorldID'=>intval($_POST['WorldID']),
				'ClassID'=>intval($_POST['ClassID']),
				'ClassType'=>intval($_POST['ClassType']),
				'ClassCheck'=>intval($_POST['ClassCheck']),
				'ClassValue'=>intval($_POST['ClassValue']),
			    'CheckValue'=>intval($_POST['ClassValue']),
				'TriggerType'=>intval($_POST['TriggerType']),	

				'IsLoop'=>intval($_POST['IsLoop']),
				'MaxCount'=>intval($_POST['MaxCount']),
				'MinLevel'=>intval($_POST['MinLevel']),
				'MaxLevel'=>intval($_POST['MaxLevel']),
				'NeedPrivilege'=>trim($_POST['NeedPrivilege']),
				'BroadcastID'=>trim($_POST['BroadcastID']),
				'ItemGetType'=>intval($_POST['ItemGetType']),
				'ConditionType'=>intval($_POST['ConditionType']),
				'ConditionValue'=>intval($_POST['ConditionValue']),
				'UniqueIndex'=>intval($_POST['UniqueIndex']),
				'EventType'=>intval($_POST['EventType']),
				'EventID'=>intval($_POST['EventID']),
				'Fparameter'=>intval($_POST['Fparameter']),               
			    'WorldTimeBegin'=>intval($_POST['WorldTimeBegin']),
			    'WorldTimeEnd'=>intval($_POST['WorldTimeEnd']),

				'DataTime'=>trim($_POST['DateTime']),
			    'DateTime'=>trim($_POST['DateTime']),
				'ItemPack'=>trim($_POST['ItemPack']),
				'ClassName'=>trim($_POST['ClassName']),
				'ItemName'=>trim($_POST['ItemName']),
				'Describes'=>trim($_POST["SellText"]),
				'Remove'=>intval(0),
			);

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
		if(!empty($_GET["ClassID"])){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]		=	max(0,intval($_GET["WorldID"]));
			$getData["ClassID"]		=	intval($_GET['ClassID']);
			$dataIndex = $this->getResult("QuerySystem/ClassList",$getData);
			$a = array();
			foreach($dataIndex["Column"] as $k=>$item){
				$a[$item]	=	$dataIndex["ClassList"][$k];
			}
			//						print_r($a);
			$this->_assign["data"]	=	$a;
		}
		$this->_assign["Item_url"]	=	$this->_urlitems();
		return $this->_assign;
	}

	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url("MasterTools",'Define',$query);
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesList',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}