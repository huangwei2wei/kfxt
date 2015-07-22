<?php
Tools::import('Game_GameInterface','.interface.php');
abstract class Game_GameBase extends Base implements GameInterface{

	/*------------以下是对象参数:在子类中用_init()配置------------*/
	protected $_gameId;			//游戏Id
	protected $_zp;				//控制器扩展包
	protected $_key='game_key';	//游戏密匙
	protected $_timer=false;	//是否使用定时器
	protected $_urlApdWO=array(	//使用定时器的工单请求地址附加字符
			'new'=>'',
			'newCbk'=>'',
			'del'=>'',
			'delCbk'=>'',
			'ev'=>'',
			'evCbk'=>'',
	);
	protected $_contentSelf = false;	//是否使用每个回复内容都在一条记录中
	protected $_preImgPath='';//前置图片地址,{server_url}代替所设地址,eg.:'{server_url}upload/images/'
	protected $_isSendOrderReplay = true;	//是否发送回复至游戏
	protected $_sendImage =false;	//是否回复时发送图片
	/*------------以上是对象参数:在子类中用_init()配置------------*/

	//	public function _init(){
	//		//[配置模板]游戏使用这个配置来覆盖父类的_init()
	//		$this->_gameId = 0;		//游戏Id
	//		$this->_zp = '';	//控制器扩展包
	//		$this->_key = '';	//游戏密匙
	//		$this->_timer = true;	//是否使用定时器
	//		$this->_urlApdWO = array(	//使用定时器的工单请求地址附加字符
	//			'new'=>'',
	//			'newCbk'=>'',
	//			'del'=>'',
	//			'delCbk'=>'',
	//			'ev'=>'',
	//			'evCbk'=>'',
	//		);
	//		$this->_contentSelf = false;	//是否使用每个回复内容都在一条记录中
	//		$this->_preImgPath = '';//前置图片地址,{server_url}代替所设地址,eg.:'{server_url}upload/images/'
	//		$this->_isSendOrderReplay = true;	//是否发送回复至游戏
	//	}


	public function __construct(){
		$this->_init();
	}

	/**
	 * 允许直接访问参数
	 * @param $varName
	 */
	protected function __get($varName){
		if(property_exists($this,$varName)){
			return $this->$varName;
		}
		return null;
	}

	/**
	 * 原来Interface一类接口的老验证方式
	 */
	public function commonChk($key = null){
		if(empty($key)){
			$key = TAKE_KEY;
		}
		$sign = trim($_REQUEST ['_sign']);
		$verifyCode = trim($_REQUEST ['_verifycode']);
		if(md5($key.$verifyCode)==$sign) {
			return true;
		}
		return false;
	}

	/**
	 * 验证来自游戏客户端(如:flash前端)的请求
	 * 验证说明：
	 * 	1、双方约定key
	 * 	2、客户端(游戏前端)使用工单、FAQ等功能前，向游戏获得_sign保存起来
	 * 	3、客户端(游戏前端)访问客服后台时，附带验证参数传送
	 * $_REQUEST参数	说明:
	 * 	$_REQUEST['_gameid']	游戏ID由客服系统定义（用于寻找相应的验证接口）
	 * 	$_REQUEST['_unique']	唯一的玩家值，可以是玩家id、玩家账号、玩家昵称 或者 游戏自定义的唯一值
	 * 	$_REQUEST['_sign']		等于md5(客户端IP + _gameid + _unique + key)
	 */
	public function clientChk($_key){
		$ip = Tools::getClientIP();
		$gameId = intval($_REQUEST['_gameid']);
		$uniquePlayer = trim($_REQUEST['_unique']);
		if(empty($_key)){
			$key = $this->_key;
		}else{
			$key	=$_key;
		}
		$sign = trim($_REQUEST['_sign']);

		if(md5($ip.$gameId.$uniquePlayer.$key) == $sign){
			return true;
		}
		return false;
	}

	/**
	 * 验证来自游戏客户端(如:flash前端)的请求
	 * 验证说明：
	 * 1、双方约定key
	 * 2、玩家在客户端(flash前端)点击客服功能窗口时，需向游戏获得_sign保存起来
	 * 3、客户端(flash前端)访问客服后台时，附带验证参数传送
	 * 4、如果客服后台返回错误(例如:客服窗口一直打开着直至超时),客户端(flash前端)重新向游戏获得_sign,并可提示 如”系统繁忙”的信息,接下来玩家可以继续操作。

	 * $_REQUEST参数	说明:
	 *  $_REQUEST['_time']		unxi时间，也用于判断超时
	 * 	$_REQUEST['_gameid']|$_REQUEST['game_id']	游戏ID由客服系统定义（用于寻找相应的验证接口）
	 * 	$_REQUEST['_unique']	唯一的玩家值，可以是玩家id、玩家账号、玩家昵称 或者 游戏自定义的唯一值
	 * 	$_REQUEST['_sign']		等于md5(客户端IP + _gameid + _unique + key)
	 */
	public function clientTimeChk($key=''){
		$time = intval($_REQUEST['_time']);
		if(CURRENT_TIME - $time > 7200){
			return 'TimeOut';
		}
		$gameId = intval($_REQUEST['game_id']);
		$uniquePlayer = trim($_REQUEST['_unique']);
		if(empty($key)){
			$key = $this->_key;
		}
		$sign = trim($_REQUEST['_sign']);
		if(!empty($time) && !empty($gameId) && !empty($uniquePlayer) && !empty($key) && md5($time.$gameId.$uniquePlayer.$key) == $sign){
			return true;
		}
//		echo $time.$gameId.$uniquePlayer.$key."</br>";
//		echo md5($time.$gameId.$uniquePlayer.$key);
//		echo $sign;
		return false;
	}

	/**
	 * 获取动态key
	 * 从一串较长的$key(比如64位)中根据$unixTime时间戳提取出$len长的key,用于加密
	 * @param string $key
	 * @param int $unixTime
	 * @param int $len
	 */
	public function getSubKey($key='',$unixTime=0,$len=20){
		$unixTime = abs(intval($unixTime));
		$keyAllLen = strlen($key);
		if($len>=$keyAllLen){
			$len = $keyAllLen;
		}
		$returnKey = '';
		for($i=0;$i<$len;$i++,$keyAllLen--){
			$site = $unixTime%$keyAllLen;
			$returnKey .= $key[$site];
			$key = substr_replace($key,'',$site,1);
		}
		return $returnKey;
	}

	public function applyEnd($data,$type = 'json'){
		$type = strtolower($type);
		switch ($type){
			case 'json':
			default:
				$data = json_decode($data,true);
		}
		if(!is_array($data)){
			return "<font color='#FF0000'>审核失败:游戏返回数据格式错误</font>";
		}
		if($data['status'] == 1){
			return '<font color="#00FF00">审核成功</font>';
		}
		$errorInfo = $data['info']?":{$data['info']}":'';
		return "<font color='#FF0000'>{$errorInfo}</font>";
	}

	/**
	 * 返回用于签名的数组
	 * @param array $data
	 */
	public function getSignArr($data=array()){
		return array();
	}

	/**
	 * 监听到听没有的方法，则返回false
	 * @param string $m
	 * @param string $a
	 */
	//	public function __call($m,$a) {
	//		return false;
	//	}

	/*
	 * 图片上传
	 * @author warren
	 * @return chat
	 */
	public function ImgUpload() {

		$uploadDir 	= 	UPDATE_DIR . '/Service/' . date ( 'Ymd',CURRENT_TIME);
		//		$saveUrl 	= 	__ROOT__ . '/Upload/Service/' . date ( 'Ymd',CURRENT_TIME);
			
		if (! file_exists ( $uploadDir ))
		mkdir ( $uploadDir, 0777,true );
		$extArr = array ('gif', 'jpg', 'jpeg', 'png', 'bmp' );
		$maxSize = 524288;//1024 * 1024 * 0.5;
		if (empty ( $_FILES ) === false) {
			$fileName = $_FILES ['file_img'] ['name'];
			$tmpName = $_FILES ['file_img'] ['tmp_name'];
			$fileSize = $_FILES ['file_img'] ['size'];
			if (! $fileName)
			return false;
			if (is_writable ( $uploadDir ) === false)
			return false;
			if (is_uploaded_file ( $tmpName ) === false)
			return false;
			if ($fileSize > $maxSize)
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

	/**
	 * 获得运营商配置
	 */
	public function getOptConf($operatorId=0){
		$operatorId = intval($operatorId);
		if($operatorId<=0){
			return false;
		}
		static $operatorExt = array();
		if(isset($operatorExt[$operatorId])){
			return $operatorExt[$operatorId];
		}
		$operatorExt[$operatorId] = array();
		$modelGameOperator = $this->_getGlobalData('Model_GameOperator','object');
		$sql = "
			select 
				*
			from 
			{$modelGameOperator->tName()}
			where 
				game_type_id = {$this->_gameId} and operator_id = {$operatorId}
		";
			$data = $modelGameOperator->select($sql,1);
			if($data['ext']){
				$operatorExt[$operatorId] = unserialize($data['ext']);
			}
			return $operatorExt[$operatorId];
	}
	/**
	 * 获取玩家的信息
	 */
	public function getPlayerDataByAccount($playerAccount = ''){
		return array();
	}

	public function getIfConf(){
		return false;
	}

	public function getNotice($data=array()){
		return false;
	}

	public function modifyNotice($data=array()){
		return false;
	}

	public function delNotice($data=array()){
		return false;
	}

	public function getOneNotice($id){
		return false;
	}

	public function addNotice($data=array()){
		return false;
	}

}