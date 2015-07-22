<?php
/**
 * 富人国公告同步表
 * @author php-朱磊
 *
 */
class Model_FrgNotice extends Model {
	protected $_tableName='frg_notice';
	
	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;
	
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * 根据服务器id删除这个服务器id所有的公告
	 * @param int $serverId
	 */
	public function delByServerId($serverId){
		$this->execute("delete from {$this->tName()} where server_id={$serverId}");
	}
	
	/**
	 * 同步服务器列表
	 * @param array $postArr
	 * @param array $serverId 运营商与服务器id
	 */
	public function synNotice($postArr,$serverIds){
		$this->delByServerId($serverIds['server_id']);
		$addArrs=array();
		foreach ($postArr as $value){
			$addArr=array();
			if ($serverIds['operator_id'])$addArr['operator_id']=$serverIds['operator_id'];
			$addArr['server_id']=$serverIds['server_id'];
			$addArr['content']=$value['Content'];
			$addArr['title']=$value['Title'];
			$addArr['start_time']=$value['Start_time'];
			$addArr['end_time']=$value['End_time'];
			$addArr['interval_time']=$value['Interval'];
			$addArr['url']=$value['Link'];
			$addArr['create_time']=$value['CreateTime']?$value['CreateTime']:'0';
			$addArr['last_send_time']=$value['Recent_time'];
			$addArr['main_id']=$value['Id'];
			array_push($addArrs,$addArr);
		}
		$this->adds($addArrs);
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
			$sendServerIds[$postArr['server_id'][$key]]["Id[{$i}]"]=$postArr['main_id'][$key];
			$i++;
		}
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'SystemNotice','a'=>'ShowList','doaction'=>'delete');
		foreach ($sendServerIds as $key=>$value){
			$this->_utilApiFrg->addHttp($key,$getArr,$value);
		}
		$this->_utilApiFrg->send();
		$this->delById($postArr['ids']);
	}
	
	public function edit($postArr){
		$updateArr=array();
		$updateArr['title']=$postArr['Notice']['Title'];
		$updateArr['content']=$postArr['Notice']['Content'];
		$updateArr['start_time']=strtotime($postArr['Notice']['Start_time']);
		$updateArr['end_time']=strtotime($postArr['Notice']['End_time']);
		$updateArr['interval_time']=$postArr['Notice']['Interval'];
		$updateArr['url']=$postArr['Notice']['Link'];
		if (is_array($postArr['auto_id'])){
			$this->update($updateArr,'Id in ('.implode(',',$postArr['auto_id']).')');
		}else {
			$this->update($updateArr,"Id={$postArr['auto_id']}");
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
	
	
	public function delByTitle($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$ids=array();
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'SystemNotice','a'=>'ShowList','doaction'=>'delete');
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
	}
}