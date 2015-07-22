<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_TitleOrGag_Default_1_1 extends Action_ActionBase{
	const INGAME = 'inGame';
	protected $_lockUserType = array(); //1 封号 2 禁言
	public function _init(){
		$this->_assign['URL_TitleOrGagLocalLog'] = $this->_urlLocalLog();
		$this->_assign['URL_TitleOrGagInGame'] = $this->_urlInGame();
		$this->_assign['URL_AddTitleOrGag'] = $this->_urlLockAccountAdd();
		$this->_lockUserTypes = Tools::gameConfig('lockUserType',$this->_gameObject->_gameId);
		$this->_assign['lockUserType'] = $this->_lockUserTypes;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		
		if($_REQUEST['LocalLog'] == 1){
			$this->_dataInLocalLog();
		} else {
			$this->_dataInGame($UrlAppend,$get,$post);
		}
		return $this->_assign;
	}
	
	private function _dataInGame($UrlAppend=null,$get=null,$post=null){
		
		$get  =  $this->_gameObject->getGetData($get);
		$get = array_merge($get,array('pageSize'=>PAGE_SIZE,'page'=>max(1,intval($_GET['page'])),));
		
		$data = $this->getResult($UrlAppend,$get);
		$list =$data['list'];
		if(is_array($list)){
			foreach($list as $key => &$sub){
				$sub['game_user_id'] = $sub['userID'];
				$sub['game_user_account'] = $sub['userName'];
				$sub['game_user_nickname'] = $sub['nickName'];
				$sub['type_str'] = $this->_lockUserType[$sub['type']];
				
				$sub['URL_del'] = $this->_urlLockAccountDel($sub['userID'],$sub['userName'],$sub['nickName'],$sub['type']);
			}
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $list;
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['dataList'] = $list;
	}
	
	private function _dataInLocalLog(){
		$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($modelGameOperateLog->tName());
		$helpSqlSearch->set_conditions("game_server_id=".intval($_REQUEST['server_id']));
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('Id desc');
		$sql=$helpSqlSearch->createSql();
		$data = $modelGameOperateLog->select($sql);
		$users=$this->_getGlobalData('user_index_id');//获得所有用户的数组
		foreach($data as &$sub){
			$sub['user_id'] = $users[$sub['user_id']];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['URL_del'] = $this->_urlLockAccountDel($sub['game_user_id'],$sub['game_user_account'],$sub['game_user_nickname']);
			$sub['info'] = unserialize($sub['info']);
		}
		$this->_assign['dataList'] = $data;
		$this->_loadCore('Help_Page');//载入分页工具
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$modelGameOperateLog->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();
		$this->_assign['LocalLog']=1;
	}
	
	private function _urlLockAccountAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'AddTitleOrGag',$query);
	}
	private function _urlLockAccountDel($playerId=null,$playerAcount=null,$playerNickName=null,$type=1){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'playerId'=>$playerId,
			'playerAccount'=>urlencode($playerAcount),
			'playerNickname'=>urlencode($playerNickName),
			'type'=>$type,
		);
		return Tools::url(CONTROL,'DelTitleOrGag',$query);
	}
	
	private function _urlInGame(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'doaction'=>self::INGAME,
		);
		return Tools::url(CONTROL,'TitleOrGag',$query);
	}
	
	private function _urlLocalLog(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'LocalLog'=>1,
		);
		return Tools::url(CONTROL,'TitleOrGag',$query);
	}
}