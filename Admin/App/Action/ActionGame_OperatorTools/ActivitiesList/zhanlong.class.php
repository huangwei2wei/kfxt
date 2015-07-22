<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesList_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$type = array(
			"WorldID"	=>"区ID",
			"ClassID"	=>"活动ID",
			"ClassType"	=>"活动类型",
			"ClassCheck"=>"检查方式",
			"CheckValue"=>"检测值",
			"TriggerType"=>"触发方式",
			"IsLoop"=>"是否循环",
			"MaxCount"	=>"最多完成循环次数",
			"MinLevel"	=>"最小等级",
			"MaxLevel"=>"最大等级",
			"PrivilegeType"	=>"需要特权类型",
			"PrivilegeYear"	=>"是否需要年费特权",
			"PrivilegeLevel"=>"是否需要等级特权",
			"ConditionType"	=>"条件类型",
			"ConditionValue"	=>"条件值",
			"NeedPrivilege"		=>	"需要特权类型",
			"UniqueIndex"	=>"只可参与一次性索引值",
			"ItemGetType"	=>"道具获得类型",
			"EventType"=>"事件类型",
			"EventID"	=>"事件ID",
			"Fparameter"	=>"浮点参数",
			"DataTime"=>"起效时间段",
			"ItemPack"	=>"礼包序列",
			"ClassName"	=>"活动名称",
			"Describes"	=>"活动描述",
		);
		$this->_assign['type']	=	$type;
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["WorldID"]==""&&$_POST["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
			$_POST["WorldID"] = $this->_getServerID();
		}
		$_GET["WorldID"] = $_POST["WorldID"];
		$this->_assign["_GET"] = $_GET;
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page'])-1);
		$getData["WorldID"]		=	max(0,intval($_POST["WorldID"]));
		$data = $this->getResult($UrlAppend,$getData);
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
				if($Column[$i]=="区ID"){
					$datalist[$a]["WorldID"] = $sub;
				}
				if($Column[$i]=="活动ID"){
					$datalist[$a]["ClassID"] = $sub;
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