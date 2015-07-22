<?php

/**
 * 问题分类
 * @author 陈成禧 
 *
 */
class QuestionTypesModel extends Model{
	private $timeOut;
	protected function _initialize(){
		parent::_initialize();
		$this->timeOut=3600*12;
	}
	
/**
	 * 问题类型
	 * @param $gameId 游戏ID 
	 * $gameId=0即查询所有问题分类
	 */
	public function getQuestionTypes($gameId){
		
		$key="question_types_".$gameId;
		$types=S($key);
		if(! $types){
			$filter=array();
			if($gameId!=0){
				$filter['game_type_id']=$gameId;
			}
			$types = $this->where($filter)->select();
			if($types && is_array($types)){
				$len = count($types);
				for($i=0;$i<$len;$i++){
					$types[$i]['form_table']=unserialize($types[$i]['form_table']);
				}
				S($key,$types,$this->timeOut);
			}
		}
		return $types;		
	}
	
	public function getGameList(){
		$gameTypes=D('Sysconfig')->getSysConfig('game_type');
		$returnData = array();
		if(is_array($gameTypes)){
			foreach($gameTypes as $k => &$value){
				if(isset($value['lang_id'])){
					$value['ask_url'] = U('Faq/index',array('game_type_id'=>$k,'lang_id'=>$value['lang_id']));
				}else{
					$value['ask_url'] = U('Faq/index',array('game_type_id'=>$k));
				}
			}
		}
		return $gameTypes;
	}
	
}