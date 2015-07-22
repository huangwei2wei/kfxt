<?php
/**
 * 客服日常工具
 * @author PHP-朱磊
 */
class Control_ServiceTools extends Control {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_Bulletin
	 * @var Model_Bulletin
	 */
	private $_modelBulletin;
	
	/**
	 * Model_BulletinDate
	 * @var Model_BulletinDate
	 */
	private $_modelBulletinDate;
	
	/**
	 * Model_Org
	 * @var Model_Org
	 */
	private $_modelOrg;
	
	/**
	 * Model_BugBook
	 * @var Model_BugBook
	 */
	private $_modelBugBook;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * Model_ActivityLink
	 * @var Model_ActivityLink
	 */
	private $_modelActivityLink;
	
	/**
	 * Model_VoteConf
	 * @var Model_VoteConf
	 */
	private $_modelVoteConf;
	
	/**
	 * Model_Vote
	 * @var Model_Vote
	 */
	private $_modelVote;
	
	/**
	 * Model_VoteLog
	 * @var Model_VoteLog
	 */
	private $_modelVoteLog;
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	private function _createUrl(){
		$this->_url['ServiceTools_Add']=Tools::url(CONTROL,'Notice',array('doaction'=>'add'));
		$this->_url['UploadImg_Bulletin']=Tools::url('Default','ImgUpload',array('type'=>'Bulletin'));	//公告上传图片路径
		$this->_url['ServiceTools_BugBackDel']=Tools::url(CONTROL,'BugBack',array('doaction'=>'del'));
		$this->_url['ServiceTools_BugBackAdd']=Tools::url(CONTROL,'BugBack',array('doaction'=>'add'));
		$this->_url['UploadImg_BUG']=Tools::url('Default','ImgUpload',array('type'=>'BUG'));	//bug反馈图片上传路径
		$this->_url['ServiceTools_EditLink']=Tools::url(CONTROL,'LinkSetup',array('doaction'=>'edit'));
		$this->_url['ServiceTools_DelLink']=Tools::url(CONTROL,'LinkSetup',array('doaction'=>'del'));
		$this->_url['ServiceTools_AddLink']=Tools::url(CONTROL,'LinkSetup',array('doaction'=>'add'));
		$this->_url['ServiceTools_AddVote']=Tools::url(CONTROL,'VoteSetup',array('doaction'=>'add'));
		$this->_url['ServiceTools_VoteConf']=Tools::url(CONTROL,'VoteSetup',array('doaction'=>'conf'));
		$this->_url['ServiceTools_AddVoteConf']=Tools::url(CONTROL,'VoteSetup',array('doaction'=>'confAdd'));
		$this->_url['ServiceTools_DelVote']=Tools::url(CONTROL,'VoteSetup',array('doaction'=>'del'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 公告管理
	 */
	public function actionNotice(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_add();
				return ;
			}
			case 'edit' :{
				$this->_edit();
				return ;
			}
			case 'del' :{
				$this->_del();
				return ;
			}
		}
	}
	
	/**
	 * 公告显示
	 */
	public function actionNoticeShow(){
		switch ($_GET['doaction']){
			case 'show' :{
				$this->_show();
				return; 
			}
			case 'read' :{
				$this->_userRead();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}

	/**
	 * 公告
	 */
	private function _index(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$this->_modelBulletinDate=$this->_getGlobalData('Model_BulletinDate','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelBulletinDate->tName());
		$helpSqlSearch->setPageLimit($_GET['page'],4);
		$helpSqlSearch->set_orderBy('date desc');
		$sql=$helpSqlSearch->createSql();
		$helpPage=new Help_Page(array('total'=>$this->_modelBulletinDate->findCount(),'perpage'=>4));
		$dateList=$this->_modelBulletinDate->select($sql);	//找到这四天的记录
		if ($dateList){
			$users=$this->_getGlobalData('user');
			$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
			$bulletinKind=$this->_modelBulletin->createBulletinKind();
			foreach ($dateList as &$date){
				$date['date']=strtotime($date['date']);
				$date['date']=date('Y-m-d',$date['date']);
				$ids=unserialize($date['child_ids']);
				if (count($ids)){
					$list=$this->_modelBulletin->findByIds($ids);
					if (count($list)){//如果有记录
						foreach ($list as &$childList){
							$childList['word_kind']=$bulletinKind[$childList['kind']];
							$childList['create_time']=date('H:i:s',$childList['create_time']);
							$childList['word_user_id']=$users[$childList['user_id']]['nick_name'];
							if (strpos($childList['content'],'\\'))$childList['content']=str_replace('\\','',$childList['content']);
							$childList['not_read']=unserialize($childList['not_read']);
							$childList['url_edit']=Tools::url(CONTROL,'Notice',array('Id'=>$childList['Id'],'doaction'=>'edit'));
							$childList['url_del']=Tools::url(CONTROL,'Notice',array('Id'=>$childList['Id'],'doaction'=>'del'));
							if (count($childList['not_read'])){//如果还有未读用户
								if (array_search($userClass['_id'],$childList['not_read'])!==false){//如果当前登录用户没有读取这个邮件的话
									$childList['user_no_read']=true;//设为用户未读这个邮件
								}
								$childList['word_not_read']=array();
								foreach ($childList['not_read'] as $userId){
									$childList['word_not_read'][$userId]=$users[$userId]['nick_name'];
								}
								$childList['word_not_read']=implode(',',$childList['word_not_read']);
							}
						}
						$date['list']=$list;
					}
				}
			}
			$this->_view->assign('dataList',$dateList);
		}
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->set_tpl(array('body'=>'ServiceTools/Index.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 编辑公告
	 */
	private function _edit(){
		#------初始化------#
		$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
		#------初始化------#
		if ($this->_isPost()){
			$data=$this->_modelBulletin->edit($_POST);
			if ($data['status']==1){
				$this->_utilMsg->showMsg($data['msg'],1,Tools::url(CONTROL,'NoticeShow'));
			}else{
				$this->_utilMsg->showMsg($data['msg'],-2);
			}
		}else{
			$data=$this->_modelBulletin->findById($_GET['Id']);
			$data['not_read']=unserialize($data['not_read']);
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			$this->_view->assign('org',$orgList);
			#------显示分组用户------#
			$this->_view->assign('selectKind',$this->_modelBulletin->createBulletinKind());	//分类
			$this->_view->assign('data',$data);
			$this->_view->set_tpl(array('body'=>'ServiceTools/Edit.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 添加公告
	 */
	private function _add(){
		#------初始化------#
		$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
		#------初始化------#
		if ($this->_isPost()){
			$data=$this->_modelBulletin->add($_POST);
			if ($data['status']==1){
				$this->_utilMsg->showMsg($data['msg'],1,Tools::url(CONTROL,'NoticeShow'));
			}else{
				$this->_utilMsg->showMsg($data['msg'],-2);
			}
		}else{
			$this->_view->assign('selectKind',$this->_modelBulletin->createBulletinKind());	//分类
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			$this->_view->assign('org',$orgList);
			#------显示分组用户------#
			$this->_view->set_tpl(array('body'=>'ServiceTools/Add.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 删除公告
	 */
	private function _del(){
		$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
		if ($this->_modelBulletin->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else{
			$this->_utilMsg->showMsg('删除公告失败',-2);
		}
	}
	
	/**
	 * 显示单个公告
	 */
	private function _show(){
		$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
		$data=$this->_modelBulletin->findById($_GET['Id']);
		if (!$data)$this->_utilMsg->showMsg('该条记录已经被删除',-1);
		$this->_modelBulletin->userRead($_GET['Id']);//当前登录用户读取$_GET['Id']邮件
		$kind=$this->_modelBulletin->createBulletinKind();
		$users=$this->_getGlobalData('user');
		$data['word_user_id']=$users[$data['user_id']]['nick_name'];
		$data['word_kind']=$kind[$data['kind']];
		$data['create_time']=date('Y-m-d H:i:s',$data['create_time']);
		$data['not_read']=unserialize($data['not_read']);
		if (count($data['not_read'])){//如果还有未读用户
			$data['word_not_read']=array();
			foreach ($data['not_read'] as $userId){
				$data['word_not_read'][$userId]=$users[$userId]['nick_name'];
			}
			$data['word_not_read']=implode(',',$data['word_not_read']);
		}
		$this->_view->assign('data',$data);
		$this->_view->set_tpl(array('body'=>'ServiceTools/Show.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 用户读取文章ajax
	 */
	private function _userRead(){
		if ($this->_isAjax()){
			$this->_modelBulletin=$this->_getGlobalData('Model_Bulletin','object');
			$this->_modelBulletin->userRead($_GET['Id']);//当前登录用户读取$_GET['Id']邮件
		}
	}
	
	/**
	 * 活动链接
	 */
	public function actionLink(){
		$this->_modelActivityLink=$this->_getGlobalData('Model_ActivityLink','object');
		$gameTypeList=$this->_getGlobalData('game_type');
		$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelActivityLink->tName());
		if ($_GET['game_type_id']){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
		}
		if ($_GET['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		$helpSqlSearch->set_orderBy('edit_time desc');
		$helpSqlSearch->setPageLimit($_GET['page']);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelActivityLink->select($sql);
		if ($dataList){
			$users=$this->_getGlobalData('user');
			foreach ($dataList as &$list){
				$list['word_user_id']=$users[$list['user_id']]['nick_name'];
				$list['edit_time']=date('Y-m-d H:i:s',$list['edit_time']);
				$list['word_game_type_id']=$gameTypeList[$list['game_type_id']];
				$list['word_operator_id']=$operatorList[$list['operator_id']];
				$list['url_del']=Tools::url(CONTROL,'LinkSetup',array('Id'=>$list['Id'],'doaction'=>'del'));
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelActivityLink->findCount($conditions),'prepage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		
		$this->_view->assign('gameTypeList',$gameTypeList);
		$this->_view->assign('operatorList',$operatorList);
		$gameTypeList['']='所有';
		$operatorList['']='所有';
		$this->_view->assign('selectGameTypeList',$gameTypeList);
		$this->_view->assign('selectOperatorList',$operatorList);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 活动链接管理
	 */
	public function actionLinkSetup(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_addLink();
				return ;
			}
			case 'edit' :{
				$this->_editLink();
				return ;
			}
			case 'del' :{
				$this->_delLink();
				return ;
			}
		}
	}
	
	/**
	 * 添加活动链接
	 */
	private function _addLink(){
		$this->_modelActivityLink=$this->_getGlobalData('Model_ActivityLink','object');
		$data=$this->_modelActivityLink->add($_POST);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}
	
	/**
	 * 编辑活动链接
	 */
	private function _editLink(){
		$this->_modelActivityLink=$this->_getGlobalData('Model_ActivityLink','object');
		$data=$this->_modelActivityLink->edit($_POST);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}
	
	/**
	 * 删除活动链接
	 */
	private function _delLink(){
		$this->_modelActivityLink=$this->_getGlobalData('Model_ActivityLink','object');
		if ($this->_modelActivityLink->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else{
			$this->_utilMsg->showMsg('删除失败',-2,1);
		}
	}
	
	/**
	 * 投票
	 */
	public function actionVote(){
		switch ($_GET['doaction']){
			case 'show' :{
				$this->_voteShow();
				return ;
			}
			default:{
				$this->_voteIndex();
				return ;
			}
		}
	}
	
	/**
	 * 投票管理
	 */
	public function actionVoteSetup(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_voteAdd();
				return ;
			}
			case 'del' :{
				$this->_voteDel();
				return ;
			}
			case 'conf' :{
				$this->_voteConf();
				return ;
			}
			case 'confAdd' :{
				$this->_voteConfAdd();
				return ;
			}
			case 'confEdit' :{
				$this->_voteConfEdit();
				return ;
			}
			case 'confDel' :{
				$this->_voteConfDel();
				return ;
			}
			default:{
				$this->_voteConf();
				return ;
			}
		}
	}
	
	/**
	 * 删除投票
	 */
	private function _voteDel(){
		$this->_modelVote=$this->_getGlobalData('Model_Vote','object');
		if ($this->_modelVote->delById($_POST['ids'])){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}
	
	/**
	 * 投票
	 */
	private function _voteIndex(){
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$users=$this->_getGlobalData('user');
		$this->_modelVote=$this->_getGlobalData('Model_Vote','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelVote->tName());
		$helpSqlSearch->set_orderBy('start_time desc,end_time desc');
		$helpSqlSearch->setPageLimit($_GET['page']);
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelVote->select($sql);
		if ($dataList){
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			Tools::import('Util_FontColor');
			foreach ($dataList as &$list){
				$list['word_user_id']=$users[$list['user_id']]['nick_name'];
				$list['word_is_open']=Util_FontColor::getVoteOpen($list['is_open']);
				$list['start_time']=date('Y-m-d H:i:s',$list['start_time']);
				$list['end_time']=date('Y-m-d H:i:s',$list['end_time']);
				$list['my_status']=$this->_modelVote->getUserStatus($userClass['_id'],unserialize($list['vote_user']));
				$list['word_my_status']=Util_FontColor::getVoteUserStatus($list['my_status']);
				$list['url_show']=Tools::url(CONTROL,'Vote',array('Id'=>$list['Id'],'doaction'=>'show'));
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelVote->findCount(),'prepage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_view->set_tpl(array('body'=>'ServiceTools/Vote.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 投票详细
	 */
	private function _voteShow(){
		$this->_modelVote=$this->_getGlobalData('Model_Vote','object');
		if ($this->_isPost()){//投票
			$data=$this->_modelVote->vote($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {//显示投票详情
			$data=$this->_modelVote->findById($_GET['Id']);
			if ($data){
				if ($data['start_time']>CURRENT_TIME)$this->_utilMsg->showMsg('投票时间还未开始',-1);
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				$users=$this->_getGlobalData('user');
				$data['word_user_id']=$users[$data['user_id']]['nick_name'];
				$data['start_time']=date('Y-m-d H:i:s',$data['start_time']);
				$data['end_time']=date('Y-m-d H:i:s',$data['end_time']);
				$data['content']=unserialize($data['content']);
				$data['vote_user']=unserialize($data['vote_user']);
				$data['my_status']=$this->_modelVote->getUserStatus($userClass['_id'],$data['vote_user']);
				#------如果是马上显示或是已经过了结束时间就可以显示投票结果------#
				if ($data['is_open'] || $data['end_time']<CURRENT_TIME){
					$result=$data['result']?unserialize($data['result']):array();
					$voteResult=array();
					foreach ($data['content'] as $key=>$option){
						$voteResult[$option]=floatval($result[$key]);
					}
					$this->_view->assign('voteResult',json_encode($voteResult));
				}
				#------如果是马上显示或是已经过了结束时间就可以显示投票结果------#
				
				#------得到详细日志------#
				$this->_modelVoteLog=$this->_getGlobalData('Model_VoteLog','object');
				$voteLogList=$this->_modelVoteLog->findByVoteId($data['Id']);
				foreach ($voteLogList as &$log){
					$log['word_user_id']=$users[$log['user_id']]['nick_name'];
				}
				$this->_view->assign('voteLogList',$voteLogList);
				#------得到详细日志------#
				
				$mySource=$data['vote_user'][$userClass['_id']];
				$this->_view->assign('mySource',$mySource);
				$this->_view->assign('data',$data);
				$this->_view->set_tpl(array('body'=>'ServiceTools/VoteShow.html'));
				$this->_utilMsg->createNavBar();
				$this->_view->display();
			}else {
				$this->_utilMsg->showMsg(false);
			}
		}

	}
	
	/**
	 * 增加新的投票
	 */
	private function _voteAdd(){
		if ($this->_isPost()){
			$this->_modelVote=$this->_getGlobalData('Model_Vote','object');
			$data=$this->_modelVote->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			$users=$this->_getGlobalData('user');
			$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
			$selectedIsOpen=1;//默认马上公开投票结果
			$voteConfList=$this->_modelVoteConf->findAll();
			foreach ($voteConfList as &$list){
				$list['url_use_conf']=Tools::url(CONTROL,'VoteSetup',array('conf_id'=>$list['Id'],'doaction'=>'add'));
				if ($_GET['conf_id']==$list['Id']){//如果有传过来conf_id就表示用户选择了投票配置,那么就会搜出此配置
					$selectedConf=$list;
					$selectedConf['content']=unserialize($selectedConf['content']);
					$selectedConf['vote_user']=unserialize($selectedConf['vote_user']);
					$this->_view->assign('selectedConf',$selectedConf);
					$selectedIsOpen=$selectedConf['is_open'];
				}
			}
			$this->_view->assign('selectedIsOpen',$selectedIsOpen);
			$this->_view->assign('voteConfList',$voteConfList);
			$this->_view->assign('radioIsOpen',array('1'=>'是','0'=>'否'));
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			$this->_view->assign('org',$orgList);
			#------显示分组用户------#
			$this->_view->assign('users',$users);
			$this->_view->set_tpl(array('body'=>'ServiceTools/AddVote.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 投票配置列表
	 */
	private function _voteConf(){
		$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
		$dataList=$this->_modelVoteConf->findAll();
		if ($dataList){
			foreach ($dataList as &$list){
				$list['url_edit']=Tools::url(CONTROL,'VoteSetup',array('Id'=>$list['Id'],'doaction'=>'confEdit'));
				$list['url_del']=Tools::url(CONTROL,'VoteSetup',array('Id'=>$list['Id'],'doaction'=>'confDel'));
			}
			$this->_view->assign('dataList',$dataList);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'ServiceTools/VoteConf.html'));
		$this->_view->display();
	}
	
	/**
	 * 编辑投票配置
	 */
	private function _voteConfEdit(){
		if ($this->_isPost()){
			$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
			$data=$this->_modelVoteConf->edit($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			$users=$this->_getGlobalData('user');
			$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
			$voteConf=$this->_modelVoteConf->findById($_GET['Id']);
			if ($voteConf){
				$voteConf['content']=unserialize($voteConf['content']);
				$voteConf['vote_user']=unserialize($voteConf['vote_user']);
				$this->_view->assign('voteConf',$voteConf);
			}
			$this->_view->assign('radioIsOpen',array('1'=>'是','0'=>'否'));
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			$this->_view->assign('org',$orgList);
			#------显示分组用户------#
			$this->_view->assign('users',$users);
			$this->_view->set_tpl(array('body'=>'ServiceTools/EditVoteConf.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 删除投票配置
	 */
	private function _voteConfDel(){
		$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
		if ($this->_modelVoteConf->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}
	
	/**
	 * 增加投票配置
	 */
	private function _voteConfAdd(){
		if ($this->_isPost()){
			$this->_modelVoteConf=$this->_getGlobalData('Model_VoteConf','object');
			$data=$this->_modelVoteConf->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			#------显示分组用户------#
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			$orgList=$this->_modelOrg->findUsersToCache();	//获取所有用户,按分组来显示 
			$this->_view->assign('org',$orgList);
			#------显示分组用户------#		
			$this->_view->set_tpl(array('body'=>'ServiceTools/AddVoteConf.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	
	public function actionBugBack(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_bugAdd();
				return ;
			}
			case 'del' :{
				$this->_bugDel();
				return ;
			}
			default:{
				$this->_bugIndx();
				return ;
			}
		}
	}
	
	/**
	 * Bug反馈
	 */
	private function _bugIndx(){
		$this->_modelBugBook=$this->_getGlobalData('Model_BugBook','object');
		$users=$this->_getGlobalData('user');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelBugBook->tName());
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelBugBook->select($sql);
		if ($dataList){
			$helpPage=new Help_Page(array('total'=>$this->_modelBugBook->findCount(),'perpage'=>PAGE_SIZE));
			foreach ($dataList as &$list){
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['word_user_id']=$users[$list['user_id']]['nick_name'];
				$list['word_reply_user_id']=$users[$list['reply_user_id']]['nick_name'];
				if (strpos($list['content'],'\\'))$list['content']=str_replace('\\','',$list['content']);
				if (strpos($list['reply_content'],'\\'))$list['reply_content']=str_replace('\\','',$list['reply_content']);
			}
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_view->set_tpl(array('body'=>'ServiceTools/BugBack.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 添加BUG
	 */
	private function _bugAdd(){
		if ($this->_isPost()){
			$this->_modelBugBook=$this->_getGlobalData('Model_BugBook','object');
			$data=$this->_modelBugBook->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}
	}
	
	/**
	 * 删除BUG
	 */
	private function _bugDel(){
		if ($this->_isPost()){
			$this->_modelBugBook=$this->_getGlobalData('Model_BugBook','object');
			$data=$this->_modelBugBook->batchDel($_POST['ids']);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}
	}
}