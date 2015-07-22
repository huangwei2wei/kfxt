<?php
/**
 * 联运链接
 * @author PHP-兴源
 */
class Model_LyLInk extends Model {
	protected $_tableName='ly_link';
	
	private $_modelUserProiorityOperator;
	
	private $_utilRbac;
	
	private $_gameOperator = array();
	
	private $_userClass;
	
	private $_type = array(
		'1'=>'公告',
		'2'=>'专题',
	);
	
	public function getType(){
		return $this->_type;
	}
	
	public function __construct(){
		$this->_modelUserProiorityOperator = $this->_getGlobalData ( 'Model_UserProiorityOperator', 'object' );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_userClass=$this->_utilRbac->getUserClass();
		if($this->_userClass['_id']){
			$this->_gameOperator = $this->_modelUserProiorityOperator->findByUserId($this->_userClass['_id']);
		}
	}
	
	public function getMyGame(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		if(!in_array($this->_userClass['_userName'],explode(',',MasterAccount) )){
			$returnDate = array();
			foreach($this->_gameOperator as $sub){
				$gameId = $sub['game_type_id'];
				if(array_key_exists($gameId,$gameTypes)){
					$returnDate[$gameId] = $gameTypes[$gameId];
				}
			}
			return $returnDate;
		}
		return $gameTypes;
	}
	
	public function getMyOperator(){
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		if(!in_array($this->_userClass['_userName'],explode(',',MasterAccount) )){
			$returnDate = array();
			foreach($this->_gameOperator as $sub){
				$operator_id = $sub['operator_id'];
				if(array_key_exists($operator_id,$operators)){
					$returnDate[$operator_id] = $operators[$operator_id];
				}
			}
			return $returnDate;		
		}
		return $operators;
	}
	
	private function getData(){
		$data['game_type_id'] = $_POST['game_type_id'];
		$data['operator_id'] = $_POST['operator_id'];
		$data['link_type'] = $_POST['link_type'];
		$data['title'] = $_POST['title'];
		$data['href'] = $_POST['href'];
		$data['edit_time'] = CURRENT_TIME;
		$data['user_id'] = $this->_userClass['_id'];
		$data['ip'] = $this->getIp();
		return $data;
	}
	
	public function linkAdd(){
		$this->add($this->getData());
	}
	
	public function linkUpdate(){
		$this->update($this->getData(),"Id={$_POST['Id']}");
	}
	
	public function getIp() {
		if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
			$ip = $_SERVER ['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return ($ip);
	}
	
	
	
}