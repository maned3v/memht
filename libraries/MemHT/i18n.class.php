<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2012 by Miltenovikj Manojlo <dev@miltenovik.com>
// http://www.memht.com
// 
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your opinion) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License along
// with this program; if not, see <http://www.gnu.org/licenses/> (GPLv2)
// or write to the Free Software Foundation, Inc., 51 Franklin Street,
// Fifth Floor, Boston, MA02110-1301, USA.
//========================================================================

/**
 * @author      Miltenovikj Manojlo <dev@miltenovik.com>
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class Language {
	static function LoadFile($path) {
		global $config_sys,$memht_lang;
		
		//Include local language file if exists
		if (file_exists($path)) include_once($path);
		
		//Clean memory
		if ($config_sys['language']!="en") unset($memht_lang['en']);
	}
	
	static function LoadPluginFile($plugin) {
		global $config_sys,$memht_lang;
		
		//Load minimum configuration language file
		self::LoadFile(_PATH_PLUGINS._DS.$plugin._DS.'languages'._DS."en.php");
		
		//Load default language file
		self::LoadFile(_PATH_PLUGINS._DS.$plugin._DS.'languages'._DS.$config_sys['default_language'].".php");
		
		//Load selected language file
		self::LoadFile(_PATH_PLUGINS._DS.$plugin._DS.'languages'._DS.$config_sys['language'].".php");
		
		//Clean memory
		if ($config_sys['language']!="en") unset($memht_lang['en']);
	}
}

function _t() {
	global $config_sys,$memht_lang;
	
	$args = func_get_args();
	if (sizeof($args)>0) {
		$element = $args[0];
		if (isset($memht_lang[$config_sys['language']][$element])) {
			$args[0] = $memht_lang[$config_sys['language']][$element];
			return (isset($memht_lang[$config_sys['language']][$element])) ? @call_user_func_array('sprintf',$args) : $element ;
		} else {
			return $element;
		}
	} else {
		return false;
	}
}

function _setlocale() {
	global $config_sys,$memht_lang;
	
	$args = (isset($memht_lang[$config_sys['language']]['LOCALE']) && is_array($memht_lang[$config_sys['language']]['LOCALE'])) ? $memht_lang[$config_sys['language']]['LOCALE'] : array(LC_ALL,'en_US');
	
	if (sizeof($args)>0) {
		@call_user_func_array('setlocale',$args);
	}
}

//Language configuration
$cookie_lang = Io::GetCookie("language","[^a-zA-Z_]");
$config_sys['language'] = (!empty($cookie_lang)) ? $cookie_lang : $config_sys['default_language'];
if ($User->IsUser()) {
	$user_lang = $User->GetOption('language');
	$config_sys['language'] = (!empty($user_lang)) ? $user_lang : $config_sys['language'];
}

//Load language
include_once(_PATH_LANGUAGES._DS."en.php");
Language::LoadFile(_PATH_LANGUAGES._DS.$config_sys['language'].".php");

//Set locale
_setlocale();

?>