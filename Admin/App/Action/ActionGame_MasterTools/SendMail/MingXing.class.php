<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_MingXing extends Action_ActionBase{
	protected $_userType;
	protected $_users;
	protected $_title;
	protected $_content;
	protected $_cause;
	protected $_tplList;

	public function _init(){
		$this->_assign['playerTypes'] = array(
			'2'=>'openId',
			'3'=>'昵称',
		);
	}
	public function getPostData($post=null){
		$this->_userType = intval($_POST['userType']);
		if(!array_key_exists($this->_userType ,$this->_assign['playerTypes'])){
			$this->jump('玩家类型错误');
		}
		$this->_users = trim($_POST['users']);
		$this->_title = trim($_POST['titlemsg']);
		$this->_content = $_POST['content'];
		$this->_cause = trim($_POST['cause']);
		$postData = array(
			'title'=>$this->_title,
			'content'=>$this->_content,
		);
		if($this->_userType==2){
			$postData['openIdlist'] = $this->_users;
		}elseif ($this->_userType==3) {
			$postData['nameList'] = $this->_users;
		}
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}

		if($this->_isAjax()){
			$this->ajaxAction($get,$UrlAppend);
			die();
		}
		if($this->_isPost() && $_REQUEST['sbm']){

			if($_POST["isSendProp"]==1){
				$postData = $this->getPostData($post);
// 				print_r($postData);exit;
				$getData = $this->_gameObject->getGetData($get);
				$cause	=	$_POST["cause"]."<br/>";
				if(is_array($_POST["propid"])){
					foreach($_POST["propid"] as $key=>&$_msg){
						$postData["tplList"][]	=		$_msg.",".$_POST["propmun"][$key];
					}
					$postData["tplList"] = implode(";",$postData["tplList"]);
				}
				$postData["silverCoin"]	=	$_POST["silverCoin"];
				$postData["vouchers"]	=	$_POST["vouchers"];
				$postData["goldCoin"]	=	$_POST["goldCoin"];
				
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($postData["goldCoin"] >20000){
						$this->jump("金币不能过20000",-1);
					}
				}
				
				$getData = array_merge($getData,$postData);
				$cause	.=	"赠送银币:".$postData["silverCoin"]."<br>";
				$cause	.=	"赠送礼券:".$postData["vouchers"]."<br>";
				$cause	.=	"赠送金币:".$postData["goldCoin"]."<br>";
				if($_REQUEST['userType']==2){
					$cause	.=	"OpenId:".$_POST["users"]."<br>";
				}elseif ($_REQUEST['userType']==3){
					$cause	.=	"昵称:".$_POST["users"]."<br>";
				}
				$cause	.=	$postData["tplList"];
				
				$applyData = array(
						'type'=>36,	//从Game拿id
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$this->_serverList[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$this->_serverList[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$cause,
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>$UrlAppend,
							'post_data'=>$postData,
							'get_data'=>$getData
						),
						'receiver_object'=>array($_REQUEST['server_id']=>''),
						'player_type'=>$_POST['userType'],
						'player_info'=>$_POST['users'],
				);
				$modelApply = $this->_getGlobalData('Model_Apply','object');
				$end	=	$modelApply->AddApply($applyData);
// 				var_dump($end);
// 				exit;
				if($end["status"]!=0){
					$this->jump($end["info"],-1);
				}else{
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->jump($showMsg,1,1,false);
				}

			}else{
				$postData = $this->getPostData($post);
				$getData = $this->_gameObject->getGetData($get);
// 				print_r($UrlAppend);
// 				print_r(array_merge($getData));
// 				print_r($postData);
				$data = $this->getResult($UrlAppend,array_merge($getData,$postData));
// 				print_r($data);
// 				echo '---';
// 				exit;
				if($data['status'] == 1){
					$this->jump('发送成功');
				}else{
					$errorInfo = '发送失败:';
					$this->jump($errorInfo,-1);
				}
			}

		}
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['userTypeSelect'] = 2;
		$this->_assign['users'] = $playerIds;
		$this->_assign['ajax_url']		=	$this->_urlTypeajax();
		$this->_assign['type']		=	$this->_typeArr();
		return $this->_assign;
	}

	public function ajaxAction($get,$UrlAppend){
		$get["action"]	=	"getItemTemplateList";
		$getData = $this->_gameObject->getGetData($get);
		$getData["type"]	=	$_GET["typeid"];
		$data = $this->getResult($UrlAppend,$getData,NULL);
		if($data["status"]==1){
			foreach($data["data"]["list"] as &$_msg){
				$_msg["name"]	=	urldecode($_msg["name"]);
			}
			$jsonData	=	$data;
		}else{
			$jsonData = array('status'=>0,'info'=>"连接错误",'data'=>NULL);
		}


		exit(json_encode($jsonData));
	}

	private function _urlTypeajax(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'doaction'=>'ajax',
		);
		return Tools::url(CONTROL,ACTION,$query);
	}

	private function _typeArr(){
		return array(
			"1"=>"装备",
			"2"=>"商城道具",
			"3"=>"道具店道具",
			"4"=>"任务道具",
			"5"=>"制作产品",
			"6"=>"制作产品",
			"7"=>"制作材料",
			"8"=>"技能书",
			"9"=>"礼盒礼包",
			"10"=>"家族任务道具",
			"12"=>"其他类道具",
			"13"=>"时装道具",
			"15"=>"签名及其碎片",
			"16"=>"宝石及粘",
		);
	}



}