<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Equipment_Xiayi extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		
		//--------POST　ACTION --------------
		if($this->_isPost()){
			
			$cmd = array(0=>'60044',1=>'60046',2=>'60047',3=>'60048');
			$case = '审核前请再次查询对比<br>';
			$case .= $this->_gameObject->_userTypeArr[$_POST['update_userType']].':'.$_POST['update_user'].'<br>装备Id:'.$_POST['update_equip_id'].'<br>';
			
			//基本信息
			$postData = array();
			if(intval($_POST['update_userType']) == 3){
				$postData['user'] = base64_encode( trim($_POST['update_user']) );
			} else {
				$postData['user'] = trim($_POST['update_user']);
			}
			$postData['userType'] = intval($_POST['update_userType']);
			$postData['id'] = trim($_POST['update_equip_id']);
			
			$UrlAppend = $cmd[ intval($_POST['update_cmd']) ];
			
			if( !in_array($_POST['update_userType'],array(1,2,3)) || !isset($cmd[ intval($_POST['update_cmd']) ]) || $postData['id']==''){
				$this->jump('参数有误',-1);
			}
			
			//提交的信息
			foreach ($_POST as $key=>$value){
				if( !preg_match("/^ac_{$_POST['update_cmd']}_(.*)/",$key,$matches) )
					continue;
				
				$newKey = $matches[1];
				$case .= $_POST['str_'.$newKey.'_'.$_POST['update_cmd']].':'.$value.'<br>';
				if($_POST['update_cmd']==2 && in_array($newKey,array('hit','hp','mp','crit')) ){
					$postData['ty'][$newKey] = trim($value);
				} else {
					$postData[$newKey] = trim($value);
				}
			}
			
			$applyData = array(
				'type'=>64,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>intval($_POST['update_userType']),//1为id，2为账号3为昵称
				'user'=>$postData['user'],//值，
			);
			
			$re = $this->_gameObject->applyAction($applyData);
			if($re[0]==1){
				$this->jump($re[1],1,1,false);
			} else {
				$this->jump($re[1],-1);
			}
			return $this->_assign;
			
		}
		//-------------------END POST ----------------------------------
		
		//查询条件
		$postData = array();
		if($_GET['userType']==3){
			$postData['user'] = base64_encode($_GET['user']);
		} else {
			$postData['user'] = $_GET['user'];
		}
		$postData['userType'] = $_GET['userType'];
		
		//查询宠物
		$equipdata = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$equipdata = json_decode($equipdata,true);
		if($equipdata['status'] !=1){
			$this->jump($equipdata['error'],-1);
		}
		
		$this->_assign['GET']=$_GET;
		$this->_assign['equip_info'] = $equipdata['equip_info'];
		$this->_assign['equip_info_json'] = json_encode($equipdata['equip_info']);

		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$equipdata['status'],'info'=>$equipdata['equip_info'],'data'=>$this->_assign));
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