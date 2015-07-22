<?php
/**
 * 玩家FAQ关键字管理
 * @author php-朱磊
 *
 */
class Model_PlayerFaqKeywords extends Model {
	protected $_tableName='player_faq_keywords';
	
	public function add($postArr){
		$addArr=array();
		array_push($addArr,array('Id'=>1,'keywords'=>$postArr['bto1']),array('Id'=>2,'keywords'=>$postArr['bto2']));
		if ($this->replaces($addArr)){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'提交失败','status'=>-2,'href'=>1);
		}
	}
}