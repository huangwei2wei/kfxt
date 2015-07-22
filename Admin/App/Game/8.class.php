<?php
Tools::import('Game_GameBase');
class Game_8 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 8;		//游戏Id
		$this->_zp = 'HaiDao';	//控制器扩展包
		$this->_key = 'Qh%)v^7c#29jHf(65G!2*mU05CzAmKi$';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
//		return true;//测试期间不验证
		$k = $this->clientTimeChk();
		if(true === $k || $k == 'TimeOut'){
			return true === $k?true:'TimeOut';
		}
		return $this->clientChk();
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		//return true;	//更新之后去掉
		if($data['status']==3){
			$title		=	'您的提问已回复';
			$content	=	"您的提问已回复，请您对我们的服务作出评价！<a href='event:WorkOrderDetail?work_order_id={$data['work_order_id']}'><b><font color='#00ff00'>点击查看</font></b></a>";
		}else{
			$title		=	'您的提问正在处理中';
			$content	=	"您的提问正在处理中 <a href='event:WorkOrderDetail?work_order_id={$data['work_order_id']}'><b><font color='#00ff00'>点击查看</font></b></a>";
		}
		$api		=	$this->_getGlobalData('Util_FRGInterface','object');
		$api->setServerUrl($data['send_url']);
		$sendParams	=	array(
							"MsgTitle"		=>	$title,
							"MsgContent"	=>	$content,
							"UserIds"		=>	$data['game_user_id'],
							"ReceiveType"	=>	0
						);
		$api->setGet(array('c'=>'Reward','a'=>'SendMail','doaction'=>'save'));
		$api->setPost($sendParams);
		$data=$api->callInterface();
		return true;
	}
	
	public function autoReplay($data=NULL){
		$api		=	$this->_getGlobalData('Util_FRGInterface','object');
		$api->setServerUrl($data["server_msg"]['game_server_id']);
		$sendParams	=	array(
							"zp"			=>	"HaiDao",
							"server_id"		=>	$data["server_msg"]['game_server_id'],
							"cause"			=>	NULL,
							"MsgTitle"		=>	$data['title'],
							"MsgContent"	=>	$data['content'],
							"UserIds"		=>	$data["server_msg"]['game_user_id'],
							"ReceiveType"	=>	0
						);
		$api->setGet(array('c'=>'Reward','a'=>'SendMail','doaction'=>'save'));
		$api->setPost($sendParams);
		$data=$api->callInterface();
//Array
//(
//    [zp] => HaiDao
//    [server_id] => 963
//    [cause] => 撒旦发生大
//    [MsgTitle] => 撒旦法
//    [MsgContent] => 啊沙发东山饭店
//    [UserIds] => 123
//    [ReceiveType] => 0
//    [submit] => 立即发送
//)
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
			//9=>array('id'=>2,'key'=>'!@$$DSDGldj*73@sls-(3','mark'=>'gamefrg32','url'=>''),	//官网
			9=>array('id'=>6,'key'=>'!asdf23!@a1~2234vaq','mark'=>'uwan','url'=>''),	//官网
			12=>array('id'=>6,'key'=>'zG2D46acO38GBkc47iMQ','mark'=>'kaixin001','url'=>''),	//开心网
			83=>array('id'=>6,'key'=>'!asdf23!@a1~2234vaq','mark'=>'qq','url'=>'http://s{var}.app28982.qqopenapp.com'),	//腾讯
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
						$utilRbac = $this->_getGlobalData('Util_Rbac','object');
						$userClass = $utilRbac->getUserClass();
						
						foreach ($postData['EffValue'] as $key=>$list){
							$cause.=" {$postData["EffObj_name_{$key}"]}：<font color='#0000FF'>{$opcode[$postData['EffOpcode'][$key]]}</font> <font color='#FF0000'>{$list}</font>. ";
							if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
								//var_dump($postData["EffObj_name_{$key}"]);
								if($list>20000 && $postData["EffObj_name_{$key}"] == '金币'){
									$utilMsg = $this->_getGlobalData('Util_Msg','object');
									$utilMsg -> showMsg("不能过20000",-1);
								}
							
							}
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
	/**
	 * 解析发送玩家ID,主要用于生成原因
	 * @param string $players 未解析的序列化数据
	 */
	private function _createUserIds($players){
		$serverList=$this->_getGlobalData('gameser_list');
		$string='<div>发送的玩家ID:<ul>';
		foreach ($players as $key=>$list){
			$string.='<li style="display:block">';
			$string.="<font color='#ff0000'><b>[{$serverList[$key]['full_name']}]： </b></font>";
			$string.=$list['UserIds'];
			$string.='</li>';
		}
		$string.='</ul></div>';
		return $string;
	}
}