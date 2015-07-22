<?php
Tools::import('Log_LogBase');
class Log_Silence extends Log_LogBase{
	public function _init(){

	}
	function update(SplSubject $subject){


		//		$subject->_serverId;
		//		$subject->_players;
		//		$subject->_playerType;
		//		$subject->_endTime;
		//		$subject->_cause;
		//		$subject->_playersData;
		//		$subject->_playersData传进来的规范
		//		$subject->_playersData = array(
		//			array('playerId'=>'玩家ID','playerAccount'=>'玩家账号','playerNickname'=>'玩家昵称',),
		//			array('playerId'=>'玩家ID','playerAccount'=>'玩家账号','playerNickname'=>'玩家昵称',),
		//		);

		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		if($subject->_silenceDel){
			$AddLog = array(
			array('操作','<font style="color:#F00">解除禁言</font>'),
			array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
			array('操作人','<b>{UserName}</b>'),
			array('操作原因',$subject->_cause),
			);
		}else{
			$AddLog = array(
			array('操作','<font style="color:#F00">禁言</font>'),
			array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
			array('操作人','<b>{UserName}</b>'),
			array('禁言结束时间',$subject->_endTime),
			array('操作原因',$subject->_cause),
			);
		}

		$AddLog = $modelGameOperateLog->addInfoMake($AddLog);
		foreach($subject->_playersData as $sub){
			$userInfo['endTime'] = trim($subject->_endTime);
			$userInfo['UserId'] = $sub['playerId'];
			$userInfo['UserAccount'] = $sub['playerAccount'];
			$userInfo['UserNickname'] = $sub['playerNickname'];
			if($subject->_silenceDel){
				$userInfo['sub_type'] = 1;
			}
			$GameOperateLog = $modelGameOperateLog->MakeDataForStore($userInfo,2,$subject->_serverId,$AddLog);
			//			var_dump($GameOperateLog);
			//			die();
			if(false !== $GameOperateLog){
				$modelGameOperateLog->add($GameOperateLog);
			}
		}


	}
}