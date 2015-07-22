<?php
/**
 * 加密函数
 * @author php-朱磊
 *
 */
class Decrypt {
	
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
}