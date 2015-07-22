<?php
/**
 * 富人国特殊活动
 * @author php-兴源
 *
 */
class Model_FrgSpecialActivity extends Model {
	protected $_tableName='frg_special_activity';
	
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
	public function syn($postArr,$serverIds,$activityTypes){
		$this->delByServerId($serverIds['server_id']);
		$addArr=array();
		$i=0;
		foreach ($postArr as $value){
			if ($serverIds['operator_id'])$addArr[$i]['operator_id']=$serverIds['operator_id'];
			$addArr[$i]['server_id']=$serverIds['server_id'];
			$addArr[$i]['special_activity_id'] = $value['Id'];
			$addArr[$i]['IdentifierValue']= $value['Identifier']?$value['Identifier']:'0';
			$addArr[$i]['Identifier']= isset($activityTypes[$value['Identifier']])?$activityTypes[$value['Identifier']]['Name']:NULL;
			$addArr[$i]['Img']=$value['Img'];
			$addArr[$i]['IsOpen']=$value['IsOpen'];
			$addArr[$i]['Title']=$value['Title'];
			$addArr[$i]['Content']=$value['Content'];
			$addArr[$i]['AwardDesc']=$value['AwardDesc'];
			$addArr[$i]['StartTime']=$value['StartTime'];
			$addArr[$i]['EndTime']=$value['EndTime'];
			$addArr[$i]['CheckType']=$value['CheckType'];
			$addArr[$i]['AwardIds']=$value['AwardIds'];
			$addArr[$i]['Awards']=$value['Awards'];
			$addArr[$i]['GetCond']=$value['GetCond'];
			
			
			//$addArr[$i]['Status']=$value['Status'];
			if ($value['Status']>0){
				if ($value['Identifier']=='EmployeeTopScore'){
					$addArr[$i]['Status']=date('Y-m-d H:i:s',$value['Status']).'已重置';
				}else {
					$addArr[$i]['Status']='奖励已发放';
				}
			}else {
				if ($value['IsOpen']){
					if (CURRENT_TIME>$value['EndTime']){
						$addArr[$i]['Status']='已结束';
					}elseif (CURRENT_TIME>$value['StartTime']) {
						$addArr[$i]['Status']='进行中';
					}elseif (CURRENT_TIME<$value['StartTime']){
						$addArr[$i]['Status']='未开始';
					}
				}else {
					$addArr[$i]['Status']='-';
				}
			}
			
			
			
			
			$addArr[$i]['MsgTitle']=$value['MsgTitle'];
			$addArr[$i]['MsgContent']=$value['MsgContent'];
			$addArr[$i]['MsgContent']=$value['MsgContent'];
			$addArr[$i]['IsShow']=$value['IsShow'];
			$i++;
		}
		$this->adds($addArr);
	}
	
	/**
	 * 根据标题删除特殊活动
	 * @param array $postArr
	 */
	public function delByTitle($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$ids=array();
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Activity','a'=>'ListSpecialActivity','action'=>'del');
		
		if($postArr['operator_id']>0){
			foreach ($postArr['title'] as $title){
				$dataList=$this->select("select Id,server_id,special_activity_id from {$this->tName()} where title='{$title}' and operator_id='{$postArr['operator_id']}'");
				foreach ($dataList as $list){
					array_push($ids,$list['Id']);
					$curPostArr=array('Ids[]'=>$list['special_activity_id']);
					$this->_utilApiFrg->addHttp($list['server_id'],$getArr,$curPostArr);
				}
				$this->_utilApiFrg->send();
			}	
		}
		else{
			foreach ($postArr['title'] as $title){
				$dataList=$this->select("select Id,server_id,special_activity_id from {$this->tName()} where title='{$title}'");
				foreach ($dataList as $list){
					array_push($ids,$list['Id']);
					$curPostArr=array('Ids[]'=>$list['special_activity_id']);
					$this->_utilApiFrg->addHttp($list['server_id'],$getArr,$curPostArr);
				}
				$this->_utilApiFrg->send();
			}	
		}

		$this->delById($ids);

	}
	
	/**
	 * 批量删除
	 * @param array $ids
	 */
	public function delByids($postArr){
		if (!count($postArr['ids']))return array('status'=>-1,'msg'=>'请选择要删除的记录','href'=>1);

		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Activity','a'=>'ListSpecialActivity','action'=>'del');
		$idStr = implode(',',$postArr['ids']);
		$sql = "select server_id,special_activity_id from {$this->tName()} where Id in ({$idStr}) ";

		$dataList=$this->select($sql);
		$curPostArr = array();
		foreach($dataList as $sub){
			$curPostArr[$sub['server_id']]['Ids'][] =  $sub['special_activity_id'];
		}
		foreach($curPostArr as $key =>$Ids){
			$this->_utilApiFrg->addHttp($key,$getArr,$Ids);
		}
		$return = $this->_utilApiFrg->send();
		$this->delById($postArr['ids']);
	}
	
	public function findServers($postArr){
		if (!$postArr['title'])return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$sql = "select Id,server_id,special_activity_id from {$this->tName()} where title='{$postArr['title']}'";
		if(isset($postArr['operator_id']) && $postArr['operator_id']>0){
			$sql .= " and operator_id = {$postArr['operator_id']}";
		}
		if($postArr['IdentifierValue']){
			$sql .= " and IdentifierValue = '{$postArr['IdentifierValue']}'";
		}		
//		if($postArr['StartTime']){
//			$sql .= " and StartTime = {$postArr['StartTime']}";
//		}
//		if($postArr['EndTime']){
//			$sql .= " and EndTime = {$postArr['EndTime']}";
//		}
		$dataList=$this->select($sql);
		if ($dataList){
			$serverList=$this->_getGlobalData('gameser_list');
			$servers=array();
			foreach ($dataList as $list){
				$servers[$list['server_id']]=array('server_name'=>$serverList[$list['server_id']]['full_name'],'special_activity_id'=>$list['special_activity_id'],'Id'=>$list['Id']);
			}
			return  array('status'=>1,'data'=>array('servers'=>$servers),'href'=>1);
		}
		return  array('status'=>-2,'msg'=>'没有服务器','href'=>1);
	}
	
	
}