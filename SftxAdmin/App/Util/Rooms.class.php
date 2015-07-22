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
		$roomList = $this->_modelRooms->findAll ();
		$roomClases = array ();
		foreach ( $roomList as $value ) {
			array_push ( $roomClases, $this->getRoom ( $value ['Id'] ) );
		}
		return $roomClases;
	}
	
	/**
	 * 创建房间
	 */
	public function createRoom($roomInfo) {
		$this->_modelRooms->add ( array ('name' => $roomInfo ['name'] ) );
		$roomsId = $this->_modelRooms->returnLastInsertId ();
		$roomInfo ['Id'] = $roomsId;
		$roomClass = new Object_Room ();
		$roomClass->initialize ( $roomInfo );
		$roomClass->setUpdateInfo ( 1 );
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