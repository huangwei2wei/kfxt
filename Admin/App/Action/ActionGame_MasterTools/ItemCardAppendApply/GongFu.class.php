<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardAppendApply_GongFu extends Action_ActionBase{
	
	const BIND_TYPE = 'bindType';
	const KEY_TYPE = 'keyType';
	const ITEM_PACKAGE_TYPE = 'itemPackageType';
	public function _init(){
		$this->_assign[self::KEY_TYPE] = Tools::gameConfig(self::KEY_TYPE,$this->_gameObject->_gameId);	//keyType
		$this->_assign[self::BIND_TYPE] = Tools::gameConfig(self::BIND_TYPE,$this->_gameObject->_gameId);	//bindType
		$this->_assign[self::ITEM_PACKAGE_TYPE] = Tools::gameConfig(self::ITEM_PACKAGE_TYPE,$this->_gameObject->_gameId);	//keyType
		$this->_assign['URL_itemUpdate'] = Tools::url(
			CONTROL,
			'Item',
			array('timeout'=>'1','zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_itemCard'] = Tools::url(
			CONTROL,
			'ItemCard',
			array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
	}
	
	public function getPostData($post=null){
		$type = intval($_POST['type']);
		$keyType = intval($_POST['keyType']);
		$classId = intval($_POST['classId']);
		$bindType = intval($_POST['bindType']);//bindType	Int	否	绑定类型。0：不绑定，1：绑定服，2：绑定玩家
		$counts = $bindType===2?1:max(1,intval($_POST['counts']));//counts	Int	否	礼包卡密的数量
		$playerId = $bindType===2?trim($_POST['playerId']):0;//playerId	Long	是	绑定玩家是玩家Id
		$mailTitle = trim($_POST['mailTitle']);//mailTitle	String	是	邮件标题
		$mailContent = trim($_POST['mailContent']);//mailContent	String	是	邮件内容
		$postData = compact('type','keyType','classId','bindType','counts','playerId','mailTitle','mailContent');
//		if($bindType == 1){
//			$postData['serverId'] = $this->_gameObject->getServerId();//在getData中已经带上
//		}
		$postData['__fields__'] = array('batchId'=>'Id');	//batchId将要换成申请的id,作为批号
		//对$postData进行数据调整
		$this->_isSendMail($postData);
		if($_POST['importFile']==1){
			//上传文件知runtime目录的某个目录中
			$postData['filePath'] = $this->_FileUpload(); //
			if(!$postData['filePath']){
				$this->jump("上传文件失败",-1);
			}
			$postData['importFile'] = 1;
		}
		$validate = array(
			'type'=>array(array('array_key_exists','###',$this->_assign[self::ITEM_PACKAGE_TYPE]),'礼包类型不合法'),
			'keyType'=>array(array('array_key_exists','###',$this->_assign[self::KEY_TYPE]),'卡类型不合法'),
			'classId'=>array(array('max',0,'###'),'礼包ID错误'),
			'bindType'=>array(array('array_key_exists','###',$this->_assign[self::BIND_TYPE]),'绑定类型不合法'),
			//'goldType'=>$postData['goldType']?array(array('array_key_exists','###',$this->_assign[self::GOLD_TYPE]),'元宝类型超出范围'):null,
			'playerId'=>$postData['bindType']==2?$validate['playerId'] = array('trim','绑定的玩家不能为空'):null,
		);
		$check = Tools::arrValidate($postData,$validate);
		if($check !== true){
			$this->jump($check,-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = array(
				'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象，如果为空，则使用内置函数
				'cal_local_method'=>'getGetData',	//使用本地方法，‘cal_local_method’判断是否使用内部方法
				'params'=>array(	//如果有参数，就使用数组方式表示
					$get,
					intval($_REQUEST['server_id']),
				),
			);
			//$postData['batchId'] = intval($_POST['batchId']);//测试
//			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($this->_apply($UrlAppend,$getData,$postData)){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->jump($showMsg,1,1,false);
			}
			$this->jump('申请失败',-1);
		}
		$this->_assign['classId'] = intval($_GET['classId']);
		$this->_assign['bindTypeValue'] = intval($_GET['bindType']);
		return $this->_assign;
	}
	
	private function _apply($UrlAppend ='',$getData=array(),$postData=array()){
		$cause = trim($_POST['cause']);
		$bindType = $this->_assign[self::BIND_TYPE];
		if(empty($cause)){
			$this->jump('操作原因缺少',-1);
		}
		$bindPlayerIdInfo = '';
		$sendMailInfo = '';
		if($postData['bindType']==2){
			$bindPlayerIdInfo = <<<bindPlayerIdInfo
				<div>绑定玩家ID：{$postData['playerId']}</div>
bindPlayerIdInfo;
			if($postData['mailTitle'] && $postData['mailContent']){
				$sendMailInfo = <<<sendMailInfo
				<div>邮件标题：{$postData['mailTitle']}</div>
				<div>邮件内容：{$postData['mailContent']}</div>
sendMailInfo;
			}
		}
		$URL_package = Tools::url(
			CONTROL,
			'ItemCard',
			array('submit'=>1,'classId'=>$postData['classId'],'zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		if($postData['importFile']!=1){
			$applyInfo =<<<applyInfo
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>申请原因：<br>{$cause}</div>
			</div>
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>卡密长度：{$this->_assign[self::KEY_TYPE][$postData['keyType']]}</div>
				<div>礼包内容：[<a href="{$URL_package}">点击查看</a>]</div>
				<div>绑定方式：{$bindType[$postData['bindType']]}</div>
				<div>生成数量：{$postData['counts']}</div>
				{$bindPlayerIdInfo}
				{$sendMailInfo}
			</div>
applyInfo;
		}else{
			$applyInfo =<<<applyInfo
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>申请原因：<br>{$cause}</div>
			</div>
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>卡密长度：{$this->_assign[self::KEY_TYPE][$postData['keyType']]}</div>
				<div>礼包内容：[<a href="{$URL_package}">点击查看</a>]</div>
				<div>绑定方式：{$bindType[$postData['bindType']]}</div>
				<div>生成数量：文件上传</div>
				{$bindPlayerIdInfo}
				{$sendMailInfo}
			</div>
applyInfo;
		}
		
		$serverId = intval($_REQUEST['server_id']);
		$applyData = array(
			'type'=>$this->_gameObject->getApplyId('ItemCardApply'),	//从Game拿id
			'server_id'=>$serverId,
			'operator_id'=>$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>str_replace(array("\r\n","\n",),'',$applyInfo),
			'send_type'=>2,	//2	http
			'send_data'=>array(
				'url_append'=>$UrlAppend,
				'post_data'=>$postData,
				'get_data'=>$getData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
					'cal_local_method'=>'SendcartFile',
				),
//				'end'=>array(
//					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
//					'cal_local_method'=>'applyEnd',	//使用本地方法
//					'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
//				),
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>empty($bindPlayerIdInfo)?0:1,
			'player_info'=>$postData['playerId'],
		);
		$modelApply = $this->_getGlobalData('Model_Apply','object');
		return $modelApply->AddApply($applyData);
	}
	/**
	 * 不发邮件时间清空标题、内容
	 * @param array $data
	 */
	private function _isSendMail(&$data){
		if(intval($_POST['isSendMail'])==0){
			unset($data['mailTitle'],$data['mailContent']);
		}
	}
	
	private function _FileUpload() {
		
		$uploadDir 	= 	RUNTIME_DIR . '/Excel/' . date ( 'Ymd',CURRENT_TIME);
			
		if (! file_exists ( $uploadDir ))
			mkdir ( $uploadDir, 0777,true );
		$extArr = array ('txt');
		if (empty ( $_FILES ) === false) {
			$fileName = $_FILES ['file'] ['name'];
			$tmpName = $_FILES ['file'] ['tmp_name'];
			$fileSize = $_FILES ['file'] ['size'];
			if (! $fileName)
				return false;
			if (is_writable ( $uploadDir ) === false)
				return false;
			if (is_uploaded_file ( $tmpName ) === false)
				return false;
			$tempArr = explode ( '.', $fileName );
			$fileExt = array_pop ( $tempArr );
			$fileExt = strtolower ( trim ( $fileExt ) );
			if (in_array ( $fileExt, $extArr ) === false)
				return false;
			$newFileName = date ( 'His' ) . '_' . rand ( 10000, 99999 ) . ".{$fileExt}";
			$filePath = $uploadDir . "/{$newFileName}";
			if (move_uploaded_file ( $tmpName, $filePath ) === false)
				return false;
			$fileUrl = $uploadDir . "/{$newFileName}";
			return $fileUrl;
		}
	}
}