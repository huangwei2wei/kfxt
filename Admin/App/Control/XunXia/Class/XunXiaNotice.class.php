<?php
/**
 * 公告管理
 * @author php-朱磊
 *
 */
class Control_XunXiaNotice extends XunXia {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['XunXiaNotice_Add']=Tools::url('XunXiaNotice','Add',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
		$this->_url['XunXiaNotice_Del']=Tools::url('XunXiaNotice','Del',array('zp'=>'XunXia'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 公告显示列表
	 */
	public function actionIndex(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'oneNotice/notice');
			if(empty($_GET["page"])){$_GET["page"]=1;}
			
			
			$dataList=$this->getApi()->queryNotice($_GET["page"],PAGE_SIZE);
			foreach($dataList->page->data as $value){
				//echo $value->succInsertIpList;
				$value->succInsertIpList	=	str_replace(",","<br>",$value->succInsertIpList);
			}
			$this->_loadCore('Help_Page');
			$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
			$this->_view->assign('dataList',$dataList->page->data);
			$this->_view->assign('pageBox',$this->_helpPage->show());
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();		
	}
	
	/**
	 * 增加公告
	 */
	public function actionAdd(){
		$this->_checkOperatorAct();	//检测服务器
		$this->_createServerList();
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],'oneNotice/notice');
			$data=$this->getApi()->saveNotice(strval($_POST['title']),strval($_POST['content']),strval($_POST['begin']),strval($_POST['end']),intval($_POST['interval']),$_POST['url']);
			if ($data==0){
				$this->_utilMsg->showMsg('添加公告成功',1,Tools::url(CONTROL,'Index',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('添加公告失败',1);
			}
		}else {
			
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();	
		}	
	}
	
	/**
	 * 群发公告
	 */
	public function actionAllAdd(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		$servers = $this->_getAllServerIds();
		$serversInfo = $this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
		$serversInfo = Model::getTtwoArrConvertOneArr($serversInfo,'marking','server_name');
		$sendData	=	array();
		if ($this->_isPost()){
			//die($_REQUEST['server_id']);
			if(empty($_POST['serverId'])){
				$this->_utilMsg->showMsg('请选择发送的服务',-1);
			}
			$sendData = array();
			foreach($_POST['serverId'] as $val){
				if($servers[$val]){
					$sendData['serverId'][$val] = $servers[$val];
					$sendData['serversInfo'][$val] = isset($serversInfo[$servers[$val]])?$serversInfo[$servers[$val]]:$servers[$val];
				}
			}
			
			if(empty($sendData['serverId'])){
				$this->_utilMsg->showMsg('服务器信息错误，请更新服务器',-1);
			}
			$sendData['serverId'] = implode(',',$sendData['serverId']);
			$this->getApi()->setUrl($_REQUEST['server_id'],'oneNotice/notice');
			
			$data=$this->getApi()->saveNotice(strval($_POST['title']),strval($_POST['content']),strval($_POST['begin']),strval($_POST['end']),intval($_POST['interval']),$_POST['url'],$sendData['serverId']);

			if ($data===0){
				$this->_utilMsg->showMsg('添加公告成功',1,Tools::url(CONTROL,'AllAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('添加公告失败',1);
			}
		}else {
			//<input class="serverId" type="checkbox" value="S1" name="serverId[]">
			if($servers){
				$serverToSlt = array();
				foreach($servers as $k =>$v){
					$serverToSlt[$k] = "{$k}";
											//$serverToSlt[$k] = isset($serversInfo[$v])?$serversInfo[$v]."($v)":$v;
				}
				$this->_view->assign('server',$serverToSlt);
			}
			//print_r($serverToSlt);
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();	
		}	
	}
	
	/**
	 * 删除公告
	 */
	public function actionDel(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		$this->getApi()->setUrl($_REQUEST['server_id'],'oneNotice/notice');
		$data=$this->getApi()->deleteNotice($_POST['idList']);
		if ($data==0){
			$this->_utilMsg->showMsg('删除公告成功',1,Tools::url(CONTROL,'Index',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg('删除公告失败',1);
		}
	}
	
	private function _getAllServerIds($effectiveTime=86400){
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$ServerArr = $this->_f('xunxia_server_id_'.$_REQUEST['server_id'],'',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($ServerArr){
				return $ServerArr;
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$dataList=$this->getApi()->getAllServerIds();
			
			if ($dataList && !$dataList instanceof PHPRPC_Error){
				//新
				//"$dataList" = Array [4]	
				//	0 = Object of: com_cndw_gm_vo_ServerIpInfo	
				//		port = (int) 8001	
				//		name = (string:3) Ss0	
				//		outerServerIp = (string:13) 183.60.66.177	
				//		key = (string:2) s0	
				//		innerServerIp = (string:12) 10.142.39.61	
				//	1 = Object of: com_cndw_gm_vo_ServerIpInfo	
				//	2 = Object of: com_cndw_gm_vo_ServerIpInfo	
				//	3 = Object of: com_cndw_gm_vo_ServerIpInfo	
				
				
				//旧
				//"$dataList" = Array [3]	
				//	0 = Object of: com_cndw_gm_dao_IPStringVo	
				//		ipStr = (string:13) 192.168.12.25	
				//		ipCode = (string:1) 3	
				//	1 = Object of: com_cndw_gm_dao_IPStringVo	
				//		ipStr = (string:14) 192.168.15.105	
				//		ipCode = (string:1) 2	
				//	2 = Object of: com_cndw_gm_dao_IPStringVo	
				//		ipStr = (string:13) 192.168.14.21	
				//		ipCode = (string:1) 1
				$ServerArr = array();
				foreach($dataList as $sub){
					if($sub->name  && $sub->outerServerIp){
						$ServerArr[$sub->name] = $sub->outerServerIp;
					}else{
						$ServerArr[intval($sub->ipCode)] = $sub->ipStr;
					}
				}
				$this->_f('xunxia_server_id_'.$_REQUEST['server_id'],$ServerArr);	//缓存数据数据
				return $ServerArr;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}