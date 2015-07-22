<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerRegName_zhanlong extends Action_ActionBase{

	public function _init(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'playerType'=>1,
		);
		$ShortcutUrl = array(
			'SendMail'=>Tools::url(CONTROL,'SendMail',$query),
			'SilenceAdd'=>Tools::url(CONTROL,'SilenceAdd',$query),
			'LockAccountAdd'=>Tools::url(CONTROL,'LockAccountAdd',$query),
			'PointDel'=>Tools::url(CONTROL,'PointDel',$query),
		);
		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_REQUEST["sbm"]==""){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		if($_GET["WorldID"]!=""){
			$WorldID = $_GET["WorldID"];
		}
		$postData=array(
			'PlayerID'=>trim($_GET['PlayerID']),
			'AccountName'=>trim($_GET['AccountName']),
			'AccountID'=>trim($_GET['AccountID']),
			'WorldID'=>trim($WorldID),
			'PlayerName'=>urlencode(trim($_GET['PlayerName'])),
			'Page'=>max(0,intval($_GET['page']-1)),
		);
		$getData	=	array_merge($getData,$postData);
		$account = $this->getResult($UrlAppend,$getData);
		if($account['Result']===0){
			$status = 1;
			if($account['PlayerList']){
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$account['Count'],'perpage'=>PAGE_SIZE));
				$this->_assign['data'] = $account;
				$this->_assign['dataList'] = $account['PlayerList'];
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			$info = $data['info'];
		}
		$this->_assign['ajax']=Tools::url(CONTROL,'PlayerRoleList',array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		));
		return $this->_assign;
	}

	private function _vocationId($vocationId=0){
		static $vocation = false;	//首次执行，放进内存保存
		if($vocation === false){
			$vocation = Tools::gameConfig('vocationId',$this->_gameObject->_gameId);
			//vocation 职业 :1武者 ,2气宗 ,3药师
		}
		return $vocation[$vocationId];

	}


}