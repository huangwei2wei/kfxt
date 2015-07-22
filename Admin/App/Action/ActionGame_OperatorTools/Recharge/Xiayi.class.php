<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Recharge_Xiayi extends Action_ActionBase{
	
	public function main($UrlAppend=null,$get=null,$post=null){

		if(isset($_GET['sbm'])){
			$key = '@weedong@';
			//$Host = '121.14.46.158:80';//'http://api.weedong.com';
			$Host = 'http://api.weedong.com';
			$post = array(
				'time'=>CURRENT_TIME,
				'act'=>'get_xyjh',
				'sign'=>md5(CURRENT_TIME.$key),
				'tdate_s'=>date('Y-m-d',strtotime(trim($_GET['regBeginTime']))),
				'tdate_e'=>date('Y-m-d',strtotime(trim($_GET['regEndTime']))),
			);
			
			$result = array();
			$HttpInterface=$this->_getGlobalData('Util_HttpInterface','object');
			foreach (array('get_perserverpay','get_oldserverpay','get_serverreg') as $one){
				$post['do'] = $one;
				$HttpInterface->set_sendUrl($Host);
				$HttpInterface->setPost($post);
				$result[$one] = $HttpInterface->callInterface(true);
			}
			
			$datalist = array();
			foreach (array('get_perserverpay','get_oldserverpay','get_serverreg') as $one){
				if($result[$one]=='No Data' || empty($result[$one]) ){
					
				} else {
					$result[$one] = json_decode($result[$one],true);
					foreach ($result[$one] as $server_id=>$info){
						$datalist[$server_id][$one]['sm'] = $info['sm'];
					}
				}
			}
			
		}
		$this->_assign['datalist'] = $datalist;
		$this->_assign['display']=true;
		return $this->_assign;
	}
}