<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_ZhiDouXing extends Action_ActionBase{

	public function _init(){
		
	}
	public function getPostData($post=null){
		$_serverId = intval($_REQUEST['server_id']);
		$remark = $_POST['remark'];
		$account = $_POST['account'];
		$startTime = strtotime($_POST['startTime']);
		$endTime = strtotime($_POST['endTime']);
		
		$validate = array(
			'account'=>array('trim','玩家不能为空'),
			'startTime'=>array('is_numeric','开始时间格式错误'),
			'endTime'=>array('is_numeric','结束时间格式错误'),
			'remark'=>array(),
		);
		$postData =  array(
			'account'=>$account,
			'startTime'=>$startTime,
			'endTime'=>$endTime,
			'remark'=>$remark
		);
		$check = Tools::arrValidate($postData,$validate);
		if ($check!==true){
			$this->jump($check,-1);
		}
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$this->_isPost()){
			return $this->_assign;
		}
		if($_REQUEST['sbm']){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$jumpUrl = $this->_urlSilence();
				$this->jump('操作成功',1,$jumpUrl);
				return;
			}else{
				$this->jump('操作失败:'.$data['info'],-1);
				return;
			}	
		}
	}
	
//"$data" = Array [3]	
//	data = Array [2]	
//		0 = Array [4]	
//			endTime = (int) 1330411559	
//			playerId = (int) 1	
//			playerName = (string:4) 花容失色	
//			accountName = (string:3) 111	
//		1 = Array [4]	
//	status = (int) 1	
//	info = null	
	
	
	private function _urlSilence(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}
	
	private function _getPlayers(){
		$_POST['players'] = trim($_POST['players']);
		if(isset($_POST['separator']) && $_POST['separator'] && $_POST['separator']!=self::SEPARATOR){
			$_POST['players'] = str_replace($_POST['separator'],self::SEPARATOR,$_POST['players']);
		}
		return $_POST['players'];
	}
}