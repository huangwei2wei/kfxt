<?php
/**
 * 接口总抽象
 * @author PHP-朱磊
 *
 */
abstract class ApiInterface extends Control {
	private $_key=TAKE_KEY;
	const WORKORDER_IF_CHK_METHOD = 'workOrderIfChk';	//工单接口验证方法名
	//private $_workOrderIfChkMethod = 'workOrderIfChk';	//工单接口验证方法名
	
	protected $_playerInfo;
	
	protected function __construct(){
		//日志测试		
//			$logs= '$_POST = '.var_export($_POST,true);
//			$logs .=";\n\r";
//			$logs .= '$_FILES = '.var_export($_FILES,true);
//			$logs .=";\n\r";
//			$logs .= '$_GET = ' .var_export($_GET,true);
//			$logs .=";\n\r#####################\n\r";
//			error_log($logs, 3, RUNTIME_DIR.'/Logs/workorder_logs_'.date('Y_m_d_H',time()).".log");	
		$check = $this->_initialize ();//如果不通过验证将退出返回出错数据
		if (true !== $check){	//恒等于true才跳过判断
			$check = $check?$check:'SIGN ERROR';
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => $check, 'data' => null ) );
		}
	}
	
	/**
	 * 验证
	 */
	private function _initialize() {
		$gameId = intval($_REQUEST['game_id']);
		$gameClass = $this->_getGlobalData($gameId,'game');
		//如果游戏有自己的验证方法将先使用默认方法workOrderIfChk
		
		if($gameClass && is_callable(array($gameClass,self::WORKORDER_IF_CHK_METHOD))){
			return call_user_func(array($gameClass,self::WORKORDER_IF_CHK_METHOD));
		}else{	//兼容某些旧FAQ接口
			$sign = trim($_REQUEST ['_sign']);
			$verifyCode = trim($_REQUEST ['_verifycode']);
			if (md5($this->_key.$verifyCode)==$sign) {
				return true;
			}
		}
		return false;
	}
	
	
}