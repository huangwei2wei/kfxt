<?php
/**
 * 富人国GM操作日志
 * @author php-朱磊
 *
 */
class Model_FrgLog extends Model {
	protected $_tableName = 'frg_log';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	private $_type;
	
	/**
	 * 增加操作日志类型
	 * @param array $postArr
	 * @param int $type
	 * @see Model::add()
	 */
	public function add($postArr,$type) {
		if (!$type)
			return false; //如果没有日志类型将退出
		$this->_type=$type;
		$addArr = array ();
		$addArr ['type'] = $type;
		$addArr ['cause'] = $postArr ['cause'];
		$addArr ['create_time'] = CURRENT_TIME;
		$addArr ['ip']=ip2long(Tools::getClientIP());
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$addArr ['user_id'] = $userClass ['_id'];
		$addArr ['description'] = $this->_getDes ( $postArr );
		parent::add ( $addArr );
	}
	
	/**
	 * 生成详细
	 * @param array $postArr
	 */
	private function _getDes($postArr) {
		switch ($this->_type) {
			case '1' : //禁言
				{
					$des = "<ul><li>禁言开始时间：{$postArr['Data']['StartTime']}</li><li>禁言结束时间：{$postArr['Data']['EndTime']}</li><li>禁言的用户ID：{$postArr['Data']['Value']}</li></ul>";
					return $des;
				}
			case '2' : //IP锁定
				{
					$des = "<ul><li>锁定的IP：{$postArr['Setting']['ForbiddenIPList']}</li>	</ul>";
					return $des;
				}
			case '3' : //封号
				{
					$des = "<ul><li>封号结束时间：{$postArr['Data']['EndTime']}</li><li>封号的用户ID：{$postArr['Data']['Value']}</li></ul>";
					return $des;
				}
			case '4' : //短信发送
				{
					$des = "<ul><li>消息标题：{$postArr['MsgTitle']}</li><li>消息内容：{$postArr['MsgContent']}</li><li>发送的用户ID：{$postArr['UserIds']}</li></ul>";
					return $des;
				}
			case '5' :	//教官
				{
					$des = "<ul><li>禁言的公司级别：{$postArr['Data']['CompanyLevel']}</li><li>教官生效时间：{$postArr['Data']['StartTime']}</li><li>发送的用户ID：{$postArr['Data']['Value']}</li></ul>";
					return $des;
				}
			case '6' : //登录
				{
					$des = "<ul><li>登录服务器：{$postArr['server_name']}</li><li>用户名：{$postArr['user_name']}</li></ul>";
					return $des;
				}
			case '7'://全服发送短信
				{
					$des="<ul><li>消息标题：{$postArr['MsgTitle']}</li><li>消息内容：{$postArr['MsgContent']}</li><li><b>全服发送</b></li></ul>";
					return $des;
				}
		}
	}

}