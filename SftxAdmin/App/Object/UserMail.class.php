<?php
/**
 * 用户emil对象,当前为mysql模式
 * @author php-朱磊
 *
 */
class Object_UserMail extends Object implements Serializable {

	protected $_id; //用户id
	protected $_userName; //用户名
	protected $_nickName; //呢称
	private $_isUpdate = 0; //是否更新 0:更新 1:不更新
	protected $_notReadCount=0;	//未读邮件的数量
	protected $_total=0;		//总邮件数量

	/**
	 * Model_UserMail
	 * @var Model_UserMail
	 */
	private $_modelUserMail;

	/**
	 * 创建对象
	 */
	public function createMail($userInfo) {
		$this->_id = $userInfo ['Id'];
		$this->_userName = $userInfo ['user_name'];
		$this->_nickName = $userInfo ['nick_name'];
	}

	/**
	 * 设置邮件数量
	 * @param int $num
	 */
	public function setMailCount($num){
		$this->_total+=$num;
		if ($this->_total<0)$this->_total=0;//防止溢出
	}

	/**
	 * @return the $_notReadCount
	 */
	public function get_notReadCount() {
		return $this->_notReadCount;
	}

	/**
	 * @return the $_total
	 */
	public function get_total() {
		return $this->_total;
	}

	/**
	 * 返回邮件列表
	 * @param array $limit
	 * @param int $type
	 */
	public function getMail($limit,$where){
		$this->_loadCore('Help_Page');
		$this->_loadCore('Help_SqlSearch');
		$this->_modelUserMail=$this->_getGlobalData('Model_UserMail','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelUserMail->tName());
		$helpSqlSearch->set_conditions("user_id={$this->_id}");
		if ($where!==null){
			foreach ($where  as $key=>$value){
				$helpSqlSearch->set_conditions("{$key}='{$value}'");
			}
		}
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($limit[0],$limit[1]);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelUserMail->select($sql);

		if (count($dataList)){
			Tools::import('Util_FontColor');
			foreach ($dataList as &$list){
				$list['word_is_read']=Util_FontColor::getMailRead($list['is_read']);
				if ($list['href']){
					$list['title']="<a href='javascript:void(0)' is_read='{$list['is_read']}' url='{$list['href']}' cur_id='{$list['Id']}' onclick='readMail($(this))'>{$list['title']}</a>";
				}else {
					$list['title']="<a href='javascript:void(0)' is_read='{$list['is_read']}' cur_id='{$list['Id']}' onclick='readMail($(this))'>{$list['title']}</a>";
				}

			}
			$helpPage=new Help_Page(array('total'=>$this->_modelUserMail->findCount($conditions),'perpage'=>$limit[1]));
			$pageBox=$helpPage->show();
			return array('data'=>$dataList,'pageBox'=>$pageBox);
		}else {
			return false;
		}

	}

	/**
	 * 读取邮件
	 * @param int $id
	 */
	public function readMail($id){
		$this->_modelUserMail=$this->_getGlobalData('Model_UserMail','object');
		$this->_modelUserMail->update(array('is_read'=>1),"Id={$id}");
		if ($this->_notReadCount>0)
			$this->_notReadCount--;
	}

	/**
	 * 增加邮件
	 * @param array $mail
	 */
	public function addMail($mail) {
		$this->_modelUserMail=$this->_getGlobalData('Model_UserMail','object');
		$mail['create_time']=CURRENT_TIME;
		$mail['user_id']=$this->_id;
		if ($this->_modelUserMail->add($mail)){
			$this->_notReadCount++;	//未读邮件+1
			$this->_total++;
			return true;
		}else {
			return false;
		}

	}

	public function serialize() {
		$data = array ();
		$data ['Id'] = $this->_id;
		$data ['user_name'] = $this->_userName;
		$data ['nick_name'] = $this->_nickName;
		$data ['not_read_count']=$this->_notReadCount;
		$data ['total']=$this->_total;
		return serialize ( $data );
	}

	public function unserialize($data) {
		$data = unserialize ( $data );
		$this->_id = $data ['Id'];
		$this->_userName = $data ['user_name'];
		$this->_mailList = $data ['mail_list'];
		$this->_notReadCount=$data ['not_read_count'];
		$this->_total=$data['total'];
	}

	/**
	 * 是否更新 0:不更新 1:更新
	 * @param int $value
	 */
	public function setUpdateInfo($value = 0) {
		$value = abs ( intval ( $value ) );
		$this->_isUpdate = $value;
	}

	public function __destruct() {
		if ($this->_isUpdate == 1) {
			$this->_saveInfo ();
		}
	}

	private function _saveInfo() {
		$filePath = USERS_DIR . "/{$this->_userName}";
		if (! file_exists ( $filePath ))
			mkdir ( $filePath, 0777 );
		$savePath = "{$filePath}/Mail.serialize.php";
		$userMail = serialize ( $this );
		$userMail=str_replace('\'','\\\'',$userMail);
		$string = "<?php \r\n";
		$string .= "return '{$userMail}'; ";
		$this->_writeData ( $savePath, $string );
	}

}