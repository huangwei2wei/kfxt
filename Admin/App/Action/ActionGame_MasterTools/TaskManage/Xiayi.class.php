<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_TaskManage_Xiayi extends Action_ActionBase{
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		
		//--------POST　ACTION --------------
		if($this->_isPost()){
	
			$postData=array();
			if($_POST['userType']==3){
				$postData['user']=base64_encode(trim($_POST['user']));
			} else {
				$postData['user']=trim($_POST['user']);
			}
			$postData['userType']=intval($_POST['userType']);
			$postData['quest_id']=intval($_POST['quest_id']);
			
			if( count($postData)<=0 ){
				$this->jump('参数有误',-1);
			}
			
			$case = $this->_gameObject->_userTypeArr[$_POST['userType']].':'.$_POST['user'].'<br>任务Id:'.$_POST['quest_id'].'<br>';
			$applyData = array(
				'type'=>61,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>intval($_POST['userType']),//1为id，2为账号3为昵称
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

}
