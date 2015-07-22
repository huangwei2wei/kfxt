<?php
/**
 * excel输出类
 * @author php-朱磊
 *
 */
class Util_ExportExcel extends Control {

	private $_fileName;
	
	private $_tplPath;
	
	private $_dataList;
	
	/**
	 * 
	 * @param $fileName 输出文件名
	 * @param $tplPath 模块目录
	 * @param $dataList 数据
	 */
	function __construct($fileName,$tplPath,$dataList){
		$this->_createView();
		$this->_fileName=$fileName;
		$this->_tplPath=$tplPath;
		$this->_dataList=$dataList;
	}
	
	private function _setHeader(){
		Header("Content-type:   application/octet-stream"); 
		Header("Accept-Ranges:   bytes"); 
		Header("Content-type:application/vnd.ms-excel;");    
		Header("Content-Disposition:attachment;filename=".$this->_fileName.".xls"); 
		print(chr(0xEF).chr(0xBB).chr(0xBF)); //utf8
	}
	
	/**
	 * 输出excel
	 */
	public function outPutExcel(){
		$this->_setHeader();	
		$this->_view->assign('dataList',$this->_dataList);
		$this->_view->display($this->_tplPath);
	}
	
	
	
	
}