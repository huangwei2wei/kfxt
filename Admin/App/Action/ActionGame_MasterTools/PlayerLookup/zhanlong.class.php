<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page']-1));
		if($_GET['AccountID']){
			$getData["AccountID"]		=	max(1,intval($_GET['AccountID']));
		}
		if($_GET['AccountName']){
			$getData["AccountName"]		=	$_GET['AccountName'];
		}
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['AccountList'] as $key=>&$sub){
				if($Column[$i]=="SellText"){
					$sub = strip_tags($sub);
				}
				$datalist[$a][$Column[$i]]	=	$sub;
				$i++;
				if($i>=count($Column)){
					$i=0;
					$a++;
				}
			}
			//			print_r($datalist);

			$this->_assign['Column']=$Column;
			$this->_assign["len"]	=	count($Column)+1;
			$this->_assign['dataList']=$data['AccountList'];
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['GET']=$_GET;
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesDel',$query);
	}
}