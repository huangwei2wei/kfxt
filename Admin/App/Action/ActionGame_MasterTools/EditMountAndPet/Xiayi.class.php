<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_EditMountAndPet_Xiayi extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		
		$getIfConf = $this->_gameObject->getIfConf();
		
		//--------POST　ACTION --------------
		if($this->_isPost()){
			
			$case = '审核前请再次查询核实<br>';
			$case2='';
			if($_POST['updateType'] == 'pet'){

				$UrlAppend = $getIfConf['EditSavePet']['UrlAppend'];
				$postData = array();
				foreach ($_POST as $key=>$value){
					if( !preg_match("/^update_pet_(.*)/",$key,$matches) )
						continue;
					
					$newKey = $matches[1];
					$case2 .=$_POST['upstr_pet_'.$newKey].':'.$value.'<br>';
					
					if($newKey == 'name'){
						$value = base64_encode($value);
					}
					$postData[$newKey] = $value;
				}
				$apply_userType=1;
				$apply_user=$postData['char_id'];
				
			} else if( $_POST['updateType'] == 'mount'){
				$UrlAppend = $getIfConf['EditSaveMount']['UrlAppend'];
				$postData = array();
				foreach ($_POST as $key=>$value){
					if( !preg_match("/^update_mount_(.*)/",$key,$matches) )
						continue;
					
					$newKey = $matches[1];
					$case2 .=$_POST['upstr_mount_'.$newKey].':'.$value.'<br>';
					
					$postData['horse_info'][$newKey] = $value;
				}
				if( $_POST['userType'] == 3 ){
					$postData['user'] = base64_encode($_POST['user']);
				} else {
					$postData['user'] = $_POST['user'];
				}
				$postData['userType'] = $_POST['userType'];
				 
				$apply_userType=$_POST['userType'];
				$apply_user=$_POST['user'];
			} else {
				$this->jump('参数有误',-1);
			}
			
			$case .= $this->_gameObject->_userTypeArr[$apply_userType].':'.$apply_user.'<br><br>'.$case2;
			if(count($postData)<=0){
				$this->jump('参数有误',-1);
			}
			
			$applyData = array(
				'type'=>63,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>$apply_userType,//1为id，2为账号3为昵称
				'user'=>$apply_user,//值，
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
		$petdata = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$petdata = json_decode($petdata,true);
		if($petdata['status'] !=1){
			$this->jump($petdata['error'],-1);
		}
		
		//查询坐骑 
		$mountdata = $this->_gameObject->result($this->_getUrl(),$postData,$getIfConf['SearchMount']['UrlAppend']);
		$mountdata = json_decode($mountdata,true);
		if($mountdata['status'] !=1){
			$this->jump($mountdata['error'],-1);
		}
		
		$this->_assign['GET']=$_GET;
		$this->_assign['pet_info'] = $petdata['pet_info'];
		$this->_assign['pet_info_json'] = json_encode($petdata['pet_info']);
		$this->_assign['mount_info'] = $mountdata['horse_info'];
		$this->_assign['mount_info_json'] = json_encode($mountdata['horse_info']);

		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$petdata['status'],'info'=>$pet_info,'data'=>$this->_assign));
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