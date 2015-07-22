<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_RewardTriggerAdd_LuanShi extends Action_ActionBase{

	public function _getData($data){
		$getData = $this->_gameObject->getGetData($data);
		if($data){
			$getData = array_merge($getData,$data);
		}
		return $getData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){

		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			if ($this->_isAjax()){//提交表单
				unset($_POST['submit']);
				$serverId=$_POST['server_id'];
				$postData['EquipageId'] = $_POST['Outfit'];
				$postData['EquipageName'] = $_POST['OutfitName'];
				$postData['EquipageNum'] = $_POST['OutfitNum'];
				$postData['content'] = $_POST['content'];
				$postData['title'] = $_POST['title'];
				$postData['ToolId']=$_POST['Tool'];
				$postData['ToolIdName']=$_POST['ToolName'];
				$postData['ToolIdEName']=$_POST['ToolIdEName'];
				$postData['ToolIdImg']=$_POST['ToolImg'];
				$postData['ToolNum'] = $_POST['ToolNum'];
				
				$postData['startTime'] = trim($_POST['startTime']);
				$postData['endTime'] = trim($_POST['endTime']);
				$postData['registerTimeStart'] = trim($_POST['registerTimeStart']);
				$postData['registerTimeEnd'] = trim($_POST['registerTimeEnd']);
				$postData['plevelStart'] = $_POST['plevelStart'];
				$postData['plevelEnd'] = $_POST['plevelEnd'];
				$postData['WarAward']['warCard'] = $_POST['WarAward']['warCard'];
				$postData['playerAward'] = $_POST['playerAward'];
				$postData['vipStart'] = trim($_POST['vipStart']);
				$postData['vipEnd'] = trim($_POST['vipEnd']);
				
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '邮件标题:'.$postData['title'].'<br>';
				$apply_info .= '邮件内容:'.$postData['content'].'<br>';
				
				if(is_array($_POST['ToolName'])){
					$apply_info .= '道具:<br>';
					foreach ($_POST['ToolName'] as $k=>$v){
						$apply_info .= $v .':'. $_POST['ToolNum'][$k].'<br>';
					}
				}
				if(is_array($_POST['OutfitName'])){
					$apply_info .= '装备：<br>';
					foreach ($_POST['OutfitName'] as $k=>$v){
						$apply_info .= $v .':'. $_POST['OutfitNum'][$k].'<br>';
					}
				}
				$apply_info .='<br>';
				if($_POST['WarAward']['warCard']){
					$apply_info .= '战令('.$_POST['WarAward']['warCard'].')';
				}
				$apply_info .='<br>';
				$arr = array('copper'=>'铜币','popularity'=>'声望','curExp'=>'经验','silver'=>'银两','soul'=>'战魂');
				foreach ($_POST['playerAward'] as $k => $v){
					if($v){
						$apply_info .= $arr[$k].'('.$v.')';
					}
				}
				
				$apply_info .= '<br>皇权范围:'.$postData['vipStart'] .'到'.$postData['vipEnd'];
				$apply_info .='<br>人物等级开始:'.$postData['plevelStart'];
				$apply_info .='<br>人物等级结束:'.$postData['plevelEnd'];
				$apply_info .='<br>开始时间:'.$postData['startTime'];
				$apply_info .='<br>结束时间 :'.$postData['endTime'];
				$apply_info .='<br>注册开始时间:'.$postData['registerTimeStart'];
				$apply_info .='<br>注册结束时间:'.$postData['registerTimeEnd'];
				$apply_info .= '</p></div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>47,
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
				if( true === $applyInfo){
					$this->ajaxReturn(array('status'=>1,'info'=>'申请成功！','data'=>null));
				}else{
					$this->ajaxReturn(array('status'=>0,'info'=>'申请失败！','data'=>null));
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
			$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
		}
		
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
		return $this->_assign;
	}
}