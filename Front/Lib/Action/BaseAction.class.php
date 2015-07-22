<?php 
class BaseAction extends Action{
	protected  $uwanName;
	protected  $uwanUserId;
	
	protected $mainTpl = null;
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
	/**
	 * 左边目录1
	 */
	protected function left1($userAccount=''){
		if(empty($userAccount)){
			if(empty($this->uwanName)){
				return;
			}
			$userAccount = $this->uwanName;
		}
		$dao = M('work_order');
		$map['user_account']=$userAccount;
		$map['operator_id']=C('WE_OPERATOR');
		$map['source']=1;
		//待处理问题数
		$map['status']=array(array('egt',1),array('elt',2));
		$this->assign("waitCount",$dao->where($map)->count());
		//已处理问题数
		$map['status']=array('eq',C('HAVE_DO'));
		$this->assign("haveCount",$dao->where($map)->count());
		//待评价数
//		$map['status']=array('eq',C('HAVE_DO'));
//		$map['evaluation_status']=0;
//		$this->assign("evCount",$dao->where($map)->count());
	}
	
	/**
	 * 使用commom/main作为主模板，只需填充right栏
	 * @param unknown_type $templateFile
	 * @param unknown_type $charset
	 * @param unknown_type $contentType
	 */
    protected function display($templateFile='',$charset='',$contentType='text/html')
    {
    	if(is_file($templateFile) || empty($this->mainTpl)){
	    	$this->dsp($templateFile,$charset,$contentType);
	    	return;
    	}
        if(''==$templateFile) {
            $_right_page_contents = '../'.MODULE_NAME.'/'.ACTION_NAME;
        }elseif(strpos($templateFile,':')){
            $_right_page_contents = '../'.str_replace(':','/',$templateFile);
        }else{
        	$_right_page_contents = '../'.MODULE_NAME.'/'.$templateFile;
        }
    	$this->assign('_right_page_contents',$_right_page_contents);
    	parent::display($this->mainTpl,$charset,$contentType);
    }
    /**
     +----------------------------------------------------------
     * 模板显示
     * 只支持PHP模板
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function dsp($templateFile='',$charset='',$contentType='text/html')
    {
    	parent::display($templateFile,$charset,$contentType);
    }
    
    /**
     * 获得玩家所选择的游戏id或者以往最后选择的游戏id
     * @param int $defaultGameId	默认游戏id
     */
    protected function getGameId($defaultGameId = 2){
    	static $gameId = null;
    	if(empty($gameId)){
    		$gameTypes = D('Sysconfig')->getSysConfig('game_type');
    		$gameId = $gameTypes[$_GET['game_type_id']]['Id'];
// 	    	$gameId = isset($_GET['game_type_id'])?intval($_GET['game_type_id']):0;
			if($gameId>0){
				setcookie(C('SERVICE_COOKIE_GAME_ID'),$gameId,0,'/');
			}elseif (isset($_COOKIE[C('SERVICE_COOKIE_GAME_ID')])){
				$gameId = intval($_COOKIE[C('SERVICE_COOKIE_GAME_ID')]);
			}
			$gameId = $gameId>0?$gameId:$defaultGameId;
    	}
		return $gameId;
    }
    
    protected function error($message,$ajax=false)
    {
    	$this->mainTpl = null;
        parent::error($message,$ajax);
    }

    protected function success($message,$ajax=false)
    {
    	$this->mainTpl = null;
        parent::success($message,$ajax);
    }
}
?>