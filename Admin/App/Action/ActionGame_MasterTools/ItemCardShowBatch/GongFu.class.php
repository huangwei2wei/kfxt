<?php
/**
 * 查某礼包的所有批次
 */
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardShowBatch_GongFu extends Action_ActionBase{
	private $_classId = 0;
	public function _init(){

	}
	public function getPostData($post=null){
		$this->_classId = intval($_GET['classId']);
		return array(
			'classId'=>$this->_classId,
		);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		$postData = $this->getPostData($post);
		$getData = $get;//$this->_gameObject->getGetData($get);
		unset($getData['serverId']);	//这个接口不用serverId这个参数
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] !=1){
			$this->_assign['connectError'] = 'Error Info:'.$data['info'];
			return $this->_assign;
		}
		$dataList = array();
		foreach($data['data'] as $batchId){
			$dataList[] = array(
				'classId'=>$this->_classId,
				'batchId'=>$batchId,
				'URL_itemCardList'=>Tools::url(
					CONTROL,
					'ItemCardList',
					array(
						'zp'=>PACKAGE,
						'__game_id'=>$this->_gameObject->_gameId,
						'server_id'=>$_REQUEST['server_id'],
						'classId'=>$this->_classId,
						'batchId'=>$batchId,
					)
				),
				'URL_itemCardDownLoad'=>Tools::url(
					CONTROL,
					'ItemCardDownLoad',
					array(
						'zp'=>PACKAGE,
						'__game_id'=>$this->_gameObject->_gameId,
						'server_id'=>$_REQUEST['server_id'],
						'classId'=>$this->_classId,
						'batchId'=>$batchId,
					)
				),
			);
		}
		$this->_assign['dataList'] = $dataList;
		return $this->_assign;
	}
}

//"$data" = Array [3]	
//	data = Array [1]	
//		0 = (int) 569	
//	status = (int) 1	
//	info = null	


