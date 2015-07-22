<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Silence_Default_1 extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 2;
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['URL_silenceLocalLog'] = $this->_urlLocalLog();
		$this->_assign['URL_silenceInGame'] = $this->_urlInGame();
		switch($_REQUEST['doaction']){
			case self::INGAME:	//拿游戏中实际封号数据
				$this->_dataInLocalLog();
				break;
			default :
				$getData = $this->_gameObject->getGetData($get);
				$getData["Page"]		=	max(0,intval($_GET['page']));
				$data = $this->getResult($UrlAppend,$getData);
				//		print_r($data);
				if($data['states'] == '1'){
					$this->_assign['dataList']=$data["List"];
					$this->_loadCore('Help_Page');
					$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
					$this->_assign['pageBox'] = $helpPage->show();
				}
				$this->_assign['gameData']=1;
				break;
		}
		
		$this->_assign['Add_Url']=$this->_urlAdd();
		$this->_assign['Del_Url']=$this->_urlDel();
		return $this->_assign;
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
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
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
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

}