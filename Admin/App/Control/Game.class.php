<?php
Tools::import('Control_BaseGm');
class Control_Game extends BaseGm {
	private $_modelGame;
	
	public function __construct(){
		$this->_createView();
		$this->_modelGame = $this->_getGlobalData('Model_Game','object');
	}
	
	public function actionGameList(){
		//<<表单参数
		$page = $_GET['page'];
		$Id = $_GET['Id'];
		$name=$_GET['name'];
		$ename=$_GET['ename'];
		$uwan_game_id=$_GET['uwan_game_id'];
		$is_list = $_GET['is_list'];
		//>>表单参数
		$data = $this->_modelGame->gameList($page,$Id,$name,$ename,$uwan_game_id,$is_list);
		$this->_view->assign('dataList',$data['list']);
		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$data['page']['total'],'perpage'=>$data['page']['pageSize']));
		$this->_view->assign('pageBox',$helpPage->show());
		$isList = array(
			''=>'-全部-',
			1=>'使用公共模块',
			0=>'使用独立模板',
		);
		$this->_view->assign('is_list',$isList);
		$this->_view->display();
	}
	
}