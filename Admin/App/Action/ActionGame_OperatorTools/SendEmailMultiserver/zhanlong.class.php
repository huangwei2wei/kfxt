<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_SendEmailMultiserver_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($this->_isPost()){
			$cause .= "全服发送邮件<br>";
			$getData = $this->_gameObject->getGetData($get);
			$r = "";
			$cause .="内容：".trim($_POST['content'])."<br>";
			$cause .="标题：".trim($_POST['title'])."<br>";
			foreach($_POST["server_ids"] as $v){
				$postData = array(
					'WorldID'=>intval($this->_serverList[$v]["ordinal"]),
					'IsAllPlayer'=>intval(1),
					'Title'=>trim($_POST['title']),
					'Content'=>trim($_POST['content']),
				);
				$SendData["data"]	=	json_encode($postData);
				$applyData = array(
					'type'=>51,	//从Game拿id
					'server_id'=>$v,
					'operator_id'=>$this->_serverList[$v]['operator_id'],
					'game_type'=>$this->_serverList[$v]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>str_replace(array("\r\n","\n",),'',$cause),
					'send_type'=>1,	//2	http
					'send_data'=>array(
						'url_append'=>$UrlAppend,
						'post_data'=>$SendData,
						'get_data'=>$getData,
						'call'=>array(
							'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
							'cal_local_method'=>'ApplySend',
							'params'	=>array('data'=>$SendData,"server_id"=>$v,"UrlAppend"=>$UrlAppend),
				),
				),
					'receiver_object'=>array($serverId=>''),
					'player_type'=>0,
					'player_info'=>0,
				);
				$modelApply = $this->_getGlobalData('Model_Apply','object');

				if($modelApply->AddApply($applyData)){
					$r .= "[".$this->_serverList[$v]["server_name"]."]提交成功<br>";
				}else{
					$r .= "[".$this->_serverList[$v]["server_name"]."]提交失败<br>";
				}
			}
			$URL_CsIndex = Tools::url('Apply','CsIndex');
			$URL_CsAll = Tools::url('Apply','CsAll');
			$showMsg = '申请成功,等待审核...<br>';
			$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
			$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
			$this->jump($r.$showMsg,1,1,false);

		}
		$this->_assign['tplServerSelect'] = 'BaseZp/MultiServerSelect.html';
		return $this->_assign;
	}


}