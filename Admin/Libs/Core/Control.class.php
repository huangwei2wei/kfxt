<?php
/**
 * 动作层基类
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 */
abstract class Control extends Base {
	
	/**
	 * View_Smarty
	 * @var View_Smarty
	 */
	protected $_view=null;

	/**
	 * url数组
	 * @param array
	 */
	protected $_url=array();

	/**
	 * 创建view层
	 */
	protected function _createView(){
		if (!is_object($this->_view)){
			$this->_loadCore('View');
			$this->_view = View::getInstance ();
			$rbac=$this->_getGlobalData('Util_Rbac','object');
			$this->_view->assign('_GET',$_GET);
			$this->_view->assign('_REQUEST',$_REQUEST);
			$this->_view->assign('_SERVER',$_SERVER);
			if ($_POST)$this->_view->assign('_POST',$_POST);
			$this->_view->assign('rbac',$rbac);
			$this->_view->assign('noData','暂无数据');
		}
	}

	
	/**
	 * 是否为post请求
	 */
	protected function _isPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	
	/**
	 * 获取对象详细信息
	 * @param string $className
	 * @param array $menu
	 */
	public static function getClassDetail($className,$menu){
		$classDetail['value']=end(explode('_',$className));
		$classDetail['name']=$menu['name'];
		$classDetail['display']=$menu['display'];
		$methods=get_class_methods($className);
		$classMethods=array();
		foreach ($methods as $value){
			if (stripos($value,'action')!==false){
				$child=array();
				$value=substr($value,6);
				$child['value']=$value;
				$child['name']=$menu['child'][$value]['name'];
				$child['display']=$menu['child'][$value]['display'];
				$classMethods[$value]=$child;
			}
		}
		$classDetail['class_methods']=$classMethods;
		return $classDetail;
	}
	
	/**
	 * 是否为ajax提交
	 */
	protected function _isAjax(){
		return isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
	}
	
	/**
	 * 返回json数据
	 * @param array $result
	 * @example 
	 * 		$result=array(
	 * 			'status'=>1,
	 * 			'msg'=>'回复成功',
	 * 			'href'=>Tools::url('Index','Login'),
	 * 			'data'=>array(......),
	 * 		);
	 * 		$this->returnAjaxJson($result)
	 */
	protected function _returnAjaxJson($result){
		exit(json_encode($result));
	}
	
	/**
	 * 设置浏览器缓存
	 * 必须在没任何输出时调用
	 * @param int $maxTime 缓存时间(秒),默认为1小时
	 */
	protected function _setBrowserCache($maxTime=3600){
		$now=CURRENT_TIME;
		$interval=$maxTime;
		header("Cache-Control: public");
  		header("Pragma: cache");		
		header ("Last-Modified: " . gmdate ('r', $now));
		header ("Expires: " . gmdate ("r", ($now + $interval)));
		header ("Cache-Control: max-age={$interval}");
		unset($now,$interval);
	}

	public function __destruct(){
		Tools::addLog();	//记录日志
	}

}