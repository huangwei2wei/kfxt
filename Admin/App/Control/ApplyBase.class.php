<?php
class ApplyBase extends Control {
	
	protected $_utilRbac;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;
	
	protected $_myGames;
	protected $_myOperators;
	protected $_utilHttpMInterface;
	protected $_utilRpc;

	public function __construct(){
		
		$this->_createView();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_myGames = $this->_utilRbac->getGameActList();	//个人授权可操作的游戏
		$this->_myOperators = $this->_utilRbac->getOperatorActList();	//个人授权可操作的运营商
		
		$this->_init();
	}
	
    // 回调方法 初始化
    protected function _init() {}
	
	protected function getMResults($serverids,$UrlAppend=NULL,$get=NULL,$post=NULL){
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		foreach ($serverids as $serverId){
			$this->_utilHttpMInterface->addHttp($serverId,$UrlAppend,$get,$post);
		}
		$this->_utilHttpMInterface->send();
		return $this->_utilHttpMInterface->getResults();
	}
	
	protected function getResult($serverId,$UrlAppend=NULL,$get=NULL,$post=NULL,$ToArray=true){
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$post['_verifycode'] = CURRENT_TIME;
		$post['_sign'] = md5(self::KEY.CURRENT_TIME);
		$this->_utilHttpMInterface->addHttp($serverId,$UrlAppend,$get,$post);
		$this->_utilHttpMInterface->send();
		$data = $this->_utilHttpMInterface->getResults();
		if($ToArray){
			return json_decode(array_shift($data),true);
		}
		return json_decode(array_shift($data));
	}
	
	/**
	 * @return Util_Rpc
	 */
	protected function getApi(){
		if (is_null($this->_utilRpc)){
			$this->_utilRpc=$this->_getGlobalData('Util_Rpc','object');
		}
		return $this->_utilRpc;
	}
	
	protected function _getAllowGameId($GameId){
		if(is_string($GameId)){
			$GameId = explode(',',$GameId);
		}		
		$returnData = array();
		if(!is_array($GameId)){
			return $returnData;
		}
		foreach($GameId as $val){
			if(array_key_exists($val,$this->_myGames)){
				$returnData[] = $val;
			}
		}
		return $returnData;
	}
	
	protected function _getAllowOperatorId($OperatorId){
		if(is_string($OperatorId)){
			$OperatorId = explode(',',$OperatorId);
		}		
		$returnData = array();
		if(!is_array($OperatorId)){
			return $returnData;
		}
		foreach($OperatorId as $val){
			if(array_key_exists($val,$this->_myOperators)){
				$returnData[] = $val;
			}
		}
		return $returnData;
	}
	
	/**
	 * 发送数据解析器（递归遍历）
	 * @param array $data
	 */
	protected function dt($data=array(),$ExtParam=false){
		if($data && is_array($data)){
			foreach($data as $key => &$val){
				$val = call_user_func_array(array($this,__FUNCTION__),array($val,$ExtParam));
			}
			if(isset($data['cal_local_method'])){
				if(false !==$ExtParam && isset($data['params']['ExtParam'])){
					$data['params']['ExtParam'] = $ExtParam;
				}
				return $this->_callMethod($data['cal_local_method'],$data['params'],$data['cal_local_object']);
			}
		}
		return $data;
	}
	
	/**
	 * 参数整合
	 * @param $params
	 * @param $sendData
	 */
	protected function _paramMerge($params,$sendData){
		if(empty($params)){
			$params = $sendData;				
		}elseif(is_array($params)){
			$params = array_merge($params,$sendData);
		}else{
			$params = array_merge(array(strval($params)),$sendData);
		}
		return $params;
	}
	
	/**
	 * 发送数据至本地接口
	 * @param unknown_type $sendData
	 */
	protected function sendToLocal($sendData=NULL){
		if($sendData['call'] && is_array($sendData['call'])){
			$sendData['data'] = $this->dt($sendData['data']);			
			$object = $sendData['call']['cal_local_object'];
			$method = $sendData['call']['cal_local_method'];
			$params = $sendData['call']['params'];
			$params = $this->_paramMerge($params,array($sendData['data']));
			return $this->_callMethod($method,$params,$object);
		}else{
			return false;
		}
	}
	

	
	/**
	 * Http调用接口，接收对象是玩家
	 * @param unknown_type $sendData
	 * @param unknown_type $receiver
	 * @param unknown_type $playerType
	 */
	protected function sendByHttp($sendData=NULL,$receiver=NULL){		
		$sendData['url_append'] = strval($this->dt($sendData['url_append']));
		$sendData['get_data'] = $this->dt($sendData['get_data']);
		$sendData['post_data'] = $this->dt($sendData['post_data']);		
		if($sendData['call'] && is_array($sendData['call'])){
			$object = $sendData['call']['cal_local_object'];
			$method = $sendData['call']['cal_local_method'];
			$params = $sendData['call']['params'];
			$params = $this->_paramMerge($params,array($sendData,$receiver));
			return $this->_callMethod($method,$params,$object);
		}else{
			$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
			$this->_utilHttpMInterface->curlInit();
			if($receiver && is_array($receiver)){
				foreach($receiver as $ServerId => $FieldData){
					$ExtPostData = array();
					if(is_array($FieldData)){
						foreach($FieldData as $field => $val){
							$ExtPostData[$field] = $val;	//在$receiver结构中的数据，可覆盖$sendData的公共数据，但不再对数据进行解析
						}
					}
					$this->_utilHttpMInterface->addHttp($ServerId,$sendData['url_append'],$sendData['get_data'],array_merge($sendData['post_data'],$ExtPostData));
				}
				$this->_utilHttpMInterface->send();
				$results = $this->_utilHttpMInterface->getResults();
				if($results && $sendData['end'] && is_array($sendData['end'])){
					foreach($results as &$val){
						$val = $this->dt($sendData['end'],$val);
					}
				}
				return $results;
			}
			return false;
		}
	}
	
	protected function sendByPhprpc($sendData=NULL,$receiver=NULL,$playerType=1){
		$sendData['url_append'] = strval($this->dt($sendData['url_append']));
		$sendData['phprpc_method'] = trim($sendData['phprpc_method']);
		$sendData['phprpc_params'] = $this->dt($sendData['phprpc_params']);		
		if(empty($sendData['phprpc_method'])){
			return false;
		}		
		if($sendData['call'] && is_array($sendData['call'])){
			$object = $sendData['call']['cal_local_object'];
			$method = $sendData['call']['cal_local_method'];
			$params = $sendData['call']['params'];
			$params = $this->_paramMerge($params,array($sendData,$receiver));
			return $this->_callMethod($method,$params,$object);
		}else{
			set_time_limit(100);	//循环网络请求，暂时加上超时时间
			$this->_utilRpc=$this->_getGlobalData('Util_Rpc','object');
			if($receiver && is_array($receiver)){
				foreach($receiver as $ServerId => $FieldData){
					$ExtPostData = array();
					if(is_array($FieldData)){
						foreach($FieldData as $field => $val){
							$ExtPostData[$field] = $val;	//在$receiver结构中的数据，可覆盖$sendData的公共数据，但不再对数据进行解析
						}	
					}
					$this->_utilRpc->setUrl($ServerId,$sendData['url_append']);
					if(!empty($sendData['phprpc_key'])){
						$this->_utilRpc->setPrivateKey($sendData['phprpc_key']);
					}
					//目前phprpc还没有支持多路请求，暂时只能使用循环网络请求
					$results[$ServerId]=$this->_utilRpc->invoke($sendData['phprpc_method'],$sendData['phprpc_params']);
				}
				if($results && $sendData['end'] && is_array($sendData['end'])){
					
					foreach($results as &$val){
						$val = $this->dt($sendData['end'],$val);
					}
				}
				
				return $results;
			}
			return false;
		}
	}
	
}