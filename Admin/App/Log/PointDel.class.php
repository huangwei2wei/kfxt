<?php
Tools::import('Log_LogBase');
class Log_PointDel extends Log_LogBase{
	
	public function _init(){}
	
	function update(SplSubject $subject){
		if(!is_numeric($subject->_players)){
			return false;
		}
		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$info = "
			银两:{$subject->_asset},
			金币:{$subject->_gold},
			银票:{$subject->_goldTicket},
		";
		$AddLog = array(
			array('操作','<font style="color:#F00">扣除点数</font>'),
			array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
			array('操作人','<b>{UserName}</b>'),
			array('扣除数据',$info),
		);
	
		$AddLog = $modelGameOperateLog->addInfoMake($AddLog);
		$userInfo['UserId'] = $subject->_players;
		$GameOperateLog = $modelGameOperateLog->MakeDataForStore($userInfo,8,$subject->_serverId,$AddLog);
		if(false !== $GameOperateLog){
			$modelGameOperateLog->add($GameOperateLog);
		}
	}
}