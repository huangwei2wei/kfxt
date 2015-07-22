<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_BossList_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$type = array(
			"WorldID"	=>"区ID",
			"EventType"	=>"事件类型",
			"EventID"	=>"事件ID",
			"StoryTitle"=>"故事标题",
			"StoryText"=>"故事内容",
			"StoryIntro"=>"故事简介",
			"StoryImage"=>"故事图片的路径",
			"StoryType"	=>"故事类型",
			"FeedtplID"	=>"3366平台专用ID",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page']));
		$getData["WorldID"]		=	max(0,intval($_POST["WorldID"]));
		$data = $this->getResult($UrlAppend,$getData);
		print_r($data);
		if($data['Result'] == '0'){
			$Column = $data["Column"];
			$MallItemList	=	$data["MallItemList"];
			$datalist		=	array();
			$i = 0;
			$a = 0;
			foreach ($data['ClassList'] as $key=>&$sub){
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
			$this->_assign['Column']=$Column;
			$this->_assign["len"]	=	count($Column)+1;
			$this->_assign['dataList']=$datalist;
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['Add_Url']=$this->_urlAdd();
		$this->_assign['Del_Url']=$this->_urlDel();
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'EventStoryAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'EventStoryDel',$query);
	}

}