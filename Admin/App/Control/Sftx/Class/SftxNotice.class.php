<?php
/**
 * 公告管理
 * @author php-朱磊
 *
 */
class Control_SftxNotice extends Sftx {

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;
	
	const API_HREF='api/oneNotice';

	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['SftxNotice_Add']=Tools::url('SftxNotice','Add',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
		$this->_url['SftxNotice_Del']=Tools::url('SftxNotice','Del',array('zp'=>'Sftx'));
		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 公告显示列表
	 */
	public function actionIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],self::API_HREF);
			$page = $_GET['page']?$_GET['page']:0;
			$dataList=$this->getApi()->selectNotices('',$page,PAGE_SIZE);
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true );
				$this->_view->assign('dataList',$dataList['data']['result']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
			
		}
		$total = $data['data']['totalCount'];
		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 增加公告
	 */
	public function actionAdd(){
		$this->_checkOperatorAct();	//检测服务器
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],self::API_HREF);
			$data=$this->getApi()->save(strval($_POST['begin']),strval($_POST['end']),intval($_POST['interval']),strval($_POST['title']),strval($_POST['content']),$_POST['url']);
			$data=json_decode($data,true);
			if ($data['status']==1){
				$this->_utilMsg->showMsg('添加公告成功',1,Tools::url(CONTROL,'Index',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('添加公告失败',-2);
			}
		}else {
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 删除公告
	 */
	public function actionDel(){
		$this->_checkOperatorAct();	//检测服务器
		$this->getApi()->setUrl($_REQUEST['server_id'],self::API_HREF);
		$data=$this->getApi()->deleteNotice($_POST['idList']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg('删除公告成功',1,Tools::url(CONTROL,'Index',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id'])),false);
		}else {
			$this->_utilMsg->showMsg('删除公告失败',1,1,false);
		}
	}
	
	/**
	 * 多服发送公告.
	 */
	public function actionAddMulti(){
		if ($this->_isAjax()){
			$this->getApi()->setUrl($_REQUEST['server_id'],self::API_HREF);
			$data=$this->getApi()->save(strval($_POST['begin']),strval($_POST['end']),intval($_POST['interval']),strval($_POST['title']),strval($_POST['content']),$_POST['url']);
			$data=json_decode($data,true);
			if ($data['status']==1){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>'发送成功'));
			}else {
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'发送失败'));
			}
		}else {
			$this->_checkOperatorAct();	//检测服务器
			$this->_createMultiServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
		
	}
}