<?php
Tools::import('Log_LogBase');
class Log_TitleOrGag extends Log_LogBase{
	public function _init(){
	}
	function update(SplSubject $subject){
		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
/* 		if($subject->_idDel){
			$AddLog = array(
				array('操作','<font style="color:#F00">删除'.$subject->_lockUserTypes[$subject->_lockUserType].'</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array('操作原因',$subject->_cause),
			);
		}else{
			$AddLog = array(
				array('操作','<font style="color:#F00">'.$subject->_lockUserTypes[$subject->_lockUserType].'</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array('禁言结束时间',$subject->_endTime),
				array('操作原因',$subject->_cause),
			);
		}
		$AddLog = $modelGameOperateLog->addInfoMake($AddLog); */
		
		foreach($subject->_playersData as $sub){
			$userInfo['endTime'] = trim($subject->_endTime);
			$userInfo['UserId'] = $sub['playerId'];
			$userInfo['UserAccount'] = $sub['playerAccount'];
			$userInfo['UserNickname'] = $sub['playerNickname'];
			
			if( $subject->_idDel ){
				$AddLog = array(
					array('操作','<font style="color:#F00">删除'.$subject->_lockUserTypes[$subject->_lockUserType].'</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('操作原因',$subject->_cause),
				);
			} else {
				$AddLog = array(
					array('操作','<font style="color:#F00">'.$subject->_lockUserTypes[$subject->_lockUserType].'</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('禁言结束时间',$subject->_endTime),
					array('操作原因',$subject->_cause),
				);
			}
			
			$AddLog = $modelGameOperateLog->addInfoMake($AddLog);
			
			if($subject->_idDel){
				$userInfo['sub_type'] = 1;
			}
			$GameOperateLog = $modelGameOperateLog->MakeDataForStore($userInfo,$subject->_lockUserType,$subject->_serverId,$AddLog);
			if(false !== $GameOperateLog){
				$modelGameOperateLog->add($GameOperateLog);
			}
		}
	}
}