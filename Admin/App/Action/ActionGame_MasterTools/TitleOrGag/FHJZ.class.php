<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_TitleOrGag_FHJZ extends Action_ActionBase{
	const INGAME = 'inGame';
	protected $_lockUserType = array(); //1 封号 2 禁言
	public function _init(){
		$this->_assign['URL_TitleOrGagLocalLog'] = $this->_urlLocalLog();
		$this->_assign['URL_TitleOrGagInGame'] = $this->_urlInGame();
		$this->_assign['URL_AddTitleOrGag'] = $this->_urlLockAccountAdd();
		$this->_lockUserType = Tools::gameConfig('lockUserType',$this->_gameObject->_gameId);
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		switch($_REQUEST['doaction']){
			case self::INGAME:	//拿游戏中实际封号数据
				$this->_dataInGame($UrlAppend,$get,$post);
				break;
			default :
				$this->_dataInLocalLog();
				break;
		}
		return $this->_assign;
	}
	
	private function _dataInGame($UrlAppend=null,$get=null,$post=null){
		$postData = $this->_gameObject->getPostData($post);
		$postData = array_merge($postData,array('pageSize'=>PAGE_SIZE,'page'=>max(1,intval($_GET['page'])),));
		$data = $this->getResult($UrlAppend,$get,$postData);
		$list =$data['data']['list'];
// 		print_r($data);
		if(is_array($list)){
			foreach($list as $key => &$sub){
				$sub['game_user_id'] = $sub['userID'];
				$sub['game_user_account'] = $sub['userName'];
				$sub['game_user_nickname'] = $sub['nickName'];
				
				$des = $this->_lockUserType[$sub['type']].' 至 '.date('Y-m-d H:i:s',$sub['endTime']);
				$sub['des'] =  $des;
				
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
		$helpSqlSearch->set_conditions("(operate_type = 1 or operate_type = 2)");
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('Id desc');
		$sql=$helpSqlSearch->createSql();
// 		echo $sql;
		$data = $modelGameOperateLog->select($sql);
		$users=$this->_getGlobalData('user_index_id');//获得所有用户的数组
		foreach($data as &$sub){
			$sub['user_id'] = $users[$sub['user_id']];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['URL_del'] = $this->_urlLockAccountDel($sub['game_user_id'],$sub['game_user_account'],$sub['game_user_nickname'],$sub['operate_type']);
			$sub['info'] = unserialize($sub['info']);
			$sub['cause'] = $sub['info']['AddString'];
			if($sub['sub_type'] == 1){
				$des = '解除    ' .$this->_lockUserType[$sub['operate_type']];
			}else{
				$des = $this->_lockUserType[$sub['operate_type']].' 至 '.$sub['info']['FromGame']['endTime'];
			}
			$sub['des'] =  $des;
		}
		$this->_assign['dataList'] = $data;
		$this->_loadCore('Help_Page');//载入分页工具
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$modelGameOperateLog->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();
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
		);
		return Tools::url(CONTROL,'TitleOrGag',$query);
	}
}