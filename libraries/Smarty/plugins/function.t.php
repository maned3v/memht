<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_t($params, &$smarty) {
	global $config_sys,$memht_lang;
	
	$args = $params;
	if (sizeof($args)>0) {
		$element = $args[1];
		if (isset($memht_lang[$config_sys['language']][$element])) {
			$args[1] = $memht_lang[$config_sys['language']][$element];
			return (isset($memht_lang[$config_sys['language']][$element])) ? @call_user_func_array('sprintf',$args) : $element ;
		} else {
			return $element;
		}
	} else {
		return false;
	}
}

?>
