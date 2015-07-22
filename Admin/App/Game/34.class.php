<?php
Tools::import('Game_GameBase');
class Game_34 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	private  $_faqKey = 'dotoe23&^$)(&HJj%dwi^&%$';
	public function _init(){
		$this->_gameId = 34;		//游戏Id
		$this->_zp = 'FHJZ';	//控制器扩展包
		$this->_key = 'fhjz23&^$)(&HJj%dwi^&%$';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
		return $this->clientTimeChk($this->_faqKey);
// 		if($_REQUEST['source']==1){
// 			return $this->commonChk();
// 		}
// 		return $this->clientTimeChk('c970ab23-abac-49d0-9976-03d1cd649d47');//特殊处理，周末更新之后换回来
	}
	
	public function sendOrderReplay($data=NULL){
		if($this->_isSendOrderReplay){
			if($data['status']==3){
				$title		=	'您的提问已回复';
				$content	=	"你的提问已经答复<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
			}else{
				$title		=	'您的提问正在处理中';
				$content	=	"您的提问正在处理中！<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
			}
			$api	=	$this->_getGlobalData('Util_HttpInterface','object');
			$getData = $this->getGetData(array('action'=>'send'),$data["server_id"]);
			$postData['users'] = $data['game_user_id'];
			$postData['userType'] = 1;//1:玩家id,2:表示用户帐号,3:表示角色名
			$postData['title'] = $title;
			$postData['content'] = $content;
			$dataReturn = $api->result($data["send_url"],'mail',$getData,$postData);
		}
		return true;
	}
	
	public function autoReplay($data=NULL){
		return false;
	}
	
	public function operatorExtParam(){
		return array();
	}
	
	public function serverExtParam(){
		return array();
	}
	
	public function getSignArr($data = array()){
		
		$sendData = array(
			'timestamp'=>CURRENT_TIME,
			'sign'=>md5(CURRENT_TIME.$this->_key),
		);
		if(is_array($data)){
			$sendData = array_merge($sendData,$data);
		}
		return $sendData;
		
	}

	
	public function getServerMarking($serverId=0,$m='marking'){
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$marking = $serverList[$serverId][$m];
		}
		return $marking;
	}
	
	/**
	 * 获得GET基本数据
	 * @param $get
	 */
	public function getGetData($get = array()){
		$gameInfo = $this->getIfConf();
		$getData = $gameInfo[ACTION]['get'];
		if($get){
			$getData = array_merge($getData,$get);
		}
		return $getData;
	}
	/**
	 * 获得POST基本数据
	 * @param $post
	 */
	public function getPostData($data=array(),$serverId=0){
		$postData = $this->getSignArr($data);
		$postData['domain'] = $this->getServerMarking($serverId,'ordinal');
// 		$gameInfo = $this->getIfConf();
// 		$post = $gameInfo[ACTION]['post'];
// 		if($post){
// 			$postData = array_merge($postData,$post);
// 		}
		return $postData;
	}
	
	public function applyEnd($data,$type = 'json'){
		$type = strtolower($type);
		switch ($type){
			case 'json':
			default:
				$data = json_decode($data,true);
		}
		//		print_r($data);exit;
		if(!is_array($data)){
			return "<font color='#FF0000'>审核失败:游戏返回数据格式错误</font>";
		}
		if($data['status'] == 0){
			return '<font color="#00FF00">审核成功</font>';
		}
		return '<font color="#FF0000">审核失败:'.$data['info'].'</font>';
	}
	
	public function SendcartFile($sendData=NULL,$receiver=NULL){
		$_utilHttpDown = $this->_getGlobalData('Util_Httpdown','object');
		$gameServerList=$this->_getGlobalData('gameser_list');
		$ServerId	=	 array_keys($receiver); 
		$filePath = $sendData['post_data']['filePath'];
		$isImportFile = $sendData['post_data']['importFile'];
		unset($sendData['post_data']['filePath'],$sendData['post_data']['importFile']);
//		foreach ($sendData['post_data'] as $k=>$v){
//			$_utilHttpDown->AddForm($k,$v);
//		}
		$url=$gameServerList[$ServerId[0]]['server_url'].$sendData['url_append']."?";
		$url.=http_build_query(array_merge($sendData['get_data'],$sendData['post_data']));
		if($isImportFile==1){
			$_utilHttpDown->AddFileContent('file',basename($filePath),file_get_contents($filePath));
		}
		$_utilHttpDown->OpenUrl($url);
 		if($_utilHttpDown->IsGetOK()){
			$dataResult=$_utilHttpDown->GetRaw();
			$dataResult = json_decode($dataResult,ture);
			if($dataResult['status']!=1){
				return $dataResult;
			}
		}
	}

	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
			'ServerManagement'=>array(	//服务器管理
					'action'=>'Default',
			),
			'PlayerLookup'=>array(
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getPlayerList',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
			),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getLogList',
				'get'=>array(),
				'body'=>'',
			),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getLogType',
				'get'=>array(),
				'body'=>'',
			),
			'SendMail'=>array(	//发邮件
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/sendEmail',
				'get'=>array(),
				'body'=>'',
			),
// 			'RechargeRecord'=>array('action'=>'Action10',),
			'GameLogin'=>array(	//游戏登录
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/loginGmae',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/GameLogin/XiYou.html',
				'notify'=>'Log_GameLogin',
			),
			
			'TitleOrGag'=>array(	//封号禁言
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getPlayerRestictList',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
			),
			'AddTitleOrGag'=>array(	//添加封号禁言
				'action'=>'XiYou',
				'UrlAppend'=>'interface/addPlayerRestict',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
				'notify'=>'Log_TitleOrGag',
			),
			'DelTitleOrGag'=>array(	//删除封号禁言
				'action'=>'XiYou',
				'UrlAppend'=>'interface/delPlayerRestict',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
				'notify'=>'Log_TitleOrGag',
			),
			
			
			'Notice'=>array(	//公告列表
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getNoticeList',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/addNotice',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'XiYou',
				'UrlAppend'=>'interface/delNotice',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/updateNotice',
				'get'=>array(),
				'body'=>'',
			),

			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'XiYou',
				'UrlAppend'=>'player/getBackpackGoodsList',
				'get'=>array(),
				'body'=>'',
			),
			'Item'=>array(	//道具更新|获得道具列表
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/getGoodsList',
				'get'=>array(),
				'body'=>'',
			),

			'ItemDel'=>array(	//道具删除
				'action'=>'XiYou',
				'UrlAppend'=>'player/updateBackpackGoods',
				'get'=>array(),
				'notify'=>'Log_Silence',
			),
			
			
			'ItemCard'=>array(	//礼包
				'action'=>'XiYou',
				'UrlAppend'=>'itemCard/getCardList',
				'get'=>array(),
			),
			'ItemCardApply'=>array(	//礼包申请
				'action'=>'FHJZ',
				'UrlAppend'=>'interface/sendEmail',
				'get'=>array(),
			),
			
// 			'FunOnOrOff'=>array(	//
// 					'action'=>'XiYou',
// 					'UrlAppend'=>'server/getFunction',
// 					'get'=>array(),
// 			),
// 			'Patch'=>array(	//补丁
// 					'action'=>'XiYou',
// 					'UrlAppend'=>'server/callInterface',
// 					'get'=>array(),
// 			),
		);
	}
}