<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameData_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
			$gamelist	=	$this->_getGlobalData('gameser_list');
			foreach($gamelist as $_msg){
				if($_msg['game_type_id']==$this->_gameObject->_gameId){
					$_REQUEST['server_id']	=	$_msg['Id'];
				}
			}
			$data = $this->getResult($UrlAppend,$get,$post);
			print_r($data);
	}
	
	
}