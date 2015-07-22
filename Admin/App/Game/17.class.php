<?php
Tools::import('Game_GameBase');
class Game_17 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 17;
		//$this->_key = TAKE_KEY;
		$this->_sendImage = true;
	}
	
	public function workOrderIfChk(){
		return $this->commonChk();
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		//warren 图片上传
		$_utilHttpDown = $this->_getGlobalData('Util_Httpdown','object');
		$sendUrl=$data['send_url'];
		$sendUrl.='question/answerQuestion';
		$random=CURRENT_TIME.rand(100000,900000);
		$verifyCode=md5($this->_key.$random);
		$sendUrl.="?_sign={$verifyCode}&_verifycode={$random}&operator=kefu";
//		$data['content'] = $data['content'];//解决三分%号发不出去		
		unset($data['send_url']);
		$file_data = $data['file_img'];
		unset($data['file_img']);

		if($file_data && is_file($file_data)){
			$webPath = pathinfo($file_data);
			$data['image'] = 'http://'.$_SERVER['HTTP_HOST'].'/Upload/Service/'.date('Ymd',CURRENT_TIME).'/'.$webPath["basename"];
			//$_utilHttpDown->AddFileContent('picture',basename($file_data),file_get_contents($file_data));
		}
		foreach ($data as $k=>$v){
			$_utilHttpDown->AddForm($k,$v);
		}
		//echo $sendUrl;
		//$sendUrl	=	"http://127.0.0.1/333.php";
		$_utilHttpDown->OpenUrl($sendUrl);
		if($_utilHttpDown->IsGetOK()){
			$dataResult=$_utilHttpDown->GetRaw();
			$dataResult = json_decode($dataResult,ture);
			
			if ($dataResult['status']!=1){
				if($file_data){	//如果存在文件，就删除
					unlink($file_data);
				}
				return Tools::getLang('SEND_MSG','Control_WorkOrder').'<br>'.serialize($dataResult);
			}else{
				return true;
			}
		}else {
			return Tools::getLang('SEND_MSG','Control_WorkOrder').'<br>'.serialize($dataResult);
		}
	}
	
	public function autoReplay($data=NULL){
			$api	=	$this->_getGlobalData('Util_Rpc','object');
			$api->setUrl($data["server_msg"]['game_server_id'],'api/msg');
			$re=$api->save($data['title'],$data['content'],$data["server_msg"]['game_user_id']);
			$re=json_decode($re,true);
			if ($re['status']==1){
				return true;
			}else {
				return false;
			}
		
		//return $this->sendOrderReplay($data);
	}
	
	public function operatorExtParam(){
		return array();
	}
	
	public function serverExtParam(){
		return array();
	}
	
	
	
}