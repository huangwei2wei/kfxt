<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_chunqiu extends Action_ActionBase{

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
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$postData=array(
			'playerId'=>trim($_GET['playerId']),
			'playerName'=>base64_encode(trim($_GET['playerName'])),
			'accountName'=>trim($_GET['accountName']),
			'regBeginTime'=>trim($_GET['regBeginTime']),
			'regEndTime'=>trim($_GET['regEndTime']),
			'loginBeginTime'=>trim($_GET['loginBeginTime']),
			'loginEndTime'=>trim($_GET['loginEndTime']),
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
		);
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = base64_encode($data);
		$data = base64_decode($data);
		$data = json_decode($data,true);
		$status = 0;
		$info = null;
		if(!empty($data)){
			$status = 1;
			foreach($data as &$player){
				$player['playerId'] = $this->_d2s($player['playerId']);
				$player['vocationId'] = $this->_vocationId($player['vocationId']);
			}
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>2000,'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $data;
			$this->_assign['pageBox'] = $helpPage->show();
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