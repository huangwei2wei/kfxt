<?php
/**
 * 充值动作
 * @author php-朱磊
 *
 */
class PayCardAction extends BaseAction {
	
	/**
	 * 富 人国充值展示页面
	 */
	public function index(){
		import('@.Util.ServerSelect');
		$selectServer=new ServerSelect();
		$serverList=$selectServer->getUserCreateServers(2);	//富人国
		$this->assign('serverList',$serverList);
		$this->display("index");
	}
	
	/**
	 * 充值 
	 */
	public function pay(){
		$modelPayCard=D('PayCard');
		$data=$modelPayCard->pay($_POST);
		$this->assign('jumpUrl',U('/PayCard/index'));
		if ($data['status']==1){
			$this->success($data['msg']);
		}ELSE{
			$this->error($data['msg']);
		}
	}
	
	
}