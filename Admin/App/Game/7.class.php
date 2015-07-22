<?php
Tools::import('Game_GameBase');
class Game_7 extends Game_GameBase{
	/**
	 * 初始化
	 */
	private  $key = 'e23&^$)(&HJjkdwi^&%$';
	public function _init(){
		$this->_gameId = 7;		//游戏Id
		$this->_zp = 'DaTang';	//控制器扩展包
		$this->_key = 'e23&^$)(&HJjkdwi^&%$';	//游戏密匙
		$this->_timer = true;	//是否使用定时器
		$this->_urlApdWO = array(	//使用定时器的工单请求地址附加字符
			'new'=>'game/getquiz',
			'newCbk'=>'game/syncquiz',
			'del'=>'game/getdel',
			'delCbk'=>'game/syncdel',
			'ev'=>'game/getappraise',
			'evCbk'=>'game/syncappraise',
		);
		$this->_preImgPath='{server_url}upload/images/';	//图片前置地址
		$this->_sendImage = true;
	}

	public function workOrderIfChk(){
		if(CONTROL == 'InterfaceFaq'){
			return true;
		}
		return $this->commonChk();
	}

	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		$_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$post['_verifycode'] = CURRENT_TIME;
		$post['_sign'] = md5('e23&^$)(&HJjkdwi^&%$'.CURRENT_TIME);
		$post['work_order_id'] 	= $data['work_order_id'];
		$post['service_id'] 	= $data['service_id'];
		$post['content'] 		= $data['content'];
		$post['status']			= $data['status'];
		$post['image']			= $data['file_img'];
		$_utilHttpMInterface->addHttp($data['send_url'],'game/answer',array(),$post);
		$_utilHttpMInterface->send();
		$dataResult = $_utilHttpMInterface->getResults();
		$dataResult =  json_decode(array_shift($dataResult),true);
		if ($dataResult['status']==1){
			return true;
		}else {
			return Tools::getLang('SEND_MSG','Control_WorkOrder').':'.$dataResult['info'];
		}
	}

	public function autoReplay($data=NULL){
		$api	=	$this->_getGlobalData('Util_HttpMInterface','object');
		$api->curlInit();
		$post['_verifycode'] = CURRENT_TIME;
		$post['_sign'] = md5($this->key.CURRENT_TIME);
		$post['playerIds'] 	= 	trim($data["server_msg"]['game_user_id']);
		$post['title']		=	$data['title'];
		$post['context']	=	$data['content'];
		$api->addHttp($data["server_msg"]['game_server_id'],'user/mail',array(),$post);
		//$dataList			=	$this->getResult($data["server_msg"]['game_server_id'],'user/mail',array(),$post);
		$api->send();
		$thisdata = $api->getResults();
	}

	public function operatorExtParam(){
		return array();
	}

	public function serverExtParam(){
		return array();
	}

	function CurrentTime(){
		return CURRENT_TIME;
	}

	function currentkey(){
		return md5($this->_key.CURRENT_TIME);
	}

	/**
	 * 审核礼品卡
	 * Enter description here ...
	 */
	public function AddItemCard($sendData,$receiver){
		//"$sendData" = Array [4]
		//	url_append = (string:13) game/makecard
		//	post_data = Array [6]
		//		iteminfo = (string:26) 2,3,4|1101:3,1102:4,1103:5
		//		name = (string:8) 11232131
		//		detail = (string:8) 11232131
		//		point = (int) 2
		//		regive = (int) 1
		//		isSendMail = (int) 0
		//	get_data = Array [0]
		//	call = Array [2]
		//		cal_local_object = (string:6) Game_7
		//		cal_local_method = (string:11) AddItemCard
		//
		//"$receiver" = Array [1]
		//	883 = (string:0)
		$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$utilHttpMInterface->curlInit();
		$postData = $sendData['post_data'];
		unset($postData['isSendMail']);

		$utilRbac = $this->_getGlobalData('Util_Rbac','object');
		$userClass=$utilRbac->getUserClass();
		$postData['verifyName'] = $userClass['_nickName'].','.$userClass['_id'];	//审核人姓名	

		$postData['_verifycode'] = CURRENT_TIME;
		$postData['_sign'] = md5($this->_key.CURRENT_TIME);
		foreach($receiver as $serverId => $val){
			$sendUrl = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$sendUrl = $sendUrl[$serverId]['server_url'];
			$utilHttpMInterface->addHttp($sendUrl,$sendData['url_append'],array(),$postData);
				
			break;//单服申请
		}
		$utilHttpMInterface->send();
		$dataReturn = $utilHttpMInterface->getResults();
		$dataReturn =  json_decode(array_shift($dataReturn),true);
		$dataCards = $dataReturn['cards'];
		$appendInfo = '';
		$URL_downLoadCards = '';
		if(is_array($dataReturn) && $dataCards && is_array($dataCards) && empty($dataReturn['address']) ){
			$count = count($dataCards);
			if($count == 1 && $sendData['post_data']['isSendMail'] && $sendData['post_data']['regive'] != 3){
				$cardId = $dataCards[0]['cardId'];
				$noSend = explode(',',$postData['playerId']);
				$sendMailData = array(
					'giftUuid'=>$dataCards[0]['cardNum'],
					'title'=>$postData['title'],
					'context'=>$postData['content'],
					'playerIds'=>$postData['playerId'],
					'playerNames'=>'',
					'_verifycode'=>CURRENT_TIME,
					'_sign'=>md5($this->_key.CURRENT_TIME)
				);
				$utilHttpMInterface->curlInit();
				$utilHttpMInterface->addHttp($serverId,'user/giftmail',array(),$sendMailData);
				$utilHttpMInterface->send();
				$sendMialReturn = $utilHttpMInterface->getResults();
				$sendMialReturn =  json_decode(array_shift($sendMialReturn),true);
				if($sendMialReturn){
					$isSend = array();
					foreach($sendMialReturn as $Player){
						$isSend[] = $Player['id'];
					}
					$noSend = array_diff($noSend,$isSend);
				}
				if($noSend){
					$noSend = implode(',',$noSend);
					$appendInfo = ',邮件没发送给：'.$noSend;
				}
			}elseif($sendData['post_data']['count'] == 1 && $sendData['post_data']['isSendMail'] && $sendData['post_data']['regive'] == 3){
				$cardId = $dataCards[0]['cardId'];
				$noSend = explode(',',$postData['playerId']);
				$sendMailData = array(
					'giftUuid'=>$dataCards[0]['cardNum'],
					'title'=>$postData['title'],
					'content'=>$postData['content'],
					'playerNames'=>'',
					'_verifycode'=>CURRENT_TIME,
					'_sign'=>md5($this->_key.CURRENT_TIME)
				);
				$utilHttpMInterface->curlInit();
				$utilHttpMInterface->addHttp($serverId,'mail/mailtoall',array(),$sendMailData);
				$utilHttpMInterface->send();
				$sendMialReturn = $utilHttpMInterface->getResults();
				$sendMialReturn =  json_decode(array_shift($sendMialReturn),true);
				if($sendMialReturn["status"]==0){
					$appendInfo = ',邮件发送失败';
				}
			}
		}elseif(is_array($dataReturn) && $dataReturn['address']){
			$cardId = 0;
			$dataReturn['address'] = $sendUrl .'download/cards/'. $dataReturn['address'];
			$URL_downLoadCards = ",卡号下载地址:<a target='_blank' href='{$dataReturn['address']}'>{$dataReturn['address']}</a>,";
		}
		else{
			return '<font color="red">审核失败</font><br>'.(is_string($dataReturn)?strip_tags($dataReturn):var_export($dataReturn,true));
		}
		return array($serverId=>array('result_mark'=>$cardId,'send_result'=>'<font color="#00FF00">审核成功</font>'.$URL_downLoadCards.$appendInfo));
	}

	public function PlayerDataModify($sendData,$receiver){
		$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$utilHttpMInterface->curlInit();
		$sendData['post_data']['_verifycode'] = CURRENT_TIME;
		$sendData['post_data']['_sign'] = md5($this->_key.CURRENT_TIME);
		$utilHttpMInterface->setTimeOut(20);

		foreach($receiver as $serverId => $val){
			$utilHttpMInterface->addHttp($serverId,$sendData['url_append'],$sendData['get_data'],$sendData['post_data']);
			break;//单服申请
		}
		$utilHttpMInterface->send();
		$data = $utilHttpMInterface->getResults();

		$data	=	 json_decode(array_shift($data),true);
		if($data['status'] == 1){
			return "<font color='#0066CC'>申请成功</font>";
		}else{
			return '<font color="red">审核失败</font>';
		}

	}

	public function getNotice($data=array()){
		$serverId	=	$data['server_id'];
		unset($data['server_id']);
		$_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$_utilHttpMInterface->curlInit();
		$data['_verifycode'] = CURRENT_TIME;
		$data['_sign'] = md5($this->key.CURRENT_TIME);
		$_utilHttpMInterface->setTimeOut(20);
		$_utilHttpMInterface->addHttp($serverId,'server/postlist',array(),$data);
		$_utilHttpMInterface->send();
		$datalist = $_utilHttpMInterface->getResults();
		return json_decode(array_shift($datalist),true);
	}

	public function TransformNoticeData($data=array()){
		$datalist	=	$this->getNotice($data['post']);
		$addArrs=array();
		if($datalist){
			foreach ($datalist['wlist'] as $value){
				$addArr=array();
				$addArr['content']=$value['context'];
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
		$serverId	=	$data['server_id'];
		unset($data['server_id']);
		$_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$_utilHttpMInterface->curlInit();
		$data['_verifycode'] = CURRENT_TIME;
		$data['_sign'] = md5($this->key.CURRENT_TIME);
		$_utilHttpMInterface->setTimeOut(20);
		$_utilHttpMInterface->addHttp($serverId,'server/removepost',array(),$data);
		$_utilHttpMInterface->send();
		$datalist = $_utilHttpMInterface->getResults();
		return json_decode(array_shift($datalist),true);
	}
}