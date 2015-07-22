<?php
Tools::import('Log_LogBase');
class Log_GameLogin extends Log_LogBase{
	public function _init(){
		
	}
	function update(SplSubject $subject){
		$playerType = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称');
		$arr = array(
			$subject->_operatorId,	//运营商ID
			$subject->_ordinal,		//服号
			$subject->_serverId,		//服务器ID
			$subject->_playerAccount,//玩家账号
			$subject->_playerType,  //账号类型
			$subject->_cause,		//操作原因
			
		);
		
		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		
		$AddLog = array(
				array('操作','<font style="color:#F00">用户登录</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array('操作原因',$subject->_cause),
				array('账号类型',$playerType[$subject->_playerType]),
				array('账号',$subject->_playerAccount),
		);
		$AddLog = $modelGameOperateLog->addInfoMake($AddLog);
		$userInfo['UserId'] = 0;
		$GameOperateLog = $modelGameOperateLog->MakeDataForStore($userInfo,14,$subject->_serverId,$AddLog);
// 		print_r($GameOperateLog);exit;
		if(false !== $GameOperateLog){
			$modelGameOperateLog->add($GameOperateLog);
		}
	}
}