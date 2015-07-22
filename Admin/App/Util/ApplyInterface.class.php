<?php
class Util_ApplyInterface extends Base {
	
	public function FrgSendReward($sendData,$receiver){
		if($receiver && is_array($receiver)){
			set_time_limit(60);
			$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
			$this->_utilHttpMInterface->curlInit();
			$this->_utilHttpMInterface->setTimeOut(50);
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
			
			$serverList=$this->_getGlobalData('gameser_list');
			$sendResult=array();
			foreach($results as $key => $result){
				$backParams = $this->_callMethod('getFrgDecrypt',array($result),'Util_FRGInterface');
				
				$color=($backParams['msgno']==1)?'#00CC00':'#ff0000';//定义颜色
				$message=($backParams['msgno']==1)?'发送成功':'发送失败';
				$message=$backParams['message']?$backParams['message']:$message;
				$string="{$serverList[$key]['full_name']} : <font color='{$color}'>{$message}</font>";
				if ($backParams['backparams']){
					$string.='<br />未发出的用户：'.implode(',',$backParams['backparams']);
				}
				//记录有问题的结果
//				if($backParams['msgno']!=1){
//					$string .= '|返回： '.(empty($result)?'空':$result);
//				}
				array_push($sendResult, $string);
			}
			$retStr=implode('<br>', $sendResult);
			return $retStr;
		}
		return '数据缺少，系统未处理！';
	}
	
	/**
	 * 对返回结果不清晰的接口使用这个默认处理结果
	 */
	public function DefaultAuditEnd(){
		return '审核完成';
	}
	/**
	 * php类游戏的审核返回结果
	 * @param unknown_type $sendData
	 * @param unknown_type $receiver
	 */
	public function PhpAuditEnd($data = NULL){
		if(is_string($data)){
			$data = $this->_callMethod('getFrgDecrypt',array($data),'Util_FRGInterface');
		}
		$tag = '链接服务器失败';
		if($data && is_array($data)){
			if($data['msgno'] == 1){
				$tag = true;
			}elseif($data['mssage']){
				$tag = $data['mssage'];
			}else {
				$tag = '审核失败';
			}
		}else{
			return '<font color="#FF0000">服务器返回结果为空</font>';
		}
		if($tag === true){
			return array(
				'send_result'=>'<font color="#00FF00">审核成功</font>',
			);
		}else{
			return '<font color="#FF0000">'.$tag.'</font>';
		}
	}	
	/**
	 * 寻侠审核数据结果描述
	 * @param $data
	 */
	public function XunXiaBackDt($data = NULL){
		$tag = '链接服务器失败';
		if($data && is_object($data)){
			if($data->code === 0){
				$tag = true;
			}elseif($data->Message){
				$tag = $data->Message;
			}
		}else{
			return '<font color="#FF0000">服务器返回结果为空</font>';
		}
		if($tag === true){
			return array(
				'send_result'=>'<font color="#00FF00">审核成功</font>',
				'result_mark'=>$data->giftId.'_'.$data->giftContentId,
			);
		}else{
			return '<font color="#FF0000">'.$tag.'</font>';
		}
	}	
}