<?php
/**
 * 活动投票配置表
 * @author php-朱磊
 *
 */
class Model_VoteConf extends Model {
	protected $_tableName='vote_conf';
	
	public function add($postArr){
		if (empty($postArr['conf_name']))return array ('status' => - 1, 'msg' => '请填定配置名称', 'href' => 1 );
		if (empty($postArr['description']))return array ('status' => - 1, 'msg' => '请填定说明', 'href' => 1 );
		if (!count($postArr['option']))return array ('status' => - 1, 'msg' => '至少要有一个投票选项', 'href' => 1 );
		if (!count($postArr['user']))return array ('status' => - 1, 'msg' => '至少要有一个投票用户', 'href' => 1 );
		$addArr=array();
		$addArr['conf_name']=$postArr['conf_name'];
		$addArr['description']=$postArr['description'];
		$addArr['is_open']=$postArr['is_open'];
		$addArr['content']=serialize($postArr['option']);
		$addArr['vote_user']=serialize($postArr['user']);
		if (parent::add($addArr)){
			return array ('status' => 1, 'msg' => "增加 [{$postArr['conf_name']}] 配置成功", 'href' => Tools::url(CONTROL,ACTION,array('doaction'=>'conf')) );
		}else {
			return array ('status' => - 1, 'msg' => '添加配置失败', 'href' => 1 );
		}
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array ('status' => - 1, 'msg' => '请选择要编辑的投票配置', 'href' => 1 );
		if (empty($postArr['conf_name']))return array ('status' => - 1, 'msg' => '请填定配置名称', 'href' => 1 );
		if (empty($postArr['description']))return array ('status' => - 1, 'msg' => '请填定说明', 'href' => 1 );
		if (!count($postArr['option']))return array ('status' => - 1, 'msg' => '至少要有一个投票选项', 'href' => 1 );
		if (!count($postArr['user']))return array ('status' => - 1, 'msg' => '至少要有一个投票用户', 'href' => 1 );
		$updateArr=array();
		$updateArr['conf_name']=$postArr['conf_name'];
		$updateArr['description']=$postArr['description'];
		$updateArr['is_open']=$postArr['is_open'];
		$updateArr['content']=serialize($postArr['option']);
		$updateArr['vote_user']=serialize($postArr['user']);
		if ($this->update($updateArr,"Id={$postArr['Id']}")){
			return array ('status' => 1, 'msg' => "编辑 [{$postArr['conf_name']}] 配置成功", 'href' => Tools::url(CONTROL,ACTION,array('doaction'=>'conf')) );
		}else {
			return array ('status' => - 1, 'msg' => '编辑配置失败', 'href' => 1 );
		}
	}
	
	
}