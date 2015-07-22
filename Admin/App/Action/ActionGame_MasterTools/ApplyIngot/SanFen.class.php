<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ApplyIngot_SanFen extends Action_ActionBase{
   private $type;
   private $playerType;
	public function _init(){
		$this->type = array('ingot'=>'金币','coin'=>'银币','gift'=>'礼券','prestige'=>'威望','exploit'=>'军功');
		$this->playerType = array(1=>'用户ID',2=>'用户账号',3=>'用户呢称 ');
		$this->_assign['playerType'] = $this->playerType;
		$this->_assign['type'] = $this->type;
	}
	public function getPostData($data){
		$playerType = $_POST['playerType'];
		$player = $_POST['player'];
		$postData = array(
				'playerType'=>$playerType,
				'player'=>$player,
		);
		foreach ($this->type as $k=>$v){
			if($k == 'ingot'){
				$utilRbac = $this->_getGlobalData('Util_Rbac','object');
				$userClass = $utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($_POST['type'][$k] >20000){
						$this->jump('金币不能过20000',-1);
					}
				}
			}
			$postData[$k] = $_POST['type'][$k];
		}
		if($data){
			$postData = array_merge($postData,$data);
		}
		$postData = array_filter($postData);
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			if ($this->_isPost()){//提交表单
				$serverId=$_POST['server_id'];
				$postData = $this->getPostData($post);
				$getData = $this->_gameObject->getGetData($get);
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= '<br>用户类型：'.$this->type[$_POST['playerType']];
				$apply_info .= '<br>用户：'.$_POST['player'];
				$apply_info .= '<br><br>金币及人物属性:';
				foreach ($_POST['type'] as $k => $v){
					if($v > 0){
						$apply_info .= '<br>'. $this->type[$k] .': '.$v;
					}
				}
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>56,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>$postData,
								'get_data'=>$getData,
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