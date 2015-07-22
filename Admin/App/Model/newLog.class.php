<?php
class Model_newLog extends Model {



	public function __construct(){
		$this->_tableName = "log";
		$this->_tableName.='_'.date('Y',CURRENT_TIME);	//当前表名
	}

	public function _createTable(){
		$sql="CREATE TABLE `cndw_".$this->_tableName."` (
			  `Id` int(11) NOT NULL auto_increment,
			  `game_id` int(5) default NULL,
			  `control` varchar(50) default NULL,
			  `action` varchar(50) default NULL,
			  `actime` int(11) default NULL,
			  `acuser` int(11) default NULL,
			  `acIP` varchar(100) default NULL,
			  PRIMARY KEY  (`Id`),
			  KEY `game_id` (`game_id`,`control`,`action`,`actime`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		if (!$this->isTableExists($this->_tableName)){
			$this->execute($sql);
		}
	}

	public function addLog(){
		//		$this->_createTable();
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$insertArr=array();
		$insertArr['acuser']=$userClass['_id']?$userClass['_id']:0;
		$insertArr['actime']=CURRENT_TIME;
		$insertArr['acIP']=Tools::getClientIP();
		$insertArr['control']=CONTROL;
		$insertArr['action']=ACTION;
		$insertArr['game_id']=$_REQUEST["__game_id"];
		parent::add($insertArr);
	}

	public function getLogData($game_id,$page,$actype,$acuser,$begin,$end){
		$min = max(0,intval($page)-1)*30;
		$table = $this->tName();
		if($begin!=""){
			$this->_tableName = "log";
			$this->_tableName.='_'.date('Y',strtotime($begin));
			$where .= " and actime>'".strtotime($begin)."'";
		}
		if($end!=""){
			$where .= " and actime<'".strtotime($end)."'";
		}
		if($actype!=""){
			$where .= " and action='".$actype."'";
		}
		if($acuser!=""){
			$where .= " and acuser='".$acuser."'";
		}
		$sql	=	"select * from {$this->tName()} where game_id='".$game_id."' ";
		$count	=	"select count(Id) from {$this->tName()} where game_id='".$game_id."' ";
		$sql .= $where;
		$count.=$where." limit 1000";
		$sql 	.= " order by actime desc limit ".$min.",30";
		//		echo $sql;
		$data["data"] = $this->select($sql);
		$data["count"] = $this->select($count);
		return $data;
	}






}