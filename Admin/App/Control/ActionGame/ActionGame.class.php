<?php
/**
 * 公共GM后台
 * @author PHP-兴源
 */
Tools::import('Control_BaseZp');
abstract class ActionGame extends BaseZp {
	function __construct(){
		parent::__construct();
		if(ACTION == 'Moudle'){
			return;	//当设置权限时，放行
		}
		$this->package = 'ActionGame';
		$this->_view->set_tpl_dir('actionTheme');	//改掉模板目录(主题)
		$this->game_id = intval($_REQUEST['__game_id']);//从接口中获取game_id
		if(!$this->game_id){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game id error','data'=>NULL));
		}
		//这个迟点可以优化到搬到game类调用
//		$this->_gameIfConf = $this->_getGlobalData('game_if_conf/'.$this->game_id);
		$gameObject = $this->_getGlobalData($this->game_id,'game');
		$this->_gameIfConf = $gameObject->getIfConf();
		if(!$this->_gameIfConf[ACTION]['action']){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game interface not found','data'=>NULL));
		}
	}
	
	protected $_actionGame = NULL;
	
	public function callAction(){		
		if(!$this->_actionGame){
			$subAction = $this->_gameIfConf[ACTION]['action'];
			$file = APP_PATH.'/Action/'.PACKAGE.'_'.CONTROL.'/'.ACTION.'/'."{$subAction}.class.php";

			if(is_file($file)){
				include $file;
				$className = PACKAGE.'_'.CONTROL.'_'.ACTION.'_'.$subAction;
				
				if(class_exists($className)){
					$this->_actionGame = new $className();
				}
			}
		}
		if($this->_actionGame){
			$params = array(
				'UrlAppend'=>$this->_gameIfConf[ACTION]['UrlAppend'],
				'get'=>$this->_gameIfConf[ACTION]['get'],
				'post'=>$this->_gameIfConf[ACTION]['post'],
			);
			//加入监听者
			if(isset($this->_gameIfConf[ACTION]['notify'])){
				$notify = $this->_getGlobalData($this->_gameIfConf[ACTION]['notify'],'object');
				$this->_actionGame->attach($notify);
			}
			return call_user_func_array(array($this->_actionGame,'main'),$params);
		}else{
			throw new Error ( "$file Not Fount class:{$className} Not Fount" );
		}
	}
	
	public function display($_body=null){
		$subAction = $this->_gameIfConf[ACTION]['action'];
		if(empty($_body)){
			if(isset($this->_gameIfConf[ACTION]['body']) && $this->_gameIfConf[ACTION]['body']){
				$_body = $this->_gameIfConf[ACTION]['body'];
			}else{
				$_body = PACKAGE.'_'.CONTROL.'/'.ACTION."/{$subAction}.html";
			}
		}
		$this->_view->set_tpl(array('body'=>$_body));
		$this->_view->display();
	}

	
}