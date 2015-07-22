<?php
class Model_Org extends Model {
	protected $_tableName='org';
	
	private $_cacheFile;
	
	public function __construct(){
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	/**
	 * 建立缓存
	 */
	public function createCache() {
		$dataList = $this->findAll ();
		$tmpArr=array();
		$num=count($dataList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$tmpArr[$dataList[$i]['Id']]=$dataList[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
	}
	
	/**
	 * 通过分组的方式返回用户
	 */
	public function findUsersToCache(){
		$orgList=$this->_getGlobalData('org');
		$users=$this->_getGlobalData('user');
		$orgList[0]=array('Id'=>0,'name'=>'未分组');
		foreach ($orgList as &$list){
			foreach ($users as $userList){
				if ($userList['org_id']==$list['Id'])$list['users'][$userList['Id']]=$userList['full_name'];
			}
		}
		return $orgList;
	}
}