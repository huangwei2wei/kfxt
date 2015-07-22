<?php
/**
 * 系统配置信息
 * @author 陈成禧
 *
 */
class SysconfigModel extends Model{
	
	private $_gameType=array (
							  1 => 
							  array (
							    'Id' => 1,
							    'img' => '/Public/front/default/images/gamelogo_01.gif',
							    'name' => '商业大亨',
							  	'UwanGameId' => 1,
							  ),
							  2 => 
							  array (
							    'Id' => 2,
							    'img' => '/Public/front/default/images/gamelogo_03.gif',
							    'name' => '富人国',
							 	'UwanGameId' => 2,
							  ),
							  5 => 
							  array (
							    'Id' => 5,
							    'img' => '/Public/front/default/images/button_81.gif',
							    'name' => '寻侠',
							    'UwanGameId' => 4,
							  ),
							  
							  
							  
							);
	
	/**
	 * 取得配置信息
	 */
	public function getSysConfig($config_name){
		if ($config_name=='game_type')return $this->_gameType;
		$key="sysconfig_".$config_name;
		$value = S($key);
		if(! $value){
			$filter['config_name']=$config_name;
			$vo=$this->where($filter)->find();
			if($vo){
				$value=unserialize($vo['config_value']);
				S($key,$value,60*10);
			}
		}
		return $value;
	}
}