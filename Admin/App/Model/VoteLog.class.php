<?php
/**
 * 客服日常工具-投票日志表
 * @author php-朱磊
 *
 */
class Model_VoteLog extends Model {
	protected $_tableName='vote_log';
	
	public function add($logArr){
		if (empty($logArr['vote_id']) || $logArr['vote_option_id']=='' || empty($logArr['user_id']) || empty($logArr['source']))
			return array ('status' => - 1, 'msg' => '日志信息不完全','href'=>1);
		$addArr=array();
		$addArr['vote_id']=$logArr['vote_id'];
		$addArr['vote_option_id']=$logArr['vote_option_id'];
		$addArr['user_id']=$logArr['user_id'];
		$addArr['source']=$logArr['source'];
		$addArr['create_time']=CURRENT_TIME;
		if (parent::add($addArr)){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'写入日志失败','status'=>-2,'href'=>1);
		}
	}
	
	/**
	 * 通过voteId来查找投票的详情记录
	 * @param int $voteId
	 */
	public function findByVoteId($voteId){
		return $this->select("select * from {$this->tName()} where vote_id={$voteId}");
	}
	
}