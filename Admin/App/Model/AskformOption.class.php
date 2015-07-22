<?php
/**
 * 问卷调查子选项表
 * @author php-朱磊
 *
 */
class Model_AskformOption extends Model {
	protected $_tableName='askform_option';
	
	public function add($postArr){
		if (empty($postArr['title']))return array('status'=>-1,'msg'=>'请填写标题','href'=>1);
		if (!count($postArr['option']))return array('status'=>-1,'msg'=>'请至少添加一个选项','href'=>1);
		if (empty($postArr['askform_id']))return array('status'=>-1,'msg'=>'没有主问卷ID,无法添加子选项','href'=>1);
		$addArr=array();
		$addArr['askform_id']=$postArr['askform_id'];
		$addArr['title']=$postArr['title'];
		$addArr['types']=$postArr['types'];
		$addArr['content']=serialize($postArr['option']);
		$addArr['allow_other']=$postArr['allow_other'];
		if (parent::add($addArr)){
			return array('status'=>1,'msg'=>false,'href'=>1);
		}else {
			return array('status'=>-2,'msg'=>'增加失败','href'=>1);
		}
	}
	
	public function findByAskformId($mainId){
		return $this->select("select * from {$this->tName()} where askform_id={$mainId}");
	}
}