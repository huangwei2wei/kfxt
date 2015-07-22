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
							    'img' => '/Public/front/default/images/game_75_75_1.jpg',
							    'name' => '商业大亨',
							  	'kind_name'=>'经营类',
							  	'UwanGameId' => 1,
							  ),
							  2 => 
							  array (
							    'Id' => 2,
							    'img' => '/Public/front/default/images/game_75_75_2.jpg',
							    'name' => '富人国',
							  	'kind_name'=>'经营类',
							 	'UwanGameId' => 2,
							  ),
								3 =>
								array (
										'Id' => 3,
										'img' => '/Public/front/default/images/game_75_75_3.jpg',
										'name' => '三分天下',
										'kind_name'=>'经营类',
										'UwanGameId' => 1001,
								),
							  5 =>
							  array (
							    'Id' => 5,
							    'img' => '/Public/front/default/images/game_75_75_5s.jpg',
							    'name' => '寻侠',
							  	'lang_id' => 10,
								'kind_name'=>'经营类',
							    'UwanGameId' => 4,
							  ),
//							  5 => 
//							  array (
//							    'Id' => 5,
//							    'img' => '/Public/front/default/images/game_75_75_5.jpg',
//							    'name' => '寻侠',
//								'kind_name'=>'经营类',
//							    'UwanGameId' => 4,
//							  ),
							  6 => 
							  array (
							    'Id' => 6,
							    'img' => '/Public/front/default/images/game_75_75_6.jpg',
							    'name' => '幻世仙征',
							  	'kind_name'=>'角色扮演',
							    'UwanGameId' => 3,
							  ),
							  7 => 
							  array (
							    'Id' => 7,
							    'img' => '/Public/front/default/images/game_75_75_7.jpg',
							    'name' => '双龙诀',
							  	'kind_name'=>'角色扮演',
							    'UwanGameId' => 5,
							  ),
							  9 => 
							  array (
							    'Id' => 9,
							    'img' => '/Public/front/default/images/game_75_75_9.jpg',
							    'name' => '商业大亨2',
							  	'kind_name'=>'经营类',
							    'UwanGameId' => 7,
							  ),
							  
							 8=> array (
							    'Id' => 8,
							    'img' => '/Public/front/default/images/game_75_75_8.jpg',
							    'name' => '海岛大亨',
							 	'kind_name'=>'经营类',
							    'UwanGameId' => 6,
							  ),
// 							  10=> array (
// 							    'Id' =>10,
// 							    'img' => '/Public/front/default/images/game_75_75_10.jpg',
// 							    'name' => '功夫',
// 							  	'kind_name'=>'角色扮演',
// 							    'UwanGameId' => 8,
// 							  ),
							 24=> array (
							    'Id' =>24,
							    'img' => '/Public/front/default/images/game_75_75_24.jpg',
							    'name' => '王者雄心',
							  	'kind_name'=>'角色扮演',
							    'UwanGameId' => 16,
							  ),
							  
							1008=> array (
							    'Id' => 8,
							    'img' => '/Public/front/default/images/game_75_75_1008.PNG',
							    'name' => '航海帝国',
								'lang_id' => 10,
							 	'kind_name'=>'战争策略',
							  ),
// 							20=> array (
// 									'Id' => 20,
// 									'img' => '/Public/front/default/images/game_75_75_20.jpg',
// 									'name' => '幻将录',
// 									'kind_name'=>'角色扮演',
// 									'UwanGameId' => 14,
// 							),
							/**21=> array (
									'Id' =>21,
									'img' => '/Public/front/default/images/game_75_75_21.jpg',
									'name' => '刀剑无双',
									'kind_name'=>'经营类',
									'UwanGameId' => 13,
							),**/
							23=> array (
									'Id' =>23,
									'img' => '/Public/front/default/images/game_75_75_23.jpg',
									'name' => '侠义江湖',
									'kind_name'=>'角色扮演',
									'UwanGameId' => 15,
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