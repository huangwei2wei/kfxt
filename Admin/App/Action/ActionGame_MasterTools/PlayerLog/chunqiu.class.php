<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_chunqiu extends Action_ActionBase{

	public function _init(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'playerType'=>1,
		);
		$ShortcutUrl = array(
			'SendMail'=>Tools::url(CONTROL,'SendMail',$query),
			'SilenceAdd'=>Tools::url(CONTROL,'SilenceAdd',$query),
			'LockAccountAdd'=>Tools::url(CONTROL,'LockAccountAdd',$query),
			'PointDel'=>Tools::url(CONTROL,'PointDel',$query),
		);
		$this->_assign['ShortcutUrl'] = $ShortcutUrl;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$type = $this->_gameObject->result($this->_getUrl(),$postData,"60022");
		$type = base64_encode($type);
		$type = base64_decode($type);
		$type = json_decode($type,true);
		$typearr = array();
		foreach($type as $k=>$v){
			$typearr[$v["id"]]["rootTypeName"]=$v["desc"];
			if(is_array($v["list"])){
				foreach($v["list"] as $ks=>$vs){
					$typearr[$v["id"]]["subTypeList"][$vs["id"]]["subTypeName"]=$vs["desc"];
				}
			}

		}
		$this->_assign["type"]	=	json_encode($typearr);
		if(!$_REQUEST['sbm']){
			return $this->_assign;
		}
		$postData=array(
			'userType'=>intval($_GET['userType']),
			'user'=>trim($_GET['user']),
			'page_id'=>max(1,intval($_GET['page'])),
			'page_size'=>PAGE_SIZE,
			'begin_time'=>intval(strtotime($_GET['begin_time'])),
			'end_time'=>intval(strtotime($_GET['end_time'])),
			'object_id'=>intval($_GET['object_id']),
			'event_id'=>intval($_GET['event_id']),
			'keywords'=>base64_encode(trim($_GET['keywords'])),
		);
		if($postData["userType"]==3){
			$postData["user"]=base64_encode($postData["user"]);
		}
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = base64_encode($data);
		$data = base64_decode($data);
		$data = json_decode($data,true);
		$status = 0;
		$info = null;
		if(is_array($data)){
			$status = 1;
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>2000,'perpage'=>PAGE_SIZE));
			$this->_assign['dataList'] = $data;
			$this->_assign['pageBox'] = $helpPage->show();
		} else if($data['status'] == 0){
			$status = 0;
			$info = $data['error'];
			$this->_assign['connectError'] = 'Error Message:'.$data['error'];
		}
			
		$this->_assign['GET'] = $_GET;
		if($this->_isAjax()){
			$this->ajaxReturn(array('status'=>$status,'info'=>$info,'data'=>$this->_assign));
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