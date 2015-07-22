<?php
/**
 * 运营工具
 * @author PHP-兴源
 *
 */
class Control_OperatorTools extends ActionGame {
	const PACKAGE='ActionGame';

	public function __construct(){
		parent::__construct();
		$this->_checkOperatorAct();	//检查运营商权限
		$this->_utilMsg->createPackageNavBar();
	}

	protected function _createUrl(){

		$this->_url['OperatorTools_Libao_serverSync']=Tools::url('OperatorTools','ItemCardSync',array('zp'=>self::PACKAGE,'doaction'=>'serverSync','__game_id'=>$_REQUEST['__game_id']));
		$this->_url['OperatorTools_Notice_serverSync']=Tools::url('OperatorTools','NoticeSync',array('zp'=>self::PACKAGE,'doaction'=>'serverSync','__game_id'=>$_REQUEST['__game_id']));
		$this->_url['OperatorTools_Activities_serverSync']=Tools::url('OperatorTools','ActivitiesSync',array('zp'=>self::PACKAGE,'doaction'=>'serverSync','__game_id'=>$_REQUEST['__game_id']));
		$this->_url['OperatorTools_ShopProduce_serverSync']=Tools::url('OperatorTools','ShopProduceSync',array('zp'=>self::PACKAGE,'doaction'=>'serverSync','__game_id'=>$_REQUEST['__game_id']));
		$this->_view->assign('url',$this->_url);
	}

	private function _default($isMultiServer=false){
		if($isMultiServer == true){
			$this->_createMultiServerList();
		}else{
			$this->_createServerList();
		}
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$_body = isset($returnData['_body'])?$returnData['_body']:null;
		$this->display($_body);
	}

	public function actionNotice(){
		$this->_default();
	}

	public function actionNoticeEdit(){
		$this->_default();
	}

	public function actionNoticeSync(){
		$this->_default();
	}

	public function actionNoticeEditDone(){
		$this->callAction();
	}

	public function actionNoticeAdd(){
		$this->_default();
	}

	public function actionBadWord(){
		$this->_default();
	}

	public function actionNoticeDel(){
		$this->callAction();
	}

	public function actionServerManagement(){
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$_body = isset($returnData['_body'])?$returnData['_body']:null;
		$this->display($_body);
	}

	public function actionAllNotice(){
		$this->_default();
	}

	public function actionAllNoticeAdd(){
		$this->_default(true);
	}
	public function actionAllNoticeDel(){
		$this->_default(true);
	}
	public function actionAddShopProduce(){
		$this->_default();
	}

	public function actionShopProduce(){
		$this->_default();
	}

	public function actionDelShopProduce(){
		$this->callAction();
	}

	public function actionAddQShopProduce(){
		$this->_default();
	}

	public function actionQShopProduce(){
		$this->_default();
	}

	public function actionDelQShopProduce(){
		$this->callAction();
	}

	public function actionActivitiesList(){
		$this->_default();
	}

	public function actionActivitiesAdd(){
		$this->_default();
	}

	public function actionActivitiesDel(){
		$this->callAction();
	}

	public function actionActivitiesSync(){
		$this->_default();
	}

	public function actionShopProduceSync(){
		$this->_default();
	}

	public function actionEventStoryList(){
		$this->_default();
	}

	public function actionEventStoryAdd(){
		$this->_default();
	}

	public function actionEventStoryDel(){
		$this->callAction();
	}

	public function actionBossList(){
		$this->_default();
	}

	public function actionBossAdd(){
		$this->_default();
	}

	public function actionItemCardSync(){
		$this->_default();
	}

	public function actionBossDel(){
		$this->callAction();
	}
	public function actionRewardTriggerList(){//奖励触发列表
		$this->_default();
	}
	public function actionRewardTriggerDel(){ // 删除奖励触发
		$this->callAction();
	}
	public function actionRewardTriggerDels(){ // 多服删除奖励触发
		$this->_default(true);
	}
	public function actionRewardTriggerAdd(){//添加奖励触发
		$this->_default();
	}
	public function actionActivationTypeAdd(){//添加激活码类型
		$this->_default();
	}
	public function actionActivationCodeAdd(){//添加激活码
		$this->_default();
	}
	public function actionActivationCodeList(){//激活码列表
		$this->_default();
	}
	public function actionActivationTypeList(){//激活码类型列表
		$this->_default();
	}
	public function actionRecharge(){//激活码类型列表
		$this->_default();
	}

	public function actionPowerIP(){//IP白名单
		$this->_default();
	}

	public function actionServicerMaintain(){//服务器维护状态
		$this->_default();
	}

	public function actionServicerMaintainSync(){//服务器维护状态
		$this->_default();
	}

	public function actionWebList(){//web list
		$this->_default();
	}

	public function actionWebAdd(){//web list
		$this->_default();
	}

	public function actionDipList(){//web list
		$this->_default();
	}

	public function actionDipAdd(){//web list
		$this->_default();
	}

	public function actionPayList(){//web list
		$this->_default();
	}

	public function actionPayAdd(){//web list
		$this->_default();
	}

	public function actionSendEmailMultiserver(){
		$this->_default(true);
	}

	public function actionSysLog(){
		$this->_default();
	}

	//多服发送物品
	public function actionSendGoodsMultiserver(){
		$this->_default();
	}
	public function actionMallLogList(){
		$this->_default();
	}

	public function actionPowerAccount(){
		$this->_default();
	}

	public function actionActivitiesConfiguration(){
		$this->_default();
	}

	public function actionActivitiesConfigurationAdd(){
		$this->_default();
	}
	public function actionActivitiesConfigurationDel(){
		$this->_default();
	}

	public function actionActivitiesConfigurationSync(){
		$this->_default();
	}
	public function actionOpenTime(){
		$this->_default();
	}
	public function actionFunOnOrOff(){
		$this->_default(true);
	}
	public function actionExcelImport(){
		$this->_default();
	}
	public function actionServerOnOrOff(){
		$this->_default(true);
	}
	public function actionActivitiesEdit(){
		$this->_default();
	}
	public function actionShopProduceEdit(){
		$this->_default();
	}
	public function actionAddPlayerAsGM(){
		$this->_default(true);
	}
	public function actionGMList(){
		$this->_default();
	}
	public function actionPatch(){
		$this->_default(true);
	}
	public function actionGetIngotType(){
		$this->_default();
	}
	public function actionGetIngot(){
		$this->_default();
	}
	public function actionAddPlayerPay(){
		$this->_default();
	}
	public function actionItemCardMotion(){
		$this->_default();
	}
	public function actionItemCardApplyMotion(){
		$this->_default();
	}
}