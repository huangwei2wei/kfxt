<?php
class Control_BTO2Gold extends BTO2 {
	
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * @var Model_GoldCard
	 */
	private $_modelGoldCard;
	
	/**
	 * Model_ApplyDataFrg
	 * @var Model_ApplyDataFrg
	 */
	private $_modelApplyDataFrg;

	
	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['FrgGold_CardAdd'][1]=Tools::url(CONTROL,'CardAdd',array('zp'=>self::PACKAGE,'card_type'=>1));
		$this->_url['FrgGold_CardAdd'][2]=Tools::url(CONTROL,'CardAdd',array('zp'=>self::PACKAGE,'card_type'=>2));
		$this->_url['FrgGold_CardAdd'][3]=Tools::url(CONTROL,'CardAdd',array('zp'=>self::PACKAGE,'card_type'=>3));
		$this->_url['FrgGold_CardAdd'][4]=Tools::url(CONTROL,'CardAdd',array('zp'=>self::PACKAGE,'card_type'=>4));
		$this->_url['FrgGold_CardAdd'][5]=Tools::url(CONTROL,'CardAdd',array('zp'=>self::PACKAGE,'card_type'=>5));
		$this->_url['FrgGold_Card_Del']=Tools::url(CONTROL,'Card',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_url['FrgGold_Card']=Tools::url(CONTROL,'Card',array('zp'=>self::PACKAGE));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 卡号管理 
	 */
	public function actionCard(){
		switch ($_GET['doaction']){
			case 'del' :{
				$this->_del();
				return ;
			}
			default:{
				$this->_checkOperatorAct(2);	//null : 检测$_SERVER['id'],true : 检测$_REQUEST['operator_id']
				$this->_index();
				return ;
			}
		}

	}
	
	private function _del(){
		if ($_POST['csv']){//如果输出csv格式
			if ($_POST['card']){
				foreach ($_POST['card'] as $value){
					echo $value.'<br />';
				}
			}
		}else {//删除
			$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
			$this->_modelGoldCard->del(array('card'=>$_POST['card'],'batch_num'=>$_POST['batch_num']));
			$this->_utilMsg->showMsg(false);
		}
	}
	
	private function _index(){
		$users=$this->_getGlobalData('user');
		$payType=Tools::getLang('CARD_TYPE',__CLASS__);
		$cardType=$this->_getGlobalData('frg_gold_card_type');
		$serverList=$this->_getGlobalData('gameser_list');
		$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelGoldCard->tName () );
		$helpSqlSearch->set_conditions('game_type='.self::GAME_ID);
		if ($_GET['card_type']){
			$helpSqlSearch->set_conditions("card_type={$_GET['card_type']}");
			$this->_view->assign('selectedCardType',$_GET['card_type']);
		}
		if ($_GET['type'] != ''){
			$helpSqlSearch->set_conditions("type={$_GET['type']}");
			$this->_view->assign('selectedPayType',$_GET['type']);
		}
		if ($_GET['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		if ($_GET['card']){
			$helpSqlSearch->set_conditions("card='{$_GET['card']}'");
			$this->_view->assign('selectedCard',$_GET['card']);
		}
		if ($_GET['is_use']!=''){
			$helpSqlSearch->set_conditions("is_use='{$_GET['is_use']}'");
			$this->_view->assign('selectedIsUse',$_GET['is_use']);
		}
		if ($_GET['batch_num']){
			$helpSqlSearch->set_conditions("batch_num='{$_GET['batch_num']}'");
			$this->_view->assign('selectedBatchnum',$_GET['batch_num']);
		}
		if ($_GET['group_batch']){
			$helpSqlSearch->set_groupBy("batch_num");
			$this->_view->assign('selectedGroupBatch',true);
		}
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		$helpSqlSearch->set_orderBy('Id desc');
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelGoldCard->select($sql);
		if ($dataList){
			$operatorList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('operator_list'),'Id','operator_name');
			foreach ($dataList as &$list){
				Tools::import('Util_FontColor');
				$list['word_operator_id']=$operatorList[$list['operator_id']];
				$list['word_is_use']=Util_FontColor::getFrgPayCardStatus($list['is_use']);
				$list['user_ip']=$list['user_ip']?long2ip($list['user_ip']):'';
				$list['word_type']=Util_FontColor::getFrgCardType($list['type']);
				$list['word_card_type']=$cardType[$list['card_type']];
				$list['word_use_server_id']=$serverList[$list['use_server_id']]['full_name'];
				$list['start_time']=$list['start_time']?date('Y-m-d H:i:s',$list['start_time']):'';
				$list['end_time']=$list['end_time']?date('Y-m-d H:i:s',$list['end_time']):'';
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['use_time']=$list['use_time']?date('Y-m-d H:i:s',$list['use_time']):'';
				$list['url_export']=Tools::url(CONTROL,'Export',array('batch_num'=>$list['batch_num']));
				$list['word_apply_user_id']=$users[$list['apply_user_id']]['nick_name'];
			}
			$this->_view->assign('dataList',$dataList);
			$this->_loadCore ( 'Help_Page' );
			$helpPage = new Help_Page ( array ('total' => $this->_modelGoldCard->findCount ( $conditions ), 'perpage' => PAGE_SIZE ) );
			$this->_view->assign ( 'pageBox', $helpPage->show () );
		}
		
		$this->_view->assign('cardStatus',array('0'=>Tools::getLang('NOT_USE','Common'),'1'=>Tools::getLang('USE','Common'),''=>Tools::getLang('ALL','Common')));
		$this->_view->assign('payType',$payType);
		$cardType['']=Tools::getLang('ALL','Common');
		$this->_view->assign('cardType',$cardType);
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::GOLD.'/Card.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 导出卡号
	 */
	public function actionExport(){
		$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
		$dataList=$this->_modelGoldCard->getBatchNumData($_GET['batch_num']);
		Tools::import('Util_ExportExcel');
		$utilExportExcel=new Util_ExportExcel($_GET['batch_num'],'Excel/GoldCard',$dataList);
		$utilExportExcel->outPutExcel();
	}
	
	/**
	 * 我申请的卡号
	 */
	public function actionMyApplyCard(){
		$this->_checkOperatorAct(2);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$payType=Tools::getLang('CARD_TYPE',__CLASS__);
		$cardType=$this->_getGlobalData('frg_gold_card_type');
		$serverList=$this->_getGlobalData('gameser_list');
		$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelGoldCard->tName () );
		$helpSqlSearch->set_conditions('game_type='.self::GAME_ID);
		$helpSqlSearch->set_conditions("apply_user_id={$userClass['_id']}");		
		if ($_GET['is_use']!=''){
			$helpSqlSearch->set_conditions("is_use='{$_GET['is_use']}'");
			$this->_view->assign('selectedIsUse',$_GET['is_use']);
		}
		if ($_GET['card_type']){
			$helpSqlSearch->set_conditions("card_type={$_GET['card_type']}");
			$this->_view->assign('selectedCardType',$_GET['card_type']);
		}
		if ($_GET['type'] != ''){
			$helpSqlSearch->set_conditions("type={$_GET['type']}");
			$this->_view->assign('selectedPayType',$_GET['type']);
		}
		if ($_GET['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		if ($_GET['card']){
			$helpSqlSearch->set_conditions("card='{$_GET['card']}'");
			$this->_view->assign('selectedCard',$_GET['card']);
		}
		if ($_GET['batch_num']){
			$helpSqlSearch->set_conditions("batch_num='{$_GET['batch_num']}'");
			$this->_view->assign('selectedBatchnum',$_GET['batch_num']);
		}
		if ($_GET['group_batch']){
			$helpSqlSearch->set_groupBy("batch_num");
			$this->_view->assign('selectedGroupBatch',true);
		}
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		$conditions=$helpSqlSearch->get_conditions();
		$helpSqlSearch->set_orderBy('Id desc');
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelGoldCard->select($sql);
		if ($dataList){
			$operatorList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('operator_list'),'Id','operator_name');
			foreach ($dataList as &$list){
				Tools::import('Util_FontColor');
				$list['word_operator_id']=$operatorList[$list['operator_id']];
				$list['word_is_use']=Util_FontColor::getFrgPayCardStatus($list['is_use']);
				$list['user_ip']=$list['user_ip']?long2ip($list['user_ip']):'';
				$list['word_type']=Util_FontColor::getFrgCardType($list['type']);
				$list['word_card_type']=$cardType[$list['card_type']];
				$list['word_use_server_id']=$serverList[$list['use_server_id']]['full_name'];
				$list['start_time']=$list['start_time']?date('Y-m-d H:i:s',$list['start_time']):'';
				$list['end_time']=$list['end_time']?date('Y-m-d H:i:s',$list['end_time']):'';
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['use_time']=$list['use_time']?date('Y-m-d H:i:s',$list['use_time']):'';
				$list['url_export']=Tools::url(CONTROL,'Export',array('batch_num'=>$list['batch_num']));
				$list['word_apply_user_id']=$userClass['_nickName'];
			}
			$this->_view->assign('dataList',$dataList);
			$this->_loadCore ( 'Help_Page' );
			$helpPage = new Help_Page ( array ('total' => $this->_modelGoldCard->findCount ( $conditions ), 'perpage' => PAGE_SIZE ) );
			$this->_view->assign ( 'pageBox', $helpPage->show () );
		}
		$this->_view->assign('cardStatus',array('0'=>Tools::getLang('NOT_USE','Common'),'1'=>Tools::getLang('USE','Common'),''=>Tools::getLang('ALL','Common')));
		$this->_view->assign('payType',$payType);
		$cardType['']=Tools::getLang('ALL','Common');
		$this->_view->assign('cardType',$cardType);
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::GOLD.'/Card.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 生成卡号
	 */
	public function actionCardAdd(){
		$this->_checkOperatorAct(true);	//null : 检测$_SERVER['id'],true : 检测$_REQUEST['operator_id']
		if ($this->_isPost()){
			//检查所在游戏是否有相同批号
			$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
			$batchNum=md5($_POST['batch_num']);
			if ($this->_modelGoldCard->select("select Id from {$this->_modelGoldCard->tName()} where game_type=".self::GAME_ID." and batch_num='{$batchNum}'",1))
				$this->_utilMsg->showMsg(Tools::getLang('CARD_BATCHCLASH',__CLASS__),-1,2);
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataBTO2','object');
			
			$gameClass = $this->_getGlobalData(self::GAME_ID,'game');
			$apply_info = "申请原因<br>{$_POST['cause']}<br>".$gameClass->AddAutoCause($_POST,12);	//1的类型是奖励发送		
			$userClass=$this->_utilRbac->getUserClass();
			$applyData = array(
				'type'=>self::APPLY_TPYE_GOLD,	//金币审核
				'server_id'=>0,
				'operator_id'=>intval($_POST['operator_id']),
				'game_type'=>self::GAME_ID,
				'list_type'=>3,	//3,金币审核列表
				'apply_info'=>$apply_info,
				'send_type'=>1,	//1	本地接口
				'send_data'=>array(
					'data'=>array(
						'card_type' =>intval($_POST['card_type']),
						'operator_id' =>intval($_POST['operator_id']),
						'batch_num' =>trim($_POST['batch_num']),	//批号(系统自动MD5)
						'gold'=>intval($_POST['gold']),	//人民币
						'num'=>intval($_POST['num']), //生成数量
						'is_time' =>intval($_POST['is_time']),//是否有时间限制
						'start_time' =>trim($_POST['start_time']),
						'end_time' =>trim($_POST['end_time']),
						'apply_user_id' =>$userClass['_id'],
						'game_type' =>self::GAME_ID,
					),
					'call'=>array(
							'cal_local_object'=>'Model_GoldCard',	//使用本地对象，如果为空，则使用内置函数
							'cal_local_method'=>'import',	//使用本地方法
							'params'=>NULL,
					),
				),
				'receiver_object'=>NULL,
				'player_type'=>0,
				'player_info'=>NULL,
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsAll = Tools::url('Apply','GoldAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsAll}'>金币审核列表(全部)</a>";
				$this->_utilMsg->showMsg($showMsg,1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
			}
		}else {
			if ($_GET['card_type']){
				$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::GOLD."/CardAdd{$_GET['card_type']}.html"));
			}else {
				$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::GOLD.'/CardAdd.html'));
			}
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 卡号充值
	 */
	public function actionCardPay(){
		if ($this->_isPost()){//post请求,充值
			$this->_modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
			$data=$this->_modelGoldCard->pay($_POST,self::GAME_ID);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],null);
			//直接发卡测试			
//			$getArr=array(
//				'm'=>'User',
//				'c'=>'Deposit',
//				'a'=>'Pay',
//				'addcoin'=>1,	//套餐
//				'Uname'=>$_POST['use_name'],
//				'Money'=>$_POST['Money'],
//				'Transactionid'=>$_POST['Transactionid'],
//				'Depay'=>0,
//				'gDepay'=>0,
//				'isGoldCard'=>1,
//				'GoldCard'=>md5(CURRENT_TIME),
//				'GameId'=>1,
//				'ServiceId'=>'B2',
//	//			'syskey'=>$this->_payKey[$cardDetail['operator_id']]['key'],
//			);
//			$syskey='!@$$DSDGldj*73@sls-(3';
//			$sign="Depay={$getArr['Depay']}&gDepay={$getArr['gDepay']}&addcoin={$getArr['addcoin']}&Uname={$getArr['Uname']}&Money={$getArr['Money']}&GameId={$getArr['GameId']}&ServiceId={$getArr['ServiceId']}&Transactionid={$getArr['Transactionid']}&Key={$syskey}";
//			$getArr['Sign']=md5($sign);
//			$serverList = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
//			$url=$serverList[$_POST['server_id']]['server_url'].'php/interface.php';
//			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
//			$this->_utilApiFrg->addHttp($url,$getArr);
//			$this->_utilApiFrg->send();
//			$data=$this->_utilApiFrg->getResult();
//			if ($data['data']==1){//成功
//				$this->_utilMsg->showMsg('领取成功',1);
//			}else {
//				$this->_utilMsg->showMsg('领取失败<br>'.$data['message'],1);
//			}
		}elseif ($this->_isAjax()){//ajax请求返回用户名
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$serverId=Tools::coerceInt($_GET['server_id']);
			$getArr=array('c'=>'UserData','a'=>'UserQuery','Page'=>1);
			$postArr=array('PageSize'=>10,'Query[start]'=>$_GET['user_name'],'Query[Items]'=>'8');
			$this->_utilApiFrg->addHttp($serverId,$getArr,$postArr);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if (count($data['data']['list'])){
				$user=reset($data['data']['list']);
				$this->_returnAjaxJson(array('status'=>1,'data'=>$user['VUserName']));	
			}else {
				$this->_returnAjaxJson(array('status'=>0,'data'=>null));	
			}
		}else {//显示页面
			$this->_checkOperatorAct();
			$serverList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
			foreach ($serverList as &$list){
				unset($list['room_id'],$list['game_type_id'],$list['marking'],$list['time_zone'],$list['timezone'],$list['server_url'],$list['timer'],$list['ext']);
			}
			$this->_view->assign('serverList',json_encode($serverList));
			$this->_view->display();
		}
	}
}