<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ApplyCard_WangZhe extends Action_ActionBase{

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

				unset($_POST['submit']);
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				$serverId=$_POST['server_id'];
// 				print_r($_POST);
// 				$postData['EquipageId'] = $_POST['Outfit'];
// 				$postData['EquipageName'] = $_POST['OutfitName'];
// 				$postData['MsgContent'] = $_POST['MsgContent'];
// 				$postData['MsgTitle'] = $_POST['MsgTitle'];
// 				$postData['ReceiveType'] = $_POST['ReceiveType'];
// 				$postData['ToolIdEName'] = $_POST['ToolIdEName'];
// 				$postData['ToolIdImg'] = $_POST['ToolImg'];
// 				$postData['ToolIdName'] = $_POST['ToolName'];
// 				$postData['ToolId'] = $_POST['Tool'];
// 				$postData['ToolNum'] = $_POST['ToolName'];
// 				$postData['UserIds'] = $_POST['UserIds'];
				$getData = array();
				$getData['content'] = base64_encode($_POST['MsgContent']);
				$getData['title'] = base64_encode($_POST['MsgTitle']);
				foreach ($_POST['ToolId'] as $k=>$v){
					$getData['propsInfo'] .= $v .','. $_POST['ToolNum'][$k].'|';
				}
				$len = strlen($getData['propsInfo']);
				if($len>0){
					$getData['propsInfo'] = substr($getData['propsInfo'], 0,$len-1);
				}
				$getData['playerType'] = $_POST['ReceiveType'];
				$getData['playerIds'] = base64_encode(str_replace('，', ',', $_POST['UserIds']));
				
				$receiveType = array(1=>'用户ID',2=>'用户名',3=>'用户昵称');
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '道具:<br>';
				foreach ($_POST['ToolName'] as $k=>$v){
					$apply_info .= $v .':'. $_POST['ToolNum'][$k].'<br>';
				}
				$apply_info .= '<p>装备：<br>';
				foreach ($_POST['OutfitName'] as $k=>$v){
					$apply_info .= $v .':'. $_POST['OutfitNum'][$k].'<br>';
				}
				$apply_info .= '<br>用户类型：'.$receiveType[$_POST['ReceiveType']];
				$apply_info .= '<br>用户：'.$_POST['UserIds'];
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>44,
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
// 				var_dump($applyInfo);exit;
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
			}else {//显示表单
				
				$data = $this->getResult("getAllProps",$this->_getData(array('UrlAppend'=>'getAllProps')),null);
				
				if ($data && is_array($data)){
					$toolDatas = array();
					$zbDatas = array();
					foreach ($data['data']['list'] as $toolData){
						$id = $toolData['expandValue']['propsModelId'];
						$toolDatas[$id]['Id'] = $id;
						$toolDatas[$id]['toolename'] = 'null';
						$toolDatas[$id]['toolsname'] = $toolData['name'];
						$toolDatas[$id]['toolsimg'] = 'null';
					}
					
					$this->_assign['toolData'] = json_encode($toolDatas);
					if ($_POST['UserId'])$this->_assign['changeUsers'] = implode(',',$_POST['UserId']);
				}else {
					$this->_assign['errorConn'] = Tools::getLang('CONNECT_SERVER_ERROR','Common');
				}
			}
		}
		return $this->_assign;
	}
}