<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_FHJZ extends Action_ActionBase{
	
	public function _init(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'playerType'=>1,
		);
		$ShortcutUrl = array(
			'SendMail'=>Tools::url(CONTROL,'SendMail',$query),
			'AddTitleOrGag'=>Tools::url(CONTROL,'AddTitleOrGag',$query),
		);
		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
		$this->_assign['userType'] = array(0=>'玩家ID',1=>'玩家账号',2=>'玩家昵称');
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$data=array(
			'user'=>trim($_GET['user']),
			'userType'=>trim($_GET['userType']),
			'regBeginTime'=>strtotime(trim($_GET['regBeginTime'])),
			'regEndTime'=>strtotime(trim($_GET['regEndTime'])),
			'loginBeginTime'=>strtotime(trim($_GET['loginBeginTime'])),
			'loginEndTime'=>strtotime(trim($_GET['loginEndTime'])),
			'pageSize'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		$postData = $this->_gameObject->getPostData($post);
		$postData = array_filter(array_merge($data,$postData),"strlen");
		$data = $this->getResult($UrlAppend,$get,$postData);
		
		
		if($data['status']==1){
			$this->_assign['column'] = $data['data']['column'];
			if(is_array($data['data']['list'])){
				foreach($data['data']['list'] as &$player){
					$player['userID'] = $this->_d2s($player['playerId']);
					$player['userName'] = $this->_d2s($player['accountName']);
					$player['nickName'] = $this->_d2s($player['playerName']);
					$player['loginTime'] = $player['loginTime']?date("Y-m-d H:i:s",$player['loginTime']):'';
					$player['createTime'] = $player['createTime']?date("Y-m-d H:i:s",$player['createTime']):'';
					$player['offLineTime'] = $player['offLineTime']?date("Y-m-d H:i:s",$player['offLineTime']):'';
				}
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			
		}
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$data['status'],'info'=>$data['info'],'data'=>$this->_assign));
		}
		return $this->_assign;
	}
	
	
	//获取势力/势力职位
	private function _vocation(){
		static $vocation = false;	//首次执行，放进内存保存
		if($vocation === false){
			$vocation = Tools::gameConfig('vocationId',$this->_gameObject->_gameId);
			//vocation 职业 :1武者 ,2气宗 ,3药师
		}
		return $vocation;
	}
	//获取玩家职业
	private function _career(){
		static $career = false;
		if($career === false){
			$career = Tools::gameConfig('career',$this->_gameObject->_gameId); 
		}
		return $career;
	}
}