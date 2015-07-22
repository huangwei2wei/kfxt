<?php
/**
 * 游戏接口规范
 * @author php-兴源
 *
 */
interface GameInterface {
	
	/**
	 * 用于初始化参数
	 */
	function _init();
	
	/**
	 * 工单验证
	 */
	function workOrderIfChk();
	
	/**
	 * 工单回复
	 */
	function sendOrderReplay($data=NULL);
	
	/**
	 * 自动回复
	 */
	function autoReplay($data=NULL);
	
	/**
	 * 运营商额外参数配置return array('字段1'=>'描述1','字段2'=>'lang|多语言描述',)
	 * 如果没有特殊配置 return array(array('co_action','合作方标识','text',''),...);字段,描述,表单类型,默认值
	 */
	function operatorExtParam();
	
	/**
	 * 服务器额外参数配置return array('字段1'=>'描述1','字段2'=>'lang|多语言描述',)
	 * 如果没有特殊配置 return array(array('db_host','数据库服务器','text',''),...);字段,描述,表单类型,默认值
	 */
	function serverExtParam();	
	
	/**
	 * 返回用于签名的数组
	 * @param array $data
	 */
	function getSignArr($data=array());
	
	/**
	 * 获取一个游戏接口的配置
	 */
	public function getIfConf();
	
	/**
	 * 返回公告以数组形式
	 */
	function getNotice($data=array());
	
	/**
	 * 修改公告，返回true/false
	 */
	function modifyNotice($data=array());
	
	/**
	 * 删除公告，返回true/false
	 */
	function delNotice($data=array());
	
	/**
	 * 读取一条公告，返回数组
	 */
	function getOneNotice($id);
	
	/**
	 * 增加公告，返回true/false
	 */
	function addNotice($data=array());
	
	

}