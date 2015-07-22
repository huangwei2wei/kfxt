<?php
/**
 * excel类
 * @author php-朱磊
 *
 */
class Help_Excel {
	/**
	 * PHPExcel
	 * @var PHPExcel
	 */
	private static $_excelObj;
	
	/**
	 * PHPExcel_Reader_Excel2007
	 * @var PHPExcel_Reader_Excel2007
	 */
	private static $_excelObj2007;
	
	/**
	 * PHPExcel_Reader_Excel5
	 * @var PHPExcel_Reader_Excel5
	 */
	private static $_execelObj5;
	
	private $_filePath;
	
	/**
	 * 单例,获取能读取文件的excel对象
	 * @var PHPExcel_Reader_Excel2007
	 */
	private $_instance;
	
	public function __construct($filePath){
		$this->_filePath=$filePath;
	}
	
	/**
	 * 获取可以读取类的phpexcel对象
	 * @return PHPExcel_Reader_Excel2007
	 */
	private function _getReadFileObj(){
		self::getInstance2007();
		if (!self::$_excelObj2007->canRead($this->_filePath)){
			self::getInstance5();
			if (!self::$_execelObj5->canRead($this->_filePath)){
				return false;
			}else {
				$this->_instance=self::$_execelObj5;
			}
		}else {
			$this->_instance=self::$_excelObj2007;
		}
	}
	
	/**
	 * 获取数据
	 * @param int $page 第几页数据
	 */
	public function getData($page){
		$this->_getReadFileObj();
		$phpExcel=$this->_instance->load($this->_filePath);
		$currentSheet=$phpExcel->getSheet($page);
		return $currentSheet->toArray();
	}

	/**
	 * 单例
	 * @return PHPExcel
	 */
	public static function getInstance(){
		if (!is_object(self::$_excelObj)){
			include_once LIB_PATH.'/phpexcel/PHPExcel.php';
			self::$_excelObj=new PHPExcel();
		}
		return self::$_excelObj;		
	}
	
	/**
	 * @return PHPExcel_Reader_Excel2007
	 */
	public static function getInstance2007(){
		if (!is_object(self::$_excelObj2007)){
			include_once LIB_PATH.'/phpexcel/PHPExcel.php';
			self::$_excelObj2007=new PHPExcel_Reader_Excel2007();
		}
		return self::$_excelObj2007;
	}
	
	/**
	 * @return PHPExcel_Reader_Excel5
	 */
	public static function getInstance5(){
		if (!is_object(self::$_execelObj5)){
			include_once LIB_PATH.'/phpexcel/PHPExcel.php';
			self::$_execelObj5=new PHPExcel_Reader_Excel5();
		}
		return self::$_execelObj5;
	}

}