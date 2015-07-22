<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Silence_chunqiu extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 2;
	public function _init(){
		$this->_assign['URL_silenceLocalLog'] = $this->_urlLocalLog();
		$this->_assign['URL_silenceInGame'] = $this->_urlInGame();
		$this->_assign['URL_silenceAdd'] = $this->_urlSilenceAdd();

	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_REQUEST['doaction']=='LocalLog'){
			$this->_dataInLocalLog();
		} else {
			$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
			$data = json_decode($data,true);
		
			if($data['status'] == 1){
				foreach($data["characters_banned_list"] as &$item){
					$item["URL_del"]	=	$this->_urlSilenceDel($item["char_id"]);
				}
				$this->_assign["dataList"]	=	$data["characters_banned_list"];
			}
		}
		return $this->_assign;
	}

	private function _dataInGame($UrlAppend=null,$get=null,$post=null){
		$postData	=	array();
		
		$dataList = array();
		if(is_array($data['data'])){
			foreach($data['data'] as $key => &$sub){
				$dataList[$key]['game_user_id'] = $sub['playerId'];
				$dataList[$key]['game_user_account'] = $sub['accountName'];
				$dataList[$key]['game_user_nickname'] = $sub['playerName'];
				$dataList[$key]['endTime'] = date('Y-m-d H:i:s',$sub['endTime']);
				$dataList[$key]['URL_del'] = $this->_urlSilenceDel($sub['playerId'],$sub['accountName'],$sub['playerName']);
			}
		}
		$this->_assign['dataList'] = $dataList;
	}

	private function _dataInLocalLog(){
		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($modelGameOperateLog->tName());
		$helpSqlSearch->set_conditions("game_server_id=".intval($_REQUEST['server_id']));
		$helpSqlSearch->set_conditions("operate_type=".self::LOGTYPE);
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('Id desc');
		$sql=$helpSqlSearch->createSql();
		$data = $modelGameOperateLog->select($sql);
		$users=$this->_getGlobalData('user_index_id');//获得所有用户的数组
		foreach($data as &$sub){
			$sub['user_id'] = $users[$sub['user_id']];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['URL_del'] = $this->_urlSilenceDel($sub['game_user_id'],$sub['game_user_account'],$sub['game_user_nickname']);
			$sub['info'] = unserialize($sub['info']);
			$sub['endTime'] = $sub['info']['FromGame']['endTime'];
		}
		$this->_assign['dataList'] = $data;
		$this->_loadCore('Help_Page');//载入分页工具
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$modelGameOperateLog->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign['showlog'] = 1;
		return $this->_assign;
	}

	private function _urlSilenceAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}
	private function _urlSilenceDel($playerId=null){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'users'=>$playerId,
		);
		return Tools::url(CONTROL,'SilenceDel',$query);
	}

	private function _urlInGame(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'doaction'=>self::INGAME,
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

	private function _urlLocalLog(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'doaction'=>'LocalLog',
		);
		return Tools::url(CONTROL,'Silence',$query);
	}
}