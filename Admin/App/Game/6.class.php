<?php
Tools::import('Game_GameBase');
class Game_6 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 6;		//游戏Id
		$this->_zp = 'XianHun';	//控制器扩展包
		$this->_key = 'test';	//游戏密匙
		$this->_isSendOrderReplay = true;	//不往游戏发送消息
	}
	
	public function workOrderIfChk(){
//		return true;	//测试期间用不验证
		if($_REQUEST['source'] == '1'){
			return $this->commonChk();
		}
		switch(CONTROL){
			case 'InterfaceWorkOrder':
//				return true;	//调试期间先不验证
//			case 'InterfaceFaq':
				$sign = trim($_REQUEST['_sign']);
//				$sign = '78c146c30ec215231fd325b399291270';
				$userId = strval($_REQUEST['user_id']);
//				error_log($sign."\n\r", 3, RUNTIME_DIR.'/Logs/sign_logs_'.date('Y_m_d_H',time()).".log");	
				$userInfo = $this->getUserBySign($sign);
//				error_log(serialize($userInfo)."\n\r", 3, RUNTIME_DIR.'/Logs/sign_logs_'.date('Y_m_d_H',time()).".log");	
								
//				if($userInfo['status']==1 && strval($userInfo['data']['user_id'])==$userId){
				if($userInfo['status']==1 && ($userInfo['data']['user_name'] || $userInfo['data']['user_nickname'] || $userInfo['data']['user_id']) ){
					return true;
				}
				return false;
			case 'InterfaceFaq':
				return true;
			default :
				return $this->commonChk();
		}
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		if($data['status']==3){
			$title		=	'您的提问已回复';
			$content	=	"您的提问已回复，请您对我们的服务作出评价！<a href='event:mo|gm|{$data['work_order_id']}'><b><font color='#00ff00'>点击查看</font></b></a>";
		}else{
			$title		=	'您的提问正在处理中';
			$content	=	"您的提问正在处理中 <a href='event:mo|gm|{$data['work_order_id']}'><b><font color='#00ff00'>点击查看</font></b></a>";
		}
		$rpc = $this->_getGlobalData('Util_Rpc','object');
		$rpc->setUrl($data['send_url'],'rpc/server');
		$rpc->setPrivateKey($this->_key);
		$dataList=$rpc->sendMails($data["game_user_id"],0,$title,$content,'',0,0);
		return true;
	}
	
	public function autoReplay($data=NULL){		
			$rpc = $this->_getGlobalData('Util_Rpc','object');
			$rpc->setUrl($data["server_msg"]['game_server_id'],'rpc/server');
			$rpc->setPrivateKey($this->_key);
			$dataList=$rpc->sendMails($data["server_msg"]['game_user_id'],0,$data['title'],$data['content'],'',0,0);
			if($dataList instanceof PHPRPC_Error ){
				return false;
			}elseif($dataList){
				
				return true;
			}else{
				return false;
			}
	}
	
	public function operatorExtParam(){
		return array();
	}
	
	public function serverExtParam(){
		return array(
			array('db_host','数据库服务器','text',''),
			array('db_name','数据库名','text',''),
			array('db_user','数据库用户','text',''),
			array('db_pwd','数据库密码','passport',''),
			array('db_port','数据库端口号','text',''),	
		);
	}
	
	/**
	 * 游戏提供的玩家身份检查接口
	 * @param string $sign
	 */
	public function getUserBySign($sign){
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		$serverMarking = trim($_REQUEST['server_marking']);
		$serverUrl = '';
		foreach ($serverList as $sub){
			if($serverMarking == $sub['marking']){
				$serverUrl = $sub['server_url'];
				break;
			}
		}
		if(empty($serverUrl)){
			return array('status'=>0,'info'=>'The server does not exist','data'=>NULL);
		}
		$_utilRpc = $this->_getGlobalData('Util_Rpc','object');
		$_utilRpc->setUrl($serverUrl,'rpc/user');
		$_utilRpc->setPrivateKey($this->_key);
		$userInfo = $_utilRpc->getUsernameBySign($sign);
		if($userInfo instanceof PHPRPC_Error){
			return array('status'=>0,'info'=>$userInfo->Message,'data'=>NULL);
		}else{
			return array('status'=>1,'info'=>NULL,'data'=>$userInfo);
		}
	}
	
	/**
	 * 道具审核数据结果描述
	 * @param $data
	 */
	public function ItemsAuditBack($data = NULL){
		$tag = '链接服务器失败';
		if($data && is_object($data)){
			if($data->code === 0){
				$tag = true;
			}elseif($data->Message){
				$tag = $data->Message;
			}
		}else{
			return '<font color="#FF0000">服务器返回结果为空</font>';
		}
		if($tag === true){
			return array(
				'send_result'=>'<font style="font-weight:bold; color:#360">审核成功</font>',
				'result_mark'=>$data->giftId.'_'.$data->giftContentId,
			);
		}else{
			return '<font color="#FF0000">'.$tag.'</font>';
		}
	}
	
	public function SendItemsToAllBack($sendOk = false){
		if($sendOk){
			return '<font style="font-weight:bold; color:#360">'.Tools::getLang('SEND_SUCCESS','Common').'</font>';
		}
		return '<font color="#FF0000">'.Tools::getLang('SEND_FAILURE','Common').'</font>';
	}

	public function getNotice($data=array()){
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		$_utilRpc = $this->_getGlobalData('Util_Rpc','object');
		$_utilRpc->setUrl($serverList[$data['server_id']]['server_url'],'rpc/server');
		$_utilRpc->setPrivateKey($this->_key);
		$dataList=	$_utilRpc->getAnnouncements(0,$data['title'],$data['content'],$data['page'],PAGE_SIZE);
		return $dataList;
	}
	
	public function TransformNoticeData($data=array()){
		$datalist	=	$this->getNotice($data['post']);
		$addArrs=array();
		if($datalist){
			foreach ($datalist['results'] as $value){
				$addArr=array();
				$addArr['content']=$value['content'];
				$addArr['title']=$value['title'];
				$addArr['start_time']=strtotime($value['startTime']);
				$addArr['end_time']=strtotime($value['endTime']);
				$addArr['interval_time']=$value['interval'];
				$addArr['url']=$value['url'];
				$addArr['create_time']='0';
				$addArr['last_send_time']='0';
				$addArr['main_id']=$value['id'];
				array_push($addArrs,$addArr);
			}
			return $addArrs;
		}
	}
	
	public function delNotice($data=array()){
		$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		$_utilRpc = $this->_getGlobalData('Util_Rpc','object');
		$_utilRpc->setUrl($serverList[$data['server_id']]['server_url'],'rpc/server');
		$_utilRpc->setPrivateKey($this->_key);
		$dataList=$_utilRpc->delAnnouncement($data['ids']);
		if($dataList instanceof PHPRPC_Error ){
			return false;
		}elseif($dataList){
			return true;	
		}else{
			return false;
		}	
	}	
}