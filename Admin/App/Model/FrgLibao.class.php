<?php
/**
 * 富人国礼包表
 * @author php-朱磊
 *
 */
class Model_FrgLibao extends Model {
	protected $_tableName='frg_libao';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * 新版富人国API接口
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	
	public function delByServerId($serverId){
		$this->execute("delete from {$this->tName()} where server_id={$serverId}");
	}
	
	/**
	 * 同步服务器列表
	 * @param array $postArr
	 * @param array $serverId 运营商与服务器id
	 */
	public function syn($postArr,$serverIds){
		$this->delByServerId($serverIds['server_id']);
		$addArr=array();
		$i=0;
		foreach ($postArr as $value){
			if ($serverIds['operator_id'])$addArr[$i]['operator_id']=$serverIds['operator_id'];
			$addArr[$i]['server_id']=$serverIds['server_id'];
			$addArr[$i]['description']=$value['CardDescribe'];
			$addArr[$i]['title']=$value['CardName'];
			$addArr[$i]['img']=$value['CardImage'];
			$addArr[$i]['main_id']=$value['Id'];
			$i++;
		}
		$this->adds($addArr);
	}
	
	public function edit($postArr){
		$updateArr=array();
		$updateArr['title']=$postArr['CardName'];
		$updateArr['img']=$postArr['CardImage'];
		$updateArr['description']=$postArr['CardDescribe'];
		if (is_array($postArr['auto_id'])){
			$this->update($updateArr,'Id in ('.implode(',',$postArr['auto_id']).')');
		}else {
			$this->update($updateArr,"Id={$postArr['auto_id']}");
		}
	}
	
	public function add($postArr){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array();
		$addArr['user_id']=$userClass['_id'];
		$addArr['title']=$postArr['CardName'];
		unset($postArr['CardName']);
		$addArr['img']=$postArr['CardImage'];
		unset($postArr['CardImage']);
		$addArr['operator_id']=$postArr['operator_id'];
		unset($postArr['operator_id']);
		$addArr['create_time']=CURRENT_TIME;
		$addArr['tools']=serialize($addArr['tools']);
		$addArr['post_data']=serialize($postArr);
		if (parent::add($addArr)){
			return array('msg'=>'添加礼包成功','status'=>1,'href'=>1);
		}else {
			return array('msg'=>'添加礼包失败','status'=>-2,'href'=>1);
		}
	}
	
	public function findServers($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$dataList=$this->select("select Id,server_id,main_id from {$this->tName()} where title='{$postArr['title']}'");
		if ($dataList){
			$serverList=$this->_getGlobalData('gameser_list');
			$servers=array();
			foreach ($dataList as $list){
				$servers[$list['server_id']]=array('server_name'=>$serverList[$list['server_id']]['full_name'],'main_id'=>$list['main_id'],'Id'=>$list['Id']);
			}
			return  array('status'=>1,'data'=>array('servers'=>$servers),'href'=>1);
		}
		return  array('status'=>-2,'msg'=>'没有服务器','href'=>1);
	}
	
	/**
	 * 根据标题删除礼包
	 * @param array $postArr
	 */
	public function delByTitle($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$ids=array();
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Card','a'=>'TypeList','doaction'=>'delete');
		foreach ($postArr['title'] as $title){
			$dataList=$this->select("select * from {$this->tName()} where title='{$title}'");
			foreach ($dataList as $list){
				array_push($ids,$list['Id']);
				$curPostArr=array('Id[]'=>$list['main_id']);
				$this->_utilApiFrg->addHttp($list['server_id'],$getArr,$curPostArr);
			}
			$this->_utilApiFrg->send();
		}
		$this->delById($ids);
		/*
		foreach ($postArr['title'] as $title){
			$dataList=$this->select("select * from {$this->tName()} where title='{$title}'");
			$ids=array();
			$mainIds=array();
			foreach ($dataList as $list){
				array_push($ids,$list['Id']);
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($list['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'TypeList','doaction'=>'delete'));
				$sendParams['Id[]']=$list['main_id'];
				$this->_utilFRGInterface->setPost($sendParams);
				$this->_utilFRGInterface->callInterface();
				$this->_utilFRGInterface=null;
			}
			$this->delById($ids);
		}*/
	}
	
	/**
	 * 批量删除
	 * @param array $ids
	 */
	public function delByids($postArr){
		if (!count($postArr['ids']))return array('status'=>-1,'msg'=>'请选择要删除的记录','href'=>1);
		#------让serverId统一,一次性发送------#
		$sendServerIds=array();
		$i=0;
		foreach ($postArr['ids'] as $key=>$id){
			$sendServerIds[$postArr['server_id'][$key]]["Id[{$i}]"]=$postArr['main_id'][$key];
			$i++;
		}
		#------让serverId统一,一次性发送------#
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Card','a'=>'TypeList','doaction'=>'delete');
		foreach ($sendServerIds as $key=>$value){
			$this->_utilApiFrg->addHttp($key,$getArr,$value);
		}
		$this->_utilApiFrg->send();
		$this->delById($postArr['ids']);
		
		/*
		foreach ($postArr['ids'] as $key=>$id){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($postArr['server_id'][$key]);
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'TypeList','doaction'=>'delete'));
			$sendParams['Id[]']=$postArr['main_id'][$key];
			$this->_utilFRGInterface->setPost($sendParams);
			$this->_utilFRGInterface->callInterface();
			$this->_utilFRGInterface=null;
		}*/
	}
}