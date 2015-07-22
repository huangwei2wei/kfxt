<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Organize_chunqiu extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			return $this->_assign;
		}
		if ( $this->_isPost() ){
			$case = '审核前请再次查询对比<br>';
			foreach ($_POST as $key=>$value){
				if( !preg_match('/^update_(.*)/',$key,$matches) )
					continue;
					
				$newKey = $matches[1];
				$case .= $_POST['upstr_'.$newKey].':'.$value.'<br>';
				if($newKey == 'guild_nm'){
					$value=base64_encode(trim($value));
				}
				$postData[$newKey] = trim($value);
			}
			
			$getIfConf = $this->_gameObject->getIfConf();
			$UrlAppend = $getIfConf['EditOrganize']['UrlAppend'];//修改帮派的地址
			
			$applyData = array(
				'type'=>59,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>1,//1为id，2为账号3为昵称
				'user'=>$postData['master_id'],//值，有需要再写
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
			'guild_nm'=>base64_encode(trim($_GET['guild_nm'])),
			'guild_id'=>trim($_GET['guild_id']),
		);
		if($postData["guild_nm"]){
			$postData["type"]	=	intval(1);
		}else{
			$postData["type"]	=	intval(0);
		}
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = base64_encode($data);
		$data = base64_decode($data);
		$data = json_decode($data,true);
		$status = 0;
		$info = null;
		if($data["status"]==1){
			$this->_assign["data"]=$data;
//			print_r($this->_assign);
		}else{
			$this->_assign["connectError"]=$data["error"];
		}
		return $this->_assign;
	}


}