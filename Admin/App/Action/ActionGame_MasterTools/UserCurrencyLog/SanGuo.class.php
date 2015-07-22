<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_UserCurrencyLog_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id'] = $_REQUEST['server_id'];
		if($_GET['playerId']){
			$getData = $this->_gameObject->getGetData($get);
			$postData=array();
			$postData['Per']	=	PAGE_SIZE;
			if($_GET['page']){
				$postData['Page']	=	$_GET['page'];
			}else{
				$postData['Page']	=	1;
			}
			if(!$_GET['playerId']){
				$this->jump('用户ID不能为空',-1);
			}else{
				$postData['user_id']	=	trim($_GET['playerId']);
			}
			if($_GET['year']){
				$postData['year']	=	$_GET['year'];
			}
			
			if($_GET['month']){
				$postData['month']	=	$_GET['month'];
			}
			
			if($_GET['day']){
				$postData['day']	=	$_GET['day'];
			}

			$obj_type	=	array(
				'1'	=>	"金币",
				'2'	=>	"木头",
				'3'	=>	"矿石",
				'4'	=>	"翡翠",
				'5'	=>	"琉璃",
				'6'	=>	"蓝田玉",
				'7'	=>	"玛瑙",
				'8'	=>	"孔雀石",
				'9'	=>	"宝石",
				'10'	=>	"经验",
				'11'	=>	"声望",
				'16'	=>	"红心",
				'17'	=>	"黑心",
				'100'	=>	"沙漏",
			);
			
			$op_type	=	array(
				'1'	=>	"增加",
				'2'	=>	"减少",
			);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == '1' && $data['data']){
				$this->_assign['post']	=	$_GET;
				foreach($data['data']['list'] as &$_msg){
					$_msg['obj_type']	=	$obj_type[$_msg['obj_type']];
					$_msg['op_type']	=	$op_type[$_msg['op_type']];
				}
				$this->_assign['dataList']=$data['data']['list'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['data']['totle_page'],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox']=$helpPage->show();
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
	
	private function _urldel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'		=>	$id
		);
		return Tools::url(CONTROL,'LockAccountDel',$query);
	}
	
//"$data" = Array [3]	
//	data = (boolean) true	
//	status = (int) 1	
//	info = null	
	
	
}