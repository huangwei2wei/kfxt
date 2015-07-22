<?php

/**
 * 错误显示类
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 *
 */
class Error extends Exception {


	public function __construct($message,$code=0){
		parent::__construct($message,$code);
	}


	/**
	 * 默认错误输出函数
	 *
	 * @todo 未完成...
	 */
	public function displayMsg($message=NULL){
		echo $this->getMessage();
		echo '<hr>';
		echo $this->getFile();
		echo '&nbsp;&nbsp;<b>'.$this->getLine().'行</b>';
	}

}
