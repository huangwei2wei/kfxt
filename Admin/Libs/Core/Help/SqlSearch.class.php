<?php
class Help_SqlSearch extends Base {
	
	//搜索条件数组
	private $_conditions=array(' 1');
	//数据库limit
	private $_limit;
	//表名
	private $_tableName;	
	//sql order by
	private $_orderBy;
	//sql group by
	private $_groupBy;
	
	//字段
	private $_field='*';
	
	public function __construct(){
		$this->_conditions=array(' 1');
	}
	
	/**
	 * 返回sql语句
	 */
	public function createSql(){
		$sql="SELECT {$this->_field} FROM {$this->_tableName} ";
		if ($this->_conditions){
			$conditions=' WHERE ';
			foreach($this->_conditions as $value){
				$conditions.=$value;
			}
			$sql.=" {$conditions} ";
		}
		if ($this->_groupBy){
			$sql.=" {$this->_groupBy} ";
		}
		if ($this->_orderBy){
			$sql.=" ORDER BY {$this->_orderBy} ";
		}
		if (is_array($this->_limit)){
			$sql.=" LIMIT {$this->_limit['begin_num']},{$this->_limit['end_num']}";
		}
		return $sql;
	}

	/**
	 * @return the $_conditions
	 */
	public function get_conditions() {
		$conditions='';
		foreach($this->_conditions as $value){
			$conditions.=$value;
		}
//		if ($this->_groupBy)$conditions.=$this->_groupBy;
		return $conditions;
	}

	/**
	 * @return the $_limit
	 */
	public function get_limit() {
		return $this->_limit;
	}
	
	public function set_groupBy($string){
		$this->_groupBy=" group by {$string} ";
	}
	
	public function getFiledGroupBy(){
		$fields = '';
		if($this->_groupBy){
			$fields = trim(str_ireplace(array('group ','by ',' '),'',$this->_groupBy));
		}
		return $fields;
	}

	/**
	 * @param $_conditions the $_conditions to set
	 */
	public function set_conditions($_conditions,$andOr='and') {
		array_push($this->_conditions," {$andOr} {$_conditions} ");
	}

	/**
	 * 设置sql语句的limit
	 * @param int $page 第几页
	 * @param int $pageSize 每页显示多少个.
	 */
	public function setPageLimit($page=1,$pageSize=PAGE_SIZE) {
		$page=abs(intval($page));
		$pageSize=abs(intval($pageSize));
		if ($page!=0)$page--;
		$begin=$page*$pageSize;
		if ($begin!==null && $pageSize!==null){
			$this->_limit=array();
			$this->_limit['begin_num']=$begin;
			$this->_limit['end_num']=$pageSize;
		}
	}
	
	/**
	 * 设置sql语句的limit,此方法是用>方式
	 * @param unknown_type $page
	 * @param unknown_type $pageSize
	 */
	public function setWherePageLimit($page,$pageSize=PAGE_SIZE){
		$page=abs(intval($page));
		$pageSize=abs(intval($pageSize));
		if ($page!=0)$page--;
		
	}
	
	/**
	 * @return the $_tableName
	 */
	public function get_tableName() {
		return $this->_tableName;
	}

	/**
	 * 设置表名
	 * @param $_tableName the $_tableName to set
	 */
	public function set_tableName($_tableName) {
		$this->_tableName = $_tableName;
	}
	
	/**
	 * @return the $_orderBy
	 */
	public function get_orderBy() {
		return $this->_orderBy;
	}

	/**
	 * @param $_orderBy the $_orderBy to set
	 */
	public function set_orderBy($_orderBy) {
		$this->_orderBy = $_orderBy;
	}
	
	/**
	 * 切换排序
	 * @param string $field
	 * @param enum $order (asc,desc)
	 */
	public function toggleOrderBy($field,$order){
		$order=($order=='asc')?'desc':'asc';
		$this->_orderBy="{$field} {$order}";
	}

	public function clearConditions(){
		$this->_conditions=array('1');
	}
	
	/**
	 * 清除所有条件
	 */
	public function clearAll(){
		$this->_conditions=array('1');
		$this->_field='*';
		$this->_limit=null;
		$this->_orderBy=null;
		$this->_tableName=null;
		$this->_groupBy=null;
	}

	/**
	 * @param $_field the $_field to set
	 */
	public function set_field($_field) {
		$this->_field = $_field;
	}
	
	/**
	 * 清除字段
	 */
	public function clearField(){
		$this->_field='*';
	}
	
	/**
	 * 批量增加条件SQL
	 * @param array $wheres
	 */
	public function addConditions(array $wheres){
		foreach ($wheres as $key=>$where){
			$this->set_conditions("{$key}={$where}");
		}
	}
	
	/**
	 * 清除最后一个条件
	 */
	public function delLastCondition(){
		array_pop($this->_conditions);
	}

}