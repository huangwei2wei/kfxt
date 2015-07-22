<?php
/**
 * 富人国申请数值表
 * @author php-朱磊
 * is_send字段 0:未发送,1审核,2拒绝
 * type字段说明 type<0 批量多服务器审核. type>0单服务器审核
 * 如果server_id=0就表示这条记录一定是多服务器发送
 */
class Model_ApplyDataFrg extends Model {
	protected $_tableName = 'apply_data_frg';
	
	private $_auditArr=array(
		'service'=>array(1,2,3,4,5,6),
		'operation'=>array(7,8,10,11),
		'gold'=>array(12),
	);
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * post信息
	 * @var array
	 */
	private $_postArr;
	
	/**
	 * 权限
	 * @var array
	 */
	private $_options;
	
	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;
	
	/**
	 * 新版富人国调用接口,解决并发问题
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * Model_GoldCard
	 * @var Model_GoldCard
	 */
	private $_modelGoldCard;
	
	/**
	 * 运营商限制
	 * @var array $_operatorsLimit
	 */
	private $_operatorsLimit;
	
	/**
	 * 
	 * @param array $operatorsLimit
	 */
	public function set_OperatorLimit($operatorsLimit){
		$this->_operatorsLimit = $operatorsLimit;
	}
	
	/**
	 * @param $_postArr the $_postArr to set
	 */
	public function set_postArr($_postArr) {
		$this->_postArr = $_postArr;
	}
	
	public function set_options($_options){
		$this->_options=$_options;
	}
	
	private function _getJumpUrl($type){
		switch ($type){
			case in_array($type,$this->_auditArr['service']) :{
				return Tools::url('FrgAudit','ServiceIndex');
			}
			case in_array($type,$this->_auditArr['operation']) :{
				return Tools::url('FrgAudit','OperationIndex');
			}
			case in_array($type,$this->_auditArr['gold']) :{
				return Tools::url('FrgAudit','GoldIndex');
			}
		}
	}
	
	public function getAudit($auditType){
		return $this->_auditArr[$auditType];
	}
	
	/**
	 * 增加记录
	 */
	public function add() {
		$postArr = $this->_postArr;
		if (! $postArr ['server_id'] && $postArr['type']<10)//如果没有serverid(服务器ID),并且type(类型)小于10,表示不是多服发送
			return array ('status' => - 1, 'msg' => '请选择服务器', 'href' => 1 );
		if ($postArr['type']>=10)$postArr['server_id']=0;	//如果type(类型)大于或是等于10就表示是多服务器发送,server_id就等于0
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$addArr = array ();
//		if ($postArr['operator_id'])$addArr ['operator_id']=$postArr['operator_id'];
		if($postArr['server_id']){
			$serverList = $this->_getGlobalData('server/server_list_2');
			$serverList = $serverList[$postArr['server_id']];
			if($serverList['operator_id']){
				$addArr ['operator_id']=$serverList['operator_id'];
			}
		}
		$addArr ['apply_user_id'] = $userClass ['_id'];
		$addArr ['apply_ip']=ip2long(Tools::getClientIP());
		$addArr ['type'] = $postArr ['type'];
		$addArr ['cause'] = $postArr ['cause'] . $this->_addAutoCause (); //说明原因加自动生成说明
		$addArr ['send_action'] = serialize ( $postArr ['send_action'] );
		$addArr ['post_data'] = serialize ( $postArr ['post_data'] );
		$addArr ['server_id'] = $postArr ['server_id'];
		$addArr ['create_time'] = CURRENT_TIME;
		if($postArr ['type'] ==1){
			$addArr ['user_info'] = $postArr ['post_data']['UserIds'];	//记录玩家的ids
		}
		else{
			$addArr ['user_info'] = $postArr ['post_data']['userid'];	//记录玩家的id
		}		
		if (parent::add ($addArr)) {
//			return array ('status' => 1, 'msg' => '申请成功,等待审核...', 'href'=>$this->_getJumpUrl($postArr['type']) );
			//跳转至原来申请的页面
			return array ('status' => 1, 'msg' => '申请成功,等待审核...', 'href'=>$_SERVER["HTTP_REFERER"] );
		} else {
			return array ('status' => - 2, 'msg' => '添加失败,请重新添加', 'href' => 1 );
		}
	}
	
	/**
	 * 拒绝审核
	 */
	public function reject(){
		$id=$this->_postArr['Id'];
		if (!$id)return array('status' => -1, 'msg' => '请选择要拒绝的记录', 'href' => 1 );
		if (is_array($id)){
			$ids=implode(',',$id);
			$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$userClass=$this->_utilRbac->getUserClass();
			$this->update(array('is_send'=>'2','audit_ip'=>ip2long(Tools::getClientIP()),'send_time'=>CURRENT_TIME,'audit_user_id'=>$userClass['_id']),"Id in ({$ids}) and is_send!=1 and type in (".implode(',',$this->_options).")");	//条件为选中的记录,并且为没有被审核过的,而且是只能在可审核的id之下
		}else{
			$this->update(array('is_send'=>'2','audit_ip'=>ip2long(Tools::getClientIP()),'send_time'=>CURRENT_TIME),"Id={$id} and is_send!=1}");
		}
		return array('status' => 1, 'msg' => false, 'href' => 1 );
	}
	
	/**
	 * 接收记录请求
	 */
	public function accept(){
		$id=$this->_postArr['Id'];
		if (!$id)return array('status' => -1, 'msg' => '请选择要审核的记录', 'href' => 1 );
		
		if (is_array($id)){
			$msg='';
			foreach ($id as $curId){
				$string=$this->_acceptId($curId);
				$msg.=$string.'<br>';
			}
		}else{
			$msg=$this->_acceptId($id);
		}
		return array('status'=>1,'msg'=>$msg,'href'=>1);
	}
	
	/**
	 * 根据id来接收一个请求
	 * @param int $id
	 * @return boolean/string 
	 */
	private function _acceptId($id){
		$data=$this->findById($id);
		if (!in_array($data['type'],$this->_options))return "Id:{$id} : <font color='#FF0000'>您没有权限审核这项</font>.";
		if($this->_operatorsLimit && !in_array($data['operator_id'],$this->_operatorsLimit)){
			return "Id:{$id} : <font color='#FF0000'>您没有权限审核这项</font>.";
		}
		if ($data){
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			if ($data['is_send']==1)return "Id:{$id} 已经被审核过";
			if ($data['type']>=10){//如果是批量审核的话
				switch ($data['type']){
					case '10' :{//多服务器发送奖励
						$serverList=$this->_getGlobalData('gameser_list');
						$data['post_data']=unserialize($data['post_data']);
						$data['post_data']['send_players']=str_replace('\\', '', $data['post_data']['send_players']);//去除\,好反序列化
						$sendPlayerIds=unserialize($data['post_data']['send_players']);//得到要发送的玩家
						unset($data['post_data']['send_players']);//删除掉这些玩家,一会好发送post数据给游戏接口
						$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
						$this->_utilApiFrg->curlInit();
						$getArr=unserialize($data['send_action']);
						foreach ($sendPlayerIds as $key=>$list){
							$data['post_data']['UserIds']=implode(',', $list);//加自要发送的玩家ID
							$data['post_data']['UserIds'] = str_replace(' ','',$data['post_data']['UserIds']);//去掉空格
							$this->_utilApiFrg->addHttp($key,$getArr,$data['post_data']);
						}
						$this->_utilApiFrg->send();
						$sendResult=array();
						$result=$this->_utilApiFrg->getResults();
						foreach ($result as $key=>$backParams){
							$color=($backParams['msgno']==1)?'#00CC00':'#ff0000';//定义颜色
							$message=($backParams['msgno']==1)?'发送成功':'发送失败';
							$backParams['message']=$backParams['message']?$backParams['message']:$message;
							if ($backParams){
								$string="{$serverList[$key]['full_name']} : <font color='{$color}'>{$backParams['message']}</font>";
								$string=$this->_createAcceptMsg(1,$backParams,$string);	//发送消息
								array_push($sendResult, $string);
							}else {
								$string="{$serverList[$key]['full_name']} : <font color='{$color}'>{$backParams['message']}</font>";
								array_push($sendResult, $string);
							}
						}
						$retStr=implode('<br>', $sendResult);
						$this->update(array('is_send'=>1,'audit_ip'=>ip2long(Tools::getClientIP()),'send_time'=>CURRENT_TIME,'audit_user_id'=>$userClass['_id'],'send_result'=>$retStr),"Id={$id}");
						$this->_utilApiFrg=null;
						return $retStr;
					}
					case '11' :{//多服务器奖励触发
						$serverList=$this->_getGlobalData('gameser_list');
						$data['post_data']=unserialize($data['post_data']);
						$serverIds=$data['post_data']['server_ids'];//得到要发送的服务器
						unset($data['post_data']['server_ids']);//删除掉这些玩家,一会好发送post数据给游戏接口
						$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
						$this->_utilApiFrg->curlInit();
						$getArr=unserialize($data['send_action']);
						foreach ($serverIds as $serverId){
							$this->_utilApiFrg->addHttp($serverId,$getArr,$data['post_data']);
						}
						$this->_utilApiFrg->send();
						$sendResult=array();
						$result=$this->_utilApiFrg->getResults();
						foreach ($result as $key=>$backParams){
							$color=($backParams['msgno']==1)?'#00CC00':'#ff0000';//定义颜色
							$message=($backParams['msgno']==1)?'发送成功':'发送失败';
							$backParams['message']=$backParams['message']?$backParams['message']:$message;
							if ($backParams){
								$string="{$serverList[$key]['full_name']} : <font color='{$color}'>{$backParams['message']}</font>";
								array_push($sendResult, $string);
							}else {
								$string="{$serverList[$key]['full_name']} : <font color='{$color}'>{$backParams['message']}</font>";
								array_push($sendResult, $string);
							}
						}
						$retStr=implode('<br>', $sendResult);
						$this->update(array('is_send'=>1,'audit_ip'=>ip2long(Tools::getClientIP()),'send_time'=>CURRENT_TIME,'audit_user_id'=>$userClass['_id'],'send_result'=>$retStr),"Id={$id}");
						$this->_utilApiFrg=null;
						return $retStr;
					}
					case '12' :{//生成金币卡
						$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
						$data=$this->_modelGoldCard->import(unserialize($data['post_data']));
						if ($data['status']==1){
							$updateArr=array(
										'is_send'=>1,
										'audit_ip'=>ip2long(Tools::getClientIP()),
										'send_time'=>CURRENT_TIME,
										'audit_user_id'=>$userClass['_id'],
										'send_result'=>"Id:{$id} <font color='#00CC00'>生成金币卡成功</font>",
							);
							$this->update($updateArr,"Id={$id}");
							return '<font color="#00CC00">'.$data['msg'].'</font>';
						}
						return '<font color="#FF0000">生成金币卡失败</font>';
						
					}
				}
				
			}else {
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($data['server_id']);
				$this->_utilFRGInterface->setGet(unserialize($data['send_action']));
				$this->_utilFRGInterface->setPost(unserialize($data['post_data']));
				$backParams=$this->_utilFRGInterface->callInterface();
				$this->_utilFRGInterface=null;
				$color=($backParams['msgno']==1)?'#00CC00':'#ff0000';//定义颜色
				if ($backParams['msgno']==1){
					$updateArr=array(
								'is_send'=>1,
								'audit_ip'=>ip2long(Tools::getClientIP()),
								'send_time'=>CURRENT_TIME,
								'audit_user_id'=>$userClass['_id'],
								'send_result'=>$this->_createAcceptMsg($data['type'],$backParams,"Id:{$id} <font color='{$color}'>{$backParams['message']}</font>"),
					);
					$this->update($updateArr,"Id={$id}");
				}
				return $this->_createAcceptMsg($data['type'],$backParams,"Id:{$id} <font color='{$color}'>{$backParams['message']}</font>");
			}
		}
		return "无此记录Id : {$id}";
	}
	
	/**
	 * 返回发送的数据
	 * @param $type 审核类型
	 * @param $result 返回结果
	 */
	private function _createAcceptMsg($type,$result,$msg){
		switch ($type){
			case 1 :{	//奖励发送
				if (count($result['backparams'])){
					$msg.="<br />未发出的用户：".implode(',',$result['backparams']);
				}
				break;
			}
			default:{
				break;
			}
		}
		return $msg;
	}
	
	/**
	 * 删除记录并且状态不能等于已经审核的
	 * @param int $id
	 */
	public function delOtherStatus($id){
		if (is_array($id)){//批量删除
			return $this->execute("delete from {$this->tName()} where is_send!=1 and Id in (".implode(',',$id).")");
		}else {//单一删除
			return $this->execute("delete from {$this->tName()} where is_send!=1 and Id={$id} limit 1");
		}
	}
	
	
	/**
	 * 解析发送玩家ID,主要用于生成原因
	 * @param string $players 未解析的序列化数据
	 */
	private function _createUserIds($players){
		$players=str_replace('\\', '', $players);
		$players=unserialize($players);
		$serverList=$this->_getGlobalData('gameser_list');
		$string='<div>发送的玩家ID:<ul>';
		foreach ($players as $key=>$list){
			$string.='<li style="display:block">';
			$string.="<font color='#ff0000'><b>[{$serverList[$key]['full_name']}]： </b></font>";
			$string.=implode(',', $list);
			$string.='</li>';
		}
		$string.='</ul></div>';
		return $string;
	}
	
	/**
	 * 自动生成申请原因清单
	 * @return string
	 */
	private function _addAutoCause() {
		$postData = $this->_postArr['post_data'];
		switch ($this->_postArr ['type']) {
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
	
	/**
	 * 统计,默认统计 客服
	 * @param array $time ['start']开始时间 ['end']结束时间
	 * @param int $auditUserId	审核人
	 * @param string $type 审核类型,默认客服
	 */
	public function stats($time,$auditUserId=NULL,$type='service'){
		$sql="select * from {$this->tName()} where send_time between {$time['start']} and {$time['end']} and is_send=1 ";
		if ($auditUserId)$sql.=" and audit_user_id={$auditUserId} ";
		$auditTypes=$this->_auditArr[$type];
		$sql.=" and type in (".implode(',',$auditTypes).")";
		$dataList=$this->select($sql);
		if ($dataList){
			$statsArr=array();
			foreach ($dataList as $value){
				$statsArr['total'][$value['type']]++;
				$statsArr[date('Y-m-d',$value['send_time'])][$value['type']]++;
			}
			foreach ($auditTypes as $value){
				if (empty($statsArr['total'][$value]))$statsArr['total'][$value]=0;
			}
			$statsArr['total']['total']=array_sum($statsArr['total']);
			ksort($statsArr['total']);
			$allDay=Tools::getdateArr($time['start'],$time['end']);
			foreach ($allDay as $day=>$tmp){
				
				if (isset($statsArr[$day])){
					foreach ($auditTypes as $value){
						if (empty($statsArr[$day][$value]))$statsArr[$day][$value]=0;
					}
				}else {
					$statsArr[$day]=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);	
				}
				$statsArr[$day]['total']=array_sum($statsArr[$day]);
				ksort($statsArr[$day]);
			}
			krsort($statsArr);
			return $statsArr;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}