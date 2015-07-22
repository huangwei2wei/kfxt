<?php
function smarty_modifier_truncateutf8($string, $leng = 100, $dot = '...') {
	if (! isset ( $string ) || $string == '') {
		return '';
	}
	preg_match_all ( "/.{1}/", $string, $chars );
	$c = '';
	$all = array ();
	$timer = 0;
	foreach ( $chars [0] as $char ) {
		$timer ++;
		if (ord ( $char ) > 127) {
			$c .= $char;
			if ($timer == 3) {
				$all [] = $c;
				$c = '';
				$timer = 0;
			}
		} else {
			$c = $char;
			$timer = 0;
			$all [] = $c;
			$c = '';
		}
	}
	if (sizeof ( $all ) <= $leng) {
		return implode ( '', $all );
	}
	return implode ( '', array_slice ( $all, 0, $leng ) ) . $dot;
}