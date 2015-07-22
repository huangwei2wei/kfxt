<?php
function smarty_modifier_htmlspecialchars($string) {
	return htmlspecialchars ( stripcslashes($string) );
}