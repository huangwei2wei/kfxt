<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_ZhiDouXing extends Action_ActionBase{

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
			'account'=>trim($_GET['accountName']),
			'installAppDateFrom'=>trim($_GET['regBeginTime']),
			'installAppDateTo'=>trim($_GET['regEndTime']),
			'lastLoginDateFrom'=>trim($_GET['loginBeginTime']),
			'lastLoginDateTo'=>trim($_GET['loginEndTime']),
			'page_size'=>PAGE_SIZE,
			'page'=>max(1,intval($_GET['page'])),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);	//功夫与其他java有异，不要的参数不传
		//		$ttt =  $sendUrl.$UrlAppend.'?'.http_build_query($getData).'&'.http_build_query($postData);
		//		echo $ttt;
		//		$data = '{"data":[{"playerId":1,"playerName":"花容失色","accountName":"111","exp":0,"expNeed":0,"asset":26559213,"gold":83688,"goldTicke":998348,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":2,"playerName":"贯丘嘉熙","accountName":"abc","exp":0,"expNeed":0,"asset":1477789,"gold":111101,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":49,"status":0},{"playerId":3,"playerName":"xxxx","accountName":"xxxx","exp":0,"expNeed":0,"asset":403418,"gold":98096,"goldTicke":1,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":4,"playerName":"濮阳修明","accountName":"222","exp":0,"expNeed":0,"asset":50019593,"gold":199987871,"goldTicke":199999913,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":89,"status":0},{"playerId":5,"playerName":"葛成双","accountName":"333","exp":0,"expNeed":0,"asset":1792,"gold":997595,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":6,"playerName":"xxx1","accountName":"xxxx1","exp":0,"expNeed":0,"asset":499990,"gold":9999998,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":43,"status":0},{"playerId":7,"playerName":"xxxx2","accountName":"xxxx2","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":40,"status":0},{"playerId":8,"playerName":"蒲璞玉","accountName":"dfdf","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":9,"playerName":"元曼易","accountName":"test88","exp":0,"expNeed":0,"asset":469800,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":10,"playerName":"皇甫冰烟","accountName":"444","exp":0,"expNeed":0,"asset":2147801307,"gold":7989,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":51,"status":0},{"playerId":11,"playerName":"戴德惠","accountName":"666","exp":0,"expNeed":0,"asset":1495999,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":12,"playerName":"yuuu","accountName":"yui","exp":0,"expNeed":0,"asset":454900,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":13,"playerName":"tttttt","accountName":"tttttt","exp":0,"expNeed":0,"asset":490800,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":14,"playerName":"空诗双","accountName":"ccc","exp":0,"expNeed":0,"asset":9995011,"gold":9999548,"goldTicke":99999911,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":100,"status":0},{"playerId":15,"playerName":"公上开诚","accountName":"777","exp":0,"expNeed":0,"asset":495020,"gold":0,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":47,"status":0},{"playerId":16,"playerName":"堵正浩","accountName":"ddd","exp":0,"expNeed":0,"asset":1492749,"gold":990,"goldTicke":0,"sex":0,"vocationId":2,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":48,"status":0},{"playerId":17,"playerName":"tee","accountName":"tee","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":67,"status":0},{"playerId":18,"playerName":"asasd","accountName":"brbin","exp":0,"expNeed":0,"asset":495210,"gold":0,"goldTicke":0,"sex":0,"vocationId":1,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":44,"status":0},{"playerId":19,"playerName":"颛孙问枫","accountName":"123156456","exp":0,"expNeed":0,"asset":500000,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":45,"status":0},{"playerId":20,"playerName":"梁丘秋灵","accountName":"tyuiuy","exp":0,"expNeed":0,"asset":480900,"gold":0,"goldTicke":0,"sex":0,"vocationId":3,"hp":0,"mp":0,"maxHp":0,"maxMp":0,"power":0,"physical":0,"agile":0,"wit":0,"freePoint":0,"attack":0,"defense":0,"hit":0,"dodge":0,"crit":0,"attackSpeed":0,"tough":0,"hurtAdd":0,"hurtRemove":0,"hurtReflex":0,"hurtAbsorb":0,"critAdd":0,"sceneId":0,"unionId":0,"dress":null,"tx":0,"ty":0,"tCurrentTime":0,"score":0,"bloodBag":0,"magicBag":0,"boxsGirds":0,"holdGrids":0,"prestige2":0,"prestige3":0,"prestige4":0,"vipLevel":0,"vipExpired":0,"x":0,"y":0,"level":30,"status":0}],"status":1,"info":null}';
		//		$data = json_decode($data,true);
		//		$echo =  $this->_getUrl()."$UrlAppend?".http_build_query($getData).'&'.http_build_query($postData);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		$status = 0;
		$info = null;
		if($data['status']==1){
			$status = 1;
			if($data['data']){
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['total'],'perpage'=>PAGE_SIZE));
				$this->_assign['dataList'] = $data['data'];
				$this->_assign['extDate']	=	$data["extDate"];
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