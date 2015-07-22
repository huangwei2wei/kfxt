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
	 *
	 * @return boolean
	 */
	public static function isAjax() {
		if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * 引入应用程序文件
	 * @example Tools::import('Control.Porject'); //引入Control层Control_Porject_class.php文件
	 * @param string $string
	 */
	public static function import($string) {
		if (! $string)
			return false;
		$filePath = explode ( '_', $string );
		$includePath = APP_PATH;
		foreach ( $filePath as $value ) {
			$includePath .= '/' . ucwords ( $value );
		}
		$includePath .= '.class.php';
		if (! file_exists ( $includePath ))
			//@todo 错误类,未完成
			throw new Error ( 'import error : ' . $includePath );
		return include_once $includePath;
	}

	/**
	 * 调试用,测试数据
	 *
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
					$urlString = __ROOT__ . "/sftxadmin.php?c={$control}&a={$action}{$getParemeter}";
					return $urlString;
					break;
				}
			case '2' : //@todo 未完成
				{
					break;
				}
		}
		return $urlString = __ROOT__ . "/sftxadmin.php?c={$control}&a={$action}{$getParemeter}";
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
	 * 通过给定的日期计算数秒
	 * @param array $date
	 * @example <br />
	 * $time=Tools::getSecond(array('min'=>20,'hour'=>3,'day'=>2)); //获取2天3小时20分钟的秒数
	 */
	/*
	public static function getSecond($date){
		$second=0;
		foreach ($date as $key=>$value){
			switch ($key){
				case 'min' :{//分钟
					$second+=intval($value)*60;
					break;
				}
				case 'hour' :{
					$second+=intval($value)*60*60;
					break;
				}
				case 'day' :{
					$second+=intval($value)*60*60*24;
					break;
				}
			}
		}
		return $second;
	}*/

	/**
	 * 验证码检测
	 * @param 用户验证的
	 * @return boolean
	 */
	/*
	public static function checkVerifyCode($imgCode){
		self::_loadCore('Help_ImgCode');
		$helpImgCode=new Help_ImgCode();
		if ($helpImgCode->check($imgCode)) {
			return true;
		}else {
			return false;
		}
	}*/

	/**
	 * 断点续传函数
	 * @example dl_file_resume("1.zip");//同级目录的1.zip 文件
	 * @param fileName $file
	 */
	public function dl_file_resume($file) {

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
		$newFilterArr = array ('a' => '', 'c' => '' );
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
		if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
			$ip = $_SERVER ['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return ($ip);
	}

	// +----------------------------------------------------------------------+
	// | Willko Framework                                                     |
	// +----------------------------------------------------------------------+
	// | Copyright (c) 2008-2009 Willko Cheng                                 |
	// +----------------------------------------------------------------------+
	// | Authors: Willko Cheng <willko@foxmail.com>                           |
	// +----------------------------------------------------------------------+
	// $string 明文 或 密文
	// $isEncrypt 是否加密
	// $key 密匙
	// 采用SHA1生成密匙簿，超过300个字符使用ZLIB压缩
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

}
