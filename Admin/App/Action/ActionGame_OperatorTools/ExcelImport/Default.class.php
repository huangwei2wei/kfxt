<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ExcelImport_Default extends Action_ActionBase{

	public $_Applytype = 51;
	public function _init(){
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
	}

	private function _upload() {
		$this->_loadCore('Help_FileUpload');
		$uploadPath = UPDATE_DIR . '/zlsg/' . date ( 'Ymd', CURRENT_TIME );
		$helpFileUpload=new Help_FileUpload($_FILES['Excel'],$uploadPath);
		$helpFileUpload->setBaseUrl(__ROOT__.'/Upload/zlsg/'.date('Ymd',CURRENT_TIME));
		$helpFileUpload->singleUpload();
		return $helpFileUpload->getSaveInfo();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if($this->_isPost()){
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');
			$file = $this->_upload();
			$data->read($file["path"]);
			$keyArr = array();
			for($a=1;$a<1000;$a++){
				if($data->sheets[0]['cells'][1][$a]!=""){
					array_push($keyArr,$data->sheets[0]['cells'][1][$a]);
				}
			}
			$dataList = array();
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				$valueArr = array();
				for($b=0;$b<count($keyArr);$b++){
					if($keyArr[$b]=="key"){
						$key = $data->sheets[0]['cells'][$i][$b+1];
					}else{
						$valueArr[$keyArr[$b]] = $data->sheets[0]['cells'][$i][$b+1];
					}
				}
				$dataList[$key] = $valueArr;
			}
			$this->_f($_POST["FileName"],$dataList);
		}
		return $this->_assign;
	}

	private function _urlitems(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Define',$query);
	}

	private function _urlBatch(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'ac'=>"batch"
			);
			return Tools::url(CONTROL,'SendMail',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}