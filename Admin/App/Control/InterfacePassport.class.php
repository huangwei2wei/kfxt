<?php
/**
 * passport接口
 * @author php-朱磊
 */
class Control_InterfacePassport extends Control {
	
	//密钥
	private static $_API_KEY = 'a3d!#!WS';
	//成功标识
	private static $_API_SUCCESS = 1;
	//失败标识
	private static $_API_FAILED = - 1;
	
	private static $_MD5_KEY = 'asd3d!#!ASd4';
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;
	/**
	 * 权限工具类
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function __construct() {
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}
	
	/**
	 * 登录
	 */
	public function actionLogin() {
		//获取passport发过来的参数
		$uname = $_GET ['uname']; //用户名
		$password = $_GET ['pwd']; //密码
		$sign = $_GET ['sign']; //签名
		$sys = $_GET ['sys']; //子系统标识名
		$pwds = $this->_authcode ( $password, 'DECODE', self::$_API_KEY ); //加密后的密码
		$destime = $this->_authcode ( $_GET ['desTime'], 'DECODE', self::$_API_KEY );
		$sign2 = md5 ( $uname . $pwds . $destime . self::$_MD5_KEY );
		
		$check_uname_url = "http://pass.uwan.com/validate.aspx?uname=" . urlencode ( $uname ) . "&destime=" . urlencode ( $_GET ['desTime'] ) . "&despwd=" . urlencode ( $password ) . "&sys=" . $sys;
		$apply = file_get_contents ( $check_uname_url );
		if ($apply == "1") {
			//passport验证成功
			if ($sign == $sign2) {
				//签名正确
				//本系统进行用户验证
				$uname=strtolower($uname);//小写
				$userInfo = $this->_modelUser->findByUserName ( $uname );
				
				if (! is_array ( $userInfo )) {
					
					//用户不存在
					//创建最低权限用户
					$user = array ();
					$user ['org_id'] = 0; //组ID
					$user ['department_id'] = 0; //部门ID
					$user ['roles'] = 'guest'; //角色
					$user ['nick_name'] = $uname; //昵称
					$user ['user_name'] = $uname; //用户名
					$user ['password'] = md5 ( $password );
					$user ['date_created'] = CURRENT_TIME; //创建时间
					$user ['date_updated'] = $user ['date_created']; //更新时间
					$user ['order_vip_level']='0,1,2,3,4,5,6';
					$user ['status']='1';
					if (! $this->_utilRbac->createUser ( $user )) {
						//创建用户失败
						echo "validate('" . $sys . "'," . self::$_API_FAILED . ");";
						exit ();
					}
				}
				//设置成已登录
				$this->_utilRbac->setLogin ( $uname );
				$utilOnline = $this->_getGlobalData ( 'Util_Online', 'object' );
				$utilOnline->setOnlineUser ( $uname ); //设置在线用户
				Tools::setHeadP3P();
				echo "validate('" . $sys . "'," . self::$_API_SUCCESS . ");";
			} else {
				//登录失败
				echo "validate('" . $sys . "'," . self::$_API_FAILED . ");";
				exit ();
			}
		} else {
			//验证失败 
			echo "validate('" . $sys . "'," . self::$_API_FAILED . ");";
			exit ();
		}
	
	}
	
	/**
	 * 加密OR解密
	 * @param string $string	原文
	 * @param string $operation DECODE=加密
	 * @param string $key 密钥
	 * @param int $expiry
	 */
	private function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		
		$key = md5 ( $key ? $key : API_KEY );
		$keya = md5 ( substr ( $key, 0, 16 ) );
		$keyb = md5 ( substr ( $key, 16, 16 ) );
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
		
		$cryptkey = $keya . md5 ( $keya . $keyc );
		$key_length = strlen ( $cryptkey );
		
		$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
		$string_length = strlen ( $string );
		
		$result = '';
		$box = range ( 0, 255 );
		
		$rndkey = array ();
		for($i = 0; $i <= 255; $i ++) {
			$rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
		}
		
		for($j = $i = 0; $i < 256; $i ++) {
			$j = ($j + $box [$i] + $rndkey [$i]) % 256;
			$tmp = $box [$i];
			$box [$i] = $box [$j];
			$box [$j] = $tmp;
		}
		
		for($a = $j = $i = 0; $i < $string_length; $i ++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box [$a]) % 256;
			$tmp = $box [$a];
			$box [$a] = $box [$j];
			$box [$j] = $tmp;
			$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
		}
		
		if ($operation == 'DECODE') {
			if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
				return substr ( $result, 26 );
			} else {
				return '';
			}
		} else {
			return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
		}
	}
}