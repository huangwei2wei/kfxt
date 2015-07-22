<?php
/**
 * smarty修改日期函数
 * @param $string 时间戳
 * @param $date	对日期的增减 
 * @param $format 格式
 */
function smarty_modifier_strtotime($string, $date, $format = 'Y-m-d H:i:s') {
	if (! $string)
		return;
	return date($format,strtotime($date,$string));
}