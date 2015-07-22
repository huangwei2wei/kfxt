<?php
/**
 * 调查问卷
 * @author 社游-陈成禧
 *
 */
class Control_Askform extends Control{
	/**
	 * Model_Askform
	 * @var Model_Askform
	 */
	private $_modelAskform;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_AskformOption
	 * @var Model_AskformOption
	 */
	private $_modelAskformOption;
	
	
	private $_askStatus=array('0'=>'停止','1'=>'已经发布');
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
	}
	
	private function _createUrl(){
		$this->_url['Askform_Add']=Tools::url(CONTROL,'Askform',array('doaction'=>'add'));
		$this->_url['Askform_Edit']=Tools::url(CONTROL,'Askform',array('doaction'=>'edit'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 问卷调察
	 */
	public function actionAskform(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_add();
				return ;
			}
			case 'edit' :{
				$this->_edit();
				return ;
			}
			case 'del' :{
				$this->_del();
				return ;
			}
			case 'addOption' :{
				$this->_addOption();
				return ;
			}
			case 'delOption' :{
				$this->_delOption();
				return ;
			}
			case 'show' :{
				$this->_show();
				return ;
			}
			default:{
				$this->_ls();
				return ;
			}
		}
	}
	
	/**
	 * 问卷列表
	 */
	private function _ls(){
		$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
		$users=$this->_getGlobalData('user');
		#------分页生成sql------#
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelAskform->tName () );
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		$sql = $helpSqlSearch->createSql ();
		$this->_loadCore ( 'Help_Page' );		
		#------分页生成sql------#		

		$dataList = $this->_modelAskform->select( $sql );
		if ($dataList){
			$helpPage = new Help_Page ( array ('total' => $this->_modelAskform->findCount (), 'perpage' => PAGE_SIZE ) );
			$this->_view->assign ( 'pageBox', $helpPage->show () );
			foreach ($dataList as &$list){
				$list['word_user_id']=$users[$list['user_id']]['nick_name'];
				$list['start_time']=date('Y-m-d H:i:s',$list['start_time']);
				$list['end_time']=date('Y-m-d H:i:s',$list['end_time']);
				$list['word_status']=$this->_askStatus[$list['status']];
				$list['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'doaction'=>'del'));
				$list['url_addoption']=Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'doaction'=>'addOption'));
				$list['url_show']=Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'doaction'=>'show'));
			}
			$this->_view->assign("dataList",$dataList);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Askform/Ls.html'));
		$this->_view->display ();
	}
	
	/**
	 * 删除问卷子选项
	 */
	private function _delOption(){
		$this->_modelAskformOption=$this->_getGlobalData('Model_AskformOption','object');
		if ($this->_modelAskformOption->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}
	
	/**
	 * 增加子项
	 */
	private function _addOption(){
		#------初始化------#
		$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
		$this->_modelAskformOption=$this->_getGlobalData('Model_AskformOption','object');
		#------初始化------#
		if ($this->_isPost()){
			$data=$this->_modelAskformOption->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			$dataList=$this->_modelAskformOption->findByAskformId($_GET['Id']);
			if ($dataList){
				foreach ($dataList as &$list){
					$list['content']=unserialize($list['content']);
					$list['result']=unserialize($list['result']);
					$list['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'doaction'=>'delOption'));
				}
				$this->_view->assign('dataList',$dataList);
			}
			$data=$this->_modelAskform->findById($_GET['Id']);
			$data['start_time']=date('Y-m-d H:i:s',$data['start_time']);
			$data['end_time']=date('Y-m-d H:i:s',$data['end_time']);
			$this->_view->assign('askStatus',$this->_askStatus);
			$this->_view->assign('data',$data);
			$this->_view->set_tpl(array('body'=>'Askform/AddOption.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}

	}
	
	/**
	 * 添加新的问卷
	 */
	private function _add(){
		if($this->_isPost()){
			$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
			$data=$this->_modelAskform->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else{
			$this->_view->assign('askStatus',$this->_askStatus);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Askform/Add.html'));
			$this->_view->display ();
		}
	}
	
	/**
	 * 编辑问卷
	 */
	private function _edit(){
		if ($this->_isPost()){
			$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
			$data=$this->_modelAskform->edit($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}
	}
	
	/**
	 * 删除问卷
	 */
	private function _del(){
		$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
		$data=$this->_modelAskform->delById($_GET['Id']);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}

	
	/**
	 * 显示调查结果
	 */
	private function _show(){
		#------初始化------#
		$this->_modelAskform = $this->_getGlobalData('Model_Askform','object');
		$this->_modelAskformOption=$this->_getGlobalData('Model_AskformOption','object');
		#------初始化------#
		$dataList=$this->_modelAskformOption->findByAskformId($_GET['Id']);
		if ($dataList){
			foreach ($dataList as &$list){
				$list['content']=unserialize($list['content']);
				$list['result']=unserialize($list['result']);
				$list['vote']=array();
				foreach ($list['content'] as $key=>$value){
					$list['vote'][$value]=intval($list['result'][$key]);
				}
				if ($list['allow_other'])$list['vote']['其它']=intval($list['result'][-1]);
			}
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('jsonDataList',json_encode($dataList));
		}
		$data=$this->_modelAskform->findById($_GET['Id']);
		$data['start_time']=date('Y-m-d H:i:s',$data['start_time']);
		$data['end_time']=date('Y-m-d H:i:s',$data['end_time']);
		$this->_view->assign('askStatus',$this->_askStatus);
		$this->_view->assign('data',$data);
		$this->_view->set_tpl(array('body'=>'Askform/Show.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
}