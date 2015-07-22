<?php
/**
 * 用户遗失账号
 * @author php-兴源
 *
 */
class Model_LostPassword extends Model {
	protected $_tableName = 'lost_password';
	
	public function getStatus(){
		return array('1'=>'待处理','2'=>'处理中','3'=>'已处理'); 
	}
	
	public function getChargeType(){
		return array('0'=>'无充值','1'=>'充值卡','2'=>'网上银行','3'=>'其他'); 
	}
	
	public function getOrder($conditions=array(),$page=1,$order=""){
		$sql = "select * from {$this->tName()} where 1";
		$sql .=$this->where($conditions);
		$sql .=$this->order($order);
		$sql .=$this->page($page);
		return $this->select($sql);
	}
	
	public function getCount($conditions=array()){
		$sql = '1 '.$this->where($conditions);
		return $this->findCount($sql);
	}
	
	protected function where($conditions=array()){
		$sql = " ";
		foreach($conditions as $key => $val){
			if(strpos($val,"=") || strpos($val,"<") || strpos($val,">")){
				$sql.= " and {$val}";
			}
			else{
				$sql.=" and {$key} = {$val}";
			}
		}
		return $sql;
	}
	
	protected function order($order=""){
		$sql = " ";
		$orderby = "";
		if(is_string($order) && strlen(trim($order))>0){
			if(strlen(trim($orderby))==0)$orderby.= " order by ";
			$sql .=  $order;
		}
		elseif(is_array($order) && count($order)>0){
			if(strlen(trim($orderby))==0)$orderby.= " order by ";
			foreach($order as $val){
				$sql.=implode(',',$order);
			}
		}
		$sql = $orderby.' '.$sql;
		return $sql;
	}
	
	protected function page($page=1,$pagesize = PAGE_SIZE){
		$sql = " ";
		if($page<=0)$page=1;
		$begin = ($page-1)*$pagesize;
		$sql .= " limit {$begin},{$pagesize}" ;
		return $sql;
	}
	
	
	
	

}