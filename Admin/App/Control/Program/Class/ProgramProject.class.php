<?php
/**
 * 项目管理
 * @author php-朱磊
 */
class Control_ProgramProject extends Program {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_ProgramProject
	 * @var Model_ProgramProject
	 */
	private $_modelProgramProject;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 项目管理首页
	 */
	public function actionIndex(){
		$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
		$users=$this->_getGlobalData('user');
		$dataList=$this->_modelProgramProject->findAll();
		foreach ($dataList as &$list){
			$list['users']=array();
			foreach ($users as $user){
				if ($user['department_id']==self::DEPARTMENT_IT && $user['project_id']==$list['Id'])array_push($list['users'],$user);
			}
			$list['url_del']=Tools::url(CONTROL,'Del',array('zp'=>'Program','Id'=>$list['Id']));
			$list['url_edit']=Tools::url(CONTROL,'Edit',array('zp'=>'Program','Id'=>$list['Id']));
			$list['word_principal_user_id']=$users[$list['principal_user_id']]['nick_name'];
		}
		$this->_view->assign('dataList',$dataList);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 增加项目
	 */
	public function actionAdd(){
		if ($this->_isPost()){
			$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
			$info=$this->_modelProgramProject->add($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$this->_view->assign('users',$this->getItUsers());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 编辑项目
	 */
	public function actionEdit(){
		$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
		if ($this->_isPost()){
			$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
			$info=$this->_modelProgramProject->edit($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$users=$this->_modelProgramProject->findProjectUser(self::DEPARTMENT_IT,$_GET['Id']);
			$users['full_user']=Model::getTtwoArrConvertOneArr($users['full_user'],'Id','nick_name');
			$users['all_user']=Model::getTtwoArrConvertOneArr($users['all_user'],'Id','nick_name');
			$dataList=$this->_modelProgramProject->findById($_GET['Id']);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('users',$users);
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	
	/**
	 * 删除项目
	 */
	public function actionDel(){
		$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
		$this->_modelProgramProject->delById($_GET['Id']);
		$this->_utilMsg->showMsg('删除成功',1);
	}
}