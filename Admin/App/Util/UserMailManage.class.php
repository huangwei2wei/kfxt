<?php
/**
 * 邮件发送类,一次只能发送一封邮件
 * @author php-朱磊
 *
 */
class Util_UserMailManage extends Control {
	/**
	 * 发送用户user_name
	 * @var array
	 */
	private $_sendUsers = array ();
	
	/**
	 * 发送的消息及标题,相关内容
	 * @var array
	 */
	private $_sendMail = null;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * 邮件类型
	 * @var array
	 */
	private $_mailType;
	
	/**
	 * 发送时失败的用户记录
	 * @var array
	 */
	private $_failureUsers = array ();
	
	public function __construct() {
		$this->_mailType=$this->_getGlobalData('mail_type');
		Tools::import ( 'Object_UserMail' );
		
	}
	
	/**
	 * 增加发送用户
	 * @param var $userNames 用户名或是用户id
	 */
	public function addUser($userNames) {
		if (is_array ( $userNames )) {
			$this->_sendUsers = array_merge ( $this->_sendUsers, $userNames );
		} else {
			array_push ( $this->_sendUsers, $userNames );
		}
	}
	
	/**
	 * 清除发送邮件的用户
	 */
	public function clearUsers() {
		$this->_sendUsers = array ();
	}
	
	/**
	 * 清除发送的邮件
	 */
	public function clearMail() {
		$this->_sendMail = null;
	}
	
	/**
	 * 工作交接/公告
	 */
	private function _getMail2($data) {
		$data ['title'] = "[{$this->_mailType[$data['type']]}] {$data['title']}";
		$arr = array ('type' => $data ['type'], 'title' => $data ['title'],'href'=>$data['href'] );
		$this->_sendMail = $arr;
	}
	
	/**
	 * 短消息 
	 */
	private function _getMail3($data) {
		$arr=array();
		$arr['title']=$data['title'];
		$arr['type']=$data['type'];
		$arr['href']=$data['href'];
		$this->_sendMail=$arr;
	}
	
	/**
	 * 其它
	 */
	private function _getMailOther($data) {
	
	}
	
	/**
	 * 发送邮件array('title'=>'title','content'=>'content','type'=>'type','href'=>'http://www.sina.com.cn')
	 * @param array $data
	 */
	public function addMail($data) {
		if (empty ( $data ['title'] ))
			return false;
		if (empty ( $data ['type'] ))
			return false;
		if (is_array ( $this->_sendMail ))
			return false;
		$data ['is_read'] = false; //是否读取
		switch ($data ['type']) {
			case '1' : //公告
			case '2' :
				{ //工作交接 
					if (empty ( $data ['href'] ))
						return false;
					$this->_getMail2 ( $data );
					break;
				}
			case '3' :
				{ //短消息 
					$this->_getMail3 ( $data );
					break;
				}
			default :
				{ //其它
					$this->_getMailOther ( $data );
					break;
				}
		}
	}
	
	/**
	 * 发送邮件
	 */
	public function send() {
		if (!$this->_sendMail)return false;
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		foreach ( $this->_sendUsers as $user ) {
			if (is_numeric ( $user )) { //判断是否为userid还是username
				$userClass = $this->_utilRbac->getUserClassById ( $user );
			} else {
				$userClass = $this->_utilRbac->getUserClass ( $user );
			}
			if (! is_object ( $userClass )) //如果发送失败,将记录这个用户的用户名或者是id
				array_push ( $this->_failureUsers, $user );
			$userClass->addMail ( $this->_sendMail );
			$userClass->setUpdateInfo ( 2 );
			$userClass = null;
			unset ( $userClass );
		}
	}
	
	/**
	 * 返回发送错误的用户
	 */
	public function getFailureUser() {
		if (count ( $this->_failureUsers ) == 0) { //返回false,表示没有错误
			return false;
		} else { //表示有错误的用户
			return implode ( ',', $this->_failureUsers );
		}
	}

}