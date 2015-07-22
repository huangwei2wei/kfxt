<?php
Tools::import('Control_ApplyBase');
class Control_Apply extends ApplyBase {

	private $_modelApply;

	private $_modelApplyType;

	const CS_LIST = 1;

	const OPT_LIST = 2;

	const GOLD_LIST = 3;

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['URL_ApplyTypeIndex'] = Tools::url(CONTROL,'ApplyType');
		$this->_url['URL_ApplyTypeCahce'] = Tools::url(CONTROL,'ApplyType',array('doaction'=>'cache'));
		$this->_url['URL_ApplyTypeAdd'] = Tools::url(CONTROL,'ApplyTypeMng',array('doaction'=>'add'));
		$this->_url['URL_MyCsApply'] = Tools::url(CONTROL,'CsIndex',array('doaction'=>'myapply'));
		$this->_url['URL_MyCsAudit'] = Tools::url(CONTROL,'CsIndex',array('doaction'=>'myaudit'));
		$this->_url['URL_MyOptApply'] = Tools::url(CONTROL,'OperatorIndex',array('doaction'=>'myapply'));
		$this->_url['URL_MyOptAudit'] = Tools::url(CONTROL,'OperatorIndex',array('doaction'=>'myaudit'));
		$this->_view->assign('url',$this->_url);
	}

	public function actionApplyType(){
		switch($_GET['doaction']){
			case 'cache':{
				$this->_applyTypeCache();
				return;
			}
			default :{
				$this->_applyTypeIndex();
				return;
			}
		}
	}

	private function _applyTypeIndex(){
		//		$gameTypes=$this->_myGames;
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name',Tools::getLang('ALL','Common'));
		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$this->_modelApplyType = $this->_getGlobalData('Model_ApplyType','Object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelApplyType->tName());
		$_GET['game_type']=intval($_GET['game_type']);
		$_GET['list_type']=intval($_GET['list_type']);
		if($_GET['game_type']){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type']}");
		}
		if($_GET['list_type']){
			$helpSqlSearch->set_conditions("list_type={$_GET['list_type']}");
		}
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('Id desc');
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->_modelApplyType->select($sql);
		foreach($dataList as &$sub){
			$sub['game_type'] = isset($gameTypes[$sub['game_type']])?$gameTypes[$sub['game_type']]:$sub['game_type'];
			$sub['URL_edit'] = Tools::url(CONTROL,'ApplyTypeMng',array('doaction'=>'edit','Id'=>$sub['Id']));
			$sub['URL_del'] = Tools::url(CONTROL,'ApplyTypeMng',array('doaction'=>'del','Id'=>$sub['Id']));
		}
		$conditions=$helpSqlSearch->get_conditions();
		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$this->_modelApplyType->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign('selected',$_GET);
		$this->_view->assign('game_type',$gameTypes);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _applyTypeCache(){
		$this->_modelApplyType = $this->_getGlobalData('Model_ApplyType','Object');
		if ($this->_modelApplyType->createToCache ()) {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1 ,$this->_url ['URL_ApplyTypeIndex'],1);
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_ERROR','Common'), -2 );
		}
	}

	public function actionApplyTypeMng(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_applyTypeAdd();
				return;
			}
			case 'edit':{
				$this->_applyTypeEdit();
				return;
			}
			case 'del':{
				$this->_applyTypeDel();
				return;
			}
			default :{
				return;
			}
		}
	}

	private function _applyTypeAdd(){
		$this->_modelApplyType = $this->_getGlobalData('Model_ApplyType','Object');
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name','--请选择游戏--');
		if($this->_isPost()){
			$_POST['name'] = trim($_POST['name']);
			if($_POST['name'] == ''){
				$this->_utilMsg->showMsg('请填写类型名',-1);
			}
			if(!intval($_POST['list_type'])){
				$this->_utilMsg->showMsg('列表ID错误',-1);
			}
			$_POST['game_type'] = intval($_POST['game_type']);
			if(!$_POST['game_type'] || !array_key_exists($_POST['game_type'],$gameTypes)){
				$this->_utilMsg->showMsg('请选择游戏',-1);
			}

			$data = array(
				'name'=>$_POST['name'],
				'game_type'=>$_POST['game_type'],
				'list_type'=>$_POST['list_type'],
			);
			if($this->_modelApplyType->add($data)){
				$this->_utilMsg->showMsg('操作成功',1,$this->_url['URL_ApplyTypeIndex'],1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$this->_view->set_tpl ( array ('body' => 'Apply/ApplyTypeMng.html' ) );
		$this->_view->assign('game_type',$gameTypes);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _applyTypeEdit(){
		$this->_modelApplyType = $this->_getGlobalData('Model_ApplyType','Object');
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name','--请选择游戏--');
		$_GET['Id'] = intval($_GET['Id']);
		if(!$_GET['Id']){
			$this->_utilMsg->showMsg('ID ERROR',-1);
		}
		if($this->_isPost()){
			$_POST['name'] = trim($_POST['name']);
			if($_POST['name'] == ''){
				$this->_utilMsg->showMsg('请填写类型名',-1);
			}
			if(!intval($_POST['list_type'])){
				$this->_utilMsg->showMsg('列表ID错误',-1);
			}
			$_POST['game_type'] = intval($_POST['game_type']);
			if(!$_POST['game_type'] || !array_key_exists($_POST['game_type'],$gameTypes)){
				$this->_utilMsg->showMsg('请选择游戏',-1);
			}
			$data = array(
				'name'=>$_POST['name'],
				'game_type'=>$_POST['game_type'],
				'list_type'=>$_POST['list_type'],
			);
			if($this->_modelApplyType->update($data,"Id={$_GET['Id']}")){
				$this->_utilMsg->showMsg('操作成功',1,$this->_url['URL_ApplyTypeIndex'],1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$data = $this->_modelApplyType->findById($_GET['Id']);
		$this->_view->assign('data',$data);
		$this->_view->set_tpl ( array ('body' => 'Apply/ApplyTypeMng.html' ) );
		$this->_view->assign('game_type',$gameTypes);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _applyTypeDel(){
		//屏蔽删除
		$this->_utilMsg->showMsg('删除功能已屏蔽,请联系管理员',-1);
		return;
		$this->_modelApplyType = $this->_getGlobalData('Model_ApplyType','Object');
		$_GET['Id'] = intval($_GET['Id']);
		if($this->_modelApplyType->delById($_GET['Id'])){
			$this->_utilMsg->showMsg('操作成功',1,$this->_url['URL_ApplyTypeIndex'],1);
		}else{
			$this->_utilMsg->showMsg('操作失败',-1);
		}

	}

	public function actionAudit(){
		switch ($_GET['doaction']){
			case 'accept' :{
				$this->_accept();
				return ;
			}
			case 'reject' :{
				$this->_reject();
				return ;
			}
			default:{
				$this->_reject();
			}
		}
	}

	private function _accept(){
		if(empty($_POST['Id'])){
			$this->_utilMsg->showMsg('没有选择',-1);
		}
		if(is_array($_POST['Id'])){
			$Ids = implode(',',$_POST['Id']);
			$amount = count($_POST['Id']);
		}else{
			$Ids = trim($_POST['Id']);
			$amount = count(explode(',',$Ids));
		}
		$this->_modelApply = $this->_getGlobalData('Model_Apply','object');
		if($amount == 1){
			$sql = 'select * from '.$this->_modelApply->tName()." where Id = {$Ids}";
		}else{
			$sql = 'select * from '.$this->_modelApply->tName()." where Id in ({$Ids})";
		}
		$dataList = $this->_modelApply->select($sql);
		$validData = array();
		//用户对象
		$userClass=$this->_utilRbac->getUserClass();
		$showMsgInfos = array();


		foreach($dataList as $sub){
			if($sub['is_send'] == 0){
				$sub['send_data'] = unserialize($sub['send_data']);
				$sub['receiver_object'] = unserialize($sub['receiver_object']);
				$sendResult = '';
				switch($sub['send_type']){
					case 1:
						$sendResult = $this->sendToLocal($sub['send_data']);
						break;
					case 2:
						//post_data的__fields__字段可以获得审核时那条数据对应字段的值
						//例如$postData['__fields__']=array('batchId'=>'Id'),batchId就获得主键Id的值
						$postData = &$sub['send_data']['post_data'];
						if(isset($postData['__fields__']) && is_array($postData['__fields__'])){
							foreach($postData['__fields__'] as $tagerField=> $__dbField ){
								if(isset($sub[$__dbField])){
									$postData[$tagerField] = $sub[$__dbField];
								}
							}
							unset($postData['__fields__']);
						}
						$sendResult = $this->sendByHttp($sub['send_data'],$sub['receiver_object']);
						break;
					case 3:
						$sendResult = $this->sendByPhprpc($sub['send_data'],$sub['receiver_object']);
						break;
					default:
						$validData[$sub['Id']] = false;
				}
				$tmp['send_result'] = '';
// 				var_dump($sendResult);
				if($sendResult && is_array($sendResult)){
					$serverList=$this->_getGlobalData('gameser_list');
					foreach ($sendResult as $serverId => $val){
						if(!is_numeric($serverId)){
							if($serverId == 'result_mark'){	//单服的标识
								$tmp['result_mark'] = $val;
							}elseif($serverId == 'send_result'){	//单服的标识
								$tmp['send_result'] = $val;
							}else{	//单服其他情况
								$tmp['send_result'] = var_export ($sendResult,true);
								break;
							}
						}elseif(is_string($val)){	//处理多服情况
							$r = json_decode($val,true);
							if(is_array($r) && is_numeric($r['status'])){
								if($r['status'] == 1){
									$tmp['send_result'] .= $serverList[$serverId]['full_name'].':'.'<font color="#00FF00">审核成功</font>';
								}elseif ($r['status'] == 0){
									$tmp['send_result'] .= $serverList[$serverId]['full_name'].':'.'<font color="#FF0000">审核失败,信息：'.$r['info'].'</font>';
								}
							}else{
								$tmp['send_result'] .= $serverList[$serverId]['full_name'].':'.$val.'<br>';
							}
						}elseif(is_array($val)){
							$tmp['send_result'] = $serverList[$serverId]['full_name'].':'.$val['send_result'].'<br>';
							$tmp['result_mark'] = $val['result_mark'];
						}elseif(is_bool($val)){
							$tmp['send_result'] = ($val==true?'操作成功':'操作失败').'<br>';
						}
					}
				}elseif(is_string($sendResult)){
					$tmp['send_result'] = $sendResult;
				}
				$tmp['Id'] = $sub['Id'];
				$tmp['audit_user_id'] = $userClass['_id'];
				$tmp['audit_ip'] = Tools::getClientIP();
				$tmp['send_time'] = CURRENT_TIME;
				$tmp['is_send'] = 1;
				$validData[$sub['Id']] = $tmp;

				$showMsgInfos[$sub['Id']] = $tmp['send_result'];//用于审核后的提示
			}else{
				$validData[$sub['Id']] = false;
			}
		}
		$info = '审核完成';
		foreach($showMsgInfos as $Id=>$showMsgInfo){
			$info .="<div style='margin:5px;'>Id:{$Id}|$showMsgInfo</div>";
		}
		$this->_modelApply->AuditUpdata($validData);
		$this->_utilMsg->showMsg($info,1,1,false);
	}

	private function _reject(){
		if(empty($_POST['Id'])){
			$this->_utilMsg->showMsg('没有选择',-1);
		}
		if(is_array($_POST['Id'])){
			$Ids = implode(',',$_POST['Id']);
			$amount = count($_POST['Id']);
		}else{
			$Ids = trim($_POST['Id']);
			$amount = count(explode(',',$Ids));
		}
		$this->_modelApply = $this->_getGlobalData('Model_Apply','object');
		if($amount == 1){
			$sql = 'select * from '.$this->_modelApply->tName()." where Id = {$Ids}";
		}else{
			$sql = 'select * from '.$this->_modelApply->tName()." where Id in ({$Ids})";
		}
		$dataList = $this->_modelApply->select($sql);
		$validData = array();
		//用户对象
		$userClass=$this->_utilRbac->getUserClass();
		foreach($dataList as $sub){
			if($sub['is_send'] == 0){
				$tmp['Id'] = $sub['Id'];
				$tmp['audit_user_id'] = $userClass['_id'];
				$tmp['audit_ip'] = Tools::getClientIP();
				$tmp['send_time'] = CURRENT_TIME;
				$tmp['is_send'] = 2;
				$validData[$sub['Id']] = $tmp;
			}else{
				$validData[$sub['Id']] = false;
			}
		}
		$this->_modelApply->AuditUpdata($validData);
		$this->_utilMsg->showMsg('审核完成');
	}

	/**
	 * 显示申请列表
	 * @param int $ListType
	 * @param boolean $isLimit
	 */
	private function _showList($ListType = 0,$isLimit = true){
		$L_All = Tools::getLang('ALL','Common');	//“全部”的语言翻译		
		$this->_modelApply=$this->_getGlobalData('Model_Apply','object');
		$this->_modelApplyType=$this->_getGlobalData('Model_ApplyType','object');
		$ApplyType=$this->_modelApplyType->getApplyType($ListType,$_GET['game_type']);
		if(false === $ApplyType){
			$this->_utilMsg->showMsg('List Type Error',-1);$this->_utilMsg->showMsg('List Type Error',-1);
		}
		$ApplyType[0] = "[{$L_All}]";
		$_GET['game_type'] = intval($_GET['game_type']);
		if($isLimit){
			//个人权限下的游戏列表 和 运营商列表
			$GameType = $this->_myGames;
			$Operator = (array)$this->_myOperators;
			if($_GET['game_type']){
				$gameOperatorIndex = $this->_getGlobalData ( 'Model_GameOperator', 'object' )->findByGameTypeId ($_GET['game_type']);
				$gameOperatorIndex = Model::getTtwoArrConvertOneArr($gameOperatorIndex,'operator_id','operator_name');
				$Operator = array_intersect_key($Operator,$gameOperatorIndex);
			}

		}else{
			//没有限制地取得 游戏列表 和运营商列表
			$GameType=$this->_getGlobalData('game_type');
			$GameType=Model::getTtwoArrConvertOneArr($GameType,'Id','name');
			$Operator=$this->_getGlobalData('operator_list');
			$Operator=Model::getTtwoArrConvertOneArr($Operator,'Id','operator_name');
			if($_GET['game_type']){
				$gameOperatorIndex = $this->_getGlobalData ( 'Model_GameOperator', 'object' )->findByGameTypeId ($_GET['game_type']);
				$gameOperatorIndex = Model::getTtwoArrConvertOneArr($gameOperatorIndex,'operator_id','operator_name');
				$Operator = array_intersect_key($Operator,$gameOperatorIndex);
			}
		}
		if(empty($GameType) || empty($Operator)){
			$this->_utilMsg->showMsg('您还没有相应的游戏或运营商操作权限',-1,1,false);
		}
		$GameType[0]="[{$L_All}]";
		$Operator[-1]='[多运营商申请]';
		$Operator['']="[{$L_All}]";

		$ServerList=$this->_getGlobalData('gameser_list');
		$IsSend = array(0=>'未审核',1=>'通过',2=>'拒绝',''=>"[{$L_All}]");
		$PlayerType = array(''=>"-{$L_All}-",1=>'玩家Id',2=>'账号',3=>'昵称',0=>'无玩家');
		$users=$this->_getGlobalData('user');

		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelApply->tName());

		//判断是否具有游戏的操作权限
		if(($_GET['game_type'] && !array_key_exists($_GET['game_type'],$GameType)) ){
			$this->_utilMsg->showMsg('无权限访问该游戏',-1);
		}

		//重置 使用者不能访问的运营商
		$operatorId = intval($_GET['operator_id']);
		if($operatorId && !array_key_exists($operatorId,$Operator)){
			$_GET['operator_id'] = '';
			$operatorId = 0;
		}
		$helpSqlSearch->set_conditions("list_type={$ListType}");
		if($_GET['game_type']){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type']}");
		}elseif($isLimit){
			if(count($this->_myGames)==1){
				$helpSqlSearch->set_conditions('game_type='.intval(key($this->_myGames)));
			}else{
				$helpSqlSearch->set_conditions('game_type in ('.implode(',',array_keys($this->_myGames)).')');
			}
		}

		if($operatorId){
			$helpSqlSearch->set_conditions("operator_id={$operatorId}");
		}elseif($isLimit){		//权限受限	
			if(count($this->_myOperators)==1){
				$helpSqlSearch->set_conditions('operator_id='.intval(key($this->_myOperators)));
			}else{
				$helpSqlSearch->set_conditions('operator_id in ('.implode(',',array_keys($this->_myOperators)).')');
			}
		}
		$_GET['type'] = intval($_GET['type']);
		if(!$_GET['type'] || !array_key_exists($_GET['type'],$ApplyType)){
			$_GET['type'] = 0;
		}else{
			$helpSqlSearch->set_conditions("type={$_GET['type']}");
		}
		$isSend = $_GET['is_send'];
		if($isSend!=''){
			$isSend = intval($isSend);
			$helpSqlSearch->set_conditions("is_send={$isSend}");
		}
		$createTimeStart = strtotime($_GET['create_time_start']);
		if($createTimeStart){
			$helpSqlSearch->set_conditions("create_time>={$createTimeStart}");
		}
		$createTimeEnd = strtotime($_GET['create_time_end']);
		if($createTimeEnd){
			$helpSqlSearch->set_conditions("create_time<={$createTimeEnd}");
		}
		$_GET['apply_ip'] = trim($_GET['apply_ip']);
		if(!empty($_GET['apply_ip'])){
			$helpSqlSearch->set_conditions("apply_ip='{$_GET['apply_ip']}'");
		}

		$_GET['apply_user_id'] = intval($_GET['apply_user_id']);
		if($_GET['apply_user_id']){
			$helpSqlSearch->set_conditions("apply_user_id={$_GET['apply_user_id']}");
		}
		$_GET['audit_user_id'] = intval($_GET['audit_user_id']);
		if($_GET['audit_user_id']){
			$helpSqlSearch->set_conditions("audit_user_id={$_GET['audit_user_id']}");
		}
		$playerType = $_GET['player_type'];
		if($playerType!=''){
			$playerType = intval($playerType);
			$helpSqlSearch->set_conditions("player_type={$playerType}");
		}
		$_GET['player_info'] = trim($_GET['player_info']);
		if(!empty($_GET['player_info'])){
			$helpSqlSearch->set_conditions("player_info like '%{$_GET['player_info']}%'");
		}
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('is_send asc,Id desc');
		$sql=$helpSqlSearch->createSql();
		//		echo $sql;
		$dataList = $this->_modelApply->select($sql);
		if($dataList && is_array($dataList)){
			foreach($dataList as &$sub){
				$sub['type'] = $ApplyType[$sub['type']];
				$sub['game_type'] = $GameType[$sub['game_type']];
				$sub['operator_id'] = $Operator[$sub['operator_id']];
				$sub['server_id'] = $ServerList[$sub['server_id']]['server_name'];
				$sub['apply_user_id'] = $users[$sub['apply_user_id']]['nick_name'];
				$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
				$sub['audit_user_id'] = $users[$sub['audit_user_id']]['nick_name'];
				$sub['send_time'] = $sub['send_time']?date('Y-m-d H:i:s',$sub['send_time']):'';
				$sub['is_send_info'] = $IsSend[$sub['is_send']];
				$sub['player_type'] = $PlayerType[$sub['player_type']];
			}
		}else{
			$dataList = array();
		}
		$this->_view->assign('dataList',$dataList);

		$conditions=$helpSqlSearch->get_conditions();
		$this->_loadCore('Help_Page');//载入分页工具
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelApply->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());

		$users=Model::getTtwoArrConvertOneArr($users,'Id','full_name',array(''=>"-{$L_All}-"));
		$this->_view->assign('users',$users);
		$this->_view->assign('game_type',$GameType);
		$this->_view->assign('operator_id',$Operator);

		$this->_view->assign('type',$ApplyType);
		$this->_view->assign('is_send',$IsSend);
		$this->_view->assign('player_type',$PlayerType);
		$this->_view->assign('_GET',$_GET);
		$this->_view->set_tpl(array('body'=>'Apply/ShowList.html'));
		$this->_view->display();
	}

	/**
	 * 客服申请与审核列表
	 */
	public function actionCsIndex(){
		$this->_url['accept']=Tools::url(CONTROL,'Audit',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Audit',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		switch ($_GET['doaction']){
			case 'myapply':
				$userClass=$this->_utilRbac->getUserClass();
				$_GET['apply_user_id']	= $userClass['_id'];
				break;
			case 'myaudit':
				$userClass=$this->_utilRbac->getUserClass();
				$_GET['audit_user_id']	= $userClass['_id'];
				break;
			case 'selectgame':	//把游戏选定，页面不再显示游戏选择，适合与在各个游戏项目内
				$userClass=$this->_utilRbac->getUserClass();
				if(!intval($_GET['game_type'])){
					$this->_utilMsg->showMsg('game_type empty',-1);
				}
				$_GET['selectgame'] = 1;
				break;
			default:
		}
		$this->_showList(self::CS_LIST);
	}

	/**
	 * 客服申请与审核列表（所有）
	 */
	public function actionCsAll(){
		$this->_url['accept']=Tools::url(CONTROL,'Audit',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Audit',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		$this->_showList(self::CS_LIST,false);
	}

	/*
	 * 运营审核列表
	 */
	public function actionOperatorIndex(){
		$this->_url['accept']=Tools::url(CONTROL,'Audit',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Audit',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		$this->_showList(self::OPT_LIST);
	}

	public function actionGoldAll(){
		$this->_url['accept']=Tools::url(CONTROL,'Audit',array('doaction'=>'accept'));
		$this->_url['reject']=Tools::url(CONTROL,'Audit',array('doaction'=>'reject'));
		$this->_view->assign('url',$this->_url);
		$this->_showList(self::GOLD_LIST,false);
	}





	//	public function actionTest(){
	//
	//		$model = $this->_getGlobalData('Model_PlayerFaq','object');
	//		$sql = 'select * FROM cndw_player_faq where game_type_id=2 and lang_id = 2 and user_id=376';
	//
	//		$data = $model->select($sql);
	//
	//
	//		foreach($data as $val){
	//			$rp = preg_replace('/\d{1,3}\.(.*)/','\\1',$val['question']);
	//			$rp = mysql_escape_string(trim($rp));
	//
	//			if($rp != $val['question']){
	//				$model->execute("update cndw_player_faq set question = '{$rp}' where Id = {$val['Id']}");
	//			}
	//		}
	//
	//	}



	public function actionTest(){

		$data1 = array(
		0=>array('cal_local_method'=>'time'),
		1=>array('cal_local_method'=>'md5','params'=>'123456'),
		2=>'111',
		3=>array(1,2,3),
		4=>array('cal_local_method'=>'addType','params'=>array(5,8),'cal_local_object'=>'Model_ApplyType'),

		5=>array(
				'cal_local_method'=>'date',
				'params'=>array('Y-m-d H:i:s',array('cal_local_method'=>'addType','params'=>array(time(),'ExtParam'=>''),'cal_local_object'=>'Model_ApplyType')),
		),
		);

		print_r($this->dt($data1,5));


	}


	public function actionIndex1(){

		$sendData['url_append'] = 'php/interface.php';
		$sendData['get_data'] = array(
			'm'=>'Admin',
			'c'=>'Reward',
			'a'=>'SendAward',
			'Action'=>'Save',
			'__hj_dt'=>'RpcSeri',	//'__hj_dt'=>'_DP_JSON',		
			'__sk'=>array(
				'cal_local_object'=>'Util_FRGInterface',
				'cal_local_method'=>'getFrgSk',
				'params'=>NULL,
		),
		);
		$sendData['post_data'] = array (
		  'ReceiveType' => '1',
		  'MsgTitle' => 'titletest',
		  'MsgContent' => 'contenttest',
		  'ToolIdEName' => 
		array (
		1 => 'MiningA',
		),
		  'Tool_name_1' => '轻巧小木铲',
		  'ToolNum' => 
		array (
		1 => '1',
		),
		  'ToolId' => 
		array (
		1 => '396',
		),
		  'ToolIdName' => 
		array (
		1 => '轻巧小木铲',
		),
		  'ToolIdImg' => 
		array (
		1 => 'WoodenShovel.jpg',
		),
		);
		//		$sendData['end'] = array(
		//			'cal_local_object'=>'Util_FRGInterface',
		//			'cal_local_method'=>'getFrgDecrypt',
		//			'params'=>array('ExtParam'=>'1'),
		//		);
		$sendData['call']=array(
			'cal_local_object'=>'Util_ApplyInterface',
			'cal_local_method'=>'FrgSendReward',
		);
		echo serialize($sendData);return;
		$receiver = array (734 => array ('UserIds' => 'youxingyuan,r2qfds532,fdsfew3','MsgTitle'=>734));



		$data = $this->sendByHttp($sendData,$receiver);

		print_r($data);
		return;
		################################

		$data = $this->sendToLocal();

	}

}