<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_WangZhe extends Action_ActionBase{
	
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
		$this->_assign['playerType'] = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称');
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=array(
			'playerType'=>$_GET['playerType'],
			'playerId'=>base64_encode($_GET['playerName']?trim($_GET['playerName']):0),
			'registStart'=>urlencode(trim($_GET['regBeginTime'])),
			'registEnd'=>urlencode(trim($_GET['regEndTime'])),
			'loginStart'=>urlencode(trim($_GET['loginBeginTime'])),
			'loginEnd'=>urlencode(trim($_GET['loginEndTime'])),
			'pageSize'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$postData,null);
// var_dump($data);
		$status = 0;
		$info = null;
		if($data['status']==1){
			$status = 1;
			if($data['data']['list']){
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['totalNum'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			$info = $data['info'];
		}
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$status,'info'=>$info,'data'=>$this->_assign));
		}
		
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