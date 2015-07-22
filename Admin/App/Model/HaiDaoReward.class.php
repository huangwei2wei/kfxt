<?php
/**
 * 富人国礼包同步表
 * @author php-朱磊
 *
 */
class Model_HaiDaoReward extends Model {
	protected $_tableName='haidao_reward';
	
	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;
	
	public function delByServerId($serverId){
		$this->execute("delete from {$this->tName()} where server_id={$serverId}");
	}
	
	/**
	 * 同步服务器礼包
	 * @param array $postArr
	 * @param array $serverId 运营商与服务器id
	 */
	public function syn($postArr,$serverIds){
		$this->delByServerId($serverIds['server_id']);
		$addArrs=array();
		foreach ($postArr as $value){
			$addArr=array();
			if ($serverIds['operator_id'])$addArr['operator_id']=$serverIds['operator_id'];
			$addArr['server_id']=$serverIds['server_id'];
			$addArr['main_id']=$value['Id'];
			$addArr['title']=$value['Title'];
			$addArr['send_time']=$value['SendTime'];
			$addArr['add_time']=$value['AddTime'];
			$addArr['end_time']=$value['EndTime'];
			$addArr['get_num']=$value['GetNum'];
			array_push($addArrs,$addArr);
		}
		$this->adds($addArrs);
	}
	
	public function edit($postArr){
		$updateArr=array();
		$updateArr['title']=$postArr['Title'];
		$updateArr['send_time']=strtotime($postArr['SendTime']);
		$updateArr['end_time']=strtotime($postArr['EndTime']);
		$this->update($updateArr,"Id={$postArr['auto_id']}");
	}
	
	/**
	 * 批量删除
	 * @param array $ids
	 */
	public function delByids($postArr){
		if (!count($postArr['ids']))return array('status'=>-1,'msg'=>'请选择要删除的记录','href'=>1);
		$sendServerIds=array();
		$i=0;
		foreach ($postArr['ids'] as $key=>$id){
			$sendServerIds[$postArr['server_id'][$key]]["Ids[{$i}]"]=$postArr['main_id'][$key];
			$i++;
		}
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Reward','a'=>'ShowList','Action'=>'Delete');
		foreach ($sendServerIds as $key=>$value){
			$this->_utilApiFrg->addHttp($key,$getArr,$value);
		}
		$this->_utilApiFrg->send();
		$this->delById($postArr['ids']);
		/*
		if (!count($postArr['ids']))return array('status'=>-1,'msg'=>'请选择要删除的记录','href'=>1);
		$ids=implode(',',$postArr['ids']);
		$this->execute("delete from {$this->tName()} where Id in ({$ids})");
		
		foreach ($postArr['ids'] as $key=>$id){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($postArr['server_id'][$key]);
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'ShowList','Action'=>'Delete'));
			$sendParams['Ids[]']=$postArr['main_id'][$key];
			$this->_utilFRGInterface->setPost($sendParams);
			$this->_utilFRGInterface->callInterface();
			$this->_utilFRGInterface=null;
		}*/
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
	
	public function delByTitle($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$ids=array();
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Reward','a'=>'ShowList','Action'=>'Delete');
		foreach ($postArr['title'] as $title){
			$dataList=$this->select("select * from {$this->tName()} where title='{$title}'");
			foreach ($dataList as $list){
				array_push($ids,$list['Id']);
				$curPostArr=array('Ids[]'=>$list['main_id']);
				$this->_utilApiFrg->addHttp($list['server_id'],$getArr,$curPostArr);
			}
			$this->_utilApiFrg->send();
		}
		$this->delById($ids);
		/*
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		foreach ($postArr['title'] as $title){
			$dataList=$this->select("select * from {$this->tName()} where title='{$title}'");
			$ids=array();
			$mainIds=array();
			foreach ($dataList as $list){
				array_push($ids,$list['Id']);
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($list['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'ShowList','Action'=>'Delete'));
				$sendParams['Ids[]']=$list['main_id'];
				$this->_utilFRGInterface->setPost($sendParams);
				$this->_utilFRGInterface->callInterface();
				$this->_utilFRGInterface=null;
			}
			$this->delById($ids);
		}*/
	}
	
	
}