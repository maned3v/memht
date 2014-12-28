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
 * @copyright	Copyright (C) 2008-2013 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class MB {
	private static $m = false;
	
	static function Initialize() {
		global $config_sys;
		
		if (is_callable('mb_internal_encoding')) {
			mb_internal_encoding("UTF-8");
			mb_http_output("UTF-8");
			mb_regex_encoding("UTF-8");
			self::$m = true;
			$config_sys['mb'] = true;
		} else {
			$config_sys['mb'] = false;
		}
	}
	
	//strtolower
	static function strtolower($str) {
		return (self::$m) ? mb_strtolower($str,"UTF-8") : strtolower($str);
	}
	
	//strtoupper
	static function strtoupper($str) {
		return (self::$m) ? mb_strtoupper($str,"UTF-8") : strtoupper($str);
	}
	
	//substr
	static function substr($str,$start,$length) {
		return (self::$m) ? mb_substr($str,$start,$length,"UTF-8") : substr($str,$start,$length);
	}
	
	//substr_count
	static function substr_count($haystack,$needle) {
		return (self::$m) ? mb_substr_count($haystack,$needle,"UTF-8") : substr_count($haystack,$needle);
	}
	
	//strstr
	static function strstr($haystack,$needle,$before_needle=false) {
		return (self::$m) ? mb_strstr($haystack,$needle,$before_needle,"UTF-8") : strstr($haystack,$needle,$before_needle);
	}
	
	//stristr
	static function stristr($haystack,$needle,$before_needle=false) {
		return (self::$m) ? mb_stristr($haystack,$needle,$before_needle,"UTF-8") : stristr($haystack,$needle,$before_needle);
	}
	
	//ucfirst
	static function ucfirst($str) {
		return (self::$m) ? mb_ucfirst($str,"UTF-8") : ucfirst($str);
	}
	
	//strrpos
	static function strrpos($haystack,$needle,$offset=0) {
		return (self::$m) ? mb_strrpos($haystack,$needle,$offset,"UTF-8") : strrpos($haystack,$needle,$offset);
	}
	
	//strripos
	static function strripos($haystack,$needle,$offset=0) {
		return (self::$m) ? mb_strripos($haystack,$needle,$offset,"UTF-8") : strripos($haystack,$needle,$offset);
	}
	
	//strpos
	static function strpos($haystack,$needle,$offset=0) {
		return (self::$m) ? mb_strpos($haystack,$needle,$offset,"UTF-8") : strpos($haystack,$needle,$offset);
	}
	
	//stripos
	static function stripos($haystack,$needle,$offset=0) {
		return (self::$m) ? mb_stripos($haystack,$needle,$offset,"UTF-8") : stripos($haystack,$needle,$offset);
	}
	
	//strrchr
	static function strrchr($haystack,$needle) {
		return (self::$m) ? mb_strrchr($haystack,$needle,"UTF-8") : strrchr($haystack,$needle);
	}
	
	//strcut (mb only)
	static function strcut($str,$start,$length) {
		return (self::$m) ? mb_strcut($str,$start,$length,"UTF-8") : $str;
	}
	
	//parse_str
	static function parse_str($encoded_string) {
		return (self::$m) ? mb_parse_str($encoded_string) : parse_str($encoded_string);
	}
}
if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst($str) {
		return mb_strtoupper(mb_substr($str, 0, 1,"UTF-8")).mb_substr($str, 1);
	}
}

?>