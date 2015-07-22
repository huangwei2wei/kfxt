<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_Default_1_1 extends Action_ActionBase{
	private $_backpackSearchType = array();
	private static $_goods = null;
	private $_playerType;
	
	public function _init(){
		$this->_playerType = Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_assign['URL_itemsUpdate'] = $this->_urlBackpackUpdate();
		$this->_backpackSearchType = Tools::gameConfig('backpackSearchType',$this->_gameObject->_gameId);
		$this->_assign['backpackSearchType'] = $this->_backpackSearchType;
		$this->_assign['userType'] = $this->_playerType;
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		
		if($_REQUEST['backpackUpdate'] == 1){
			$this->backpackUpdate();
			return;
		}
		
		if($this->_isPost()){
			$get  =  $this->_gameObject->getGetData($get);
			if(!$_POST['user']){
				$this->jump('用户不能为空',-1);
			}
			$get['user'] = $_POST['user'];
			$get['userType'] = $_POST['userType'];
			$data = $this->getResult($UrlAppend,$get);
			if($data['status'] == '1' ){
				if($data['data']){
					$goods = $data['data']['list'];
					$this->_assign['dataList'] = $goods;
					$this->_assign['UserID'] = $data['data']['UserID'];
					$this->_assign['UserName'] = $data['data']['UserName'];
					$this->_assign['NickName'] = $data['data']['NickName'];
				}else{
					$this->jump('没有此用户',-1);
				}
			}else{
				$this->jump('查询失败'.$data['info'],-1);
			}
		}

		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
	private function _urladd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccountAdd',$query);
	}
	private function _urlBackpackUpdate(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'backpackUpdate'=>1,
		);
		return Tools::url(CONTROL,'BackpackSearch',$query);
	}
	private function _getGoodsName($goodsId){
		if(is_array($this->_goods)){
			foreach ($this->_goods as $subK=>$subV ){
				foreach ($subV['subList'] as $k => $v){
					if ($k == $goodsId){
						return $v;
					}
				}
			}
		}
	}
	
	private function backpackUpdate(){
		if($this->_isPost()){
			$get  =  $this->_gameObject->getGetData($get);
			if(!$_POST['user']){
				$this->jump('用户不能为空',-1);
			}
			
			$get['user'] = $_POST['user'];
			$get['userType'] = $_POST['userType'];
			$get['goods'] = array();
			$apply_info = '';
			
			$goodInfo = array();
			foreach ($_POST['itemNum'] as $key=>$num){
				if($num=='') continue;
				list($gid,$goodsId) = explode('_',$key);
				$get['goods'][] = $gid.'_'.$goodsId.'_'.$num;
				$goodInfo[] = $_POST['itemInfo'][$key].'个数调整为'.$num.'个';
			}
			$get['goods'] = implode(';',$get['goods']);
			
			$getIfConf = $this->_gameObject->getIfConf();
			$UrlAppend = $getIfConf['BackpackUpdate']['UrlAppend'];
			
			$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
			$apply_info .= '服务器:'.$gameser_list[ $_REQUEST['server_id'] ]['server_name'].'<br>';
			$apply_info .= '用户:'.$get['user'].' 用户类型:'.$this->_playerType[ $get['userType'] ].'<br>';
			$apply_info .= '信息:'.implode(',',$goodInfo).'<br>';
			$apply_info .= '操作原因:'.htmlspecialchars( $_POST['cause']);
			
			$applyData = array(
					'type'=>70,//46,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
							'url_append'=>$UrlAppend,
							'post_data'=>array(),
							'get_data'=>$get,
// 							'call'=>array(
// 									'cal_local_object'=>'Util_ApplyInterface',
// 									'cal_local_method'=>'FrgSendReward',
// 							)
					),
					'receiver_object'=>array($_REQUEST['server_id']=>''),
					'player_type'=>$_POST['userType'],
					'player_info'=>$_POST['user'],
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');

			$applyInfo = $_modelApply->AddApply($applyData);

			if( $applyInfo ){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->jump($showMsg,1,1,false);
			}else{
				$this->jump('申请失败'.$data['info'],-1);
			}
		}

		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
}