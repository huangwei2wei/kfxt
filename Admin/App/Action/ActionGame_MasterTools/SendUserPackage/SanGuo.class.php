<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendUserPackage_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		
		if(!$_REQUEST['server_id']){
			return array();
		}
		$Property	=	array(
			"gold"		=>	"金幣",
			"wood"		=>	"木頭",
			"ore"		=>	"礦石",
			"metal1"	=>	"翡翠",
			"metal2"	=>	"琉璃",
			"metal3"	=>	"藍田玉",
			"metal4"	=>	"瑪瑙",
			"metal5"	=>	"孔雀石",
			"gem"		=>	"寶石",
			"gem1"		=>	"沙漏",
		);
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($this->_isPost()){
			if(empty($_POST['user_id'])){
				$this->jump("玩家ID不能为空",-1);
			}
			if(empty($_POST['cause'])){
				$this->jump("原因不能为空",-1);
			}
			$post['user_id']	=	explode(',',$_POST['user_id']);
			if($_POST['Property']){
				foreach($_POST['Property'] as $k=>$_msg){
					$post['package_string'].=$k.",".$_msg."|";
					$strinfo.=$Property[$k].",".$_msg."|";
				}
			}
			$getData = $this->_gameObject->getGetData($get);
			$gameser_list = $this->_getGlobalData('gameser_list');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>玩家ID：</div><div style="padding-left:10px;">'.implode(",", $post['user_id']).'</div>';
			$apply_info.='<div>道具：</div><div style="padding-left:10px;">'.$strinfo.'</div>';
			$applyData = array(
						'type'=>19,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>$UrlAppend,
							'post_data'=>$post,
							'get_data'=>$getData,
							'end'=>array(
								'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
								'cal_local_method'=>'applyEnd',	//使用本地方法
								'params'=>array('ExtParam'=>'1'),),
							),
						'receiver_object'	=>array($_REQUEST['server_id']=>''),
						'player_type'		=>1,
						'player_info'		=>$_POST['user_id'],
				);	
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->jump("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1);
			}else{
				$this->jump($applyInfo['info'],-1);
			}
			
//			$getData = $this->_gameObject->getGetData($get);
//			$data = $this->getResult($UrlAppend,$getData,$post);
//			if($data['status'] == '1'){
//				$this->jump('修改成功',-1);
//			}else{
//				$this->jump('操作失败',-1);
//			}
		}
		$this->_assign['Property']	=	$Property;
		$this->_assign['get']	=	$_GET;
		return $this->_assign;
		
	}
	
	
}