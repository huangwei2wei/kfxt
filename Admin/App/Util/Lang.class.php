<?php
/**
 * 多语言类
 * @author php-朱磊
 *
 */
class Util_Lang extends Base{
	
	private static $_sysSetup=array('game_type','workorder_source','workorder_status','verify_level','player_evaluation','quality_options','mail_type','frg_audit_type','faq_opinion','quality_status','verify_type','verify_status','verify_source','frg_log','frg_gold_card_type','lang','game_operate_type');
	
	private static $_langArr=array('menu'=>'name','menuIndex'=>'name','question_types'=>'title');
		
	/**
	 * 返回多语言配置文件
	 * @param $key
	 */
	public static function getLangCache($key){
		if (in_array($key,self::$_sysSetup)){
			//print_r(CACHE_DIR . '/sys_lang/' .LANG_ID. "/{$key}.cache.php<br>");
			return self::_includeFile(CACHE_DIR . '/sys_lang/' .LANG_ID. "/{$key}.cache.php");
		}
		if (array_key_exists($key,self::$_langArr)){
			return self::_convertKey($key);
		}
		return self::_includeFile(CACHE_DIR . "/{$key}.cache.php");
	}
	
	/**
	 * 转换字段KEY
	 * @param $key
	 */
	private static function _convertKey($key){
		$langPath=(LANG_ID===1)?'':LANG_ID;
		$arr= self::_includeFile(CACHE_DIR . "/{$key}.cache.php");
		if ($langPath=='')return $arr;
		$wordName=self::$_langArr[$key];
		foreach ($arr as $key=>&$value){
			$value[$wordName]=$value[$wordName.'_'.LANG_ID];
			unset($value[$wordName.'_'.LANG_ID]);
			if (isset($value['actions'])){
				foreach ($value['actions'] as $chilKey=>&$childValue){
					$childValue[$wordName]=$childValue[$wordName.'_'.LANG_ID];
					unset($childValue[$wordName.'_'.LANG_ID]);
				}
			}
		}
		return $arr;
	}
	
	
}