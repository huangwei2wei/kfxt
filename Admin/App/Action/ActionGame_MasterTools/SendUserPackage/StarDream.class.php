<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendUserPackage_StarDream extends Action_ActionBase{

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
		if($this->_isPost())
		{
			$utilRbac = $this->_getGlobalData('Util_Rbac','object');
			$userClass = $utilRbac->getUserClass();
			if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
				if( intval($_POST['goldCoin']) >20000){
					$this->jump('不能超过20000',-1);
				}
			}
					
			$postData = array();
			$sendArr = $postArr = array();
			if(count($_POST['itemIds'])>0){
				foreach ($_POST['itemIds'] as $index=>$one){
					if(!$one) continue;
					$sendArr[]= $_POST['toolname'][$index].$_POST['toolnum'][$index].'个';
					$postArr[] = $one.'_'.intval($_POST['toolnum'][$index]);
				}
			}
			
			$typelist = array('玩家ID','玩家账号','玩家昵称');
			$cause = '发送道具：<br>'.$typelist[$_POST['userType']].':'.$_POST['user'].'<br>';
			$cause .= '操作原因：'.$_POST['cause'].'<br>邮件标题：'.$_POST['title'].'<br>邮件内容：'.$_POST['content'].'<br>';
			$cause .= intval($_POST['goldCoin'])?'金币：'.intval($_POST['goldCoin']).'个<br>':'';
			$cause .= intval($_POST['silverCoin'])?'银币：'.intval($_POST['silverCoin']).'个<br>':'';
			$cause .= intval($_POST['vouchers'])?'礼券：'.intval($_POST['vouchers']).'个<br>':'';
			$cause .= join(',',$sendArr);
			
			$postData = array(
				'user'=>trim($_POST['user']),
				'userType'=>intval($_POST['userType']),
				'goldCoin'=>intval($_POST['goldCoin']),
				'silverCoin'=>intval($_POST['silverCoin']),
				'vouchers'=>intval($_POST['vouchers']),
				'goods'=>join(';',$postArr),
				'title'=>base64_encode( trim($_POST['title']) ),
				'content'=>base64_encode( trim($_POST['content']) ),
				'server_id'=>$_REQUEST['server_id'],
			);
			
			$UrlAppend = 'SendUserPackage';
			$applyData = array(
				'type'=>68,//45,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$cause,
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
		
		$data = $this->_gameObject->result('UserPackageList');
		if($data['status'] != 1){
			$this->_assign['connectError'] = '获取道具列表失败,请刷新:'.$data['info'];
			$this->_assign['toollist'] = null;
		} else {
			$this->_assign['toollist'] = json_encode($data['data']['list']);
		}

		return $this->_assign;
	}
	
}