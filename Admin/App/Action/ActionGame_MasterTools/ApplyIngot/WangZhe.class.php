<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ApplyIngot_WangZhe extends Action_ActionBase{

	public function _getData($data){
		$getData = $this->_gameObject->getGetData($data);
		if($data){
			$getData = array_merge($getData,$data);
		}
		return $getData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){

		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			if ($this->_isPost() && $_POST['submit']){//提交表单
				$serverId=$_POST['server_id'];
				$getData = array();
				$getData['playerType'] = $_POST['ReceiveType'];
				$getData['playerIds'] = base64_encode(str_replace('，', ',', $_POST['UserIds']));
				$getData['num'] = intval($_POST['ingotNum']);
				$getData['title'] = base64_encode(trim($_POST['MsgTitle']));
				$getData['content'] = base64_encode(trim($_POST['MsgContent']));
				
				$utilRbac = $this->_getGlobalData('Util_Rbac','object');
				$userClass = $utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($getData['num'] >20000){
						$this->jump('元宝不能过20000',-1);
					}
				}
				
				$receiveType = array(1=>'用户ID',2=>'用户名',3=>'用户昵称');
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '元宝:'.$_POST['ingotNum'];
				$apply_info .= '<br>用户类型：'.$receiveType[$_POST['ReceiveType']];
				$apply_info .= '<br>用户：'.$_POST['UserIds'];
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>45,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>array(),
								'get_data'=>$this->_getData($getData),
// 								'call'=>array(
// 										'cal_local_object'=>'Util_ApplyInterface',
// 										'cal_local_method'=>'FrgSendReward',
// 								)
						),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>$_POST['ReceiveType'],
						'player_info'=>$_POST['UserIds'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
// 				print_r($applyData);exit;
				$applyInfo = $_modelApply->AddApply($applyData);
// 				print_r($applyInfo);exit;
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->jump($showMsg,1,1,false);
				}else{
					$this->jump($applyInfo['info'],-1);
				}
			}
		}
		return $this->_assign;
	}
}