<?php
class Control_ShareToOperator extends LianYun {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	private $_utilRbac;
	
	/**
	 * psd链接
	 * @var unknown_type
	 */
	private $_modelLyPsdLink;
	
	/**
	 * 运营商详细信息
	 * @var Model_LyOperatorInfo
	 */
	private $_modelLyOperatorInfo;
	
	/**
	 * 联运的奖励进度记录
	 * @var Model_LyShareInfo
	 */
	private $_modelLyShareInfo;
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}
	
	private function _createUrl() {
		$this->_url['PsdLink']=Tools::url(CONTROL,'PsdLink',array('zp'=>'LianYun'));
		$this->_url['PsdLinkDAdd']=Tools::url(CONTROL,'PsdLinkOpt',array('zp'=>'LianYun','doaction'=>'add'));
		$this->_view->assign ( 'url', $this->_url );
	}
	
	public function actionPsdLink() {
		$this->_modelLyPsdLink = $this->_getGlobalData ( 'Model_LyPsdLink', 'object' );
		$gameTypes = $this->_modelLyPsdLink->getMyGame ();		
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelLyPsdLink->tName () );
		
		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		if($_GET['game_type_id']){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
		}else{
			$InData = array_keys($gameTypes);
			$InData = implode(',',$InData);
			$helpSqlSearch->set_conditions("game_type_id in ({$InData})");
		}
		$StartTime = strtotime($_GET ['start_time']);
		if($StartTime){
			$helpSqlSearch->set_conditions("edit_time>={$StartTime}");
		}
		$EndTime = strtotime($_GET ['end_time']);
		if($EndTime){
			$helpSqlSearch->set_conditions("edit_time<={$EndTime}");
		}
		$helpSqlSearch->set_orderBy ( 'Id desc' );
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		$sql = $helpSqlSearch->createSql ();
		
		$dataList = $this->_modelLyPsdLink->select ( $sql );
		foreach ( $dataList as &$sub ) {
			$sub['game_type'] = $gameTypes[$sub['game_type_id']];
			$sub ['edit'] = Tools::url ( CONTROL, 'PsdLinkOpt', array ('zp' => 'LianYun', 'Id' => $sub ['Id'], 'doaction' => 'edit' ) );
			$sub ['del'] = Tools::url ( CONTROL, 'PsdLinkOpt', array ('zp' => 'LianYun', 'Id' => $sub ['Id'], 'doaction' => 'del' ) );
		}
		$conditions=$helpSqlSearch->get_conditions();	
		$this->_loadCore('Help_Page');//载入分页工具		
		$helpPage=new Help_Page(array('total'=>$this->_modelLyPsdLink->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());		
		$this->_view->assign ( 'dataList', $dataList );
		$gameTypes [0] = Tools::getLang ( 'ALL', 'Common' );
		$this->_view->assign ( 'gameTypes', $gameTypes );
		$this->_view->assign ( 'selected', $_GET );
		$this->_utilMsg->createPackageNavBar ();
		$this->_view->display ();
	}
	
	public function actionPsdLinkOpt() {
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_psdLinkAdd();
				return ;
			}
			case 'del' :{
				$this->_psdLinkDel();
				return ;
			}
			case 'edit' :
			default:{
				$this->_psdLinkEdit();
				return ;
			}
		}
	}
	
	private function _psdLinkAdd(){
		$this->_modelLyPsdLink = $this->_getGlobalData ( 'Model_LyPsdLink', 'object' );
		$gameTypes = $this->_modelLyPsdLink->getMyGame();
		if($this->_isPost()){		
			$_POST['game_type_id'] = intval($_POST['game_type_id']);
			if(!$_POST['game_type_id'] || !array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_GAME','Common'),-1,2);
			}
			if(trim($_POST['title']) == ''){
				$this->_utilMsg->showMsg('标题不能为空',-1,2);
			}
			if(trim($_POST['href']) == ''){
				$this->_utilMsg->showMsg('链接不能为空',-1,2);
			}			
			$this->_modelLyPsdLink->linkAdd();			
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$this->_url['PsdLink'],1);
		}
		$this->_view->assign('gameTypes',$gameTypes);	
		$this->_utilMsg->createPackageNavBar ();	
		$this->_view->display();
	}
	
	private function _psdLinkDel(){
		$this->_modelLyPsdLink = $this->_getGlobalData ( 'Model_LyPsdLink', 'object' );
		if ($this->_modelLyPsdLink->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else{
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'),-2);
		}
	}
	
	private function _psdLinkEdit(){
		$this->_modelLyPsdLink = $this->_getGlobalData ( 'Model_LyPsdLink', 'object' );
		$gameTypes = $this->_modelLyPsdLink->getMyGame();
		$selected = $this->_modelLyPsdLink->findById($_GET['Id']);
		if(!$selected){
			$this->_utilMsg->showMsg('已删除',-1,2);
		}		
		if($this->_isPost()){
			$_POST['Id'] = intval($_POST['Id']);
			if(!$this->_modelLyPsdLink->findById($_POST['Id'])){
				$this->_utilMsg->showMsg(Tools::getLang('LINK_DELETED',__CLASS__),-1,2);
			}			
			$_POST['game_type_id'] = intval($_POST['game_type_id']);
			if(!$_POST['game_type_id'] || !array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_GAME','Common'),-1,2);
			}
			if(trim($_POST['title']) == ''){
				$this->_utilMsg->showMsg('标题不能为空',-1,2);
			}
			if(trim($_POST['href']) == ''){
				$this->_utilMsg->showMsg('链接不能为空',-1,2);
			}
			$this->_modelLyPsdLink->linkUpdate();
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$this->_url['PsdLink'],1);
		}
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_utilMsg->createPackageNavBar ();
		$this->_view->display();
	}


}