<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerRoleDelList_zhanlong extends Action_ActionBase{

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

	public function regainRole(){
		$getData = $this->_gameObject->getGetData($get);

		$getData["DeletedID"]	=	$_REQUEST["playerId"];
		$data = $this->getResult("UpdatePlayer/RetrievePlayer",$getData);
		if($data["Result"]===0){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		if($_REQUEST["regainRole"]){
			$this->regainRole();
		}
		$getData = $this->_gameObject->getGetData($get);
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		$postData=array(
			'PlayerID'=>trim($_GET['PlayerID']),
			'AccountName'=>trim($_GET['AccountName']),
			'AccountID'=>trim($_GET['AccountID']),
			'WorldID'=>trim($_GET['WorldID']),
			'OnlineTime'=>intval($_GET['OnlineTime']),
			'CreateTime'=>$_GET['CreateTime'],
			'Page'=>max(0,intval($_GET['page']-1)),
		);
		$getData	=	array_merge($getData,$postData);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);	//功夫与其他java有异，不要的参数不传
		//		$ttt =  $sendUrl.$UrlAppend.'?'.http_build_query($getData).'&'.http_build_query($postData);
		//		echo $ttt;
		//		$data = '{"data":[{"playerId":1,"playerName":"花容失色","accountName":"111","exp":0,"expNeed":0,"asset":26559213,"gold":83688,"goldTicke":998348,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":2,"playerName":"贯丘嘉熙","accountName":"abc","exp":0,"expNeed":0,"asset":1477789,"gold":111101,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":49,"status":0},{"playerId":3,"playerName":"xxxx","accountName":"xxxx","exp":0,"expNeed":0,"asset":403418,"gold":98096,"goldTicke":1,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":4,"playerName":"濮阳修明","accountName":"222","exp":0,"expNeed":0,"asset":50019593,"gold":199987871,"goldTicke":199999913,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":89,"status":0},{"playerId":5,"playerName":"葛成双","accountName":"333","exp":0,"expNeed":0,"asset":1792,"gold":997595,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":6,"playerName":"xxx1","accountName":"xxxx1","exp":0,"expNeed":0,"asset":499990,"gold":9999998,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":43,"status":0},{"playerId":7,"playerName":"xxxx2","accountName":"xxxx2","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":40,"status":0},{"playerId":8,"playerName":"蒲璞玉","accountName":"dfdf","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":9,"playerName":"元曼易","accountName":"test88","exp":0,"expNeed":0,"asset":469800,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":10,"playerName":"皇甫冰烟","accountName":"444","exp":0,"expNeed":0,"asset":2147801307,"gold":7989,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":51,"status":0},{"playerId":11,"playerName":"戴德惠","accountName":"666","exp":0,"expNeed":0,"asset":1495999,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":12,"playerName":"yuuu","accountName":"yui","exp":0,"expNeed":0,"asset":454900,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":13,"playerName":"tttttt","accountName":"tttttt","exp":0,"expNeed":0,"asset":490800,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":14,"playerName":"空诗双","accountName":"ccc","exp":0,"expNeed":0,"asset":9995011,"gold":9999548,"goldTicke":99999911,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":15,"playerName":"公上开诚","accountName":"777","exp":0,"expNeed":0,"asset":495020,"gold":0,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":47,"status":0},{"playerId":16,"playerName":"堵正浩","accountName":"ddd","exp":0,"expNeed":0,"asset":1492749,"gold":990,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":17,"playerName":"tee","accountName":"tee","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":67,"status":0},{"playerId":18,"playerName":"asasd","accountName":"brbin","exp":0,"expNeed":0,"asset":495210,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":44,"status":0},{"playerId":19,"playerName":"颛孙问枫","accountName":"123156456","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":20,"playerName":"梁丘秋灵","accountName":"tyuiuy","exp":0,"expNeed":0,"asset":480900,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":30,"status":0}],"status":1,"info":null}';
		//		$data = json_decode($data,true);
		//		$echo =  $this->_getUrl()."$UrlAppend?".http_build_query($getData).'&'.http_build_query($postData);
		$data = $this->getResult($UrlAppend,$getData);
		//		print_r($data);
		$status = 0;
		$info = null;
		if($data['Result']===0){
			$status = 1;
			if($data['PlayerListInfoDelted']){
				//				foreach($data['data']['PlayerDelted'] as &$player){
				//					$player['playerId'] = $this->_d2s($player['playerId']);
				//					$player['vocationId'] = $this->_vocationId($player['vocationId']);
				//				}
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['Count'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['PlayerListInfoDelted'];
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			$info = $data['info'];
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