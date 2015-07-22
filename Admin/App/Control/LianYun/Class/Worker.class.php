<?php
class Control_Worker extends LianYun {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	private $_utilRbac;
	
	/**
	 * Model_LyWorker
	 * @var Model_LyWorker
	 */
	private $_modelLyWorker;
	
	private $_modelLyProblem;
	
	private $_modelGameOperator;
	
	/**
	 * 运营商详细信息
	 * @var Model_LyOperatorInfo
	 */
	private $_modelLyOperatorInfo;
	
	private $_pageSize = 10;
	
	
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
	}
	
	private function _createUrl(){
		$this->_url['UploadImg_Bulletin']=Tools::url('Default','ImgUpload',array('type'=>'Bulletin'));
		$this->_url['Problem_Add']=Tools::url(CONTROL,'ProblemOpt',array('doaction'=>'add','zp'=>'LianYun'));
		$this->_url['Problem']=Tools::url(CONTROL,'Problem',array('zp'=>'LianYun'));
		$this->_url['read']=Tools::url(CONTROL,'Problem',array('doaction'=>'read','zp'=>'LianYun'));

		$this->_url['operator_info']=Tools::url(CONTROL,'OperatorInfo',array('zp'=>'LianYun'));
		$this->_url['operator_info_add']=Tools::url(CONTROL,'OperatorInfoEdit',array('doaction'=>'add','zp'=>'LianYun'));
	
		$this->_view->assign('url',$this->_url);
	}
	
	public function actionIndex(){
		$users=$this->_getGlobalData('user_index_id');
		$this->_modelLyWorker=$this->_getGlobalData('Model_LyWorker','object');
		$gameTypes=$this->_modelLyWorker->getGameType();
		$type=$this->_modelLyWorker->getType();
		$progress=$this->_modelLyWorker->getProgress();
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelLyWorker->tName());
		$helpSqlSearch->set_orderBy('edit_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],$this->_pageSize);
		$selected=array();
		if ($_GET['game_type_id']){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$selected['game_type_id']=$_GET['game_type_id'];
		}
		if ($_GET['type']){
			$helpSqlSearch->set_conditions("type={$_GET['type']}");
			$selected['type']=$_GET['type'];
		}
		if ($_GET['progress']){
			$helpSqlSearch->set_conditions("progress={$_GET['progress']}");
			$selected['progress']=$_GET['progress'];
		}
		if ($_GET['title']){
			$helpSqlSearch->set_conditions(" (title like '%{$_GET['title']}%' or  content like '%{$_GET['title']}%')");
			$selected['title']=$_GET['title'];
		}
		if ($_GET['start_time'] && $_GET['end_time']){
			$helpSqlSearch->set_conditions("edit_time between ".strtotime($_GET['start_time'])." and ".strtotime($_GET['end_time']));
			$selected['start_time']=$_GET['start_time'];
			$selected['end_time']=$_GET['end_time'];
		}
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelLyWorker->select($sql);
		if ($dataList){
			foreach ($dataList as &$list){
				$list['time']=date('Y-m-d H:i:s',$list['edit_time']);
				$list['url_edit']=Tools::url(CONTROL,'Edit',array('zp'=>PACKAGE,'Id'=>$list['Id']));
				$list['url_del']=Tools::url(CONTROL,'Del',array('zp'=>PACKAGE,'Id'=>$list['Id']));
				$list['word_game_type_id']=$gameTypes[$list['game_type_id']];
				$list['word_type']=$type[$list['type']];
				$list['word_progress']=$progress[$list['progress']];
				$list['content']=stripcslashes($list['content']);
				$list['word_user_id']=$users[$list['user_id']];
			}
			$this->_view->assign('dataList',$dataList);
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelLyWorker->findCount($conditions),'perpage'=>$this->_pageSize));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$gameTypes['']='所有';
		$type['']='所有';
		$progress['']='所有';
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('type',$type);
		$this->_view->assign('progress',$progress);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionAdd(){
		$this->_modelLyWorker=$this->_getGlobalData('Model_LyWorker','object');
		if ($this->_isPost()){
			$info=$this->_modelLyWorker->insert($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$this->_view->assign('gameTypes',$this->_modelLyWorker->getGameType());
			$this->_view->assign('type',$this->_modelLyWorker->getType());
			$this->_view->assign('progress',$this->_modelLyWorker->getProgress());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	public function actionEdit(){
		$this->_modelLyWorker=$this->_getGlobalData('Model_LyWorker','object');
		if ($this->_isPost()){
			$info=$this->_modelLyWorker->edit($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$dataList=$this->_modelLyWorker->findById($_GET['Id']);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('gameTypes',$this->_modelLyWorker->getGameType());
			$this->_view->assign('type',$this->_modelLyWorker->getType());
			$this->_view->assign('progress',$this->_modelLyWorker->getProgress());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	public function actionDel(){
		$this->_modelLyWorker=$this->_getGlobalData('Model_LyWorker','object');
		$this->_modelLyWorker->delById($_GET['Id']);
		$this->_utilMsg->showMsg(false);
	}
	
	public function actionProblem(){
		switch ($_GET['doaction']){
			case 'read' :{
				$this->_problemRead();
				break ;
			}
			default:{
				$this->_problemIndex();
			}
		}
	}
	
	private function _problemIndex(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes['']='全部';
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		$operators[0]="全部";
		$servers=$this->_getGlobalData('gameser_list');
		$servers=Model::getTtwoArrConvertOneArr($servers,'Id','server_name');
		$servers[0]="全部";
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperator = $this->_modelGameOperator->getGameOperator(true);
		$selectedJson = NULL;
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$this->_modelLyProblem = $this->_getGlobalData('Model_LyProblem','Object');
		$helpSqlSearch->set_tableName($this->_modelLyProblem->tName());
		$selectedJson = array();
		$selectedJson['game_type_id'] = $_GET['game_type_id'];
		$selectedJson['operator_id'] = $_GET['operator_id'];
		$selected['start_time'] = $_GET['start_time'];
		$selected['end_time'] = $_GET['end_time'];
		$selected['user_id'] = $_GET['user_id'];
		$start_time = strtotime($_GET['start_time']);
		if($start_time>0){
			$helpSqlSearch->set_conditions("start_time>={$start_time}");
		}
		$end_time = strtotime($_GET['end_time']);
		if($end_time>0){
			$end_time +=86399;
			$helpSqlSearch->set_conditions("end_time<={$end_time}");
		}
		if($_GET['game_type_id']>0){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
		}
		if($_GET['operator_id']>0){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
		}
		if($_GET['user_id']>0){
			$helpSqlSearch->set_conditions("user_id={$_GET['user_id']}");
		}
		$helpSqlSearch->set_orderBy('Id desc');
		$helpSqlSearch->setPageLimit($_GET['page'],$this->_pageSize);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->_modelLyProblem->select($sql);
		$users=$this->_getGlobalData('user_index_id');
		$userClass=$this->_utilRbac->getUserClass();
		foreach($dataList as &$sub){
			$sub['user_id'] = $users[$sub['user_id']];
			$sub['game_type_id'] = $gameTypes[$sub['game_type_id']];
			$sub['operator_id'] = $operators[$sub['operator_id']];
			$sub['server_id'] = $servers[$sub['server_id']];
			$sub['start_time'] = date('Y-m-d',$sub['start_time']);
			$sub['end_time'] = date('Y-m-d',$sub['end_time']);
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['not_read'] = unserialize($sub['not_read']);
			if(is_array($sub['not_read'])){
				$sub['iNoRead'] = in_array($userClass['_id'],$sub['not_read'])?true:false;
				$tmp = array();
				foreach($sub['not_read'] as $val){
					$tmp[] = $users[$val];
				}
				$sub['not_read'] = implode(',',$tmp);
				unset($tmp);
			}
			$sub['url_del']=Tools::url(CONTROL,'ProblemOpt',array('Id'=>$sub['Id'],'doaction'=>'del','zp'=>'LianYun'));
			$sub['url_edit']=Tools::url(CONTROL,'ProblemOpt',array('Id'=>$sub['Id'],'doaction'=>'edit','zp'=>'LianYun'));
		}
		$helpPage=new Help_Page(array('total'=>$this->_modelLyProblem->findCount($conditions),'perpage'=>$this->_pageSize));
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign('users',$users);
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('gameTypesJson',json_encode($gameTypes));
		$this->_view->assign('gameOperatorJson',json_encode($gameOperator));
		$this->_view->assign('selectedJson',json_encode($selectedJson));
		$this->_view->assign('selected',$selected);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _problemRead(){
		$this->_modelLyProblem=$this->_getGlobalData ( 'Model_LyProblem', 'object' );
		$theData = $this->_modelLyProblem->findById(intval($_GET['Id']));
		$theData['not_read'] = unserialize($theData['not_read']);
		$userClass=$this->_utilRbac->getUserClass();
		$data = NULL;
		if(is_array($theData['not_read'])){
			$data = array_diff($theData['not_read'],array($userClass['_id']));
		}
		$tag = false;
		if($this->_modelLyProblem->findById(intval($_GET['Id']))){
			$tag = $this->_modelLyProblem->update(array('not_read'=>serialize($data)),'Id='.intval($_GET['Id']));
		}
		$returnData = array('status'=>0,'info'=>'阅读失败','data'=>NUll);
		if($tag){
			$users=$this->_getGlobalData('user_index_id');
			$tmp = array();
			if(is_array($data)){
				foreach($data as $val){
					$tmp[] = $users[$val];
				}
				$data = implode(',',$tmp);
			}			
			unset($tmp);
			$returnData = array('status'=>1,'info'=>'阅读成功','data'=>$data);
		}
		$this->_returnAjaxJson($returnData);
	}
	
	public function actionProblemOpt(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_problemAdd();
				break ;
			}
			case 'del' :{
				$this->_problemDel();
				break ;
			}
			case 'edit' :{
				$this->_problemEdit();
				break ;
			}
		}
	}
	
	private function _problemCheck($gameTypes,$operators){
		if(!array_key_exists($_POST['game_type_id'],$gameTypes)){
			$this->_utilMsg->showMsg('游戏不存在',-1,2);
		}
		if(!array_key_exists($_POST['operator_id'],$operators) && $_POST['operator_id']!=0){
			$this->_utilMsg->showMsg('运营商不存在',-1,2);
		}
		$servers=$this->_getGlobalData('gameser_list');
		if(!array_key_exists($_POST['server_id'],$servers) && $_POST['server_id']!=0){
			$this->_utilMsg->showMsg('服务器不存在',-1,2);
		}
		if(!strtotime($_POST['start_time'])){
			$this->_utilMsg->showMsg('需要设定开始时间',-1,2);
		}
		if(!strtotime($_POST['end_time'])){
			$this->_utilMsg->showMsg('需要设定结束时间',-1,2);
		}
		if(strtotime($_POST['end_time'])>strtotime($_POST['end_time'])){
			$this->_utilMsg->showMsg('开始时间大于结束时间',-1,2);
		}
		if(trim($_POST['content']) == ''){
			$this->_utilMsg->showMsg('内容必须',-1,2);
		}
	}
	
	private function _problemAdd(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		if($this->_isPost()){
			$this->_problemCheck($gameTypes,$operators);
			$userClass=$this->_utilRbac->getUserClass();
			$data['user_id'] = $userClass['_id'];
			$data['game_type_id'] = $_POST['game_type_id'];
			$data['operator_id'] = $_POST['operator_id'];
			$data['server_id'] = $_POST['server_id'];
			$data['start_time'] = strtotime($_POST['start_time']);
			$data['end_time'] = strtotime($_POST['end_time'])+86399;
			$data['content'] = $_POST['content'];
			$data['create_time'] = CURRENT_TIME;
			$data['not_read'] = serialize($_POST['users']);
			$this->_modelLyProblem=$this->_getGlobalData ( 'Model_LyProblem', 'object' );
			$this->_modelLyProblem->add($data);
			$this->_utilMsg->showMsg('添加成功',1,$this->_url['Problem']);
		}
		else{
			
			$this->_modelGameOperator=$this->_getGlobalData ( 'Model_GameOperator', 'object' );
			$servers = $this->_modelGameOperator->getGmOptSev();
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			#------显示分组用户------#
			$this->_view->assign('gameTypes',json_encode($gameTypes));
			$this->_view->assign('operators',json_encode($operators));
			$this->_view->assign('servers',json_encode($servers));
			$this->_view->assign('org',$orgList);	//用户分组
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}

	}
	
	private function _problemDel(){
		$this->_modelLyProblem=$this->_getGlobalData('Model_LyProblem','object');
		if ($this->_modelLyProblem->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else{
			$this->_utilMsg->showMsg('删除公告失败',-2);
		}
	}
	
	private function _problemEdit(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		$this->_modelLyProblem=$this->_getGlobalData ( 'Model_LyProblem', 'object' );
		if($this->_isPost()){
			$this->_problemCheck($gameTypes,$operators);
			$userClass=$this->_utilRbac->getUserClass();
			$data['user_id'] = $userClass['_id'];
			$data['game_type_id'] = $_POST['game_type_id'];
			$data['operator_id'] = $_POST['operator_id'];
			$data['server_id'] = $_POST['server_id'];
			$data['start_time'] = strtotime($_POST['start_time']);
			$data['end_time'] = strtotime($_POST['end_time'])+86399;
			$data['content'] = $_POST['content'];
			$data['create_time'] = CURRENT_TIME;
			$data['not_read'] = serialize($_POST['users']);
			$this->_modelLyProblem->update($data,'Id='.intval($_GET['Id']));
			$this->_utilMsg->showMsg('编辑成功',1,$this->_url['Problem']);
		}
		else{
			$theData = $this->_modelLyProblem->findById(intval($_GET['Id']));
			if(!$theData)$this->_utilMsg->showMsg('数据不存在',-1,2);
			$selected['game_type_id'] = $theData['game_type_id'];
			$selected['operator_id'] = $theData['operator_id'];
			$selected['server_id']	= $theData['server_id'];
			$selected['start_time'] = date('Y-m-d',$theData['start_time']);
			$selected['end_time'] = date('Y-m-d',$theData['end_time']);
			$selected['content'] = $theData['content'];
			$selected['not_read'] = unserialize($theData['not_read']);
			$this->_modelGameOperator=$this->_getGlobalData ( 'Model_GameOperator', 'object' );
			$servers = $this->_modelGameOperator->getGmOptSev();
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			#------显示分组用户------#
			$this->_view->assign('selected',$selected);
			$this->_view->assign('gameTypes',json_encode($gameTypes));
			$this->_view->assign('operators',json_encode($operators));
			$this->_view->assign('servers',json_encode($servers));
			$this->_view->assign('org',$orgList);	//用户分组
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	public function actionOperatorInfo(){
		$_GET['page'] = intval(max(1,$_GET['page']));
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes[0] = '全部';
		$operators = $this->_getGlobalData ( 'operator_list' );
		$operators = Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');

		//谁出客服
		$CsSupply = array('0' => '我方','1' => '对方');
		//谁出服务器
		$ServerSupply = array('0' => '我方','1' => '对方');

		$this->_modelLyOperatorInfo = $this->_getGlobalData ( 'Model_LyOperatorInfo', 'object' );
		
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelLyOperatorInfo->tName());
		
		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		if($_GET['game_type_id'] > 0){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
		}
		//《--选择
		$selected['game_type_id'] = $_GET['game_type_id'];
		//选择--》
		
		$helpSqlSearch->set_orderBy('Id desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();
		
		$dataList=$this->_modelLyOperatorInfo->select($sql);
		$urlLenLimit = 25;
		foreach($dataList as &$value){
			$value['game_type_id'] = isset($gameTypes[$value['game_type_id']])?$gameTypes[$value['game_type_id']]:'-';
			$value['operator_id'] = isset($operators[$value['operator_id']])?$operators[$value['operator_id']]:'-';
			$value['cs_supply'] = isset($CsSupply[$value['cs_supply']])?$CsSupply[$value['cs_supply']]:'-';
			$value['server_supply'] = isset($ServerSupply[$value['server_supply']])?$ServerSupply[$value['server_supply']]:'-';
			$value['edit'] = Tools::url(CONTROL,'OperatorInfoEdit',array('doaction'=>'edit','zp'=>'LianYun','Id'=>$value['Id']));
			$value['del'] = Tools::url(CONTROL,'OperatorInfoEdit',array('doaction'=>'del','zp'=>'LianYun','Id'=>$value['Id']));

			$value['website_url_show'] = strlen($value['website_url'])>$urlLenLimit?substr($value['website_url'],0,$urlLenLimit).'...':$value['website_url'];
			$value['forum_url_show'] = strlen($value['forum_url'])>$urlLenLimit?substr($value['forum_url'],0,$urlLenLimit).'...':$value['forum_url'];
			$value['datum_url_show'] = strlen($value['datum_url'])>$urlLenLimit?substr($value['datum_url'],0,$urlLenLimit).'...':$value['datum_url'];
	
		}
		
		$conditions=$helpSqlSearch->get_conditions();
		$total = $this->_modelLyOperatorInfo->findCount($conditions);
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_view->assign('selected',$selected);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionOperatorInfoEdit(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_operatorInfoAdd();
				break ;
			}
			case 'del' :{
				$this->_operatorInfoDel();
				break ;
			}
			case 'edit' :{
				$this->_operatorInfoEdit();
				break ;
			}
			default :{
				$this->_utilMsg->showMsg('不存在子方法！',-1);
			}
		}
	}
	
	private function _operatorInfoAdd(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		
		$operators = $this->_getGlobalData ( 'operator_list' );
		$operators = Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		//谁出客服
		$CsSupply = array('0' => '我方','1' => '对方');
		//谁出服务器
		$ServerSupply = array('0' => '我方','1' => '对方');
		if($this->_isPost()){
			if(!array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg('所选游戏不存在',-1);
			}
			if(!array_key_exists($_POST['operator_id'],$operators)){
				$this->_utilMsg->showMsg('所选运营商不存在',-1);
			}
			if(!array_key_exists($_POST['cs_supply'],$CsSupply)){
				$this->_utilMsg->showMsg('所选客服不存在',-1);
			}
			if(!array_key_exists($_POST['server_supply'],$ServerSupply)){
				$this->_utilMsg->showMsg('所选服务器不存在',-1);
			}
			if('' == trim($_POST['marking'])){
				$this->_utilMsg->showMsg('标识必须',-1);
			}
//			if('' == trim($_POST['company_name'])){
//				$this->_utilMsg->showMsg('公司必须',-1);
//			}
			$NeedField = 'game_type_id,operator_id,marking,company_name,cs_supply,server_supply,qq_group,website_url,forum_url,datum_url,principal,coupling';
			$AddData = Tools::fieldFilter($NeedField,$_POST);
			$this->_modelLyOperatorInfo = $this->_getGlobalData ( 'Model_LyOperatorInfo', 'object' );
			if($this->_modelLyOperatorInfo->add($AddData)){
				$this->_utilMsg->showMsg('添加成功',1,$this->_url['operator_info'],1);
			}else{
				$this->_utilMsg->showMsg('添加失败',-1);
			}	
		}		
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_view->assign('operator_id',$operators);
		$this->_view->assign('cs_supply',$CsSupply);
		$this->_view->assign('server_supply',$ServerSupply);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _operatorInfoDel(){
		$this->_modelLyOperatorInfo = $this->_getGlobalData ( 'Model_LyOperatorInfo', 'object' );
		if($this->_modelLyOperatorInfo->delById(intval($_GET['Id']))){
			$this->_utilMsg->showMsg('删除成功',1,$this->_url['operator_info'],1);
		}else{
			$this->_utilMsg->showMsg('删除失败',-1);
		}
	}
	
	private function _operatorInfoEdit(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		
		$operators = $this->_getGlobalData ( 'operator_list' );
		$operators = Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		//谁出客服
		$CsSupply = array('0' => '我方','1' => '对方');
		//谁出服务器
		$ServerSupply = array('0' => '我方','1' => '对方');
		
		$this->_modelLyOperatorInfo = $this->_getGlobalData ( 'Model_LyOperatorInfo', 'object' );
		
		if($this->_isPost()){
			if(!array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg('所选游戏不存在',-1);
			}
			if(!array_key_exists($_POST['operator_id'],$operators)){
				$this->_utilMsg->showMsg('所选运营商不存在',-1);
			}
			if(!array_key_exists($_POST['cs_supply'],$CsSupply)){
				$this->_utilMsg->showMsg('所选客服不存在',-1);
			}
			if(!array_key_exists($_POST['server_supply'],$ServerSupply)){
				$this->_utilMsg->showMsg('所选服务器不存在',-1);
			}
			if('' == trim($_POST['marking'])){
				$this->_utilMsg->showMsg('标识必须',-1);
			}
			if('' == trim($_POST['company_name'])){
				$this->_utilMsg->showMsg('标识必须',-1);
			}
			$NeedField = 'game_type_id,operator_id,marking,company_name,cs_supply,server_supply,qq_group,website_url,forum_url,datum_url,principal,coupling';
			$UpdateData = Tools::fieldFilter($NeedField,$_POST);
			if($this->_modelLyOperatorInfo->update($UpdateData,"Id = {$_GET['Id']}")){
				$this->_utilMsg->showMsg('修改成功',1,$this->_url['operator_info'],1);
			}
			else{
				$this->_utilMsg->showMsg('修改失败',-1);
			}
		}
		$data = $this->_modelLyOperatorInfo->findById($_GET['Id']);		
		$this->_view->assign('data',$data);
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_view->assign('operator_id',$operators);
		$this->_view->assign('cs_supply',$CsSupply);
		$this->_view->assign('server_supply',$ServerSupply);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
}