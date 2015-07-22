
<?php
/**
 * 富人国玩家数据导入表
 * @author php-朱磊
 *
 */
class Model_FrgPlayerData extends Model {
	protected $_tableName='frg_player_data';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function add($postArr){
		$postArr['user_name']=array_unique($postArr['user_name']);
		$count=count($postArr['user_name']);
		if ($count<1)return array('msg'=>'导入的数据为空','href'=>1,'status'=>-1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$bathNum=md5(CURRENT_TIME.rand(0,1000));
		$bathNum=substr($bathNum,0,16);
		$addArr=array();
		for ($i=0;$i<$count;$i++){
			$addArr[$i]=array(
				'operator_id'=>$postArr['operator_id'][$i],
				'server_id'=>$postArr['server_id'][$i],
				'player_id'=>$postArr['user_name'][$i],
				'create_time'=>CURRENT_TIME,
				'user_id'=>$userClass['_id'],
				'batch_num'=>$bathNum,
			);
		}
		if ($this->adds($addArr)){
			$dataList=$this->select("select * from {$this->tName()} where batch_num='{$bathNum}'");
			return array('msg'=>false,'href'=>1,'status'=>1,'data'=>$dataList);
		}else{
			return array('msg'=>'导入失败','href'=>1,'status'=>-2);
		}
	}
	
	
	
	/**
	 * 检察这个excel是否可以上传
	 * @param array $fileName
	 * @return boolean true:可以上传,false:不能上传
	 */
	private function _isUpload($fileName){
		$data=$this->select("select Id from {$this->tName()} where file_name='{$fileName}'",1);
		return $data?false:true;
	}
	
	/**
	 * 增加新的记录
	 * @param array $postArr
	 * @param string $file 上传的文件
	 */
	public function importExcel($postArr,$file){
		$serverList=$this->_getCheckServerList();
		$operatorMark=$this->_getOperatorMark();
		$operatorId=$postArr['operator_id'];		
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$this->_loadCore('Help_Excel');
		$this->_loadCore('Help_FileUpload');
		if (!$file)return array('msg'=>'文件未上传','status'=>-1,'href'=>1);

		$helpFileUpload=new Help_FileUpload($file,EXCEL_DIR.'/'.date('Ymd'),1024*1024*8,array('xls','xlsx'));
		$helpFileUpload->singleUpload();
		$fileInfo=$helpFileUpload->getSaveInfo();
		$helpExcel=new Help_Excel($fileInfo['path']);
		$excelData=$helpExcel->getData(0);
		array_shift($excelData);
		$addArr=array();
		$num=0;//记数器,第几行
		$retArr=array();//返回消息
		$batchNum=md5(Tools::getRandCode(5).CURRENT_TIME);
		$batchNum=substr($batchNum,0,16);
		foreach ($excelData as $value){
			$value[0]=strtoupper($value[0]);
			$num++;
			if(empty($value[0])){
				continue;
			}
			$marking=strtolower($operatorMark[$operatorId]).'|'.trim($value[0]);
			if (!array_key_exists($marking, $serverList)){//如果服务器ID不存在将跳过
				array_push($retArr, "第 [{$num}] 行服务器ID不存在!");	
				continue;
			}
			
			$arr=array();
			$arr['create_time']=CURRENT_TIME;
			$arr['operator_id']=$operatorId?$operatorId:array_search(strtolower(trim($value[2])),$operatorMark);	//运营商ID
			$arr['server_id']=$serverList[$marking];	//serverid
			$arr['user_id']=$userClass['_id'];
			$arr['batch_num']=$batchNum;
			$arr['player_id']=trim($value[1]);
			array_push($addArr,$arr);
			
		}
		if (count($retArr)){//如果有错误信息
			return array('msg'=>implode('<br />',$retArr),'status'=>-1,'href'=>1);
		}
		if ($this->adds($addArr)){
			$dataList=$this->select("select * from {$this->tName()} where batch_num='{$batchNum}'");
			return array('msg'=>'上传文件成功','status'=>1,'href'=>1,'data'=>$dataList);
		}else {
			return array('msg'=>'失败','status'=>-1,'href'=>1);
		}
		
	}
	
	/**
	 * 获取运营商mark
	 */
	private function _getOperatorMark(){
		static $operatorIds=null;
		if (is_null($operatorIds)){
			Tools::import('Cache_OperatorSetup');
			$operatorList=Cache_OperatorSetup::getFrgConf();
			$operatorIds=array();
			foreach ($operatorList as $key=>$list){
				$operatorIds[$key]=$list['mark'];
			}
		}
		return $operatorIds;
	}
	
	/**
	 * 获取检测服务器ID
	 */
	private function _getCheckServerList(){
		$serverList=$this->_getGlobalData('server/server_list_2');
		unset($serverList[100],$serverList[200]);
		$serverArr=array();
		foreach ($serverList as $list){
//			if (strpos($list['marking'],'|')===false)$list['marking']='gamefrg|'.$list['marking'];
			$serverArr[$list['marking']]=$list['Id'];
		}
		return $serverArr;
	}
	
	
}