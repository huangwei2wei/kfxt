<?php
class Model_PlayerFaq extends Model {
	protected $_tableName='player_faq';
	
	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;
	
	/**
	 * status字段说明
	 * @var array
	 */
	private static $_faqStatus=array(
		'0'=>'通用',
		'1'=>'官网',
		'2'=>'游戏',
	);
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function findByids($ids){
		return $this->select("select * from {$this->tName()} where Id in (".implode(',',$ids).")");
	}
	
	public function add($keyvalue){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		
		$keyvalue['user_id']=$userClass['_id'];
		$keyvalue['time']=CURRENT_TIME;
		parent::add($keyvalue);
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'增加了一条FAQ记录');
		$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
		$gameTypeName=$this->_modelPlayerKindFaq->getGameKind();
		$gameTypeName=$gameTypeName[$keyvalue['game_type_id']];
		array_push($logArr,"FAQ所属游戏[{$gameTypeName}]");
		
		$lang=$this->_getGlobalData('lang');
		$lang=$lang[$keyvalue['lang_id']];
		array_push($logArr,"FAQ所属语言[{$lang}]");
		
		
		$kindList=$this->_modelPlayerKindFaq->findById($keyvalue['kind_id']);
		$kindName=$kindList['name'];
		array_push($logArr,"FAQ所属类型[{$kindName}]");
		
		array_push($logArr,"FAQ记录 [{$keyvalue['question']}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		
		return true;
		#------记录日志(生成msg)-------#
	}

	public function update($keyvalue,$conditions){
		#------修正更改分类后计数器不正常的情况------#
		$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
		$agoList=$this->select("select * from {$this->tName()} where {$conditions} ",1);
		if ($agoList){
			if (isset($keyvalue['kind_id']) && $agoList['kind_id']!=$keyvalue['kind_id']){
				$this->_modelPlayerKindFaq->update(array('count'=>'count+1'),"Id={$keyvalue['kind_id']}");
				$this->_modelPlayerKindFaq->update(array('count'=>'count-1'),"Id={$agoList['kind_id']}");
			}
		}
		#------修正更改分类后计数器不正常的情况------#
				
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$keyvalue['user_id']=$userClass['_id'];
		$keyvalue['time']=CURRENT_TIME;
		
		parent::update($keyvalue,$conditions);
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'修改了一条FAQ记录');
		
		$gameTypeName=$this->_modelPlayerKindFaq->getGameKind();
		$gameTypeName=$gameTypeName[$keyvalue['game_type_id']];
		array_push($logArr,"FAQ所属游戏[{$gameTypeName}]");
		
		$lang=$this->_getGlobalData('lang');
		$lang=$lang[$keyvalue['lang_id']];
		array_push($logArr,"FAQ所属语言[{$lang}]");
		
		
		$kindList=$this->_modelPlayerKindFaq->findById($keyvalue['kind_id']);
		$kindName=$kindList['name'];
		array_push($logArr,"FAQ所属类型[{$kindName}]");
		
		array_push($logArr,"FAQ记录 [{$conditions}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		return true;
		#------记录日志(生成msg)-------#
	}
	
	/*
	 * 检测faq
	 * */
	public function checkfaq($faqId){
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass=$this->_utilRbac->getUserClass();
		return parent::update(array('check_status'=>CURRENT_TIME,'check_user_id'=>$userClass['_id']),"Id={$faqId}");
	}

	/**
	 * 根据kind_id 查找相同类型的问题
	 * @param $id
	 * @return array
	 */
	public function findByKindId($id){
		return $this->select("select * from {$this->tName()} where kind_id={$id}");
	}
	
	/**
	 * 根据ID删除指定的一条记录
	 * @param int $id
	 */
	public function deleteById($id){
		$this->execute("delete from {$this->tName()} where Id={$id}");
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'删除了一条FAQ记录');
		array_push($logArr,"FAQ记录Id [{$id}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		return true;
		#------记录日志(生成msg)-------#
	}
	
	/**
	 * 根据kind_id删除FAQ记录
	 * @param int $id
	 */
	public function deleteByKindId($id){
		return $this->execute("delete from {$this->tName()} where kind_id={$id}");
	}
	
	/**
	 * 查找游戏类型为$gameTypeId,数量为$num的最高点击率FAQ
	 * @param int $gameTypeId
	 * @param int $num
	 * @param int $lang
	 */
	public function findHotList($gameTypeId,$num,$lang=1){
		return $this->select("select Id,ratio,game_type_id,kind_id,question from {$this->tName()} where game_type_id={$gameTypeId} and status!=1 and lang_id={$lang} order by ratio desc limit {$num}");
	}
	
	/**
	 * 更新faq点击率
	 * @param array $postArr
	 */
	public function ratioEdit($postArr){
		if (!Tools::coerceInt($postArr['Id']))return array('msg'=>'请选择正确的FAQ','status'=>-1,'href'=>1);
		$ratio=Tools::coerceInt($postArr['ratio']);
		if ($this->update(array('ratio'=>$ratio),"Id={$postArr['Id']}")){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'更改失败','status'=>-2,'href'=>1);
		}
	}
	
	/**
	 * 更新FAQ点击率
	 * @param int $faqId
	 */
	public function additionRatio($faqId){
		$this->update(array('ratio'=>'ratio+1'),"Id={$faqId}");
	}
	
	/**
	 * @return the $_faqStatus
	 */
	public static function getFaqStatus() {
		return Model_PlayerFaq::$_faqStatus;
	}
	
	/**
	 * 返回该语言中，已经复制过的FAQ Id
	 * @param array $FaqData
	 * @param int $LangId
	 * return Array
	 */
	public function findExistCopyFaqIds($FaqData,$LangId,$verifyField='Id'){
		$FaqDataIdsArr = Model::getTtwoArrConvertOneArr($FaqData,'Id',$verifyField);
		$FaqDataIds = implode(',',$FaqDataIdsArr);
		$sql="select group_concat(copy_from) as exist_ids from {$this->tName()} where copy_from in({$FaqDataIds}) and lang_id={$LangId} ";
		$ExistIds=$this->select($sql,1);		
		return explode(',',$ExistIds['exist_ids']);
	}
	
	
	public function FaqCount($select){
		$game	=	$this->_getGlobalData ('game_type');
		$game['0']= array(
			'count'	=>	0,
			'name'	=>	'总数'
		);
		foreach($game as &$msg){
			$msg['count']	=	0;
		}
		
		$str	=	'select*from '.$this->tName().' where check_user_id in('.implode(',',$select['userid']).') and check_status!=""';
		if($select['start_date']){
			$str	.=	' and check_status>'.strtotime($select['start_date']);
		}
		if($select['end_date']){
			$str	.=	' and check_status<'.strtotime($select['end_date']);
		}
		foreach($this->select($str) as $_msg){
				$game[$_msg['game_type_id']]['count']++;
				$game['0']['count']++;
		}
		return $game;
	}
	
	
	
	
	
	
	
	
	

}