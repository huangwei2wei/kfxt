<?php
class Control_SysManagement extends Control {
	/**
	 * Model_QuestionType
	 * @var Model_QuestionType
	 */
	private $_modelQuestionType;

	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * 问题类型表单
	 * @var array
	 */
	private $_formType=array(
		'text'=>'文本输入框',
		'select'=>'下拉选择框',
		'game_server_list'=>'游戏服务器列表',
	);

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
		$this->_modelQuestionType = $this->_getGlobalData('Model_QuestionType','object');
		$this->_modelSysconfig = $this->_getGlobalData('Model_Sysconfig','object');
	}

	/**
	 * 创建url方便smarty调用
	 */
	private function _createUrl() {
		$this->_url ['SysManagement_QuestionAdd'] = Tools::url ( CONTROL, 'Question',array('doaction'=>'add') );
		$this->_url ['SysManagement_QuestionEdit'] = Tools::url ( CONTROL, 'Question',array('doaction'=>'edit') );
		$this->_url ['SysManagement_SysSetupEdit'] = Tools::url ( CONTROL, 'Sys',array('doaction'=>'edit') );
		$this->_url ['SysManagement_SysSetupCreateCache'] = Tools::url ( CONTROL, 'Sys',array('doaction'=>'cache') );
		$this->_url ['SysManagement_QuestionCreateCache'] = Tools::url ( CONTROL , 'Question',array('doaction'=>'cache'));
		$this->_url ['SysManagement_QuestionFormAdd'] = Tools::url(CONTROL, 'Question',array('doaction'=>'formAdd'));
		$this->_url ['SysManagement_QuestionFormEdit'] = Tools::url(CONTROL, 'Question',array('doaction'=>'formEdit'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 提问类型编辑
	 */
	public function actionQuestion(){
		switch ($_GET['doaction']){
			case 'form' :{
				$this->_qFormIndex();
				return ;
			}
			case 'formAdd' :{
				$this->_qFormAdd();
				return ;
			}
			case 'formEdit' :{
				$this->_qFormEdit();
				return ;
			}
			case 'formDel' :{
				$this->_qFormDel();
				return ;
			}
			case 'add' :{
				$this->_qAdd();
				return ;
			}
			case 'edit' :{
				$this->_qEdit();
				return ;
			}
			case 'del' :{
				$this->_qDel();
				return ;
			}
			case 'cache' :{
				$this->_qCreateCache();
				return ;
			}
			default:{
				$this->_qIndex();
				return ;
			}
		}
	}

	/**
	 * 问题列表
	 */
	private function _qIndex() {
		$dataList = $this->_modelQuestionType->findAll (false);
		$gameTypeList=Model::getTtwoArrConvertOneArr($this->_modelSysconfig->getValueToCache ( 'game_type' ),'Id','name');;
		foreach ( $dataList as &$list ) {
			$list ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $list ['Id'],'doaction'=>'edit' ) );
			$list ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $list ['Id'],'doaction'=>'del' ) );
			$list ['url_question_form'] = Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'doaction'=>'form'));
			$list ['game_type'] = $gameTypeList[$list ['game_type_id']];
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_view->set_tpl(array('body'=>'SysManagement/QuestionIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	private function _qCreateCache(){
		if ($this->_modelQuestionType->createToCache()){
			$this->_utilMsg->showMsg('缓存更新成功',1);
		}else {
			$this->_utilMsg->showMsg('缓存更新失败',-2);
		}
	}

	/**
	 * 表单问题集列表
	 */
	private function _qFormIndex(){
		$questionTypes=$this->_modelQuestionType->findById($_GET['Id'],false);
		$this->_view->assign('formType',$this->_formType);
		$questionFormTable=unserialize($questionTypes['form_table']);
		if ($questionFormTable){
			foreach ($questionFormTable as $key=>&$value){
				$value['word_type']=$this->_formType[$value['type']];
				$value['word_required']=$value['required']?'是':'否';
				$value['url_edit']=Tools::url(CONTROL,ACTION,array('key'=>$key,'Id'=>$_GET['Id'],'doaction'=>'formEdit'));
				$value['url_del']=Tools::url(CONTROL,ACTION,array('key'=>$key,'Id'=>$_GET['Id'],'doaction'=>'formDel'));
				if ($value['options']){
					$value['word_options']=implode(',',$value['options']);
				}else {
					$value['word_options']='/';
				}
			}
		}else {
			$questionFormTable=array();
		}

		$this->_view->assign('title',$questionTypes['title']);
		$this->_view->assign('dataList',$questionFormTable);
		$this->_view->assign('questionId',$_GET['Id']);
		$this->_view->set_tpl(array('body'=>'SysManagement/QuestionForm.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 *增加表单问题集
	 */
	private function _qFormAdd(){
		if (!array_key_exists($_POST['type'],$this->_formType))$this->_utilMsg->showMsg('请选择正常的类型',-1);
		$questionTypes=$this->_modelQuestionType->findById($_POST['Id'],false);
		$questionFormTable=unserialize($questionTypes['form_table']);
		if (!$questionFormTable)$questionFormTable=array();
		$newFormChild=array(
			'type'=>$_POST['type'],
			'required'=>$_POST['required']?true:false,
		);
		if ($_POST['name'])$newFormChild['name']=$_POST['name'];
		if ($_POST['title'])$newFormChild['title']=$_POST['title'];
		if ($_POST['description'])$newFormChild['description']=$_POST['description'];
		if ($_POST['options'])$newFormChild['options']=explode(',',$_POST['options']);
		array_push($questionFormTable,$newFormChild);
		if ($this->_modelQuestionType->update(array('form_table'=>serialize($questionFormTable)),"Id={$_POST['Id']}")){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('增加失败',-2);
		}
	}

	/**
	 * 修改问题表单集
	 */
	private function _qFormEdit(){
		if ($this->_isPost()){
			$questionTypes=$this->_modelQuestionType->findById($_POST['Id'],false);
			$questionFormTable=unserialize($questionTypes['form_table']);
			$questionChild=$questionFormTable[$_POST['key']];
			$questionChild['name']=$_POST['name'];
			$questionChild['title']=$_POST['title'];
			$questionChild['description']=$_POST['description'];
			$questionChild['required']=$_POST['required']?true:false;
			if ($_POST['options'])$questionChild['options']=explode(',',$_POST['options']);
			$questionFormTable[$_POST['key']]=$questionChild;
			if ($this->_modelQuestionType->update(array('form_table'=>serialize($questionFormTable)),"Id={$_POST['Id']}")){
				$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,ACTION,array('Id'=>$_POST['Id'],'doaction'=>'form')));
			}else {
				$this->_utilMsg->showMsg('增加失败',-2);
			}
		}else{
			$questionTypes=$this->_modelQuestionType->findById($_GET['Id'],false);
			$questionFormTable=unserialize($questionTypes['form_table']);
			$questionFormTable=$questionFormTable[$_GET['key']];
			$questionFormTable['word_type']=$this->_formType[$questionFormTable['type']];	//转换
			if ($questionFormTable['options'])$questionFormTable['word_options']=implode(',',$questionFormTable['options']);
			$this->_view->assign('title',$questionTypes['title']);
			$this->_view->assign('data',$questionFormTable);
			$this->_view->assign('Id',$_GET['Id']);
			$this->_view->assign('key',$_GET['key']);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'SysManagement/QuestionFormEdit.html'));
			$this->_view->display();
		}
	}

	/**
	 * 删除问题表单集
	 */
	private function _qFormDel(){
		$questionTypes=$this->_modelQuestionType->findById($_GET['Id'],false);
		$questionFormTable=unserialize($questionTypes['form_table']);
		unset($questionFormTable[$_GET['key']]);
		sort($questionFormTable,SORT_NUMERIC);
		if ($this->_modelQuestionType->update(array('form_table'=>serialize($questionFormTable)),"Id={$_GET['Id']}")){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}

	/**
	 * 增加问题类型
	 */
	private function _qAdd(){
		if ($this->_isPost()){
			if ($this->_modelQuestionType->add($_POST)){
				$this->_utilMsg->showMsg('增加成功',1);
			}else {
				$this->_utilMsg->showMsg('增加失败',-2);
			}
		}else {
			$gameType=Model::getTtwoArrConvertOneArr($this->_modelSysconfig->getValueToCache ( 'game_type' ),'Id','name');;
			$this->_view->assign('gameType',$gameType);
			$this->_view->set_tpl(array('body'=>'SysManagement/QuestionAdd.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 问题类型编辑
	 */
	private function _qEdit() {
		if ($this->_isPost ()) {
			$_POST ['form_table'] = str_replace ( '\\', '', $_POST ['form_table'] );
			$_POST ['form_table'] = 'return ' . $_POST ['form_table'] . ';';
			$formTable = eval ( $_POST ['form_table'] );
			$formTable = serialize ( $formTable );
			$updateArr = array ('game_type_id' => $_POST ['game_type_id'], 'title' => $_POST ['title'], 'form_table' => $formTable );
			if ($this->_modelQuestionType->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '编辑成功', 1 );
			} else {
				$this->_utilMsg->showMsg ( '编辑失败', 1 );
			}
		} else {
			$gameTypeList=Model::getTtwoArrConvertOneArr($this->_modelSysconfig->getValueToCache ( 'game_type' ),'Id','name');;
			$this->_view->assign ( 'gameListSelect', $gameTypeList );
			$dataList = $this->_modelQuestionType->findById ( $_GET ['Id'] , false);
			$dataList ['form_table'] = unserialize ( $dataList ['form_table'] );
			$dataList ['form_table'] = var_export ( $dataList ['form_table'], true );
			$this->_view->assign ( 'list', $dataList );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'SysManagement/QuestionEdit.html'));
			$this->_view->display ();
		}
	}

	private function _qDel() {
		$this->_utilMsg->showMsg ( '未完成', 1 );
	}
	
	/**
	 * 系统变量设置
	 */
	public function actionSys(){
		switch ($_GET['doaction']){
			case 'edit' :{
				$this->_sysEdit();
				return ;
			}
			case 'cache' :{
				$this->_sysCreateCache();
				return ;
			}
			default:{
				$this->_sysIndex();
				return ;	
			}
		}
	}

	private function _sysIndex() {
		$dataList = $this->_modelSysconfig->findAll (false);
		foreach ( $dataList as &$value ) {
			$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'] ,'doaction'=>'edit' ) );
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'del' ) );
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'SysManagement/SysIndex.html'));
		$this->_view->display ();
	}

	private function _sysEdit() {
		if ($this->_isPost ()) {
			$_POST ['config_value'] = str_replace ( '\\', '', $_POST ['config_value'] );
			$_POST ['config_value'] = 'return ' . $_POST ['config_value'] . ';';
			$configValue = eval ( $_POST ['config_value'] );
			$configValue = serialize ( $configValue );
			$updateArr = array ('config_name' => $_POST ['config_name'], 'title' => $_POST ['title'], 'config_value' => $configValue );

			if ($this->_modelSysconfig->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '编辑成功', 1,Tools::url(CONTROL,ACTION) );
			} else {
				$this->_utilMsg->showMsg ( '编辑失败', 1 );
			}

		} else {
			$dataList = $this->_modelSysconfig->findById ( $_GET ['Id'] );
			$dataList ['config_value'] = unserialize ( $dataList ['config_value'] );
			$dataList ['config_value'] = var_export ( $dataList ['config_value'], true );
			$this->_view->assign ( 'list', $dataList );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'SysManagement/SysEdit.html'));
			$this->_view->display ();
		}
	}

	private function _sysCreateCache(){
		$this->_modelSysconfig->createToCache();
		$this->_utilMsg->showMsg('生成完成',1);
	}
	

}