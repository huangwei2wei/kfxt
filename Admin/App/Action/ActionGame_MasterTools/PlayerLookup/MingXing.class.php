<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_MingXing extends Action_ActionBase{
	
	public function _init(){
//		$query = array(
//			'zp'=>PACKAGE,
//			'__game_id'=>$this->_gameObject->_gameId,
//			'server_id'=>$_REQUEST['server_id'],
//			'playerType'=>1,
//		);
//		$ShortcutUrl = array(
//			'SendMail'=>Tools::url(CONTROL,'SendMail',$query),
//			'SilenceAdd'=>Tools::url(CONTROL,'SilenceAdd',$query),
//			'LockAccountAdd'=>Tools::url(CONTROL,'LockAccountAdd',$query),
//			'PointDel'=>Tools::url(CONTROL,'PointDel',$query),
//		);
//		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=array(
			'nickName'=>urlencode(trim($_GET['nickName'])),
			'openId'=>trim($_GET['openId']),
			'regStartTime'=>strtotime($_GET['regStartTime']),
			'regEndTime'=>strtotime($_GET['regEndTime']),
			'loginStartTime'=>strtotime($_GET['loginStartTime']),
			'loginEndTime'=>strtotime($_GET['loginEndTime']),
			'pageIndex'=>max(1,intval($_GET['page'])),
			'pageSize'=>PAGE_SIZE,
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		$sendData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$sendData);
		$status = 0;
		$info = null;
		if($data['status']==1){
			$status = 1;
			if($data['data']['list'] && is_array($data['data']['list'])){
				$qqvip	=	array("0"=>"普通用户","1"=>"绿钻","2"=>"黄钻","3"=>"蓝钻","4"=>"红钻");
				foreach($data['data']['list'] as &$sub){
					$sub['lastLoginDate']	=	date("Y-m-d h:i:s",$sub['lastLoginDate']);
					$sub['creationDate']	=	date("Y-m-d h:i:s",$sub['creationDate']);
					$sub['lastAccessDate']	=	date("Y-m-d h:i:s",$sub['lastAccessDate']);
					$sub['privilege']	=	$qqvip[$sub['privilege']];
					$sub['URL_personalInformation'] = Tools::url(
						CONTROL,
						'PersonalInformation',
						array(
							'zp'=>PACKAGE,
							'__game_id'=>$this->_gameObject->_gameId,
							'server_id'=>$_REQUEST['server_id'],
							'nickName'=>$sub['nickName'],
							'openId'=>$sub['openId'],
							'sbm'=>'1',
						)
					);
				}
				
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['count'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			$info = $data['info'];
		}
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$status,'info'=>$info,'data'=>$this->_assign));
		}
		return $this->_assign;
	}
}