<?php
/**
 * GM工具
 * @author PHP-兴源
 *
 */
class Control_MasterTools extends ActionGame {

	public function __construct(){
		parent::__construct();
		$this->_checkOperatorAct();	//检查运营商权限
		$this->_utilMsg->createPackageNavBar();
	}

	public function actionPlayerRoleList(){
		$this->_default();
	}

	protected function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}

	private function _default(){
		$this->_createServerList();
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$_body = isset($returnData['_body'])?$returnData['_body']:null;
		$this->display($_body);
	}
	//玩家查询
	public function actionPlayerLookup(){
		$this->_default();
	}

	public function actionServerState(){
		$this->_default();
	}
	//玩家详细查询
	public function actionPlayerDetail(){
		$this->_default();
	}
	//玩家日志
	public function actionPlayerLog(){
		$this->_default();
	}
	//玩家日志类型
	public function actionPlayerLogType(){
		$this->callAction();
	}
	//邮件列表
	public function actionMailList(){
		$this->_default();
	}
	//发邮件
	public function actionSendMail(){
		$this->_default();
	}
	//
	public function actionRechargeRecord(){
		$this->_default();
	}

	public function actionConsumption(){
		$this->_default();
	}

	public function actionGameLogin(){
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$_body = isset($returnData['_body'])?$returnData['_body']:null;
		$this->display($_body);
	}
	//禁言列表
	public function actionSilence(){
		$this->_default();
	}

	public function actionOrganizeMenber(){
		$this->_default();
	}

	public function actionOrganize(){
		$this->_default();
	}

	//增加禁言
	public function actionSilenceAdd(){
		$this->_default();
	}
	//删除禁言
	public function actionSilenceDel(){
		$this->callAction();
	}
	//封号列表
	public function actionLockAccount(){
		$this->_default();
	}
	//增加封号
	public function actionLockAccountAdd(){
		$this->_default();
	}

	public function actionLog(){
		$this->_default();
	}
	//删除封号
	public function actionLockAccountDel(){
		$this->callAction();
	}
	//多服禁言|封号
	public function actionMultiLock(){
		$this->_createMultiServerList();
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$this->display();
	}
	//IP封锁列出
	public function actionLockIP(){
		$this->_default();
	}
	//IP封锁保存配置
	public function actionLockIPDone(){
		// 		$this->callAction();
		$this->_default();
	}
	//删除ip封号
	public function actionDelLockIP(){
		$this->callAction();
	}
	//Q币查询
	public function actionOrderDetail(){
		$this->_default();
	}


	public function actionItemIssuance(){

	}

	public function actionOperatorServer(){

	}

	public function actionGetOperatorServer(){
		$this->_createOperatorList();
		$returnData = $this->callAction();
		$this->_view->assign($returnData);
		$this->display();
	}

	public function actionPersonalInformation(){
		$this->_default();
	}

	public function actionUpdatePersonalInformation(){
		$this->_default();
	}

	public function actionUserCurrencyLog(){
		$this->_default();
	}

	public function actionGameData(){
		$this->callAction();
	}
	//背包道具
	public function actionBackpackSearch(){
		$this->_default();
	}
	//玩家装备
	public function actionEquipment(){
		$this->_default();
	}
	//装备升级申请
	public function actionEquipmentUpgrade(){
		$this->callAction();
	}
	//装备删除申请
	public function actionEquipmentDel(){
		$this->callAction();
	}
	public function actionSendUserPackage(){//礼包发放
		$this->_default();
	}
	//道具更新|道具获得
	public function actionItem(){
		$this->callAction();
	}
	//道具扣除
	public function actionItemDel(){
		$this->callAction();
	}

	//道具发放/扣除
	public function actionPropertySend(){
		$this->_default();
	}
	//道具卡列表
	public function actionItemCard(){
		$this->_default();
	}

	public function actionItemCard2(){
		$this->_default();
	}
	//道具卡申请
	public function actionItemCardApply(){
		$this->_default();
	}
	//道具卡申请
	public function actionApplyCard(){
		$this->_default();
	}
	//元宝申请
	public function actionApplyIngot(){
		$this->_default();
	}
	public function actionItemCardApply2(){
		$this->_default();
	}
	//道具卡追加至现有礼包 申请
	public function actionItemCardAppendApply(){
		$this->_default();
	}
	//道具卡号查询
	public function actionItemCardQuery(){
		$this->_default();
	}
	//礼包卡查看批次
	public function actionItemCardShowBatch(){
		$this->_default();
	}
	//卡号列表
	public function actionItemCardList(){
		$this->_default();
	}
	//卡号下载
	public function actionItemCardDownLoad(){
		$this->_createServerList();
		$returnData = $this->callAction();
	}
	//礼包编辑
	public function actionItemPackageEdit(){
		$this->_default();
	}
	//礼包领取条件
	public function actionItemReceiveCondition(){
		$this->callAction();
	}
	//点数扣除
	public function actionPointDel(){
		$this->_default();
	}
	//玩家伙伴
	public function actionPlayerPartner(){
		$this->_default();
	}
	//玩家伙伴属性
	public function actionPlayerPartnerInfo(){
		$this->_default();
	}
	//在线用户
	public function actionOnLine(){
		$this->_default();
	}
	//

	public function actionPlayerRoleDelList(){
		$this->_default();
	}

	public function actionSetVIP(){
		$this->_default();
	}
	public function actionUserLoginLog(){
		$this->_default();
	}

	public function actionPlayerValueUpdate(){
		$this->_default();
	}

	public function actionLockIPDel(){
		$this->callAction();
	}

	public function actionMailDel(){
		$this->callAction();
	}

	public function actionDefine(){
		$this->callAction();
	}
	public function actionTaskManage(){
		$this->_default();
	}
	public function actionClearCopyNum(){
		$this->_default();
	}
	public function actionEditBoneLvel(){
		$this->_default();
	}
	public function actionEditMountAndPet(){
		$this->_default();
	}
	public function actionItemLog(){
		$this->_default();
	}

	public function actionCopyPlayer(){
		$this->_default();
	}

	public function actionTitleOrGag(){
		$this->_default();
	}
	public function actionAddTitleOrGag(){
		$this->_default();
	}
	public function actionDelTitleOrGag(){
		$this->_default();
	}
	public function actionPlayerRegName(){
		$this->_default();
	}
	public function actionDelUserPackage(){
		$this->_default();
	}
	public function actionConsumptionDetail(){
		$this->_default();
	}

	public function actionLogType(){
		$this->_default();
	}
	
	public function actionPlayerInfoSale(){
		$this->_default();
	}
	
	public function actionTradeManage(){
		$this->_default();
	}
}