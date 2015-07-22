<?php
/**
 * FAQ分类DAO
 * @author 陈成禧
 *
 */
class PlayerKindFaqModel extends Model{
	private $timeOut;
	protected function _initialize(){
		parent::_initialize();
		$this->timeOut=3600*12;
	}
	
	/**
	 * 取FAQ分类
	 * @param int $game_id 游戏ID
	 * @return array|false
	 */
	public function getKinds($game_id){
		$key="faq_kind_".$game_id;
		$list=S($key);
		if($list==false){
			$list=$this->where("game_type_id={$game_id}")->select();
			echo $this->getLastSql();
			if($list){
				S($key,$list,$this->timeOut);
			}
		}
		return $list;
	}
	

}