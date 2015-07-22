<?php
/**
 * 公告
 * @author PHP-朱磊
 *
 */
class Model_Bulletin extends Model {
	
	protected $_tableName='bulletin';
	
	private $_bulletinKind;	//公告分类
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * Model_BulletinDate
	 * @var Model_BulletinDate
	 */
	private $_modelBulletinDate;
		
	/**
	 * Util_UserMailManage
	 * @var Util_UserMailManage
	 */
	private $_utilUserMailManage;
	
	/**
	 * 建立公告类列表
	 */
	public function createBulletinKind(){
		if (!$this->_bulletinKind){
			$gameTypeList=$this->_getGlobalData('game_type');
			$this->_bulletinKind['0']='部门通知';
			if($gameTypeList && is_array($gameTypeList)){
				foreach($gameTypeList as $key => $val){
					$this->_bulletinKind[$key]=$val['name'];
				}
			}
			$this->_bulletinKind['-10']='海外富人国';
			$this->_bulletinKind['-11']='海外大亨';
			$this->_bulletinKind['-13']='英文双龙诀';
			$this->_bulletinKind['-5']='手游寻侠';
		}
		return $this->_bulletinKind;
	}
	
	/**
	 * 通过ids查找记录
	 * @param array $ids
	 */
	public function findByIds($ids){
		$ids=implode(',',$ids);
		return $this->select("select * from {$this->tName()} where Id in ({$ids}) order by create_time desc");
	}
	
	/**
	 * 用户读取这篇文章
	 * @param int $id 公告,文章id
	 */
	public function userRead($id){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$userId=$userClass['_id'];//获取用户的id
		$data=$this->findById($id);	//找到这个文章的记录
		$users=unserialize($data['not_read']);	//获取未读取这篇文章的用户集
		$userIdKey=array_search($userId,$users);	//查看人物ID是否在这个记录上
		if ($userIdKey!==false){//如果存在就删除掉这个未读记录,并且更新数据库
			unset($users[$userIdKey]);
			$this->update(array('not_read'=>serialize($users)),"Id={$id}");
		}
	}
	
	/**
	 * 增加公告
	 * @param array $postArr
	 */
	public function add($postArr){
		if (!$postArr)return array('status'=>0,'msg'=>'请填定完成的资料');
		if (!$postArr['title'])return array('status'=>0,'msg'=>'请填定完成的资料');
		if (!$postArr['content'])return array('status'=>0,'msg'=>'请填定完成的资料');
		if ($postArr['kind']=='')return array('status'=>0,'msg'=>'请选择分类');
		if (!count($postArr['users']))return array('status'=>0,'msg'=>'请选择用户');
		$users=serialize($postArr['users']);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array();
		$addArr['user_id']=$userClass['_id'];
		$addArr['title']=$postArr['title'];
		$addArr['content']=$postArr['content'];
		$addArr['create_time']=CURRENT_TIME;
		$addArr['kind']=$postArr['kind'];
		$addArr['not_read']=$users;
		if (parent::add($addArr)){
			$id=$this->returnLastInsertId();
			
			#------向用户发送消息------#
			$sendMsg=array();
			$sendMsg['type']=($addArr['kind'])?'2':'1';
			$sendMsg['title']=$postArr['title'];
			$sendMsg['href']=Tools::url('ServiceTools','NoticeShow',array('Id'=>$id,'doaction'=>'show'));
			$this->_utilUserMailManage=$this->_getGlobalData('Util_UserMailManage','object');
			$this->_utilUserMailManage->addUser($postArr['users']);
			$this->_utilUserMailManage->addMail($sendMsg);
			$this->_utilUserMailManage->send();
			$error=$this->_utilUserMailManage->getFailureUser();
			#------向用户发送消息------#
			
			$this->_modelBulletinDate=$this->_getGlobalData('Model_BulletinDate','object');
			$this->_modelBulletinDate->addBulletin($id);
			return array('status'=>1,'msg'=>$error);
		}else {
			return array('status'=>0,'msg'=>'添加失败');
		}
	}
	
	public function edit($postArr){
		if (!$postArr)return array('status'=>0,'msg'=>'请填定完成的资料');
		if (!$postArr['Id'])return array('status'=>0,'msg'=>'您需要编辑的公告不存在');
		if (!$postArr['title'])return array('status'=>0,'msg'=>'请填定完成的资料');
		if (!$postArr['content'])return array('status'=>0,'msg'=>'请填定完成的资料');
		if ($postArr['kind']=='')return array('status'=>0,'msg'=>'请选择分类');
		if (!count($postArr['users']))return array('status'=>0,'msg'=>'请选择用户');
		$users=serialize($postArr['users']);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$updateArr=array();
		$updateArr['user_id']=$userClass['_id'];
		$updateArr['title']=$postArr['title'];
		$updateArr['content']=$postArr['content'];
		$updateArr['create_time']=CURRENT_TIME;
		$updateArr['kind']=$postArr['kind'];
		$updateArr['not_read']=$users;
		if (parent::add($updateArr)){
			$id=$this->returnLastInsertId();
			
			#------向用户发送消息------#
			$sendMsg=array();
			$sendMsg['type']=($updateArr['kind'])?'2':'1';
			$sendMsg['title']=$postArr['title'];
			$sendMsg['href']=Tools::url('ServiceTools','NoticeShow',array('Id'=>$id,'doaction'=>'show'));
			$this->_utilUserMailManage=$this->_getGlobalData('Util_UserMailManage','object');
			$this->_utilUserMailManage->addUser($postArr['users']);
			$this->_utilUserMailManage->addMail($sendMsg);
			$this->_utilUserMailManage->send();
			$error=$this->_utilUserMailManage->getFailureUser();
			#------向用户发送消息------#
			
			$this->delById($postArr['Id']);
			$this->_modelBulletinDate=$this->_getGlobalData('Model_BulletinDate','object');
			$this->_modelBulletinDate->addBulletin($id);
			return array('status'=>1,'msg'=>$error);
		}else {
			return array('status'=>0,'msg'=>'添加失败');
		}
	}
}