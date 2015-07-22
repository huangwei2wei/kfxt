<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivationTypeAdd_LuanShi extends Action_ActionBase{

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
				$serverId=$_POST['server_id'];
				
				$postData['EquipageId'] = $_POST['Outfit'];
				$postData['EquipageName'] = $_POST['OutfitName'];
				$postData['EquipageNum'] = $_POST['OutfitNum'];
				$postData['cardType'] = $_POST['cardType'];
				
				$postData['ToolIdEName'] = $_POST['ToolIdEName'];
				$postData['ToolIdImg'] = $_POST['ToolImg'];
				$postData['ToolIdName'] = $_POST['ToolName'];
				$postData['ToolId'] = $_POST['Tool'];
				$postData['ToolNum'] = $_POST['ToolNum'];
			 
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '类型名 :'.$postData['cardType'].'<br>';
				$apply_info .= '道具:<br>';
				foreach ($_POST['ToolName'] as $k=>$v){
					$apply_info .= $v .':'. $_POST['ToolNum'][$k].'<br>';
				}
				$apply_info .= '<p>装备：<br>';
				foreach ($_POST['OutfitName'] as $k=>$v){
					$apply_info .= $v .':'. $_POST['OutfitNum'][$k].'<br>';
				}
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>48,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>$postData,
								'get_data'=>$this->_getData($get),
// 								'call'=>array(
// 										'cal_local_object'=>'Util_ApplyInterface',
// 										'cal_local_method'=>'FrgSendReward',
// 								)
						),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>0,
						'player_info'=>0,
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
// 				print_r($applyData);exit;
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
				$data = $this->getResult($UrlAppend,$this->_getData(array('m'=>'Admin','c'=>'Reward','a'=>'SendReward','__hj_dt'=>'_DP_JSON_CLIENT')),null);
				if ($data && is_array($data)){
					$toolDatas = array();
					$zbDatas = array();
					foreach ($data['data']['ToolData'] as $toolData){
						$toolDatas[$toolData['id']]['Id'] = $toolData['id'];
						$toolDatas[$toolData['id']]['toolename'] = $toolData['ename'];
						$toolDatas[$toolData['id']]['toolsname'] = $toolData['cname'];
						$toolDatas[$toolData['id']]['toolsimg'] = $toolData['image'];
					}
					$this->_assign['toolData'] = json_encode($toolDatas);
					foreach ($data['data']['EquipagesData'] as $zbData){
						$zbDatas[$zbData['id']]['Id'] = $zbData['id'];
						$zbDatas[$zbData['id']]['Name'] = $zbData['cname'];
					}
					$this->_assign['outfitData']= json_encode($zbDatas);
					if ($_POST['UserId'])$this->_assign['changeUsers'] = implode(',',$_POST['UserId']);
				}else {
					$this->_assign['errorConn'] = Tools::getLang('CONNECT_SERVER_ERROR','Common');
				}
			}
		}
		return $this->_assign;
	}
}