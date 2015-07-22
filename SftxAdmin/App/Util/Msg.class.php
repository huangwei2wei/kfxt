<?php
/**
 * 用户工具类
 * @author Administrator
 *
 */
class Util_Msg extends Control {
	/**
	 * 弹出提示信息
	 * @param $code 提示信息
	 * @param $errorLevel 错误等级 1 -1 -2 错误等级,默认为1正常
	 * @param $url 返回的URL地址,1为默认上一页 2为history.go(-1).
	 * @param $reftime 几秒之后返回
	 */
	public function showMsg($code, $errorLevel = 1, $url = 1, $reftime = 3) {
		$this->_createView ();
		$this->_view->assign('url_status',$url);
		if ($url == 1)$url = $_SERVER ["HTTP_REFERER"];
		if ($code==false)exit("<script>location.href=\"{$url}\"</script>");
		$this->_view->assign ( 'msg', $code );
		$this->_view->assign ( 'errorLevel', $errorLevel );
		$this->_view->assign ( 'url', $url );
		$this->_view->assign ( 'reftime', $reftime );
		$this->_view->display ( 'Default/Msg' );
		exit ();
	}
	
	/**
	 * 生成导航条
	 * @param string $control 控制器
	 * @param string $action 动作
	 */
	public function createNavBar($control=CONTROL,$action=ACTION){
		$this->_createView();
		$menuIndex=$this->_getGlobalData('menuIndex');
		$curValue="{$control}_{$action}";

		#------生成2,3级导航条------#
		$curAction=$menuIndex[$curValue];
		if ($curAction['super_action']){
			$lv3="<a href='javascript:void(0)'>{$curAction['name']}</a>";
			$superName=$menuIndex[$curAction['super_action']]['name'];
			$superValue=$menuIndex[$curAction['super_action']]['value'];
			$superAction=explode('_',$superValue);
			$superUrl=Tools::url($superAction[0],$superAction[1]);
			$lv2="<a href='{$superUrl}'>{$superName}</a>";
			$mainId=$menuIndex[$superValue]['parent_id'];
			foreach ($menuIndex as $value){
				if ($value['Id']==$mainId)$lv1=$value['name'];
			}
		}else {
			$lv2="<a href='".Tools::url($control,$action)."'>{$curAction['name']}</a>";
			$mainId=$menuIndex[$curValue]['parent_id'];
			foreach ($menuIndex as $value){
				if ($value['Id']==$mainId)$lv1=$value['name'];
			}
		}
		#------生成2,3级导航条------#
		$this->_view->set_tpl(array('navBar'=>'Default/NavigationBar.html'));
		$navBar=array('lv1'=>$lv1,'lv2'=>$lv2,'lv3'=>$lv3);
		$this->_view->assign('nav',$navBar);
	}
}