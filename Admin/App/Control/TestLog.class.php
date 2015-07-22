<?php
Tools::import('Control_BaseGm');
class Control_TestLog extends Control {


	private $_utilMsg;
	private $_Key	=	'^%&DFGDG%$RGTW#SDF';


	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
	}

	public function _createUrl(){
	}

	private function _getPassword($sign){
		return md5($this->_Key.$sign);
	}

	public function actionAddlog(){
		if($this->_isPost()){
			for($i=0;$i<500;$i++){
				$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
				$utilHttpMInterface->curlInit();
				$postData	=	$_POST;
				$get["sign"]	=	time();
				$get['password']=	$this->_getPassword($get["sign"]);
				$utilHttpMInterface->addHttp("http://127.0.0.1:8080","/cndw/clientinput",$get,$postData);
				$utilHttpMInterface->send();
				$dataReturn = $utilHttpMInterface->getResults();
			}

			print_r($dataReturn);
		}
		$this->_view->assign('urllog',Tools::url(CONTROL,'Addlog'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();

	}

	public function actionLookfor(){
		
		if(empty($_GET["page"])){
			$_GET["page"]=1;
		}
		$log_type	=	array(
			"user_id"=>"玩家ID",
			"vusername"=>"玩家昵称",
			"root_id"=>"日志一级分类",
			"type_id"=>"日志二级分类",
			"type_son_id"=>"日志三级分类",
			"create_time"=>"创建时间",
			"log_content"=>"内容",
			"other1"=>"其他内容1",
			"other2"=>"其他内容2",
			"other3"=>"其他内容3",
		);
		$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$utilHttpMInterface->curlInit();
		$get["sign"]	=	time();
		$get['password']=	$this->_getPassword($get["sign"]);
		$get["pageindex"]	=	$_GET["page"];
		$get["statr"]	=	strtotime($_GET["statr"]);
		$get["end"]		=	strtotime($_GET["end"]);
		$get["user_id"]			=	$_GET["user_id"];
		$get["pagesize"]		=	$_GET["pagesize"];
		$utilHttpMInterface->addHttp("http://127.0.0.1:8080","/cndw/Output",$get,array());
		$utilHttpMInterface->send();
		$dataReturn = $utilHttpMInterface->getResults();
		$data	=	$dataReturn["1"];
		$data	=	explode("\r\n",$data);
		$a	=	0;
		$data[0]	=	json_decode($data[0], true);
		if($data[0]["status"]==1){
			$count	=	$data[0]["msgcount"];
			unset($data[0]);
			foreach($data as $value){
				if(!empty($value)){
					$value=explode("\t",$value);
					$i	=	0;
					if(count($value)>1){
						foreach($log_type as $key=>$item){
							if($key=="create_time"){
								$rdata[$a][$key]	=	date("Y-m-d h:i:s",$value[$i]);
								$i++;	
							}else{
								$rdata[$a][$key]	=	$value[$i];
								$i++;
							}
						
						}
					}
					$a++;
				}
			}
			$this->_loadCore ( 'Help_Page' );
			$helpPage=new Help_Page(array('total'=>$count,'perpage'=>'20'));
			$this->_view->assign('pageBox',$helpPage->show());
			$this->_view->assign('data',$rdata);
		}
		
		
		$this->_view->assign('log_type',$log_type);
		$this->_view->assign('get',$_GET);
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	public function actionSendfile(){
		print_r($_POST);
		if($_FILES["logfile"]){
			print_r($_FILES);
			$this->_newsFileUpload($_FILES["logfile"],5,1024*1024*10*10);
		}
		exit('end');
	}
	
	public function actionUploadfile(){
		if($this->_isPost()){
			$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
			$utilHttpMInterface->curlInit();
			$get["sign"]		=	time();
			$get['password']	=	$this->_getPassword($get["sign"]);
			$get["sendfile"]	=	"1";
			$get["statr"]	=	strtotime($_POST["statr"]);
			$get["end"]		=	strtotime($_POST["end"]);
			$utilHttpMInterface->addHttp("http://127.0.0.1:8080","/cndw/Output",$get,array());
			$utilHttpMInterface->send();
			$dataReturn = $utilHttpMInterface->getResults();
			$data	=	$dataReturn["1"];
			$data	=	explode("\r\n",$data);
			$this->_view->assign('data',$data);
		}
		$this->_view->assign('get',$_POST);
		$this->_view->assign('urllog',Tools::url(CONTROL,'Uploadfile'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
}