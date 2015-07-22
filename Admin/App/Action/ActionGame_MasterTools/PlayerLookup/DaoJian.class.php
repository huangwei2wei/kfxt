<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_DaoJian extends Action_ActionBase{
	
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
		
		$getData = $this->_gameObject->getGetData($get);
		
		$postData=array(
			'playerId'=>trim($_GET['playerId']),
			'playerName'=>trim($_GET['playerName']),
			'userName'=>trim($_GET['accountName']),
			'regBeginTime'=>trim($_GET['regBeginTime'])?date('Y-m-d',strtotime(trim($_GET['regBeginTime']))):'',
			'regEndTime'=>trim($_GET['regEndTime'])?date('Y-m-d',strtotime(trim($_GET['regEndTime']))+3600*24):'',
			'loginBeginTime'=>trim($_GET['loginBeginTime'])?date('Y-m-d',strtotime(trim($_GET['loginBeginTime']))):'',
			'loginEndTime'=>trim($_GET['loginEndTime'])?date('Y-m-d',strtotime(trim($_GET['loginEndTime']))+3600*24):'',
				
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$data = $this->getResult($UrlAppend,$getData,$postData);
// echo trim($_GET['regBeginTime']);
// print_r($data);
// print_r($postData);


		
		$status = 0;
		$info = null;
		if($data['result']==0){
			$status = 1;
			if($data['data']){
				$sex = array (0=>'男',1=>'女');
				$camp = array(0=>'无阵营',1=>'豪杰营',2=>'侠客营');
				foreach($data['data'] as &$player){
					$player['sex'] = $sex[$player['sex']];
					$player['camp'] = $camp[$player['camp']];
					$player['playerId'] = $this->_d2s($player['playerId']);
					$player['vocationId'] = $this->_vocationId($player['vocationId']);
				}
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['totals'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data'];
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