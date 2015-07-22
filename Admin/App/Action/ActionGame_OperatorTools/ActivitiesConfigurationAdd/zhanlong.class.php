<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesConfigurationAdd_zhanlong extends Action_ActionBase{
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
				'TableID'=>intval($_POST['TableID']),
				'TableType'=>intval($_POST['TableType']),
				'TableIcon'=>trim($_POST['TableIcon']),
				'TableName'=>trim($_POST['TableName']),
			    'TableEffect'=>trim($_POST['TableEffect']),
				'WorldTimeBegin'=>intval($_POST['WorldTimeBegin']),	
				'WorldTimeEnd'=>intval($_POST['WorldTimeEnd']),	
				'TableTime'=>trim($_POST['TableTime']),	
				'MinLevel'=>intval($_POST['MinLevel']),	
				'MaxLevel'=>intval($_POST['MaxLevel']),
				'NeedPrivilege'=>trim($_POST['NeedPrivilege']),	
				'ConditionValue'=>trim($_POST['ConditionValue']),	
				'TableTextType'=>intval($_POST['TableTextType']),
				'TableText'=>trim($_POST['TableText']),
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
		if(!empty($_GET["TableID"])){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]		=	max(0,intval($_GET["WorldID"]));
			$getData["TableID"]		=	intval($_GET['TableID']);
			$dataIndex = $this->getResult("QuerySystem/ClassTable",$getData);
			$this->_assign["data"]	=	$dataIndex["ClassTable"][0];
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
		return Tools::url(CONTROL,'ActivitiesConfiguration',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}