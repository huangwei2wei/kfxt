<?php
class Util_Rooms extends Control {
	/**
	 * Model_Rooms
	 * @var Model_Rooms
	 */
	private $_modelRooms;
	
	/**
	 * Object_Room
	 * @var Object_Room
	 */
	private $_objectRooms;
	
	public function __construct() {
		$this->_modelRooms = $this->_getGlobalData ( 'Model_Rooms', 'object' );
		Tools::import ( 'Object_Room' );
	}
	
	/**
	 * 获取所有房间信息
	 * @return array,array里面都是ObjectRoom对象
	 */
	public function findAllRooms() {		
//		$modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
//		$roomIdsStr = $modelGameSerList->getAllRoomIds();
//		$roomList = $this->_modelRooms->getRoomByIds($roomIdsStr);
		$roomList = $this->getMyRooms();
		if (!$roomList)return false;
		$roomClases = array ();
		foreach ( $roomList as $value ) {
			array_push ( $roomClases, $this->getRoom ( $value ['Id'] ) );
		}
		return $roomClases;
	}
	
	public function getMyRooms(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$gameTypeIds=$userClass->getUserGameTypeIds();
		$operatorIds=$userClass->getUserOperatorIds();
		$modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
//		$roomIdsStr = $modelGameSerList->getAllRoomIds($gameTypeIds,$operatorIds);
//		$roomList = $this->_modelRooms->getRoomByIds($roomIdsStr);
		$roomList = $this->_modelRooms->findAll();
		if(empty($roomList)){
			return array();
		}
		if(!in_array($userClass['_userName'],explode(',',MasterAccount) )){
			$check = array();
			foreach($userClass['_operatorIds'] as $value){
				$check[] = $value['game_type_id'].'_'.$value['operator_id'];
			}
			//$check = array_unique($check);
			foreach($roomList as $key =>$value){
				$chenkTag = $value['game_type_id'].'_'.$value['operator_id'];
				if(!in_array($chenkTag,$check)){
					unset($roomList[$key]);
				}
			}	
		}
		return $roomList;
	}
	
	/**
	 * 创建房间
	 */
	public function createRoom($roomInfo) {
		$this->_modelRooms->add ( array ('name' => $roomInfo ['name'], 'game_type_id'=>$roomInfo['game_type_id'],'operator_id'=>$roomInfo['operator_id'],'autoreply'=>$roomInfo['autoreply'], ) );
		$roomsId = $this->_modelRooms->returnLastInsertId ();
		$roomInfo ['Id'] = $roomsId;
		$roomClass = new Object_Room ();
		$roomClass->initialize ( $roomInfo );
		$roomClass->setUpdateInfo ( 1 );
	}
	
	/**
	 * 更改房间
	 * @param array $roomInfo
	 */
	public function editRoom($roomInfo){
		$this->_modelRooms->update(array ('name' => $roomInfo ['name'], 'game_type_id'=>$roomInfo['game_type_id'],'operator_id'=>$roomInfo['operator_id'] ,'autoreply'=>$roomInfo['autoreply']),"Id={$roomInfo['Id']}");
		$roomClass=$this->getRoom($roomInfo['Id']);
		$roomClass['_roomName']=$roomInfo['name'];
		$roomClass->setUpdateInfo(1);
	}
	
	/**
	 * 获取房间信息
	 * @param int $id 房间的id号
	 * @return Object_Room
	 */
	public function getRoom($id) {
		if (!$id)return false;
		return $this->_getGlobalData ( $id, 'room' );
	}
	
	/**
	 * 删除房间
	 * @param 房间Id $id
	 * @return boolean
	 */
	public function delRoom($id) {
		$delPath = ROOMS_DIR . "/{$id}.serialize.php";
		if ($this->_modelRooms->execute ( "delete from {$this->_modelRooms->tName()} where Id={$id}" )) {
			unlink ( $delPath );
			return true;
		} else {
			return false;
		}
	
	}

}