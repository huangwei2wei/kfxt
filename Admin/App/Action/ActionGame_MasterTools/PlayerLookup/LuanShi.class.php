<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_LuanShi extends Action_ActionBase{
	
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
		$getData = $this->_gameObject->getGetData($get);
		$postData=array(
			'playerId'=>trim($_GET['playerId']),
			'prolename'=>urlencode(trim($_GET['playerName'])),
			'pname'=>urlencode(trim($_GET['accountName'])),
			'registerTimeStart'=>urlencode(trim($_GET['regBeginTime'])),
			'registerTimeEnd'=>urlencode(trim($_GET['regEndTime'])),
			'loginTimeStart'=>urlencode(trim($_GET['loginBeginTime'])),
			'loginTimeEnd'=>urlencode(trim($_GET['loginEndTime'])),
			
			'pageSize'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$getData = array_merge($postData,$getData);
		$getData = array_filter($getData);
		$data = $this->getResult($UrlAppend,$getData,null);
// 		print_r($data);exit;
		
		$status = 0;
		$info = null;
		if($data['status']==1){
			$status = 1;
			$playerList = $data['data']['playerList'];
			if($playerList){
				foreach($playerList as &$player){
					$player['playerId'] = $this->_d2s($player['playerId']);
					$player['vocationId'] = $this->_vocationId($player['vocationId']);
				}
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data']['playerList'];
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
	
//"$data" = Array [3]	
//	data = Array [20]	
//		0 = Array [51]	
//			level = (int) 100	
//			status = (int) 0	
//			x = (int) 0	
//			y = (int) 0	
//			playerId = (int) 1	
//			playerName = (string:4) 花容失色	
//			accountName = (string:3) 111	
//			exp = (int) 0	
//			expNeed = (int) 0	
//			asset = (int) 26559213	
//			gold = (int) 83688	
//			goldTicke = (int) 998348	
//			sex = (int) 0	
//			vocationId = (int) 3	
//			hp = (int) 0	
//			mp = (int) 0	
//			maxHp = (int) 0	
//			maxMp = (int) 0	
//			power = (int) 0	
//			physical = (int) 0	
//			agile = (int) 0	
//			wit = (int) 0	
//			freePoint = (int) 0	
//			attack = (int) 0	
//			defense = (int) 0	
//			hit = (int) 0	
//			dodge = (int) 0	
//			crit = (int) 0	
//			attackSpeed = (int) 0	
//			tough = (int) 0	
//			hurtAdd = (int) 0	
//			hurtRemove = (int) 0	
//			hurtReflex = (int) 0	
//			hurtAbsorb = (int) 0	
//			critAdd = (int) 0	
//			sceneId = (int) 0	
//			unionId = (int) 0	
//			dress = null	
//			tx = (int) 0	
//			ty = (int) 0	
//			tCurrentTime = (int) 0	
//			score = (int) 0	
//			bloodBag = (int) 0	
//			magicBag = (int) 0	
//			boxsGirds = (int) 0	
//			holdGrids = (int) 0	
//			prestige2 = (int) 0	
//			prestige3 = (int) 0	
//			prestige4 = (int) 0	
//			vipLevel = (int) 0	
//			vipExpired = (int) 0	
//		1 = Array [51]	
//		2 = Array [51]	
//		3 = Array [51]	
//		4 = Array [51]	
//		5 = Array [51]	
//		6 = Array [51]	
//		7 = Array [51]	
//		8 = Array [51]	
//		9 = Array [51]	
//		10 = Array [51]	
//		11 = Array [51]	
//		12 = Array [51]	
//		13 = Array [51]	
//		14 = Array [51]	
//		15 = Array [51]	
//		16 = Array [51]	
//		17 = Array [51]	
//		18 = Array [51]	
//		19 = Array [51]	
//	status = (int) 1	
//	info = null	
	
	
}