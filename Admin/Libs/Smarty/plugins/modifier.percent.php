<?php
/**
 * 百分比函数
 * @param int $divisor
 * @param int $dividend
 */
function smarty_modifier_percent($divisor,$dividend)
{
	$divisor=intval($divisor);
	$dividend=intval($dividend);
	if ($divisor<1 || $divisor<1)return '∞';
    return sprintf('%.4f',$divisor/$dividend)*100 . '%';
}

/* vim: set expandtab: */

?>
