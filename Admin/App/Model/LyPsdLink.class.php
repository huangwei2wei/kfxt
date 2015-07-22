<?php
/**
 * 联运PSD链接
 * @author PHP-兴源
 */
class Model_LyPsdLInk extends Model {
	protected $_tableName='ly_psdlink';
	
	private $_modelUserProiorityOperator;
	
	private $_utilRbac;
	
	private $_gameOperator = array();
	
	private $_userClass;
	
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
	
	private function getData(){
		$data['game_type_id'] = $_POST['game_type_id'];
		$data['title'] = $_POST['title'];
		$data['href'] = $_POST['href'];
		$data['edit_time'] = CURRENT_TIME;
		return $data;
	}
	
	public function linkAdd(){
		$this->add($this->getData());
	}
	
	public function linkUpdate(){
		$this->update($this->getData(),"Id={$_POST['Id']}");
	}
	
	
	
}