<?php
/**
 * 游戏后台操作日志
 * @author php-兴源
 */
class Model_GameOperateLog extends Model {
	protected $_tableName='game_operate_log';
	
	public function findGameUserLog($GameServerId=0,$GameUserId=0,$type=0){
		$GameServerId = intval($GameServerId);
		$GameUserId = intval($GameUserId);
		$type = intval($type);
		if(!$GameServerId || !$GameUserId) return false;
		$dataList=$this->select("select * from {$this->tName()} where operate_type ={$type} and game_server_id={$GameServerId} and game_user_id={$GameUserId} order by Id desc limit 20");
		return $dataList;
	}
	
	/**
	 * 整理一批入库数据
	 * @param $data //含有$sub['UserId']的数组
	 * @param $operate_type
	 * @param $game_server_id
	 * @param $addInfo
	 */
	public function GameOperateLogMake($data,$operate_type,$game_server_id=-1,$addInfo='{UserName} to do'){
		
		if(!isset($data) || !is_array($data) || count($data)==0)return false;		
		$GameserList =$this->_getGlobalData ( 'gameser_list' );
		if($game_server_id!=-1 && !array_key_exists($game_server_id,$GameserList))return false;
		$GameOperateType = $this->_getGlobalData ( 'game_operate_type' );
		if(!array_key_exists($operate_type,$GameOperateType))return false;
		//额外信息
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass=$this->_utilRbac->getUserClass();
		$addInfo = str_replace('{UserName}',$userClass['_nickName'],$addInfo);
		$ReturnData =array();
		foreach($data as $key => $sub){
			$info = array('FromGame'=>$sub,'AddString'=>$addInfo);
			$info = serialize($info);			
			$ReturnData[$key]['game_user_id'] = $sub['UserId'];
			$ReturnData[$key]['game_type'] = $GameserList[$game_server_id]['game_type_id'];
			$ReturnData[$key]['operator_id'] = $GameserList[$game_server_id]['operator_id'];
			$ReturnData[$key]['game_server_id'] = $game_server_id;
			$ReturnData[$key]['user_id'] = $userClass['_id'];
			$ReturnData[$key]['operate_type'] = $operate_type;
			$ReturnData[$key]['info'] = $info;
			$ReturnData[$key]['create_time'] = CURRENT_TIME;
		}
		return $ReturnData;
	}
	
	/**
	 * 整理单个入库数据
	 * @param $data //含有UserId键名
	 * @param $operate_type
	 * @param $game_server_id
	 * @param $addInfo
	 */
	public function MakeDataForStore($data,$operate_type,$game_server_id=-1,$addInfo='{UserName} to do'){
		if(!isset($data) || !is_array($data) || count($data)==0)return false;		
		$GameserList =$this->_getGlobalData ( 'gameser_list' );
		if($game_server_id!=-1 && !array_key_exists($game_server_id,$GameserList))return false;
		$GameOperateType = $this->_getGlobalData ( 'game_operate_type' );
		if(!array_key_exists($operate_type,$GameOperateType))return false;
		//额外信息
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass=$this->_utilRbac->getUserClass();
		$addInfo = str_replace('{UserName}',$userClass['_nickName'],$addInfo);
		$info = array('FromGame'=>$data,'AddString'=>$addInfo);
		$info = serialize($info);
		$ReturnData =array();
		$ReturnData['game_user_id'] = $data['UserId'];
		if(isset($data['UserAccount'])){
			$ReturnData['game_user_account'] = $data['UserAccount'];
		}
		if(isset($data['UserNickname'])){
			$ReturnData['game_user_nickname'] = $data['UserNickname'];
		}
		if(isset($data['sub_type'])){
			$ReturnData['sub_type'] = $data['sub_type'];
		}
		$ReturnData['game_type'] = $GameserList[$game_server_id]['game_type_id'];
		$ReturnData['operator_id'] = $GameserList[$game_server_id]['operator_id'];
		$ReturnData['game_server_id'] = $game_server_id;
		$ReturnData['user_id'] = $userClass['_id'];
		$ReturnData['operate_type'] = $operate_type;
		$ReturnData['info'] = $info;
		$ReturnData['create_time'] = CURRENT_TIME;
		return $ReturnData;
	}
	
	public function addInfoMake($data){
		$addStr = '';
		if($data && is_array($data)){
			foreach($data as $val){
				if(is_array($val)){
					$addStr .= array_shift($val);
					$addStr .= ':';
					$addStr .= array_shift($val);
					$addStr .= '<br>';
				}
			}
		}
		return $addStr;		
	}
	
	public function getDetail($serverId=0,$gameUserId=0,$operateType=0,$page=1,$pageSize=50){
		$serverId = intval($serverId);
//		$gameUserId = intval($gameUserId);
		$operateType = intval($operateType);
		$page = max(1,intval($page));
		$pageSize = abs($pageSize);
		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_field('Id,info,create_time');
		$helpSqlSearch->set_tableName($this->tName());
		if($serverId){
			$helpSqlSearch->set_conditions("game_server_id={$serverId}");
		}
		if($gameUserId){
			$helpSqlSearch->set_conditions("game_user_id={$gameUserId}");
		}
		if($operateType){
			$helpSqlSearch->set_conditions("operate_type={$operateType}");
		}
		$helpSqlSearch->set_orderBy('Id desc');
		$helpSqlSearch->setPageLimit($page,$pageSize);
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->select($sql);
		if(!$dataList)return array();
		foreach($dataList as &$sub){
			$sub['info'] = unserialize($sub['info']);
			$sub['info'] = $sub['info']['AddString'];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
		}
		return $dataList;
	}
}