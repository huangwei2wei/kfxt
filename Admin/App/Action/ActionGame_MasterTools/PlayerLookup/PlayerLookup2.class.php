<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_PlayerLookup2 extends Action_ActionBase{
	
	public function _init(){
		
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if ($_REQUEST['server_id']){
			$utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserQuery','Page'=>$_GET['page']));
			$utilFRGInterface->setPost($sendParams);
			$data=$utilFRGInterface->callInterface();
			$returnData = array();
			if ($data){
				if ($data['data']['list']){
					foreach ($data['data']['list'] as &$value){
						$value['url_ask']=Tools::url('Verify','OrderVerify',array(
																			'game_type_id'=>2,
																			'operator_id'=>$this->_operatorId,
																			'game_server_id'=>$_REQUEST['server_id'],
																			'game_user_id'=>$value['UserId'],
																			'user_account'=>$value['UserName'],
																			'user_nickname'=>$value['VUserName'],));

						$value['url_emp']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>1,'PageSize'=>10));
						$value['url_shop']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>2,'PageSize'=>10));
						$value['url_tools']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>3,'PageSize'=>10));
						$value['url_msg']=Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'PageSize'=>10,'doaction'=>'message'));
						$value['url_send_msg']=Tools::url(CONTROL,'SendMail',array('server_id'=>$_REQUEST['server_id'],'UserId[]'=>$value['Id'],'lock'=>true,'user_name'=>$value['UserName'],'nick_name'=>$value['VUserName']));
						$value['url_event_list']=Tools::url(CONTROL,'EventList',array('server_id'=>$_REQUEST['server_id'],'UserId'=>$value['Id'],));
					}
				}
				$this->_loadCore('Help_Page');
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']) ) );
					$returnData['pageBox'] = $helpPage->show();
				}
				$data['data']['query']['Items']=$data['data']['query']['Items']?$data['data']['query']['Items']:'9';//默认角色名称
				$selectPage=Tools::getLang('PAGE_OPTION','Control_MasterFRG');
				$returnData['selectPage'] = $selectPage;
				$returnData['select'] = $data['data']['Items'];
				$returnData['dataList'] = $data['data']['list'];
				$returnData['selectedQuery'] = $data['data']['query'];
				$returnData['selectedPageSize'] = $data['data']['PageSize'];
				$returnData['companyNum'] = $data['data']['CompanyNum'];
			}
			return $returnData;
		}
		return array();
	}
	
}