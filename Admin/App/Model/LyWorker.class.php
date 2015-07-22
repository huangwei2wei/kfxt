<?php
class Model_LyWorker extends Model {
	protected $_tableName='ly_worker';
	
	private $_status;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function getGameType(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		return $gameTypes;
	}
	
	public function getType(){
		return array(1=>'游戏建议',2=>'后台优化',3=>'工作备注');	
	}
	
	public function getProgress(){
		return array(1=>'进行中',2=>'已完成');
	}

	public function getStatus(){
		
	}
	
	public function insert($postArr){
		if (!$postArr['game_type_id'])return array('msg'=>'请选择游戏','status'=>'-1','href'=>2);
		if (!$postArr['type'])return array('msg'=>'请选类型','status'=>'-1','href'=>2);
		if (!$postArr['progress'])return array('msg'=>'请选择进展','status'=>'-1','href'=>2);
		if (!$postArr['title'])return array('msg'=>'请输入标题','status'=>'-1','href'=>2);
		if (!$postArr['content'])return array('msg'=>'请输入内容','status'=>'-1','href'=>2);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array(
			'game_type_id'=>intval($postArr['game_type_id']),
			'type'=>intval($postArr['type']),
			'progress'=>intval($postArr['progress']),
			'title'=>$postArr['title'],
			'content'=>$postArr['content'],
			'edit_time'=>CURRENT_TIME,
			'user_id'=>$userClass['_id'],
		);
		$this->add($addArr);
		return array('msg'=>'增加成功','status'=>'1','href'=>Tools::url('Worker','Index',array('zp'=>'LianYun')));
	}
	
	public function edit($postArr){
		$postArr['Id']=intval($postArr['Id']);
		if (!$postArr['Id'])return array('msg'=>'参数错误','status'=>'-1','href'=>2);
		if (!$postArr['game_type_id'])return array('msg'=>'请选择游戏','status'=>'-1','href'=>2);
		if (!$postArr['type'])return array('msg'=>'请选类型','status'=>'-1','href'=>2);
		if (!$postArr['progress'])return array('msg'=>'请选择进展','status'=>'-1','href'=>2);
		if (!$postArr['title'])return array('msg'=>'请输入标题','status'=>'-1','href'=>2);
		if (!$postArr['content'])return array('msg'=>'请输入内容','status'=>'-1','href'=>2);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$updateArr=array(
			'game_type_id'=>intval($postArr['game_type_id']),
			'type'=>intval($postArr['type']),
			'progress'=>intval($postArr['progress']),
			'title'=>$postArr['title'],
			'content'=>$postArr['content'],
			'edit_time'=>CURRENT_TIME,
			'user_id'=>$userClass['_id'],
		);
		$this->update($updateArr,"Id={$postArr['Id']}");
		return array('msg'=>'编辑成功','status'=>'1','href'=>Tools::url('Worker','Index',array('zp'=>'LianYun')));
	}
	

}