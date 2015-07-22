<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_AddPlayerPay_SanFen extends Action_ActionBase{

	public function _init(){
// 		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
	}
	public function getPostData($post=null){

		$postData = array(
			'playerId'=>$_POST['playerId'],
			'payPrice'=>$_POST['payPrice'],
		);
		$utilRbac = $this->_getGlobalData('Util_Rbac','object');
		$userClass = $utilRbac->getUserClass();
		if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
			if($_POST['payPrice']>20000){
				$this->jump('不能过20000',-1);
			}
		}
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		$serverId = $_REQUEST['server_id'];

// 		if($this->_isAjax()){
		if($this->_isPost()){
			if(!$serverId){
				$this->jump("请选择服务器!",-1);
			}
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			
// 			$data = $this->getResult($UrlAppend,$getData,$postData);
			$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
			$apply_info = "MVP金币申请:".'<br>';
			$apply_info.= "理由：".$_POST['cause'].'<br>';
			$apply_info .= '用户id:'.$_POST['playerId'].'<br>';
			$apply_info .= '游戏币:'.$_POST['payPrice'].'<br>';
			$apply_info .= '</div>';
			
			$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
			$applyData = array(
					'type'=>73,
					'server_id'=>$serverId,
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
							'url_append'=>$UrlAppend,
							'post_data'=>$postData,
							'get_data'=>$getData,
							'end'=>array(
								'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
								'cal_local_method'=>'applyEnd',	//使用本地方法
								'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
							),
					),
					'receiver_object'=>array($serverId=>''),
					'player_type'=>1,
					'player_info'=>$_POST['playerId'],
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			// 				print_r($applyData);exit;
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
			
			
// 			if($data['status'] == 1 && $data['data']=='true'){
// 				$this->jump('操作成功！',-1);
// 			}else{
// 				$this->jump('操作失败！'.$data['info'],-1);
// 			}
			
		}
		
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['userTypeSelect'] = 1;
		$this->_assign['users'] = $playerIds;
		return $this->_assign;
	}
	
}