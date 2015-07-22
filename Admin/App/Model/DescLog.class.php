<?php



class  Model_descLog extends Model{

	public $_tableName = "log_desc";


	public function addLog($returnData,$subData){
		$data = array();
		$this->_utilRbac=	$this->_getGlobalData('Util_Rbac','object');
		$userClass		=	$this->_utilRbac->getUserClass();
		$data["acuser"]		=	$userClass['_id']?$userClass['_id']:0;
		$data["returnData"]	=	base64_encode($returnData);
		$data["subData"]	=	base64_encode($subData);
		$data["actime"]		=	CURRENT_TIME;
		parent::add($data);
	}

	public function findByActimeAndAcuser($actime,$acuser){
		$sql = "select * from {$this->tName()} where actime='".$actime."' and acuser='".$acuser."'";
		return $this->select($sql);
	}
}


?>