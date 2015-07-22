<?php
import('@.Action.BaseAction');
class ServerSelect extends BaseAction {
	
	/**
	 * 获取某个游戏的服务器列表
	 * @param int $gameId
	 */
	public function getGameServers($gameId){	
		$key='gameser_list_'.$gameId;
		$servers=S($key);
		if(! $servers){
			$dao = M("gameser_list");
			$filter=array();
			$filter['game_type_id']=$gameId;
			//官网所属ID 
			$filter['operator_id']=C('WE_OPERATOR');
			$servers = $dao->where("game_type_id={$gameId} and operator_id={$filter['operator_id']} and Id!=100 and Id!=200")->select();
//			foreach ($servers as $key=>&$value){
//				if (strpos($value['marking'],'|'))continue;
//			}
			if($servers){
				S($key,$servers,60*10);
			}
		}
		return $servers;		
	}
	
	/*
	 * 获取所有服务器列表
	 */
	public function getAllservers(){
		$key="gameser_list_";
		$servers=S($key);
		if(! $servers){
			$dao = M("gameser_list");
			$servers = $dao->where("Id!=100 and Id!=200")->select();
			if($servers){
				S($key,$servers,60*10);
			}
		}
		return $servers;
	}
	
	/**
	 * 获取指定服务器id的资料
	 * @param int $serverId
	 */
	public function getServerApiUrl($serverId){
		$servers=$this->getAllservers();
		foreach ($servers as $key=>$list){
			if ($list['Id']==$serverId)return $list;
		}
		return false;
	}
	
	
	public function getUserCreateServers($gameId){
		$servers=$this->getGameServers($gameId);
		$createUserServers=$this->_getCreateUserServerIds($gameId);//获取可用标识
		if (!count($createUserServers))return array();
		foreach ($servers as $key=>$list){//循环服务器
			
			
			if(count(explode('|',$list['marking'])) == 2){
				$list['marking']=end(explode('|',$list['marking']));
			}else{
				$list['marking']=strtoupper(reset(explode('.',$list['marking'])));
			}
			
			
			
			if (!array_key_exists($list['marking'],$createUserServers))//如果当前循环的服务器不在已建立用户的服务器下面,将删除掉.
				unset($servers[$key]);
		}
		return $servers;
	}
	
	/**
	 * 返回用户在哪个服务器里面有角色的服务器列表
	 * @param  $gameId
	 */
	private function _getCreateUserServerIds($gameId){
		
		$game_type = $this->getSysConfig("game_type");
		$UwanGameId = $game_type[$gameId]['UwanGameId'];
		$key="create_user_server_{$UwanGameId}_{$this->uwanName}";
		$serverList=S($key);
		if (!$serverList){
			import('@.Util.WebService');
			$webService=new WebService();
			$webService->setUrl(C('USER_CREATE_SERVER_URL'));
			$webService->setGet(array('g'=>$UwanGameId,'u'=>$this->uwanName));
			$webService->sendData();
			$data=$webService->getRaw();
			$data=json_decode($data);
			$serverList=get_object_vars($data->last_list);
			foreach ($serverList as $key=>$list){
				if (strpos($list,'N'))unset($serverList[$key]);
			}
			if ($serverList){
				S($key,$serverList,60*60*2);
			}
		}

		return $serverList;
	}
}