<?php
/**
 * 语言包函数
 * @param string $key
 * @param string $fileName
 */
function smarty_modifier_lang($key, $fileName = NULL) {
	if ($fileName===null)$fileName='Control_'.CONTROL;
	$key='TPL_'.$key;
	if(CONTROL=="MasterTools"||CONTROL=="OperatorTools"){
		$fileName = "GameAction_".$_GET["__game_id"];
	}
	$re	=Tools::getLang(strtoupper($key),$fileName);
	if(empty($re)){
		$re	=Tools::getLang(strtoupper($key),"GameAction");
	}
	return $re;
}

?>
