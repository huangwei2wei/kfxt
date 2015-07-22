<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PropertySend_ZhiDouXing extends Action_ActionBase{

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isAjax()){
			if($_REQUEST['doaction'] == 'ajaxForZh'){
				$this->ajaxAction($get);
				exit;
			}elseif($_REQUEST['doaction'] == 'ajaxForDj'){
				$this->ajaxForDjAction($get);
				exit;
			}
		}
		if($this->_isPost()){
			$postData=$this->getPostData($_POST);
// 			print_r($postData);exit;
			$getData = $this->_gameObject->getGetData($get);
			//			$data = $this->getResult($UrlAppend,$getData,$postData);
			$cause	=	"发送玩家账号：".$_POST["accounts"]."<br/>";
			if($_POST["e"]=="delete"){
				$cause	.=	"删除道具";
			}else{
				$cause	.=	"发送道具";
			}
			$cause	.=	" :".$postData["names"]."<br/>";
			
			$serverId = intval($_REQUEST['server_id']);
			$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
			$applyData = array(
			'type'=>37,	//从Game拿id
			'server_id'=>$serverId,
			'operator_id'=>$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>$cause.$_POST["cause"],
			'send_type'=>2,	//2	http
			'send_data'=>array(
				'url_append'=>$UrlAppend,
				'post_data'=>$postData,
				'get_data'=>$getData,
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>1,
			'player_info'=>$postData['playerId'],
			);
			
// 			print_r($postData);
// 			print_r(array(
// 				'url_append'=>$UrlAppend,
// 				'post_data'=>$postData,
// 				'get_data'=>$getData,
// 			));
// 			exit;
			$modelApply = $this->_getGlobalData('Model_Apply','object');
			if($modelApply->AddApply($applyData)){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->jump($showMsg,1,1,false);
			}else{
				$this->jump('操作失败',-1);
			}
		}
		$this->_assign['ajax_url']		=	$this->_ajaxUrl();
		$this->_assign['type']		=	$this->_typeArr();
		return $this->_assign;
	}


	public function _ajaxUrl(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			
		);
		return Tools::url(CONTROL,ACTION,$query);
	}


	public function ajaxAction($get){
		$get["a"]	=	"getPlayerInfo";
		$getData = $this->_gameObject->getGetData($get);
		$getData["account"]	=	$_GET["playerid"];
		$data = $this->getResult("client",$getData,NULL);
		if($data["status"]==1){
			$jsonData	=	$data;
		}else{
			$jsonData = array('status'=>0,'info'=>"连接错误",'data'=>NULL);
		}
		echo json_encode($jsonData);
		return;
	}

	public function ajaxForDjAction($get){
		$get["a"]	=	"goodsType";
		$getData = $this->_gameObject->getGetData($get);
		$getData["type"]	=	$_GET["type"];
		$data = $this->getResult("client",$getData,NULL);
		$str = '';
		if($data["status"] == 1){
			foreach ($data['data'] as $k =>$v){
				$str .= '<tr onclick="checkedDj(this);"><td>'.$k.'</td><td>'.$v.'</td></tr>';
			}
		}
		echo $str;
		return;
	}
	private function _typeArr(){
		return array(
				'0' =>'请选择',
				"all"=>"所有",
				"500"=>"资源类",
				"2001"=>"提效类",
				"2002"=>"减时类",
				"2003"=>"补给类",
				"2004"=>"材料类",
				"2005"=>"卡片类",
		);
	}
	
	public function getPostData($post){
		
		$goodsIds = $post['goodsId'];
		$goodsNums = $post['goodsNum'];
		$goodsNames = $post['nameDj'];
		if(count($goodsIds) != count($goodsNums) ){
			$this->jump('格式错误！',-1);
		}
		$str = '';
		$names = '';
		foreach ($goodsIds as $k => $v){
			if (empty($v)){
				continue;
			}
			$goodsNums[$k] = $goodsNums[$k]?$goodsNums[$k]:1;
			$str .= $v . '|'. $goodsNums[$k].',';
			$names .= $goodsNames[$k] . '|'. $goodsNums[$k].',';
		}
		$str = substr($str,0,strlen($str)-1);
		$names = substr($names,0,strlen($names)-1);
		$post['goods'] = $str;
		$post['names'] = $names;
		
		unset($post['goodsId']);
		unset($post['goodsNum']);
		return $post;

	}

}