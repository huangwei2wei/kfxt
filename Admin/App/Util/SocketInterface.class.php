<?php
/* 
 * @author 黄巍
 * @return String || -1 链接失败  || -2 写数据失败  ||-3 读取数据超时
 * 
 *  */
class Util_SocketInterface extends Base {
	private $_domain;
	private $_urlPort;
	private $_timeout;
	private $_errorNo = null;
	private $_errorDesc = null;
	private $_connect = null;
	private $_headerLen;
	
	private function init($domain,$port,$errorNo,$errorDesc,$timeout,$headerLen){
		$this->_url = $domain;
		$this->_urlPort = $port;
		$this->_errorNo = $errorNo;
		$this->_errorDesc = $errorDesc;
		$this->_timeout = $timeout;
		$this->_headerLen = $headerLen;
	}
	private function connect(){
		$this->_connect = fsockopen($this->_url, $this->_urlPort, $this->_errorNo, $this->_errorDesc, $this->_timeout);
		if ($this->is_connected() == false){
// 			echo "Error:----- $this->_errorNo: $this->_errorDesc\n";
			return -1;
		}
		stream_set_timeout($this->_connect, ceil($this->_timeout/3));
		return true;
	}
	private function is_connected(){
		return $this->_connect?true:false;
	}
	private function writeData($sendData){
		$result = fwrite($this->_connect,$sendData,strlen($sendData));
		if ($result === false){
			return -2;
		}else{
			return true;
		}
	}
	private function readData(){
		$header = fread($this->_connect, $this->_headerLen);
		$status = stream_get_meta_data($this->_connect);
		if ($status['timed_out']) {
			return -3;
		}
		$header = unpack('S*',$header);
		$len = $header[1] - $this->_headerLen;
		$body = '';
		while ($len > 0 ) {
			$buf = fread($this->_connect, $len);
			$len -= strlen($buf);
			$body .= $buf;
		}
// 		$body = unpack('A*', $body);
		return $body;
	}
	private function close(){
		try {
			if ($this->_connect){
				fclose($this->_connect);
				return true;
			}
		} catch(Exception $e) {
// 			echo $e->getMessage();
			return false;
		}
	}
	public function result($domain,$port,$sendData,$errorNo=null,$errorDesc=null,$timeout=20,$headerLen=12){
		$this->init($domain,$port,$errorNo,$errorDesc,$timeout,$headerLen);
		$c = $this->connect();
		if($c !== true){
			return $c;
		}
		$w = $this->writeData($sendData);
		if($w !== true){
			return $w;
		}
		$result = $this->readData();
		if($this->is_connected()){
			$this->close();
		}
// 		var_dump($result);exit;
		return $result;
	}
}