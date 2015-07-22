<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Silence_ZhiDouXing extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 2;
	public function _init(){
		$this->_assign['URL_silenceAdd'] = $this->_urlSilenceAdd();
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$this->_dataInGame($UrlAppend,$get,$post);
		return $this->_assign;
	}
	
	private function _dataInGame($UrlAppend=null,$get=null,$post=null){
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		
		if(is_array($data['data'])){
			foreach($data['data'] as $key => &$sub){
				$startTime = $sub['startTime']/1000;
				$endTime = $sub['endTime']/1000;
				$sub['account'] = $sub['account'];
				$sub['playerName'] = $sub['playerName'];
				$sub['startTime'] = date('Y-m-d H:i:s',$startTime);
				$sub['endTime'] = date('Y-m-d H:i:s',$endTime);
				$sub['remark'] = $sub['remark'];
				$sub['URL_del'] = $this->_urlSilenceDel($sub['account']);
				
				if($sub['status']==0 ){
					$sub['statusName'] ='强制解封';
				}elseif ($sub['status']==1 ){
					if(time() <$endTime && time()> $startTime){
						$sub['statusName'] ='禁言中';
					}elseif (time() < $startTime){
						$sub['statusName'] ='禁言尚未开始';
					}elseif (time() > $endTime ){
						$sub['statusName'] ='自动解封';
					}
				}
			}
		}
		$this->_assign['dataList'] = $data['data'];
	}
	
	
	private function _urlSilenceAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}
	private function _urlSilenceDel($account=null){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'account'=>$account
		);
		return Tools::url(CONTROL,'SilenceDel',$query);
	}
}