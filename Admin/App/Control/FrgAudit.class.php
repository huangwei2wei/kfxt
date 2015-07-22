<?php
class Control_FrgAudit extends Control {
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_ApplyDataFrg
	 * @var Model_ApplyDataFrg
	 */
	private $_modelApplyDataFrg;

	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 我的申请
	 */
	public function actionMyApplication(){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$service = $this->_modelApplyDataFrg->getAudit('service');
		$operation = $this->_modelApplyDataFrg->getAudit('operation');
		$gold = $this->_modelApplyDataFrg->getAudit('gold');
		$type = array_merge($service,$operation,$gold);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$this->_view($type,$userClass['_id']);	
	}
	
	/**
	 * 有运营商权限限制的审核列表
	 */
	public function actionMyAuditList(){
		$this->_url['accept']=Tools::url(CONTROL,'MyAuditDo',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'MyAuditDo',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$service = $this->_modelApplyDataFrg->getAudit('service');
		$operation = $this->_modelApplyDataFrg->getAudit('operation');
		$gold = $this->_modelApplyDataFrg->getAudit('gold');
		$type = array_merge($service,$operation,$gold);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$myOperators = $this->_utilRbac->getOperatorActList();	//个人授权可操作的运营商
		if(empty($myOperators)){
			$this->_utilMsg->showMsg('no power!',-1);
		}
		$myOperators = array_keys($myOperators);
		$this->_view($type,0,$myOperators);
	}
	
	/**
	 * 有运营商权限限制的审核
	 */
	public function actionMyAuditDo(){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$service = $this->_modelApplyDataFrg->getAudit('service');
		$operation = $this->_modelApplyDataFrg->getAudit('operation');
		$gold = $this->_modelApplyDataFrg->getAudit('gold');
		$type = array_merge($service,$operation,$gold);		
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$myOperators = $this->_utilRbac->getOperatorActList();	//个人授权可操作的运营商
		if(empty($myOperators)){
			$this->_utilMsg->showMsg('no power!',-1);
		}
		$myOperators = array_keys($myOperators);
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		switch ($_GET['doaction']){
			case 'accept' :{
				$this->_accept($type,$myOperators);
				return ;
			}
			case 'reject' :{
				$this->_reject($type);
				return ;
			}
			default:{
				$this->_utilMsg(false,1,Tools::url(CONTROL,'ServiceIndex'));
			}
		}
	}
	
	/**
	 * 客服审核列表
	 */
	public function actionServiceIndex(){
		$this->_url['accept']=Tools::url(CONTROL,'Service',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Service',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$this->_view($this->_modelApplyDataFrg->getAudit('service'));
	}
		
	/**
	 * 运营商审核列表
	 */
	public function actionOperationIndex(){
		$this->_url['accept']=Tools::url(CONTROL,'Operation',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Operation',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$this->_view($this->_modelApplyDataFrg->getAudit('operation'));
	}
	
	/**
	 * 金币审核列表
	 */
	public function actionGoldIndex(){
		$this->_url['accept']=Tools::url(CONTROL,'Gold',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Gold',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$this->_view($this->_modelApplyDataFrg->getAudit('gold'));
	}
	
	public function actionService(){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		switch ($_GET['doaction']){
			case 'accept' :{
				$this->_accept($this->_modelApplyDataFrg->getAudit('service'));
				return ;
			}
			case 'reject' :{
				$this->_reject($this->_modelApplyDataFrg->getAudit('service'));
				return ;
			}
			default:{
				$this->_utilMsg(false,1,Tools::url(CONTROL,'ServiceIndex'));
			}
		}
	}
	
	public function actionOperation(){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		switch ($_GET['doaction']){
			case 'accept' :{
				$this->_accept($this->_modelApplyDataFrg->getAudit('operation'));
				return ;
			}
			case 'reject' :{
				$this->_reject($this->_modelApplyDataFrg->getAudit('operation'));
				return ;
			}
			default:{
				$this->_utilMsg(false,1,Tools::url(CONTROL,'OperationIndex'));
			}
		}
	}
	
	public function actionGold(){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		switch ($_GET['doaction']){
			case 'accept' :{
				$this->_accept($this->_modelApplyDataFrg->getAudit('gold'));
				return ;
			}
			case 'reject' :{
				$this->_reject($this->_modelApplyDataFrg->getAudit('gold'));
				return ;
			}
			default:{
				$this->_utilMsg(false,1,Tools::url(CONTROL,'GoldIndex'));
			}
		}
	}
	
	/**
	 * 接受
	 */
	private function _accept($options,$operatorsLimit=array()){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$this->_modelApplyDataFrg->set_options($options);
		$this->_modelApplyDataFrg->set_postArr($_POST);
		$this->_modelApplyDataFrg->set_OperatorLimit($operatorsLimit);		
		$data=$this->_modelApplyDataFrg->accept();
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],null);
	}
	
	/**
	 * 拒绝
	 */
	private function _reject($options){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$this->_modelApplyDataFrg->set_options($options);
		$this->_modelApplyDataFrg->set_postArr($_POST);
		$data=$this->_modelApplyDataFrg->reject();
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}
	
	
	/**
	 * 显示页面
	 * @param 可显示的参数 $options
	 */
	private function _view($options,$apply_id_of_mine = 0,$myOperator = array()){
		$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
		$auditType=$this->_getGlobalData('frg_audit_type');
		foreach ($auditType as $key=>&$value){
			if (!in_array($key,$options))unset($auditType[$key]);
		}
		
		$user=$this->_getGlobalData('user');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$gameServerList=$this->_getGlobalData('gameser_list');
		
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelApplyDataFrg->tName());
		if($myOperator){
			$myOperator = implode(',',$myOperator);
			if(strpos($myOperator,',')){
				$helpSqlSearch->set_conditions("operator_id in ({$myOperator})");
			}else{
				$helpSqlSearch->set_conditions("operator_id={$myOperator}");				
			}
		}
		if ($_GET['type'] && in_array($_GET['type'],$options)){
			$helpSqlSearch->set_conditions("type={$_GET['type']}");
			$this->_view->assign('selectedType',$_GET['type']);
		}else {
			$helpSqlSearch->set_conditions("type in (".implode(',',$options).")");
		}
		
		if ($_GET['send']!=''){
			$helpSqlSearch->set_conditions("is_send={$_GET['send']}");
			$this->_view->assign('selectedSend',$_GET['send']);
		}
		if ($_GET['audit_user_id']){
			$helpSqlSearch->set_conditions("audit_user_id='{$_GET['audit_user_id']}'");
			$this->_view->assign('selectedAuditUserId',$_GET['audit_user_id']);
		}
		if ($_GET['apply_user_id']){
			$helpSqlSearch->set_conditions("apply_user_id='{$_GET['apply_user_id']}'");
			$this->_view->assign('selectedApplyUserId',$_GET['apply_user_id']);
		}
		elseif($apply_id_of_mine > 0){
			$helpSqlSearch->set_conditions("apply_user_id='{$apply_id_of_mine}'");
			$this->_view->assign('selectedApplyUserId',$apply_id_of_mine);
		}
		//搜索玩家
		if($_GET['user_info'] != ''){
			$helpSqlSearch->set_conditions("user_info Like '%{$_GET['user_info']}%'");
			$this->_view->assign('user_info',$_GET['user_info']);
		}
		$helpSqlSearch->set_orderBy('is_send asc , create_time desc');
		
		if (!$_POST['xls'])$helpSqlSearch->setPageLimit($_GET['page'],10);	//如果为引出excel
		
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelApplyDataFrg->select($sql);
		Tools::import('Util_FontColor');
		if ($dataList){
			foreach ($dataList as $key=>&$list){
				if (!in_array($list['type'],$options))unset($dataList[$key]);//如果不是在选项中的,就unset掉
				$list['word_type']=Util_FontColor::getFRGauditType($list['type'],$auditType[$list['type']]);
				$list['word_apply_user_id']=$user[$list['apply_user_id']]['nick_name'];
				$list['word_audit_user_id']=$user[$list['audit_user_id']]['nick_name'];
				$list['send_action']=unserialize($list['send_action']);
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['word_is_send']=Util_FontColor::getFRGsendType($list['is_send']);
				$list['word_server_id']=$list['server_id']?$gameServerList[$list['server_id']]['full_name']:Tools::getLang('AUDIT_MANYSERVER',__CLASS__);
				$list['url_view']=$this->_createAuditDetailUrl($list);
				$list['send_time']=$list['send_time']?date('Y-m-d H:i:s',$list['send_time']):'';
				$list['cause']=stripcslashes($list['cause']);
				$list['audit_ip']=$list['audit_ip']?long2ip($list['audit_ip']):'';
				$list['apply_ip']=$list['apply_ip']?long2ip($list['apply_ip']):'';
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelApplyDataFrg->findCount($conditions),'prepage'=>10));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$auditType['']=Tools::getLang('ALL','Common');
		$this->_view->assign('auditType',$auditType);
		
		$sendType=Util_FontColor::$frgSendString;
		$sendType['']=Tools::getLang('ALL','Common');
		$this->_view->assign('sendType',$sendType);
		$users=Model::getTtwoArrConvertOneArr($user,'Id','full_name');
		$this->_view->assign('users',$users);
		if($apply_id_of_mine>0){
			$this->_view->set_tpl(array('body'=>'FrgAudit/MyApplication.html'));
		}
		else{
			$this->_view->set_tpl(array('body'=>'FrgAudit/Audit.html'));
		}		
		$this->_utilMsg->createNavBar();
		if ($_GET['xls']){
			Tools::import('Util_ExportExcel');
			$this->_utilExportExcel=new Util_ExportExcel(Tools::getLang('AUDIT_EXCELNAME',__CLASS__),'Excel/FrgAudit',$dataList);
			$this->_utilExportExcel->outPutExcel();
		}else {
			$this->_view->display();
		}
		
	}
	
	
	/**
	 * 创建审核url
	 */
	private function _createAuditDetailUrl($list){
		switch ($list['type']){
			case '8' :
			case '7' :
			case '1' :{//奖励发放
				return false;
			}
			case '2':{//玩家数值修改
				$url=Tools::url('MasterFRG','UserData',array('audit_id'=>$list['Id'],'server_id'=>$list['server_id'],'request'=>'read'));
				return $url;
			}
			case '3' :{//工厂
				$url=Tools::url('MasterFRG','UserFactory',array('audit_id'=>$list['Id'],'server_id'=>$list['server_id'],'request'=>'read'));
				return $url;
			}
			case '4' :{//员工数值
				$url=Tools::url('MasterFRG','UserEmployeeTune',array('audit_id'=>$list['Id'],'server_id'=>$list['server_id'],'request'=>'read'));
				return $url;
			}
			case '5' :{//座架
				$url=Tools::url('MasterFRG','UserCarTune',array('audit_id'=>$list['Id'],'server_id'=>$list['server_id'],'request'=>'read'));
				return $url;
			}
			case '6' :{//商会数值
				$url=Tools::url('MasterFRG','UserCofcTune',array('audit_id'=>$list['Id'],'server_id'=>$list['server_id'],'request'=>'read'));
				return $url;
			}
		}
	}
	
	
}