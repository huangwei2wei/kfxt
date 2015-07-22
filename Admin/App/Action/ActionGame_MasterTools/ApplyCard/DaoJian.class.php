<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ApplyCard_DaoJian extends Action_ActionBase{

	public function _getData($data,$actionName = ''){
		$getData = $this->_gameObject->getGetData($data,0,$actionName);
		if($data){
			$getData = array_merge($getData,$data);
		}
		return $getData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
//print_r($_POST);
//exit;	
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$type = array(2=>'铜币',3=>'元宝',4=>'礼金',5=>'声望',6=>'真气',7=>'经验',8=>'荣誉',9=>'帮派贡献',10=>'Q点',12=>'功勋',14=>'兽魂'); //0:道具1:装备
			if ($this->_isPost() && $_POST['submit']){//提交表单
				$attachments = '';
				$serverId = $_POST['server_id'];
				$postData['content'] = $_POST['MsgContent'];
				$postData['title'] = $_POST['MsgTitle'];
				$postData['endTime'] = $_POST['endTime'];
				$postData['players'] = $_POST['UserIds'];
				$postData['playerType'] = $_POST['ReceiveType'];
				$postData['sender']='系统邮件';
				
				if(is_array($_POST['Tool'])){
					foreach ($_POST['Tool'] as $k=>$v){ // 道具      物品的类型_物品的数量_物品ID_绑定状态_物品的星级|...
						$attachments .= '0_'. $_POST['ToolNum'][$k].'_'.$v .'_0'.'|';
					}
				}
				if(is_array($_POST['Outfit'])){
					foreach ($_POST['Outfit'] as $k=>$v){  // 装备
						$attachments .= '1_'.$_POST['OutfitNum'][$k].'_'.$v .'_0_'.$_POST['starNum'][$k] .'|';
					}
				}
				if(is_array($_POST['otype'])){
					foreach ($_POST['otype'] as $k=>$v){  // 其他
						if($v){
							$attachments .=  $k.'_'.$v.'|';
						}
					}
				}
				$attachments = substr($attachments,0,strlen($attachments)-1);
				$postData['attachments'] = $attachments;
				
				$receiveType = array(1=>'用户ID',2=>'用户名',3=>'用户昵称');
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '邮件标题:'.$postData['MsgTitle'].'<br>';
				$apply_info .= '邮件内容:'.$postData['MsgContent'].'<br>';
				$apply_info .= '结束时间:'.$postData['endTime'].'<br>';
				
				$apply_info .= '道具:<br>';
				foreach ($_POST['ToolName'] as $k=>$v){
					$apply_info .= $v .' 数量:'. $_POST['ToolNum'][$k].'<br>';
				}
				$apply_info .= '<p>装备：<br>';
				foreach ($_POST['OutfitName'] as $k=>$v){
					$apply_info .= $v .' 数量:'. $_POST['OutfitNum'][$k].'  星级：'.$_POST['starNum'][$k].'<br>';
				}
				$apply_info .='<br>';
				
				foreach ($_POST['otype'] as $k => $v){
					if($k == 3){
						$utilRbac = $this->_getGlobalData('Util_Rbac','object');
						$userClass = $utilRbac->getUserClass();
						if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
							if($v>20000){
								$this->jump('元宝不能过20000',-1);
							}
						}
					}
					if($v){
						$apply_info .= $type[$k].'('.$v.')';
					}
				}
				
				$apply_info .= '<br>用户类型：'.$receiveType[$_POST['ReceiveType']];
				$apply_info .= '<br>用户：'.$_POST['UserIds'];
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>53,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>$postData,
								'get_data'=>$this->_getData(),
// 								'call'=>array(
// 										'cal_local_object'=>'Util_ApplyInterface',
// 										'cal_local_method'=>'FrgSendReward',
// 								)
								'end'=>array(
									'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
									'cal_local_method'=>'applyEnd',	//使用本地方法
									'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
								),
						),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>$_POST['ReceiveType'],
						'player_info'=>$_POST['UserIds'],
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
				
				$this->_assign['type'] = $type;
				$data = $this->getResult('getGoods',$this->_getData(array('gameId'=>1),'getGoods'),null);
//				print_r($data);
//				exit;
				
				if ($data && $data['result'] == 0 ){
					$toolDatas = array();
					$zbDatas = array();
					foreach ($data['data'] as $k => $v){
							if($v['type'] == 0){ // 1-装备,0-道具,
								$toolDatas[$v['ID']]['Id'] = $v['ID'];
								$toolDatas[$v['ID']]['toolename'] = 'null';
								$toolDatas[$v['ID']]['toolsname'] = $v['name'];
								$toolDatas[$v['ID']]['toolsimg'] = 'null';
							}elseif ($v['type'] == 1){
								$zbDatas[$v['ID']]['Id'] = $v['ID'];
								$zbDatas[$v['ID']]['Name'] = $v['name'];
							}
					}

					$this->_assign['toolData'] = json_encode($toolDatas);
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