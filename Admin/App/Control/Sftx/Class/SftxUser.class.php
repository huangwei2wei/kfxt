<?php
/**
 * 游戏用户管理
 * @author PHP-朱磊
 *
 */
class Control_SftxUser extends Sftx {

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;

	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;

	private $_searchType=array(	1=>'用户ID',	2=>'用户名',	3=>'时间范围',);

	private $_searchUserType=array(1=>'用户ID',2=>'用户名',3=>'等级范围',4=>'金币范围',5=>'系统金币',6=>'银币范围');

	public function __construct(){
		$_GET['page']=$_GET['page']?$_GET['page']:1;
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 用户查询
	 */
	public function actionIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/player');
			$dataList=$this->getApi()->select(intval($_GET['type']),$_GET['dataMin'],$_GET['dataMax'],intval($_GET['page']),PAGE_SIZE);
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
				$this->_view->assign('optionList',$dataList['data']['optionList']);
				$this->_view->assign('selectedArr',$dataList['data']['select']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 游戏金币操作日志
	 */
	public function actionUserGoldLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$_GET['detailId'] = intval($_GET['detailId']);
			$_GET['playerId'] = intval($_GET['playerId']);
			
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$selected['detailId']=$_GET['detailId'];
			$selected['playerId']=$_GET['playerId'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/expendLog');
			

			if($_GET['detailId']){
				$dataList=$this->getApi()->select($_GET['detailId'],$_GET['playerId'],$_GET['page'],PAGE_SIZE);
			}else{
				$dataList=$this->getApi()->select($_GET['type'],$_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
			}		
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if (count($dataList['data']['dataList']['result'])){
					$subType=$this->getSubType();
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['logTime']=date('Y-m-d H:i:s',$list['logTime']);
						$list['useValue']=$list['oldValue']-$list['newValue'];
						$list['typeDes']=isset($subType[$list['type']])?$subType[$list['type']]:$list['type'];
						$list['subTypeDes']=isset($subType[$list['subType']])?$subType[$list['subType']]:$list['subType'];
					}
					$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				}
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏银币操作日志
	 */
	public function actionUserSilverLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$_GET['detailId'] = intval($_GET['detailId']);
		$_GET['playerId'] = intval($_GET['playerId']);
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$selected['detailId']=$_GET['detailId'];
			$selected['playerId']=$_GET['playerId'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/operationLog');
			


			if($_GET['detailId']){
				$dataList=$this->getApi()->select($_GET['detailId'],$_GET['playerId'],$_GET['page'],PAGE_SIZE,1);
			}else{
				$dataList=$this->getApi()->select($_GET['type'],$_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE,1);
			}
			
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if ($dataList['data']['dataList']['result']){
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['logTime']=date('Y-m-d H:i:s',$list['createAtStr']);
					}
				}
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏粮食操作日志
	 */
	public function actionUserFoodLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$_GET['detailId'] = intval($_GET['detailId']);
			$_GET['playerId'] = intval($_GET['playerId']);
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$selected['detailId']=$_GET['detailId'];
			$selected['playerId']=$_GET['playerId'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/operationLog');			

			if($_GET['detailId']){
				$dataList=$this->getApi()->select($_GET['detailId'],$_GET['playerId'],$_GET['page'],PAGE_SIZE,2);
			}else{
				$dataList=$this->getApi()->select($_GET['type'],$_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE,2);
			}			
			
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if ($dataList['data']['dataList']['result']){
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['logTime']=date('Y-m-d H:i:s',$list['createAtStr']);
					}
				}
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 修改玩家的游戏名
	 */
	public function actionModifyName(){		
		switch($_REQUEST['doaction']){
//			case 'findPlayer':{
//				$this->_modifyNameFindPlayer();
//			}
			case 'modifyName':{
				$this->_modifyNameDoit();
			}
			default:{
				$this->_modifyNameIndex();
			}
		}
	}
	
	private function _modifyNameIndex(){				
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$_POST['playerType'] = intval($_POST['playerType']);
			$_POST['player'] = trim($_POST['player']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'api/modifyPlayer');
			if($_POST['playerType']){
				$dataList=$rpc->findPlayerByName($_POST['player']);
			}else{
				$dataList=$rpc->findPlayerById(intval($_POST['player']));
			}			
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$dataList = json_decode($dataList,true);
				$jump = Tools::url(CONTROL,'ModifyName',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
				if(intval($dataList['playerId']) == 0){
					$this->_utilMsg->showMsg('查无数据',-1,$jump);
				}
				$this->_view->assign('URL_ReQuery',$jump);
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _modifyNameDoit(){
		if(!$_REQUEST['server_id']){
			$this->_utilMsg->showMsg('服务器为空',-1);		
		}
		if ($this->_isPost()){
			$_POST['playerId'] = intval($_POST['playerId']);
			$_POST['rename'] = trim($_POST['rename']);
			if($_POST['playerId']<=0 && $_POST['rename']==''){
				$this->_utilMsg->showMsg('提交有误',-1);	
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/modifyPlayer');			
			$dataList=$this->getApi()->modifyName($_POST['playerId'],$_POST['rename']);
			if($dataList instanceof PHPRPC_Error){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$jump = Tools::url(CONTROL,'ModifyName',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
				$this->_utilMsg->showMsg('操作成功',1,$jump,1);	
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$this->_utilMsg->showMsg('非POST提交',-1);	
		}
	}
	

	
	
}