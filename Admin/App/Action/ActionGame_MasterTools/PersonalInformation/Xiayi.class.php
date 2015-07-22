<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PersonalInformation_Xiayi extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		
		if($this->_isPost()){
			
			$getIfConf = $this->_gameObject->getIfConf();
			$postData = array();
			$case = '审核前请再次查询对比<br>';
			
			$UrlAppend = $getIfConf['SavePersonalInformation']['UrlAppend'];//修改接口
			$postData = array();
			foreach ($_POST as $key=>$value){
				if( !preg_match("/^gm_update_(.*)/",$key,$matches) )
					continue;
				
				$newKey = $matches[1];
				$case .= $_POST['gm_strup_'.$newKey].':'.$value.'<br>';
				
				if ($matches[1]=='char_name')
					$value = base64_encode($value);
				$postData[$matches[1]] = $value;
			}
				
			if(count($postData)<=0){
				$this->jump('参数有误',-1);
			}
			$applyData = array(
				'type'=>60,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>1,//1为id，2为账号3为昵称
				'user'=>$postData['char_id'],//值，
			);
			
			$re = $this->_gameObject->applyAction($applyData);
			if($re[0]==1){
				$this->jump($re[1],1,1,false);
			} else {
				$this->jump($re[1],-1);
			}
			return $this->_assign;
			
		}
		
		$postData=array(
			'playerId'=>trim($_GET['playerId']),
			'playerName'=>base64_encode(trim($_GET['playerName'])),
			'accountName'=>trim($_GET['accountName']),
			'regBeginTime'=>trim($_GET['regBeginTime']),
			'regEndTime'=>trim($_GET['regEndTime']),
			'loginBeginTime'=>trim($_GET['loginBeginTime']),
			'loginEndTime'=>trim($_GET['loginEndTime']),
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
		);
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = base64_encode($data);
		$data = base64_decode($data);
		$data = json_decode($data,true);

		$info = null;
		if($data['status'] == 1){

			foreach($data['users'] as &$player){
				$player['playerId'] = $this->_d2s($player['playerId']);
				$player['vocationId'] = $this->_vocationId($player['vocationId']);
			}
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>2000,'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $data['users'];
			$this->_assign['dataList_jsonencode'] = json_encode(array('users'=>$data['users']));
			$this->_assign['pageBox'] = $helpPage->show();
			$this->_assign['submitUrl'] = Tools::url(CONTROL,'PersonalInformation',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'sbm'=>1));
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['error'];
			$info = $data['error'];
		}
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$data['status'],'info'=>$info,'data'=>$this->_assign));
		}
		return $this->_assign;
	}

	private function _vocationId($vocationId=0){
		static $vocation = false;	//首次执行，放进内存保存
		if($vocation === false){
			$vocation = Tools::gameConfig('vocationId',$this->_gameObject->_gameId);
			//vocation 职业 :1武者 ,2气宗 ,3药师
		}
		return $vocation[$vocationId];

	}


}