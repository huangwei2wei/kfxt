<?php
/**
 * 常用工具类
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 *
 */
class Tools extends Base {
	
	/**
	 * 功能：过滤get,post,cookie值
	 */
	public static function trimValue() {
		foreach ( $_REQUEST as &$value ) {
			$value = trim ( $value );
		}
	}
	
	/**
	 * 强制整型
	 * @param string $value
	 */
	public static function coerceInt($value){
		return abs(intval($value));
	}
	
	/**
	 * 判断是否为ajax请求,注:此方便只适用Jquery
	 * @return boolean
	 */
	public static function isAjax() {
		return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest');
	}
	
	/**
	 * 引入应用程序文件
	 * @example Tools::import('Control_Porject'); //引入Control层Control_Porject.class.php文件
	 * @param string $string
	 */
	public static function import($string,$extension='.class.php') {
		if (! $string)
			return false;
		$filePath = explode ( '_', $string );
		$includePath = APP_PATH;
		foreach ( $filePath as $value ) {
			$includePath .= '/' . ucwords ( $value );
		}
		$includePath .= $extension;
		if (! is_file ( $includePath )){
			//@todo 错误类,未完成
			throw new Error ( 'import error : ' . $includePath );
		}
		return include_once $includePath;
	}
	
	/**
	 * 调试用,测试数据
	 * @param string|array $arr
	 */
	public static function dump($arr, $return = null) {
		if (is_array ( $arr ) || is_object ( $arr )) {
			echo '<pre>';
			print_r ( $arr, $return );
			echo '</pre>';
		} else {
			echo $arr . '<br>';
		}
	}
	
	/**
	 * 替换空格,\n 为html代码 
	 * @param string $string
	 */
	public static function convertHtml($string) {
//		$string = str_replace ( ' ', '&nbsp;', $string );
		return nl2br ( $string );
	}
	
	/**
	 * 数组转换url函数
	 *
	 * @param Array $arr
	 * @return String
	 */
	private static function _convertUrlParemeter($arr) {
		$urlString = '';
		foreach ( $arr as $key => $value ) {
			$urlString .= "&{$key}={$value}";
		}
		return $urlString;
	}
	
	/**
	 * 页面跳转函数
	 *
	 * @param String $url
	 * @param int $time
	 * @param String $msg
	 */
	public static function redirect($url, $time = 0, $msg = '') {
		//多行URL地址支持
		$url = str_replace ( array ("\n", "\r" ), '', $url );
		if (empty ( $msg ))
			$msg = "系统将在{$time}秒之后自动跳转到{$url}！";
		if (! headers_sent ()) {
			// redirect
			if (0 === $time) {
				header ( "Location: " . $url );
			} else {
				header ( "refresh:{$time};url={$url}" );
				echo ($msg);
			}
			exit ();
		} else {
			$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if ($time != 0)
				$str .= $msg;
			exit ( $str );
		}
	}
	
	/**
	 * url生成函数
	 *
	 * @param String $control
	 * @param String $action
	 * @param Array $getArr
	 * @return String URL函数
	 */
	public static function url($control, $action, $getArr = null) {
		$control = ucwords ( $control );
		$action = ucwords ( $action );
		if (is_array ( $getArr ))
			$getParemeter = self::_convertUrlParemeter ( $getArr );
		switch (URL_MODE) {
			case '1' :
				{
					$urlString = __ROOT__ . "/admin.php?c={$control}&a={$action}{$getParemeter}";
					return $urlString;
					break;
				}
			case '2' : //@todo 未完成
				{
					break;
				}
		}
		return $urlString = __ROOT__ . "/admin.php?c={$control}&a={$action}{$getParemeter}";
	}
	
	/**
	 * 功能：综合提示JS代码输出
	 * 参数 $msg 为提示信息,如果等于空将不弹出提示框
	 * $direct 为提示类型 0为提示 1为提示刷新返回　2为提示返回
	 * 输出提示代码并结束程序false为默认,为不提示直接跳到指定页面
	 */
	public static function alertMsg($msg = false, $direct = "0") {
		switch ($direct) {
			case '0' : //提示
				$script = "";
			case '1' : //提示刷新返回
				$script = "location.href=\"" . $_SERVER ["HTTP_REFERER"] . "\";";
				break;
			case '2' : //提示返回
				$script = "history.back();";
				break;
			default : //提示转向指定页面
				$script = "location.href=\"" . $direct . "\";";
		}
		if ($msg == false) {
			echo "<script language='javascript'>" . $script . "</script>";
		} else {
			echo "<script language='javascript'>window.alert('" . $msg . "');" . $script . "</script>";
		}
	}
	
	/**
	 * 功能：获取用户IP
	 */
	public static function getUserIp() {
		$ip = false;
		if ($_SERVER ['HTTP_X_FORWARDED_FOR'] != "") {
			$REMOTE_ADDR = $_SERVER ['HTTP_X_FORWARDED_FOR'];
			$tmp_ip = explode ( ",", $REMOTE_ADDR );
			$ip = $tmp_ip [0];
		}
		return ($ip ? $ip : $_SERVER ['REMOTE_ADDR']);
	}
	
	/**
	 * 判断是否为utf8格式
	 *
	 * @param String $word
	 * @return boolean
	 */
	public static function isUtf8($word) {
		if (preg_match ( "/^([" . chr ( 228 ) . "-" . chr ( 233 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}){1}/", $word ) == true || preg_match ( "/([" . chr ( 228 ) . "-" . chr ( 233 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}){1}$/", $word ) == true || preg_match ( "/([" . chr ( 228 ) . "-" . chr ( 233 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}[" . chr ( 128 ) . "-" . chr ( 191 ) . "]{1}){2,}/", $word ) == true) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 根据开始时间与结束时间返回可显示的秒数
	 * @param int $startTime 时间戳
	 * @param int $endTime 时间戳
	 * @param string $format
	 */
	public static function calculateTimePast($startTime, $endTime, $format = 's秒前') {
		$timeSpan = $endTime - $startTime;
		if ($timeSpan > 60)
			$format = 'i分s秒前';
		if ($timeSpan > 60 * 60)
			$format = 'H小时i分s秒前';
		if ($timeSpan > 24 * 60 * 60)
			$format = 'd天H小时i分s秒前';
		return gmdate ( $format, $timeSpan );
	}
	
	/**
	 * 根据秒数来转换时间
	 * @param $time
	 */
	public static function getTimeFormat($time){
		$format = 's秒';
		if ($time > 60)
			$format = 'i分s秒';
		if ($time > 60 * 60)
			$format = 'H小时i分s秒';
		if ($time > 24 * 60 * 60)
			$format = 'd天H小时i分s秒';
		return gmdate ( $format, $time );
	}
	
	/**
	 * 检测是否超时
	 */
	public static function isTimeOut($time,$timeout){
		$timeout=$time+$timeout;
		if (CURRENT_TIME>$timeout){
			return true;
		}else {
			return $timeout-CURRENT_TIME;
		}
	}
	
	/**
	 * 根据开始时间和结束时间返回数组
	 * @param int $startTime
	 * @param int $endTime
	 * @return array
	 */
	public static function getdateArr($startTime,$endTime,$format='Y-m-d'){
		if (is_string($startTime))$startTime=strtotime($startTime);
		if (is_string($endTime))$endTime=strtotime($endTime);
		$dayArr=array();
		while (true){
			$dayArr[date($format,$startTime)]=true;
			$startTime=strtotime('+1 day',$startTime);
			if (date('Ymd',$startTime)>date('Ymd',$endTime))break;
		}
		return $dayArr;
	}
	
	/**
	 * 断点续传函数
	 * @example dl_file_resume("1.zip");//同级目录的1.zip 文件
	 * @param fileName $file
	 */
	public static function dl_file_resume($file) {
		
		//检测文件是否存在
		if (! is_file ( $file )) {
			die ( "<b>404 File not found!</b>" );
		}
		
		$len = filesize ( $file ); //获取文件大小
		$filename = basename ( $file ); //获取文件名字
		$file_extension = strtolower ( substr ( strrchr ( $filename, "." ), 1 ) ); //获取文件扩展名
		

		//根据扩展名 指出输出浏览器格式
		switch ($file_extension) {
			case "exe" :
				$ctype = "application/octet-stream";
				break;
			case "zip" :
				$ctype = "application/zip";
				break;
			case "mp3" :
				$ctype = "audio/mpeg";
				break;
			case "mpg" :
				$ctype = "video/mpeg";
				break;
			case "avi" :
				$ctype = "video/x-msvideo";
				break;
			default :
				$ctype = "application/force-download";
		}
		
		//Begin writing headers
		header ( "Cache-Control:" );
		header ( "Cache-Control: public" );
		
		//设置输出浏览器格式
		header ( "Content-Type: $ctype" );
		if (strstr ( $_SERVER ['HTTP_USER_AGENT'], "MSIE" )) { //如果是IE浏览器
			# workaround for IE filename bug with multiple periods / multiple dots in filename
			# that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
			$iefilename = preg_replace ( '/\./', '%2e', $filename, substr_count ( $filename, '.' ) - 1 );
			header ( "Content-Disposition: attachment; filename=\"$iefilename\"" );
		} else {
			header ( "Content-Disposition: attachment; filename=\"$filename\"" );
		}
		header ( "Accept-Ranges: bytes" );
		
		$size = filesize ( $file );
		//如果有$_SERVER['HTTP_RANGE']参数
		if (isset ( $_SERVER ['HTTP_RANGE'] )) {
			/*   ---------------------------
			   Range头域 　　Range头域可以请求实体的一个或者多个子范围。
			   例如， 　　表示头500个字节：bytes=0-499
			   			  表示第二个500字节：bytes=500-999
			   			  表示最后500个字节：bytes=-500
			   			  表示500字节以后的范围：bytes=500-
			   			  第一个和最后一个字节：bytes=0-0,-1
			   			  同时指定几个范围：bytes=500-600,601-999
			   	但是服务器可以忽略此请求头，如果无条件GET包含Range请求头，
			   	响应会以状态码206（PartialContent）返回而不是以200 （OK）。
			---------------------------*/
			
			// 断点后再次连接 $_SERVER['HTTP_RANGE'] 的值 bytes=4390912-
			

			list ( $a, $range ) = explode ( "=", $_SERVER ['HTTP_RANGE'] );
			//if yes, download missing part
			str_replace ( $range, "-", $range ); //这句干什么的呢。。。。
			$size2 = $size - 1; //文件总字节数
			$new_length = $size2 - $range; //获取下次下载的长度
			header ( "HTTP/1.1 206 Partial Content" );
			header ( "Content-Length: $new_length" ); //输入总长
			header ( "Content-Range: bytes $range$size2/$size" ); //Content-Range: bytes 4908618-4988927/4988928   95%的时候
		} else { //第一次连接
			$size2 = $size - 1;
			header ( "Content-Range: bytes 0-$size2/$size" ); //Content-Range: bytes 0-4988927/4988928
			header ( "Content-Length: " . $size ); //输出总长
		}
		//打开文件
		$fp = fopen ( "$file", "rb" );
		//设置指针位置
		fseek ( $fp, $range );
		//虚幻输出
		while ( ! feof ( $fp ) ) {
			//设置文件最长执行时间
			set_time_limit ( 0 );
			print (fread ( $fp, 1024 * 8 )) ; //输出文件
			flush (); //输出缓冲
			ob_flush ();
		}
		fclose ( $fp );
		exit ();
	}
	
	public static function passport_encrypt($txt, $key, $encrypt_key = '') {
		if ($encrypt_key == '')
			$encrypt_key = md5 ( microtime ( true ) );
		$ctr = 0;
		$tmp = '';
		$tl = strlen ( $txt );
		for($i = 0; $i < $tl; $i ++) {
			$ctr = $ctr == 32 ? 0 : $ctr;
			$tmp .= $encrypt_key [$ctr] . ($txt [$i] ^ $encrypt_key [$ctr ++]);
		}
		return self::gf_base64_encode ( self::passport_key ( $tmp, $key ) ); //R 真正使用密码加密
	}
	
	public static function gf_base64_encode($plain_str) {
		return str_replace ( array ('=', '+', '/' ), array (',', '_', '(' ), base64_encode ( $plain_str ) );
	}
	
	public static function gf_base64_decode($encode_str) {
		return base64_decode ( str_replace ( array (',', '_', '(' ), array ('=', '+', '/' ), $encode_str ) );
	}
	
	public static function base16_encode($s) {
		$TranTable = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P' );
		$ret = '';
		$ord = 0;
		if ($s == '')
			return '';
		$l = strlen ( $s );
		for($i = 0; $i < $l; $i ++) {
			$ord = ord ( $s [$i] );
			$ret .= $TranTable [($ord & 0xF0) >> 4] . $TranTable [$ord & 0x0F];
		}
		return $ret;
	}
	
	public static function base16_decode($s) {
		$TranTable = array ('A' => 0, 'B' => 0x1, 'C' => 0x2, 'D' => 0x3, 'E' => 0x4, 'F' => 0x5, 'G' => 0x6, 'H' => 0x7, 'I' => 0x8, 'J' => 0x9, 'K' => 0xA, 'L' => 0xB, 'M' => 0xC, 'N' => 0xD, 'O' => 0xE, 'P' => 0xF );
		$ret = '';
		$ord = 0;
		if ($s == '')
			return '';
		$l = strlen ( $s );
		for($i = 0; $i < $l; $i += 2) {
			$ret .= chr ( $TranTable [$s [$i]] << 4 | $TranTable [$s [$i + 1]] );
		}
		return $ret;
	}
	
	public static function passport_decrypt($txt, $key) {
		$txt = self::passport_key ( self::gf_base64_decode ( $txt ), $key );
		$tmp = '';
		for($i = 0; $i < strlen ( $txt ); $i ++) {
			$tmp .= $txt [$i] ^ $txt [++ $i];
		}
		return $tmp;
	}
	
	public static function passport_key($txt, $encrypt_key) {
		
		$encrypt_key = md5 ( $encrypt_key );
		$ctr = 0;
		$tmp = '';
		$tl = strlen ( $txt );
		for($i = 0; $i < $tl; $i ++) {
			$ctr = $ctr == 32 ? 0 : $ctr;
			$tmp .= $txt [$i] ^ $encrypt_key [$ctr ++];
		}
		return $tmp;
	}
	
	/**
	 * 过虑Request参数并生成新的数组
	 * add by jacky
	 * @param array $filterArr 需要过渡的参数，默认过滤c和a两个参数
	 */
	public static function getFilterRequestParam($filterArr = array()) {
		$newFilterArr = array ('a' => '', 'c' => '' ,'zp'=>'','__game_id'=>'');
		if (is_array ( $filterArr )) {
			foreach ( $filterArr as $v ) {
				$newFilterArr [$v] = $v;
			}
		}
		
		foreach ( $_GET as $k => $v ) {
			if (! array_key_exists ( $k, $newFilterArr )) {
				if (is_array ( $v )) {
					//数组参数
					foreach ( $v as $tempK => $tempV ) {
						$newArray [$k . '[' . $tempK . ']'] = $tempV;
					}
				} else {
					$newArray [$k] = $v;
				}
			}
		}
		foreach ( $_POST as $k => $v ) {
			if (! array_key_exists ( $k, $newFilterArr )) {
				if (is_array ( $v )) {
					//数组参数
					foreach ( $v as $tempK => $tempV ) {
						$newArray [$k . '[' . $tempK . ']'] = $tempV;
					}
				} else {
					$newArray [$k] = $v;
				}
			}
		}
		return $newArray;
	}

	/**
	 * 获取客户端IP地址
	 */
	public static function getClientIP() {
		if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			return getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			return getenv('HTTP_CLIENT_IP');
		}elseif(getenv('HTTP_CDN_SRC_IP') && strcasecmp(getenv('HTTP_CDN_SRC_IP'), 'unknown')) {
			return getenv('HTTP_CDN_SRC_IP');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			return getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			return $_SERVER['REMOTE_ADDR'];
		}
//		if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
//			$ip = getenv ( "HTTP_CLIENT_IP" );
//		else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
//			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
//		else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
//			$ip = getenv ( "REMOTE_ADDR" );
//		else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
//			$ip = $_SERVER ['REMOTE_ADDR'];
//		else
//			$ip = "unknown";
//		return ($ip);
	}

	/**
	 * 加解密字符串,彩用sha1生成密钥,超过300字符使用zlib压缩
	 * @param $string 明文 或 密文
	 * @param $isEncrypt 是否加密
	 * @param $key 密匙
	 */
	public static function dencrypt($string, $isEncrypt = true, $key = KEY_SPACE) {
		if (! isset ( $string {0} ) || ! isset ( $key {0} )) {
			return false;
		}
		$dynKey = $isEncrypt ? hash ( 'sha1', microtime ( true ) ) : substr ( $string, 0, 40 );
		$fixedKey = hash ( 'sha1', $key );
		$dynKeyPart1 = substr ( $dynKey, 0, 20 );
		$dynKeyPart2 = substr ( $dynKey, 20 );
		$fixedKeyPart1 = substr ( $fixedKey, 0, 20 );
		$fixedKeyPart2 = substr ( $fixedKey, 20 );
		$key = hash ( 'sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2 );
		$string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : (isset ( $string {339} ) ? gzuncompress ( base64_decode ( substr ( $string, 40 ) ) ) : base64_decode ( substr ( $string, 40 ) ));
		$n = 0;
		$result = '';
		$len = strlen ( $string );
		
		for($n = 0; $n < $len; $n ++) {
			$result .= chr ( ord ( $string {$n} ) ^ ord ( $key {$n % 40} ) );
		}
		return $isEncrypt ? $dynKey . str_replace ( '=', '', base64_encode ( $n > 299 ? gzcompress ( $result ) : $result ) ) : substr ( $result, 20, - 20 );
	}
	
	public static function setHeadP3P(){
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	}
	
	/**
	 * 将utf8转换为bg2312
	 */
	public static function convertGBK($string){
		return iconv('UTF-8','GB2312',$string);
	}
	
	/**
	 * 记录系统日志
	 * @param string $msg
	 * @param bollean $constraint 是否强制插入
	 */
	public static function addLog($msg=NULL,$constraint=FALSE){
		static $isAdd=false;//单例,只插入一次
		if ($isAdd && $constraint==false)return ;
		self::import('Model_Log');
		$modelLog=new Model_Log();
		$modelLog->add($msg,$constraint);
		$isAdd=true;
	}
	
	/**
	 * 格式化日志
	 * @param array $logArr 只能是一维数组
	 */
	public static function formatLog($logArr){
		$string='<ul>';
		foreach ($logArr as $msg){
			$string.="<li>{$msg}</li>";
		}
		$string.='</ul>';
		return $string;
	}
	
	/**
	 * 获取语言包
	 * @param string $key
	 * @param string $fileName	文件名称
	 * @param array $variable	需要替换的变量数组.
	 */
	public static function getLang($key,$fileName,$variable=NULL){
		$key=strtoupper($key);
		$langArr=self::_getGlobalData($fileName,'lang');
		if ($variable===null)return $langArr[$key];
		foreach ($variable as $k=>$v){
			$langArr[$key]=str_replace("{{$k}}",$v,$langArr[$key]);
		}
		return $langArr[$key];
	}
	
	/**
	 * 获取语言包里游戏的配置
	 * @param string $key
	 * @param string $fileName	文件名称
	 */
	public static function gameConfig($key=null,$gameId=0){
		if(!$key || !$gameId){
			return false;
		}
		$langArr=self::_getGlobalData('Game/'.$gameId,'lang');
		return $langArr[$key];
	}
	
	
	/**
	 * 递归遍历数组
	 * @param array $array 引用数组
	 * @param array $functions  处理函数数组
	 */
	public static function arrayMap(&$array,$functions){
		foreach ( $array as $key => &$val ) {
			if (is_array ( $val )) {
				self::arrayMap ( $val ,$functions);
			} else {
				foreach ($functions as $fun){
					$val=$fun($val);
				}
			}
		}
	}
	
	/**
	 * 通过字符串返回数字
	 * @param string $string
	 */
	public static function getNum($string){
		$numArr=array(48,49,50,51,52,53,54,55,56,57);//数值范围
		$num='';
		for ($i=0;$i<strlen($string);$i++){
			if (in_array(ord($string{$i}),$numArr))$num.=$string{$i};
		}
		return $num;
	}
	
	/**
	 * 获取机随字母
	 * @param int $len 长度
	 * @param int $wholeWord 1:大写,2:小写,默认小写 
	 */
	public static function getRandCode($len,$wholeWord=2){
		$str='';
		if ($wholeWord==1){
			$rand=array(97,122);
		}else {
			$rand=array(65,90);
		}
		for ($i=0;$i<$len;$i++){
			$str.=chr(mt_rand($rand[0],$rand[1]));
		}
		return $str;
	}
	
	/**
	 * 字段过滤
	 * @param string_or_array $NeedField
	 * @param array $DataIn
	 */
	public static function fieldFilter($NeedFields = array(),$DataIn = array()){
		if(count($DataIn)==0)return $DataIn;
		$DataOut = array();
		if(is_array($NeedFields) && count($NeedFields)>0){
			foreach($NeedFields as $formfield =>$needfield){
				if(isset($DataIn[$formfield])){
					$DataOut[$needfield] = $DataIn[$formfield];
				}
				else{
					$DataOut[$needfield] = NULL;
				}
			}
		}
		elseif(is_string($NeedFields)){
			$NeedFields = explode(',',$NeedFields);
			foreach($NeedFields as $field){
				if(isset($DataIn[$field])){
					$DataOut[$field] = $DataIn[$field];
				}
				else{
					$DataOut[$field] = NULL;
				}
			}
		}
		return $DataOut;
	}
	
	/**
	 * 将double型转化成字符串
	 * @param $digital
	 */
	public static function d2s($digital){
		if(is_double($digital)){
			$digital = rtrim(ltrim(serialize($digital),'d:'),';');
		}else{
			$digital = strval($digital);
		}
		return $digital;
	}
	
	/**
	 * 数组验证
	 *  $validate举例
	 * 	$validate = array(
	 *		'type'=>'intval',//最简单的函数判断
	 *		'name'=>array('trim','名字不能为空'),//自定义提示
	 *		'goldValue'=>array(array('max','###',0),'必须大于0'),//含1个参数，其中'###'代替原数组值
	 *		'bindType'=>array(array('in_array','###',array(0,1,2)),'绑定类型超出范围'),//含多个参数
	 *		'expire'=>array(array(array($this->_gameObject,'timeChk'),'###','参数2'),'时间类型错误'),//使用对象
	 *	);
	 * @param array $arr
	 * @param array $validate
	 */
	public static function arrValidate($arr=array(),$validate=array()){
		if(!is_array($validate)){
			return "检查参数不是数组";
		}
		foreach($validate as $field =>$method){
			if($method){
				if(is_string($method)){
					$method = array($method);	//将兼容的简洁模式拼合成下面的可用模式
				}
				if(!is_array($method)){
					continue;
				}
			}else{
				continue;
			}
			if(!isset($arr[$field])){
				return "被检查数组不存在{$field}字段";
			}
			$funcInfo = current($method);
			if(!$funcInfo){
				continue;
			}
			$errorInfo = next($method);
			$errorInfo = $errorInfo?$errorInfo:"{$field}字段错误";	//如果没有错误描述，就使用默认的
			//整理出函数(方法)和参数
			if(is_string($funcInfo)){
				$paramArrs = array($arr[$field]);
				$func = $funcInfo;
			}elseif(is_array($funcInfo)){
				$paramArrs = $funcInfo;
				$func = array_shift($paramArrs);
				foreach($paramArrs as &$param){
					if($param==='###'){
						$param = $arr[$field];
					}
				}
			}
			//检查函数(方法)的是否有效
			if(is_string($func)){
				if(!function_exists($func)){
					return "{$func}函数不存在";
				}
			}elseif(is_array($func)){
				if(!method_exists($func[0],$func[1]) || !is_callable($func)){
					return "不能访问".get_class($func[0])."::{$func[1]}";
				}
			}else{
				return "未知错误函数".var_export($func,true);
			}
			//调用函数(方法)检查
			if(!call_user_func_array($func,$paramArrs)){
				return $errorInfo;
			}
		}//foreach
		return true;
	}
	
	public static function htmlPage($url='',$get=array(),$post=array(),$isReturn = true){
		if($get && is_array($get)){
			$get = self::buildHttpData($get);
			if(strpos($url,'?')){
				$url .='&'.http_build_query($get);
			}else{
				$url .='?'.http_build_query($get);
			}
		}
		if($post && is_array($post)){
			$post = self::buildHttpData($post);
		}
		$method = $post?'post':'get';
		$str ="url :{$url}<br>";
		$str .="
		<form action='{$url}' method='{$method}'>
		";
		if($post){
			foreach($post as $key => $val){
				$str.="
				{$key} <input type='text' name='{$key}' value='{$val}' /><br>
				";
			}
		}
		$str.="
		<input type='submit' />
		</form>
		";
		if($isReturn){
			return $str;
		}
		echo $str;
	}
	
	public static function buildHttpData($data){
		$returnData = array();
		if($data && is_array($data)){
			while(false !== list($key,$val) = each($data)){
				if(is_array($val)){
					foreach ($val as $subKey => $subVal){
						$data["{$key}[{$subKey}]"] = $subVal;
					}
				}else{
					$returnData[$key] = $val;
				}
			}
		}
		return $returnData;
	}
}
