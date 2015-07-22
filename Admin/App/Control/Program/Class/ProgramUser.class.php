<?php
/**
 * 技术用户管理
 * @author php-朱磊
 *
 */
class Control_ProgramUser extends Program {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;
	
	private $_setupFilePath;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['ProgramUser_SetupAdd']=Tools::url(CONTROL,'SetupAdd',array('zp'=>'program'));
		$this->_url['ProgramUser_SetupEdit']=Tools::url(CONTROL,'SetupEdit',array('zp'=>'program'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 用户列表
	 */
	public function actionIndex(){
		$positionList=$this->_getGlobalData('program/position');
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$users=$this->_modelUser->findByDepartment(self::DEPARTMENT_IT);
		foreach ($users as &$list){
			$list['url_edit']=Tools::url('User','User',array('doaction'=>'edit','Id'=>$list['Id']));
			$list['word_position_id']=$positionList[$list['position_id']]['name'];
		}
		$this->_view->assign('dataList',$users);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 职位设置
	 */
	public function actionSetup(){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_setupFilePath=CACHE_DIR . '/program/position.cache.php';
		if (file_exists($this->_setupFilePath)){
			$dataList=$this->_getGlobalData('program/position');
			foreach ($dataList as $key=>&$list){
				$list['url_del']=Tools::url(CONTROL,'SetupDel',array('zp'=>'Program','Id'=>$key));
			}
		}else {
			$dataList=null;
		}
		if ($dataList!==null){
			$users=$this->_modelUser->findByDepartment(self::DEPARTMENT_IT);
			foreach ($users as $user){
				if (!$user['position_id'])continue;
				if (array_key_exists($user['position_id'],$dataList)){
					if (!is_array($dataList[$user['position_id']]['users']))$dataList[$user['position_id']]['users']=array();
					array_push($dataList[$user['position_id']]['users'],$user);
				}

			}
		}
		$this->_view->assign('dataList',$dataList);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionSetupAdd(){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_setupFilePath=CACHE_DIR . '/program/position.cache.php';
		if (file_exists($this->_setupFilePath)){
			$dataList=$this->_getGlobalData('program/position');
			$dataList[$_POST['Id']]=array('Id'=>$_POST['Id'],'name'=>$_POST['name']);
			$this->_addCache($dataList,$this->_setupFilePath);
		}else {
			$dataList=array();
			$dataList[$_POST['Id']]=array('Id'=>$_POST['Id'],'name'=>$_POST['name']);
			$this->_addCache($dataList,$this->_setupFilePath);
		}
		$this->_utilMsg->showMsg(false);
	}
	
	public function actionSetupEdit(){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_setupFilePath=CACHE_DIR . '/program/position.cache.php';
		$dataList=array();
		foreach ($_POST['Id'] as $key=>$id){
			$dataList[$id]=array('Id'=>$id,'name'=>$_POST['name'][$key]);
		}
		$this->_addCache($dataList,$this->_setupFilePath);
		$this->_utilMsg->showMsg(false);
	}
	
	public function actionSetupDel(){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_setupFilePath=CACHE_DIR . '/program/position.cache.php';
		$dataList=$this->_getGlobalData('program/position');
		unset($dataList[$_GET['Id']]);
		if (count($dataList)){
			$this->_addCache($dataList,$this->_setupFilePath);
		}else{ 
			unlink($this->_setupFilePath);
		}
		$this->_utilMsg->showMsg(false);
	}

	
	
}