<?php

require_once LIB_PATH . '/Smarty/Smarty.class.php';

/**
 * Smarty模板初初始化
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core.View
 *
 */
class View_Smarty extends Smarty {
	
	//当前默认页面
	private $_curPage;
	
	private $_curCss;
	
	private $_curJs;
	
	private $_tpl;
	
	public function __construct() {
		$this->_createSmarty ();
		parent::Smarty ();
	}

	/**
	 * @return the $_tpl
	 */
	public function get_tpl() {
		return $this->_tpl;
	}

	/**
	 * @param $_tpl the $_tpl to set
	 */
	public function set_tpl($_tpl) {
		if (!$_tpl)return false;
		foreach ($_tpl as $key=>$value){
			$this->_tpl[$key] = $value;
		}
	}
	
	/**
	 * 获取当前动作css页面路径
	 * @return the $_curCss
	 */
	public function get_curCss() {
		return $this->_curCss;
	}
	
	/**
	 * 获取当前动作js页面路径
	 * @return the $_curJs
	 */
	public function get_curJs() {
		return $this->_curJs;
	}
	
	/**
	 * @param $_curCss the $_curCss to set
	 */
	public function set_curCss($_curCss) {
		$this->_curCss = $_curCss;
	}
	
	/**
	 * @param $_curJs the $_curJs to set
	 */
	public function set_curJs($_curJs) {
		$this->_curJs = $_curJs;
	}
	
	/**
	 * @return the $_curPage
	 */
	public function get_curPage() {
		return $this->_curPage;
	}
	
	/**
	 * @param $_curPage the $_curPage to set
	 */
	public function set_curPage($_curPage) {
		$this->_curPage = $_curPage;
	}
	
	/**
	 * 设置template_dir
	 */
	public function set_tpl_dir($theme=''){
		$dirName = APP_PATH . '/Tpl/' . $theme;
		if($theme && is_dir($dirName)){
			$this->template_dir = $dirName;
			return true;
		}
		return false;
	}
	
	/**
	 * 对Smarty进行实例化
	 *
	 */
	private function _createSmarty() {
		//设置模板目录
		$theme = $_COOKIE ['theme'] ? $_COOKIE ['theme'] : TEMPLATE_THEME;
		$templateDir = APP_PATH . '/Tpl/' . $theme;
		if (! file_exists ( $templateDir ))
			$templateDir = TEMPLATE_DIR . '/' . TEMPLATE_THEME;
		$this->template_dir = $templateDir;
		if (!DEBUG)$this->compile_check=false;
		//设置编译目录和缓存目录
		$templateCompileDir = ROOT_PATH . '/RunTime/templates_c';
		$templateCacheDir = ROOT_PATH . '/RunTime/templates_c/cache';
		if (! file_exists ( $templateCompileDir ))
			mkdir ( $templateCompileDir, 0777 );
		if (! file_exists ( $templateCacheDir ))
			mkdir ( $templateCacheDir, 0777 );
		$this->compile_dir = $templateCompileDir;
		$this->cache_dir = $templateCacheDir;
		
		$this->cache_lifetime = 0;
		$this->caching = FALSE;
		$this->left_delimiter = TEMPlATE_LEFT_DELIMITER;
		$this->right_delimiter = TEMPLATE_RIGHT_DELIMITER;
		
		$this->_setTemplateParameter ();
		$this->_curCss = CONTROL . '/' . ACTION . '.css.' . TEMPLATE_TYPE;
		$this->_curJs = CONTROL . '/' . ACTION . '.js.' . TEMPLATE_TYPE;
		$this->_curPage=defined('PACKAGE')? PACKAGE . '/' . CONTROL . '/' . ACTION :  CONTROL . '/' . ACTION;//设置默认页面
		$this->_tpl=array('top'=>'Default/Top.html','body'=>$this->_curPage . '.html','bottom'=>'Default/Bottom.html');
		
	}
	
	/**
	 * 生成模板常用变量
	 *
	 */
	private function _setTemplateParameter() {
		$this->assign ( '__ROOT__', __ROOT__ );
		$this->assign ( '__JS__', __JS__ );
		$this->assign ( '__CSS__', __CSS__ );
		$this->assign ( '__IMG__', __IMG__ );
		$this->assign ( '__SWF__', __SWF__ );
		$this->assign ( '__CONTROL__', CONTROL );
		$this->assign ( '__ACTION__', ACTION );
		$this->assign ( '__REQUEST__',CONTROL.'_'.ACTION);
		$this->assign ( '__GAMEID__',intval($_REQUEST['__game_id']));
		if (defined('PACKAGE'))$this->assign ( '__PACKAGE__',PACKAGE);
	}
	
	/**
	 * 重写display函数,给予默认值
	 *
	 * @param String $resourceName
	 * @param int $cacheId
	 * @param int $complieId
	 */
	public function display($resourceName = null, $cacheId = null, $complieId = null) {
		$this->assign('tpl',$this->_tpl);
		if ($resourceName) {
			$resourceName = $resourceName . '.' . TEMPLATE_TYPE;
		} else {
			$resourceName = TEMPLATE_DEFALUT_DISPLAY_PAGE . '.' . TEMPLATE_TYPE;
		}
		parent::display ( $resourceName, $cacheId, $complieId );
	}

}