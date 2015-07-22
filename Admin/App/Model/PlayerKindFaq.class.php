<?php
class Model_PlayerKindFaq extends Model {
	protected $_tableName='player_kind_faq';

	/**
	 * @var $_mainKind array FAQ分类 
	 */
	private $_mainKind=array();
	
	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;


	public function __construct(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$this->_mainKind=$gameTypes;
		$this->_mainKind[0]='网上银行';
	}
	
	public function getGameKind(){
		return $this->_mainKind;
	}
	
	public function recount($kindId){
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$count=$this->_modelPlayerFaq->findCount("kind_id={$kindId}");
		parent::update(array('count'=>$count),"Id={$kindId}");
		return array('status'=>1,'msg'=>'更新计数器成功','href'=>1);
	}
	
	
	public function add($keyvalue){
		parent::add($keyvalue);
		
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'增加了一个FAQ分类');
		$gameTypeName=$this->_mainKind[$keyvalue['game_type_id']];
		array_push($logArr,"FAQ分类所属游戏[{$gameTypeName}]");
		$lang=$this->_getGlobalData('lang');
		$lang=$lang[$keyvalue['lang_id']];
		array_push($logArr,"FAQ所属语言[{$lang}]");
		array_push($logArr,"FAQ分类名称[{$keyvalue['name']}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		#------记录日志(生成msg)-------#
		
		return true;
	}
	
	/**
	 * 排序
	 * @param array $postArr
	 */
	public function sort($postArr){
		if (!count($postArr['sort_id']))return array('status'=>-2,'msg'=>'更新失败','href'=>1);
		foreach ($postArr['sort_id'] as $key=>$value){
			parent::update(array('sort_id'=>$value),"Id={$key}");
		}
		return array('status'=>1,'msg'=>'更新排序','href'=>1);
	}
	
	
	public function update($keyvalue,$conditions){
		parent::update($keyvalue,$conditions);
		
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'修改了一条FAQ分类');
		array_push($logArr,"修改FAQ分类名称[{$keyvalue['name']}]");
		array_push($logArr,"FAQ分类ID [{$conditions}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		return true;
		#------记录日志(生成msg)-------#
	}
	
	/**
	 * 根据游戏ID,语言查找分类
	 * @param int $gameTypeId
	 * @param string $lang 语言,默认1 简体
	 */
	public function findByGameTypeId($gameTypeId=NULL,$lang='1'){
		$sql="select * from {$this->tName()} where lang_id='{$lang}' ";
		if (is_numeric($gameTypeId))$sql.="and game_type_id={$gameTypeId}";
		$sql.=" order by sort_id";
		return $this->select($sql);
	}
	
	
	/**
	 * 根据ID删除一条记录
	 * @param int $id
	 */
	public function deleteById($id){
		#------记录日志(生成msg)-------#
		$logArr=array();
		array_push($logArr,'删除了一条FAQ分类');
		$data=$this->findById($id);
		$delName=$data['name'];
		array_push($logArr,"删除分类的名称[{$delName}]");
		array_push($logArr,"FAQ记录Id [{$id}]");
		$msg=Tools::formatLog($logArr);
		Tools::addLog($msg,true);
		
		#------记录日志(生成msg)-------#
		return $this->execute("delete from {$this->tName()} where Id={$id} limit 1");
		return true;
	}
	
	/**
	 * 返回FAQ类型,二维数组
	 * @param int $gameTypeId
	 * @param int $lang
	 */
	public function findListAll($gameTypeId=null,$lang=NULL){
		$sql="select * from {$this->tName()} where 1";
		if ($gameTypeId)$sql.=" and game_type_id={$gameTypeId}";
		if ($lang)$sql.=" and lang_id={$lang}";
		$sql.=" order by game_type_id desc";
		$dataList=$this->select($sql);
		if (!$dataList)return false;
		$retArr=array();
		foreach ($dataList as $list){
			$list['name']=$gameTypeId?$list['name']:"[{$this->_mainKind[$list['game_type_id']]}]{$list['name']}";//如果有gametypeid就不加游戏前缀,否则只显示FAQ名称 
			$retArr[$list['Id']]=$list['name'];
		}
		return $retArr;
	}
	
	/**
	 * 返回数据表中的纯数据（by:xy）
	 * @param int $gameTypeId
	 * @param int $lang
	 * @param int $kind_id
	 */
	public function findTableData($kind_id=NULL,$gameTypeId=null,$lang=NULL){
		$sql="select * from {$this->tName()} where 1";
		if ($kind_id)$sql.=" and Id={$kind_id}";
		if ($gameTypeId)$sql.=" and game_type_id={$gameTypeId}";
		if ($lang)$sql.=" and lang_id={$lang}";
		$sql.=" order by game_type_id desc";
		$dataList=$this->select($sql);
		return $dataList;
	}
	
	public function findLastId(){
		$sql="select Id from {$this->tName()} where 1 order by Id desc limit 1";
		$data=$this->select($sql);
		return $data[0]['Id'];
	}
	
	public function findCopyKind($CopyFrom = 0,$LangId = 0){
		$sql="select Id from {$this->tName()} where copy_from={$CopyFrom} and lang_id={$LangId} ";
		$data=$this->select($sql,1);
		return $data;
	}
	
	public function findLangIdByKindId($kindId){
		$sql="select lang_id from {$this->tName()} where Id = {$kindId} ";
		$data=$this->select($sql,1);
		return $data['lang_id'];
	}

	
}