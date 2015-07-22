<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendUserPackage_Default_1  extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign["items"]	=	$this->_f($this->getFileCacheName("ActionGame_MasterTools_Item_Default_1"));
		$this->_assign["updatecache"] = Tools::url(CONTROL,'Item',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			if(!empty($_POST["Users"])){
				$postData["Users"]		=	$_POST["Users"];
				$postData["userType"]	=	$_POST["userType"];
			}
			$userTypeArr=array(0=>'玩家Id:',1=>'玩家账号:',2=>'玩家昵称:');
			$cause =  "账号信息:".$userTypeArr[$postData['userType']].$postData['Users']."<br/>";
			$cause .= "原因:<br>".$_POST["cause"]."<br/>";
			//发道具时需要邮件发送功能
			if(trim($_POST['Content']) !='' && trim($_POST['Title']) != ''){
				$cause .= "邮件标题:<br>".$_POST["Title"]."<br/>";
				$cause .= "邮件内容:<br>".$_POST["Content"]."<br/>";
			}
			
			$sendArr = array();
			$i=0;
			$cause .="道具：<br>";
			foreach($_POST["itemName"] as $k=>$v){
				if( intval($_POST['itemNum'][$k]) == 0) continue;
				
				//个数限制(能量的个数做限制)
				if($k == 999999){
					$utilRbac = $this->_getGlobalData('Util_Rbac','object');
					$userClass = $utilRbac->getUserClass();
					if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
						if( intval($_POST['itemNum'][$k]) >20000){
							$this->jump('不能超过20000',-1);
						}
					}
				}
					
				if($_POST["is_reduce"][$k]==1){
					$sendArr[$i]["isAdd"]	=	$_POST["is_reduce"][$k];
					$cause .= "扣除 ".$v."/数量：".$_POST["itemNum"][$k]."<br>";
				}else{
					$sendArr[$i]["isAdd"]	=	0;
					$cause .= "发送 ".$v."/数量：".$_POST["itemNum"][$k]."<br>";
				}
				
				$sendArr[$i]["itemId"]		=	$k;
				$sendArr[$i]["Itemtype"]	=	$_POST["itemType"][$k];
				$sendArr[$i]["Count"]		=	intval($_POST["itemNum"][$k]);
				$i++;
			}
			if(count($sendArr) <=0){
				$this->jump('参数有误',-1);
			}
			$postData["itemList"]	=	json_encode($sendArr);
			$serverId	=	$_REQUEST["server_id"];
			
			//发道具时需要邮件发送功能
			if(trim($_POST['Content']) !='' && trim($_POST['Title']) != ''){
				$getIfConf = $this->_gameObject->getIfConf();
				$postData['SendMail'] = array(
					'UrlAppend'=>$getIfConf['SendMail']['UrlAppend'],
					'getData'=>$getData,
					'data'=>array(
						"Content" => trim($_POST['Content']),
						"Title"  => trim($_POST['Title']),
						"userType" => intval($_POST['userType']),
						"Users"	=>	trim($_POST['Users']),
					)
				);	
			}
			
			$applyData = array(
			'type'=>55,	
			'server_id'=>$serverId,
			'operator_id'=>$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>str_replace(array("\r\n","\n",),'',$cause),
			'send_type'=>1,	//2	http
			'send_data'=>array(
				'url_append'=>$UrlAppend,
				'post_data'=>$postData,
				'get_data'=>$getData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
					'cal_local_method'=>'ApplySend',
					'params'	=>array('data'=>$postData,"server_id"=>$_REQUEST['server_id'],"UrlAppend"=>$UrlAppend),
			),
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>$postData["userType"],
			'player_info'=>$postData["Users"],
			);

			$modelApply = $this->_getGlobalData('Model_Apply','object');
			if($modelApply->AddApply($applyData)){
				//				die();
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->jump($showMsg,1,1,false);
			}
			$this->jump('申请失败',-1);
		}
		return $this->_assign;
	}

	private function _urlNoticeDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'NoticeID'=>$id,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}

	private function _urlNoticeEdit($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'NoticeID'=>$id,
		);
		return Tools::url(CONTROL,'NoticeEdit',$query);
	}

	private function _urlNoticeAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeAdd',$query);
	}

	//"$data" = Array [3]
	//	data = Array [6]
	//		0 = Array [6]
	//			endTime = (int) 0
	//			url = (string:0)
	//			beginTime = (int) 0
	//			id = (int) 1
	//			title = (string:10) 欢迎访问游戏!!!!	
	//			initialDelay = (int) 60
	//		1 = Array [6]
	//		2 = Array [6]
	//		3 = Array [6]
	//		4 = Array [6]
	//		5 = Array [6]
	//	status = (int) 1
	//	info = null

}