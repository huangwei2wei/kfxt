<?php
Tools::import('Game_GameBase');
class Game_2 extends Game_GameBase{
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 2;
		$this->_sendImage = true;
	}
	
	public function workOrderIfChk(){
		return $this->commonChk();
	}
	
	

	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		$data['send_url'].='php/interface.php?m=clerk&c=UserQuiz&a=GetClientReply';
		$_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
		$_utilFRGInterface->set_sendUrl($data['send_url']);
		
		$fileData = $data['file_img'];
		$isFile = is_file($fileData);
		if($isFile){
			$webPath = pathinfo($fileData);
			$data['ReplyFile'] = 'http://'.$_SERVER['HTTP_HOST'].'/Upload/Service/'.date('Ymd',CURRENT_TIME).'/'.$webPath["basename"];
		}
		unset($data['send_url'],$data['file_img']);
		$data ['_sign'] = md5 ( TAKE_KEY . CURRENT_TIME );
		$data ['_verifycode'] = CURRENT_TIME;
		$_utilFRGInterface->setPost($data);
		$data=$_utilFRGInterface->callInterface();
		$errorInfo = Tools::getLang('SEND_MSG','Control_WorkOrder');
		if ($data){
			if ($data['msgno']==1){
				return true;
			}
			$errorInfo = Tools::getLang('FRG_SEND_ERROR','Control_WorkOrder',array('data[message]'=>$data['message']));
		}
		if($isFile){
			unlink($fileData);
		}
		return $errorInfo;
	}
	
	public function autoReplay($data=NULL){	
		$utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
		$utilFRGInterface->setServerUrl($data['send_url']);	//初始化连接url地址
		$utilFRGInterface->setGet(array('c'=>'Reward','a'=>'SendMail','doaction'=>'save'));
		$sendParams = array(
			'MsgTitle'=>$data['title'],
			'MsgContent'=>$data['content'],
			'UserIds'=>$data['player'],
			'ReceiveType'=>0,
		);
		$utilFRGInterface->setPost($sendParams);
		$return = $utilFRGInterface->callInterface();
	}
	
	public function operatorExtParam(){
		return array(
			array('syskey','系统登录密匙','password',''),	//字段，描述，表单类型，默认值
			array('co_action','合作方标识','text',''),
			array('GameId','游戏标识ID','text',''),
		);
	}
	
	public function serverExtParam(){
		return array();
	}
	
	/**
	 * 获得运营商配置
	 */
	public static function getOptConf1(){
		return array(
			0=>array('id'=>null,'key'=>null,'mark'=>null),
			9=>array('id'=>2,'key'=>'!@$$DSDGldj*73@sls-(3','mark'=>'gamefrg','url'=>'http://s{$var}.r.uwan.com/'),	//官网
			11=>array('id'=>3,'key'=>'345$%@#DTFET$%38()DFE','mark'=>'360cn','url'=>'http://s{$var}.frg.g.1360.com/'),	//360
			12=>array('id'=>4,'key'=>'@#$$%DFGTRHERFGuyjr945756','mark'=>'kaixin001','url'=>'http://k{$var}.frg.kaixin001.com.cn/'),		//开心
			13=>array('id'=>5,'key'=>'$%()#JEREU8754@!^(DFJ4E','mark'=>'renren','url'=>'http://x{$var}.frg.renren.com/'),		//人人网
			14=>array('id'=>6,'key'=>'$#%#DFIUYEK()$-DSFR$8','mark'=>'duowan','url'=>'http://s{$var}.frg.game.yy.com/'),	//多玩
			15=>array('id'=>92,'key'=>'DFHYUEW#$DEKJH54DSFY','mark'=>'Lekool','url'=>'http://s{$var}.rs.lekool.com/'),	//乐酷
			32=>array('id'=>9,'key'=>'RETFWERFWE#$@!#$254DFEURYM','mark'=>'6711','url'=>'http://s{$var}.frg.6711.com/'),	//6711
			31=>array('id'=>11,'key'=>'YUTFE#@$dFDFKJHVCDY56UJDF','mark'=>'91wan','url'=>'http://frg{$var}.91wan.com/'),//91wan
			39=>array('id'=>13,'key'=>'IUEFHW#$()DUF21@#@DFJEU','mark'=>'game2','url'=>'http://s{$var}.frg.game2.com.cn/'),//game2
			33=>array('id'=>10,'key'=>'UYFCN%#!7893KUSYDF()UEMV','mark'=>'4399','url'=>'http://frg{$var}.my4399.com/'),//4399
			37=>array('id'=>3,'key'=>'!@$$DSDGldj*73@sls-(3','mark'=>'dovogame','url'=>'http://s{$var}.rs.dovogame.com/'),//海外官网
			38=>array('id'=>12,'key'=>'YNMFGW@#$$%DWT948TYTY','mark'=>'95k','url'=>'http://s{$var}.frg.95k.com/'),//95K
			16=>array('id'=>7,'key'=>'UNFDSF%^@3UDFYKJ5634UJYDFMVX','mark'=>'e8online','url'=>'http://s{$var}.frg.szgwbn.com.cn/'),//长宽
			22=>array('id'=>40,'key'=>'WEFDS$#DFKJHFGRG908JU','mark'=>'tom','url'=>'http://frg{$var}.game.tom.com'),//TOM
			30=>array('id'=>8,'key'=>'RETEWF$#@TYUJ672FDEWR','mark'=>'pps','url'=>'http://rich{$var}.g.pps.tv/'),//pps
			40=>array('id'=>14,'key'=>'EFUHKR789GIGIURY@#KEFU','mark'=>'37wan','url'=>'http://s{$var}.frg.37wan.com/'),	//37wan
			41=>array('id'=>15,'key'=>'RTUG*(DFUHEF!@3DFJ#$FGKJER','mark'=>'21cn','url'=>'http://rjt{$var}.igame.21cn.com/'),//21cn
			42=>array('id'=>'rs@elex337_tr_1','key'=>'kdsfhjn@7','mark'=>'337_t','url'=>'http://s{$var}cbo.337.com/'),//智明星通
			43=>array('id'=>16,'key'=>'YUJFDKM^%YWSG()UJFT124DET','mark'=>'game5','url'=>'http://s{$var}.frg.game5.cn/'),	//game5
			44=>array('id'=>17,'key'=>'KUEFHEF%^#$532()34223DKSFU','mark'=>'juyou','url'=>'http://s{$var}.frg.juu.cn/'),//聚游
			45=>array('id'=>18,'key'=>'IUEFDF@#5%DVKJ()KJDFERH','mark'=>'51wan','url'=>'http://s{$var}.frg.51wan.com/'),	//51wan
			46=>array('id'=>19,'key'=>'KJVDFRT#$%2DJKFH9EF!@VEU','mark'=>'56uu','url'=>'http://s{$var}.frg.56uu.com/'),//56uu
			21=>array('id'=>20,'key'=>'REG#$EFIEJ56BAILKDU7U%','mark'=>'baidu','url'=>'http://b{$var}.fhrs.uwan.com/'),	//百度
			49=>array('id'=>39,'key'=>'UE98FHJDGH$#KDYHFJU','mark'=>'Kongregate','url'=>'http://k{$var}.kgrs.dovogame.com'), //空门
            50=>array('id'=>22,'key'=>'YUTEFE(*#$EFJIHEF34DGUEKB','mark'=>'youxi','url'=>'http://frg{$var}.youxi.com/'),//游戏网
			51=>array('id'=>23,'key'=>'KJEUHK$%^KDFJ98RETD(DJD32','mark'=>'96pk','url'=>'http://s{$var}.frg.96pk.com/'),	//96pk
			54=>array('id'=>38,'key'=>'KIHJH98476IRY6884KHRKJDF','mark'=>'Dragonsmeet','url'=>'http://y{$var}.rs.dovogame.com/'),	//龙会洲
			27=>array('id'=>24,'key'=>'KUHF(GHI#@)DIYGEHVRH34DF','mark'=>'pplive','url'=>''),	//pplive
			52=>array('id'=>25,'key'=>'VKDHUE#$@KDJFHUY@34F','mark'=>'yaowan','url'=>'http://s{$var}.frg.yaowan.com/'),	//要玩
			23=>array('id'=>21,'key'=>'DJFER$%#()DKESDUY4563JDFTGE','mark'=>'shengda','url'=>'http://frg{$var}.game.qidian.com/'),	//盛大 - new
            58=>array('id'=>26,'key'=>'UYFKE$#(KFHWE)DFKIU23#@FWEJ','mark'=>'3977','url'=>'http://s{$var}.frg.3977.com/'), //3977
            62=>array('id'=>27,'key'=>'OIFHJDFHEYT#DKUFY93GGD','mark'=>'5iplay','url'=>'http://s{$var}.frg.5iplay.cn/'), //手机钱包
            60=>array('id'=>28,'key'=>'O6J85FHEYT#DKU5kLKDSI','mark'=>'xinlang','url'=>'http://s{$var}.frg.auto.sina.com/'), //新浪
            65=>array('id'=>30,'key'=>'IUEJHF%$^#DKFU643DFUYH9','mark'=>'funshion','url'=>'http://s{$var}.frg.funshion.com/'), //风行
            67=>array('id'=>31,'key'=>'KHEIF%^#KUJHR34UJDF09F','mark'=>'klrich','url'=>'http://s{$var}.rich.kunlun.tw/'),//昆仑
            68=>array('id'=>32,'key'=>'YTEIHYU%$@KFE896KDFD','mark'=>'jjbs','url'=>'http://s{$var}.frg.jj.cn/'),//竞技比赛
            69=>array('id'=>33,'key'=>'YTEFIUY$%#KFJU389KDFUE','mark'=>'verycd','url'=>'http://frg{$var}.game.verycd.com/'), //电驴
			73=>array('id'=>34,'key'=>'RYKFJE%^%98()34DKFY-03KJF','mark'=>'SDO','url'=>'http://frg{$var}.g.sdo.com/'), //盛大SDO
			76=>array('id'=>36,'key'=>'IEFYHJHG%^()DKFH359GKHJ','mark'=>'yxfy','url'=>'http://s{$var}.frg.smggame.net/'), //游戏风云
			77=>array('id'=>37,'key'=>'IUYHEUHCVB$%^3KG990EKF','mark'=>'591pk','url'=>'http://s{$var}.frg.591pk.com/'), //591PK
			84=>array('id'=>41,'key'=>'ERTGG$%^#DJFKHH7476','mark'=>'Wondershare','url'=>'http://s{$var}-rs.ws-games.com/'), //深圳万兴
			85=>array('id'=>7,'key'=>'UNFDSF%^@3UDFYKJ5634UJYDFMVX','mark'=>'e8s','url'=>'http://s{$var}.frg.szgwbn.com.cn/'), //长宽-联竣e8
			86=>array('id'=>21,'key'=>'DJFER$%#()DKESDUY4563JDFTGE','mark'=>'qidian','url'=>'http://frg{$var}.game.qidian.com/'), //长宽-联竣e8
			87=>array('id'=>42,'key'=>'YTGEFBJHD^$%#KJFHD','mark'=>'91','url'=>'http://s{$var}.ceo.91.com/'), //网龙
			89=>array('id'=>93,'key'=>'JEUIHD#$DFJKH324JGHG1H','mark'=>'nidong','url'=>'http://s{$var}.richstate.ugamehome.com/'), //妮东科技
			);
	}
	


	
}