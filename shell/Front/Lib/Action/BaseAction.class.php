<?php 
class BaseAction extends Action{
	protected  $uwanName;
	protected  $uwanUserId;
	/**
	 * SysconfigModel 
	 * @var SysconfigModel object
	 */
	private $sysConfigModel=null;
	function _initialize(){
		$uwanName=$_COOKIE['dovo_UserName'];
		
		if(isset($uwanName)){			
			$this->uwanName=urldecode($uwanName);
			$this->uwanUserId=$_COOKIE['dovo_userIdValue'];
			$this->assign("username",$this->uwanName);
		}
		
	}
	
	private function getSysConfigModel(){
		if($this->sysConfigModel==null){
			$this->sysConfigModel=new SysconfigModel();
		}
		return $this->sysConfigModel;
	}
	
	
	/**
	 * 数据库中的系统配置信息
	 * @param string $configName 配置名
	 * @return array|false
	 */
	public function getSysConfig($configName){
		$model=$this->getSysConfigModel();
		$faqOpinion=$model->getSysConfig($configName);
		return $faqOpinion;
	}
	
	/**
	 * 检测登录
	 */
	protected function checklogin(){
			if(!isset($this->uwanName)){
			$url=C('LOGIN_URL')."http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			redirect($url);
		}
	}

}
?>