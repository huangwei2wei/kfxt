<?php
/**
 * @author php-朱磊
 */
class Control_Default extends Control {
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function __construct() {
	
	}
	
	/**
	 * 生成验证码地址Tools::url('Default','VerifyCode');
	 */
	public function actionVerifyCode() {
		$this->_loadCore ( 'Help_ImgCode' );
		$helpImgCode = new Help_ImgCode ();
		$helpImgCode->image ();
	}
	
	/**
	 * 上传图片方法,所有editor的上传方法 
	 */
	public function actionImgUpload() {
		switch ($_GET ['type']) { //跟据类型选择上传文件夹,默认玩家Faq
			case 'Verify' :
				{//查证处理
					$uploadDir = UPDATE_DIR . '/Verify/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/Verify/' . date ( 'Ymd' );
					break;
				}
			case 'Bulletin' :
				{//公告,工作交接
					$uploadDir = UPDATE_DIR . '/Bulletin/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/Bulletin/' . date ( 'Ymd' );
				}
			case 'BUG':
				{//BUG
					$uploadDir = UPDATE_DIR . '/BUG/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/BUG/' . date ( 'Ymd' );
				}
			case 'Quality':
				{	//质检
					$uploadDir = UPDATE_DIR . '/Quality/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/Quality/' . date ( 'Ymd' );
				}
			case 'GameFaq'://游戏里面的faq
				{
					$uploadDir = UPDATE_DIR . '/GameFaq/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/GameFaq/' . date ( 'Ymd' );
				}
			default :
				{
					$uploadDir = UPDATE_DIR . '/PlayerFaq/' . date ( 'Ymd' );
					$saveUrl = __ROOT__ . '/Upload/PlayerFaq/' . date ( 'Ymd' );
					break;
				}
		}
		if (! file_exists ( $uploadDir ))
			mkdir ( $uploadDir, 0777,true );
		$extArr = array ('gif', 'jpg', 'jpeg', 'png', 'bmp' );
		$maxSize = 1024 * 1024 * 2;
		if (empty ( $_FILES ) === false) {
			$fileName = $_FILES ['imgFile'] ['name'];
			$tmpName = $_FILES ['imgFile'] ['tmp_name'];
			$fileSize = $_FILES ['imgFile'] ['size'];
			if (! $fileName)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR1',__CLASS__) );
			if (is_writable ( $uploadDir ) === false)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR2',__CLASS__) );
			if (is_uploaded_file ( $tmpName ) === false)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR3',__CLASS__) );
			if ($fileSize > $maxSize)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR4',__CLASS__) );
			$tempArr = explode ( '.', $fileName );
			$fileExt = array_pop ( $tempArr );
			$fileExt = strtolower ( trim ( $fileExt ) );
			if (in_array ( $fileExt, $extArr ) === false)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR5',__CLASS__) );
			$newFileName = date ( 'His' ) . '_' . rand ( 10000, 99999 ) . ".{$fileExt}";
			$filePath = $uploadDir . "/{$newFileName}";
			if (move_uploaded_file ( $tmpName, $filePath ) === false)
				$this->_imgAlert ( Tools::getLang('IMGUPLOAD_ERORR6',__CLASS__) );
			$fileUrl = $saveUrl . "/{$newFileName}";
			echo json_encode ( array ('error' => 0, 'url' => $fileUrl ) );
		}
	}
	
	private function _imgAlert($msg, $error = 1) {
		echo json_encode ( array ('error' => $error, 'message' => $msg ) );
	}
	
}
