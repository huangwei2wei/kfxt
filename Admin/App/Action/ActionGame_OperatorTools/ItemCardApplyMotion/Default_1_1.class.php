<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ItemCardApplyMotion_Default_1_1 extends Action_ActionBase{
	private $_playerType;
	private $_cardType;

	private $goodsDis = '物品：<br>';
	public function _init(){
		$this->_playerType = Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_cardType = Tools::gameConfig('cardType',$this->_gameObject->_gameId);
		$this->_assign['playerType'] = $this->_playerType;
		/*客服现在全服功能  */
		$utilRbac = $this->_getGlobalData('Util_Rbac','object');
		$userClass = $utilRbac->getUserClass();
		//if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
	 	//	unset($this->_cardType[3],$this->_cardType[4]);
		//}
		/*客服现在全服功能  */
		$this->_assign['cardType'] = $this->_cardType;
		$this->_assign['items'] = $this->partner('Item','main',array(),'MasterTools');
		$this->_assign['URL_itemUpdate'] = Tools::url(
				//CONTROL,
				'MasterTools',
				'Item',
				array('timeout'=>'1','zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
	}
	
	public function getPostData($post=null){
		$playerType = $_POST['playerType'];
		$player = $_POST['player'];
		$mailTitle = $_POST['mailTitle'];
		$mailContent = $_POST['mailContent'];
		$type = $_POST['cardType'];
		$invalidTime = strtotime(trim($_POST['invalidTime']));
		$count = max(1,$_POST['count']);
		$goods = '';
		foreach ($_POST['itemNum'] as $k => $v){
			$goods .= $k.'_'.$v.'&';
			$this->goodsDis .= '--名称：'.$_POST['itemName'][$k].'  数量：'.$v .'</br>';
		}
		$goods = substr($goods,0,strlen($goods)-1);
		$postData = array(
				'type'=>$type,
				'userType'=>$playerType,
				'user'=>$player,'title'=>$mailTitle,
				'title'=>$mailTitle,
				'content'=>$mailContent,
				'goods'=>$goods,
				'invalidTime'=>$invalidTime,
				'count'=>$count,
				);
		if($post && is_array($post)){
			$postData = array_merge($postData,$post);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		$serverId = $_REQUEST['server_id'];
		if(!$serverId){
			return $this->_assign;
		}
		if($this->_isPost()){
				$postData = $this->getPostData($post);
				
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				
				$serverStr = $gameser_list[ $_REQUEST['server_id'] ]['server_name'];
				$apply_info = "申请原因：<br>{$_POST['cause']}";
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '<div>服务器地址:'.$serverStr.'</div>';
				$apply_info .= '<div>标题：'.$_POST['mailTitle'].'</div>';
				$apply_info .= '<div>内容：'.$_POST['mailContent'].'</div>';
				$apply_info .= $this->goodsDis;
				$apply_info .= "<div>类型：{$this->_cardType[$_POST['cardType']]}</div>";
				if($_POST['cardType'] == 2){
					$apply_info .= "<div>生成数量：{$_POST['count']}</div>";
				}
				if($_POST['cardType'] == 1){
					$apply_info .= '<div>账号类型：'.$this->_playerType[$_POST['playerType']].'</div>';
					$apply_info .= '<div>账号：'.$_POST['player'].'</div>';
				}
				$apply_info .= '</div>';
				
				$applyData = array(
						'type'=>70,//46,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>$postData,
								'get_data'=>$this->_gameObject->getGetData($get),
// 								'call'=>array(
// 										'cal_local_object'=>'Util_ApplyInterface',
// 										'cal_local_method'=>'FrgSendReward',
// 								)
						),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>$_POST['playerType'],
						'player_info'=>$_POST['player'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');

				$applyInfo = $_modelApply->AddApply($applyData);

				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->jump($showMsg,1,1,false);
				}else{
					$this->jump('申请失败',1);
				}
		}
		//$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
		return $this->_assign;
	}
	
}