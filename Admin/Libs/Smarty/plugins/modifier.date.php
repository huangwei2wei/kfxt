<?php
function smarty_modifier_date($string, $format = 'Y-m-d H:i:s') {
	if (!is_numeric($string))
		return $string;
	return date ( $format, strtotime($string) );
}

?>
