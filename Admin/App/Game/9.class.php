<?php
Tools::import('Game_GameBase');
class Game_9 extends Game_GameBase{
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 9;		//游戏Id
		$this->_zp = 'BTO2';	//控制器扩展包
		$this->_key = 'gamebto_kefu';	//游戏密匙
		$this->_timer = false;	//是否使用定时器
		$this->_urlApdWO = array(	//使用定时器的工单请求地址附加字符
			'new'=>'',
			'newCbk'=>'',
			'del'=>'',
			'delCbk'=>'',
			'ev'=>'',
			'evCbk'=>'',
		);
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
//		if(CONTROL == 'InterfaceWorkOrder'){
//			$logs= '$_POST = '.var_export($_POST,true);
//			$logs .=";\n\r";
//			$logs .= '$_FILES = '.var_export($_FILES,true);
//			$logs .=";\n\r";
//			$logs .= '$_GET = ' .var_export($_GET,true);
//			$logs .=";\n\r#####################\n\r";
//			error_log($logs, 3, RUNTIME_DIR.'/Logs/9_logs_'.date('Y_m_d_H',time()).".log");
//		}
		if(strval($_REQUEST['source']) == '1'){
			return $this->commonChk();	//如果是来自官网前端,使用旧密匙
		}
		$time = intval($_REQUEST['_time']);
		if(CURRENT_TIME - $time > 7200){
			return 'TimeOut';
		}
		$gameId = intval($_REQUEST['_gameid']);
		$uniquePlayer = trim($_REQUEST['_unique']);
		$key = $this->_key;
		$sign = trim($_REQUEST['_sign']);
		if($time && $gameId && $uniquePlayer && $key && md5($time.$gameId.$uniquePlayer.$key) == $sign){
			return true;
		}
		return false;
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		return true;

//		$data['send_url'].='php/interface.php?m=clerk&c=UserQuiz&a=GetClientReply';
//		$_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
//		$_utilFRGInterface->set_sendUrl($data['send_url']);
//		unset($data['send_url']);
//		$data ['_sign'] = md5 ( TAKE_KEY . CURRENT_TIME );
//		$data ['_verifycode'] = CURRENT_TIME;
//		$_utilFRGInterface->setPost($data);
//		$data=$_utilFRGInterface->callInterface();
//		if ($data){
//			if ($data['msgno']==1){
//				return true;
//			}else {
//				return Tools::getLang('FRG_SEND_ERROR','Control_WorkOrder',array('data[message]'=>$data['message']));
//			}
//		}else {
//			return Tools::getLang('SEND_MSG','Control_WorkOrder');
//		}
	}
	
	public function autoReplay($data=NULL){
		$api		=	$this->_getGlobalData('Util_FRGInterface','object');
		$api->setServerUrl($data["server_msg"]['game_server_id']);
		$api->setGet(array('c'=>'Reward','a'=>'SendMail','doaction'=>'save'));
		$sendParams	=	array(
							"zp"			=>	"BTO2",
							"server_id"		=>	$data["server_msg"]['game_server_id'],
							"cause"			=>	NULL,
							"MsgTitle"		=>	$data['title'],
							"MsgContent"	=>	$data['content'],
							"UserIds"		=>	$data["server_msg"]['game_user_id'],
							"ReceiveType"	=>	0
						);
//		Array
//(
//    [zp] => BTO2
//    [server_id] => 964
//    [cause] => asfddasf
//    [MsgTitle] => asdfdsa
//    [MsgContent] => fasfasd
//    [UserIds] => asdfsaf
//    [ReceiveType] => 0
//    [submit] => 立即发送
//)
		$api->setPost($sendParams);
		$data=$api->callInterface();
		if ($data){
			if ($data['msgno']==1){
				if ($data['backparams'] && is_array($data['backparams'])){
					return false;
				}
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
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
	public function getOptConf1(){
		return array(
			0=>array('id'=>null,'key'=>null,'mark'=>null),
			9=>array('id'=>7,'key'=>'UEHFO67$%#KIU34GE364','mark'=>'','url'=>'http://s{$var}.bto2.uwan.com/'),	//官网
			83=>array('id'=>7,'key'=>'UEHFO67$%#KIU34GE364','mark'=>'','url'=>'http://s{$var}.app31145.qqopenapp.com/'),//腾讯(service)
			84=>array('id'=>7,'key'=>'UEHFO67$%#KIU34GE364','mark'=>'','url'=>'http://s{$var}.app31145.qqopenapp.com/'),//腾讯(内网crm在用)
			);
	}

	/**
	 * 自动生成申请原因清单
	 * @param unknown_type $postData
	 * @param unknown_type $type
	 */
	public function AddAutoCause($postData,$type) {
		switch ($type) {
			case '1' ://奖励发送
			case '10' ://多服务器奖励发送
				{
					if ($postData['UserIds']){
						$userIds="发送的玩家ID：<font color='#0000ff'>{$postData['UserIds']}</font>\r\n";
					}else {
						$userIds=$this->_createUserIds($postData['send_players']);
					}
					$cause="<div style='padding:3px; margin:3px; border:1px dashed #000'>";
					$cause.=$userIds;
					$cause.='效果道具：';
					if ($postData['EffValue']){
						$opcode=array('1'=>'增加','2'=>'减少','3'=>'改为');
						foreach ($postData['EffValue'] as $key=>$list){
							$cause.=" {$postData["EffObj_name_{$key}"]}：<font color='#0000FF'>{$opcode[$postData['EffOpcode'][$key]]}</font> <font color='#FF0000'>{$list}</font>. ";
						}
						
					}
					$cause.="\r\n";
					if ($postData['ToolNum']){
						$cause.='奖励道具：';
						foreach ($postData['ToolNum'] as $key=>$list){
							$cause.=" {$postData["Tool_name_{$key}"]}：<font color='#FF0000'>{$list}</font>. ";
						}
					}
					$cause.="\r\n";
					if ($postData['OutfitNum']){
						$cause.='装备道具：';
						foreach ($postData['OutfitNum'] as $key=>$list){
							$cause.="{$postData["Outfit_name_{$key}"]} ： <font color='#ff0000'>{$list}</font>.";
						}
					}
					$cause.="<div>消息标题：{$postData['MsgTitle']}</div>";
					$cause.="<div>消息内容：{$postData['MsgContent']}</div>";
					$cause.="</div>";
					break;
				}
			case '2' ://玩家数值修改
				{
					break;
				}
			case '3' :
				{
					break;
				}
			case '4' :
				{
					break;
				}
			case '5' :
				{
					break;
				}
			case '6' :
				{
					break;
				}
			case '7':
				{	//生成卡号
					$cause="<div style='padding:3px; margin:3px; border:1px dashed #000'>";
					$cause.="<b>生成礼包</b>：<a href='".Tools::url('MasterFRG','EditLibao',array('read_only'=>true,'Id'=>$postData['TypeId'],'server_id'=>$postData['server_id']))."'>{$postData['TypeName']}</a>\r\n";
					$cause.='<b>生成张数</b>：'.$postData['Number'];
					$cause.="</div>";
					break;
				}
			case '8':	//单服奖励
			case '11' :	//多服务器奖励
				{//物品触发
					$cause="<div style='padding:3px; margin:3px; border:1px dashed #000'>";
					if ($postData['EveryDay'])$cause.="<b><font color='#ff0000'>每日发放!</font></b>\r\n";
					
					if ($this->_postArr['server_ids']){//如果是多服务器
						$serverList=$this->_getGlobalData('gameser_list');
						$cause.='<b>发送的服务器</b> ： ';
						foreach ($this->_postArr['server_ids'] as $serverId){
							$cause.=$serverList[$serverId]['full_name'] . ' . ';
						}
					}
					$cause.="\r\n";
					$cause.='<b>领取条件</b>：';
					if ($postData['GetValue']){
						$opcode=array('1'=>'大于','2'=>'小于','3'=>'等于');
						foreach ($postData['GetValue'] as $key=>$list){
							$cause.=" {$postData["GetObj_name_{$key}"]}：<font color='#0000FF'>{$opcode[$postData['GetOpcode'][$key]]}</font> <font color='#FF0000'>{$list}</font>. ";
						}
					}
					$cause.="\r\n";
					$cause.='<b>效果道具</b>：';
					if ($postData['EffValue']){
						$opcode=array('1'=>'增加','2'=>'减少','3'=>'改为');
						foreach ($postData['EffValue'] as $key=>$list){
							$cause.=" {$postData["EffObj_name_{$key}"]}：<font color='#0000FF'>{$opcode[$postData['EffOpcode'][$key]]}</font> <font color='#FF0000'>{$list}</font>. ";
						}
					}
					$cause.="\r\n";
					if ($postData['ToolNum']){
						$cause.='<b>奖励道具</b>：';
						foreach ($postData['ToolNum'] as $key=>$list){
							$cause.=" {$postData["Tool_name_{$key}"]}：<font color='#FF0000'>{$list}</font>. ";
						}
					}
					$cause.="<div><b>发放时间</b>：[<font color='#0000FF'>{$postData['SendTime']}</font>] - [<font color='#0000FF'>{$postData['EndTime']}</font>]</div>";
					$cause.="<div><b>消息标题</b>：{$postData['MsgTitle']}</div>";
					$cause.="<div><b>消息内容</b>：{$postData['MsgContent']}</div>";
					$cause.="</div>";
					break;
				}
			case '12' :
				{//生成金币卡号审核
					$cardType=$this->_getGlobalData('frg_gold_card_type');
					$operatorList=$this->_getGlobalData('operator_list');
					$cause="<div style='padding:3px; margin:3px; border:1px dashed #000'>";
					$cause.="<b>充值卡号类型</b>：{$cardType[$postData['card_type']]}\r\n";
					$cause.="<b>批号</b>：".strtoupper(md5($postData['batch_num']))."\r\n";
					$cause.="<b>人民币</b>：{$postData['gold']}\r\n";
					$cause.="<b>生成张数</b>：{$postData['num']}\r\n";
					$cause.="<b>运营商</b>：{$operatorList[$postData['operator_id']]['operator_name']}\r\n";
					if ($postData['is_time'])$cause.="<b>时间限制</b>：{$postData['start_time']} - {$postData['end_time']}\r\n";
					$payType=$postData['type']?'套餐':'金币';
					$cause.="<b>充值类型</b>：{$payType}";
					$cause.="</div>";
					break;
				}
		}
		return $cause;
	}	
}