<?php
/**
 * 投票表
 * @author php-朱磊
 *
 */
class Model_Vote extends Model {
	protected $_tableName='vote';
	
	/**
	 * Model_VoteLog
	 * @var Model_VoteLog
	 */
	private $_modelVoteLog;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function add($postArr){
		if (empty($postArr['start_time']) || empty($postArr['end_time']))return array ('status' => - 1, 'msg' => '请设置开始时间与结速时间', 'href' => 1 );
		if (empty($postArr['title']))return array ('status' => - 1, 'msg' => '请填定投票名称', 'href' => 1 );
		if (empty($postArr['description']))return array ('status' => - 1, 'msg' => '请填定说明', 'href' => 1 );
		if (!count($postArr['option']))return array ('status' => - 1, 'msg' => '至少要有一个投票选项', 'href' => 1 );
		if (!count($postArr['user']))return array ('status' => - 1, 'msg' => '至少要有一个投票用户', 'href' => 1 );
		$startTime=strtotime($postArr['start_time']);
		$endTime=strtotime($postArr['end_time']);
		if ($startTime>=$endTime)return array ('status' => - 1, 'msg' => '开始时间不能大于或等于结束时间', 'href' => 1 );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		
		$addArr=array();
		$addArr['title']=$postArr['title'];
		$addArr['description']=$postArr['description'];
		$addArr['is_open']=$postArr['is_open'];
		$addArr['content']=serialize($postArr['option']);
		$addArr['vote_user']=serialize($postArr['user']);
		$addArr['start_time']=$startTime;
		$addArr['end_time']=$endTime;
		$addArr['user_id']=$userClass['_id'];
		if (parent::add($addArr)){
			return array ('status' => 1, 'msg' => false, 'href' => Tools::url('ServiceTools','Vote') );
		}else {
			return array ('status' => - 1, 'msg' => '新建投票失败', 'href' => 1 );
		}		
	}
	
	/**
	 * 用户投票动作
	 * @param array $postArr
	 */
	public function vote(array $postArr){
		if (empty($postArr['Id']))return array ('status' => - 1, 'msg' => '请选择要投票的项目', 'href' => 1 );
		if (!is_array($postArr['source']))return array ('status' => - 1, 'msg' => '您没有投票', 'href' => 1 );
		$data=$this->findById($postArr['Id']);
		if (!$data)return array ('status' => - 1, 'msg' => '该投票项目不存在', 'href' => 1 );
		if ($data['end_time']<CURRENT_TIME)return array ('status' => - 1, 'msg' => '投票已结束', 'href' => 1 );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$data['vote_user']=unserialize($data['vote_user']);//获取所有用户的投票
		$mySources=$data['vote_user'][$userClass['_id']];	//获取当前用户的投票数
		if ($this->getUserStatus($userClass['_id'],$data['vote_user'])!=1)return array ('status' => - 1, 'msg' => '您没有权限投票或已经投过票', 'href' => 1 );
		$voteResult=$data['result']?unserialize($data['result']):array();//获取当前投票的结果集
		$this->_modelVoteLog=$this->_getGlobalData('Model_VoteLog','object');
		if (($postArr['source'][1]==$postArr['source'][2]) || ($postArr['source'][1]==$postArr['source'][3]) || ($postArr['source'][2]==$postArr['source'][3]))
			return array ('status' => - 1, 'msg' => '投票不能投相同的人', 'href' => 1 );
		foreach ($postArr['source'] as $vote=>$option){//循环将3个投票都投出去
			$voteResult[$option]=floatval($voteResult[$option]);
			$voteResult[$option]+=floatval($mySources[$vote]);
			$voteResult[$option]=sprintf('%.2f',$voteResult[$option]);
			$voteResult[$option]=strval($voteResult[$option]);
			$logArr=array('vote_id'=>$data['Id'],'vote_option_id'=>$option,'user_id'=>$userClass['_id'],'source'=>floatval($mySources[$vote]));//写日志
			$this->_modelVoteLog->add($logArr);
		}
		$data['vote_user'][$userClass['_id']]=array_fill(1,count($data['vote_user'][$userClass['_id']]),0);
		$updateArr=array();
		$updateArr['vote_user']=serialize($data['vote_user']);
		$updateArr['result']=serialize($voteResult);
		if ($this->update($updateArr,"Id={$data['Id']}")){
			return array ('status' => 1, 'msg' => '投票成功', 'href' => 1 );
		}else{
			return array ('status' => - 1, 'msg' => '投票失败', 'href' => 1 );
		}
	}
	
	/**
	 * 获取用户相对于这个用户集的状态
	 * @param int $userId
	 * @param array $userArr
	 */
	public function getUserStatus($userId,$userArr){
		if (!is_array($userArr))return 0;
		if (!array_key_exists($userId,$userArr))return 0;//无权限 
		foreach ($userArr[$userId] as $value){
			if (floatval($value))return 1;//未投票
		}
		return 2;//已投票
	}
}