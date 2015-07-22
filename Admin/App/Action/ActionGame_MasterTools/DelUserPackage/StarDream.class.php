<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_DelUserPackage_StarDream extends Action_ActionBase{

	public function getPostData($post){
		$postData = array();
		//$postData['cause'] = $post['cause'];
		$postData['userType'] = $post['userType'];
		$postData['user'] = $post['user'];
		$postData['title'] = base64_encode($post['title']);
		$postData['content'] = base64_encode($post['content']);
		
		if($post['goldCoin']){
			$postData['goldCoin'] = $post['goldCoin'];
		}
		if($post['silverCoin']){
			$postData['silverCoin'] = $post['silverCoin'];
		}
		if($post['vouchers']){
			$postData['vouchers'] = $post['vouchers'];
		}
		return array($cause,$postData);
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		
		if($this->_isPost()){
			
			$UrlAppend = 'DelUserPackage';
			$case = '调整道具数：<br>';
			foreach ($_POST['update_info'] as $one){
				$case .= $one['toolname'].'('.($one['canpresent']==1?'可赠送':'不可赠送').'):调整前'.$one['beforenum'].'个,调整后'.$one['afternum'].'个<br>';
				$postData['goods'] .= $one['boxId'].'_'.$one['ItemId'].'_'.max(intval($one['afternum']),0).';';
			}
			$postData['user'] = $_POST['user'];
			$postData['userType'] = $_POST['userType'];
			$postData['server_id'] = $_REQUEST['server_id'];
			
			$typelist = array('玩家ID','玩家账号','玩家昵称');
			$caseAll = $typelist[$postData['userType']].':'.$postData['user'].'<br>'.'操作原因:'.htmlspecialchars($_POST['cause']).'<br>'.$case;
			$caseAll .= '<br><span style="color:red">注:</span>同一个玩家同个道具调整后的个数以最后执行审核的为准,此时调整前个数以第一条执行的为准';
			$applyData = array(
				'type'=>68,//45,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$caseAll,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>$postData['userType'],//1为id，2为账号3为昵称
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
		
		if(isset($_GET['sbm'])){
			if(trim($_GET['playerId'])){
				$postData['user'] = trim($_GET['playerId']);
				$postData['userType'] = 0;
			}
			if(trim($_GET['accountName'])){
				$postData['user'] = trim($_GET['accountName']);
				$postData['userType'] = 1;
			}
			if(trim($_GET['playerName'])){
				$postData['user'] = trim($_GET['playerName']);
				$postData['userType'] = 2;
			}
			$getGetData = $this->_gameObject->getGetData($get);
			$data = $this->_gameObject->result('PackageList',$getGetData,$postData);
			if($data['status'] == 1){
				$this->_assign['dataList'] = $data['data']['list'];
				$this->_assign['user'] = $postData['user'];
				$this->_assign['userType'] = $postData['userType'];
			} else {
				$this->_assign['connectError'] = 'Error Message:'.$data['info'];
			}
			return $this->_assign;
		}
		
		return $this->_assign;
	}
	
}