<?php


/**
 * TCP接口
 * @author 方华龙
 *
 */
class Util_TcpInterfack{
	var $_server;
	var $_timeout	=	50;
	var $_readsize  =   10240;
	var $_output;
	var $_warning;
	var $_proxy;
	var $_port;
	var $_host;
	var $_key;
	var $_keylen;
	var $_encryptMode;
	var $_charset;
	var $_socket;
	var $_clientid;
	var $_http_version;
	var $_keep_alive;


	public function result($host,$port,$data){
		$this->setHost($host);
		$this->setPort($port);
		$this->_connect();
		$this->sendParameter($data);
		$result = $this->_socket_read();
		$this->_disconnect();
		return $result;
	}

	public function _connect(){
		$this->_socket = @pfsockopen($this->_host, $this->_port, $errno, $errstr, $this->_timeout);
		if ($this->_socket === false) {
			return new PHPTCP_Error($errno, $errstr);
		}
		stream_set_write_buffer($this->_socket, 0);
		socket_set_timeout($this->_socket, $this->_timeout);
	}

	public function setPort($port){
		$this->_port=$port;
	}

	public function setHost($host){
		$this->_host = $host;
	}

	public function sendParameter($data){
		fwrite($this->_socket,$data,strlen($data));
	}

	public function _disconnect() {
		if ($this->_socket !== false) {
			fclose($this->_socket);
			$this->_socket = false;
		}
	}

	public function _socket_read() {
		$len = fread($this->_socket, 8); //包长，返回的长度包括8个字节的包长
		$len = intval($len) - 8; //减掉8个字节的包长
		$data = '';
		while ($len > 0) {
			$buf = fread($this->_socket, 1024);
			$len -= strlen($buf);
			$data .= $buf;
		}
		return $data;
	}
}

class PHPTCP_Error {
	var $Number;
	var $Message;
	function PHPTCP_Error($errno, $errstr) {
		$this->Number = $errno;
		$this->Message = $errstr;
	}
	function toString() {
		return $this->Number . ":" . $this->Message;
	}
	function __toString() {
		return $this->toString();
	}
	function getNumber() {
		return $this->Number;
	}
	function getMessage() {
		return $this->Message;
	}
}


?>