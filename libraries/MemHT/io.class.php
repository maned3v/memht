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

/* Kses */
require_once(_PATH_LIBRARIES._DS."kses"._DS."kses.php");

class Io {
	static function GetVar($METHOD,$varname,$filter=false,$encode=true,$default=false) {
		$METHOD = MB::strtoupper($METHOD);
		switch ($METHOD) {
			default:
			case "REQUEST":	$input = &$_REQUEST;	break;
			case "GET":		$input = &$_GET;		break;
			case "POST":	$input = &$_POST;		break;
			case "COOKIE":	$input = &$_COOKIE;		break;
			case "SESSION":	$input = &$_SESSION;	break;
			case "FILES":	$input = &$_FILES;		break;
			case "SERVER":	$input = &$_SERVER;		break;
			case "ENV":		$input = &$_ENV;		break;
		}
		return (isset($input[$varname]) && $input[$varname]!=="") ? self::Filter($input[$varname],$filter,$encode) : $default;
	}
	
	static function GetInt($METHOD,$varname,$default=0) {
		return self::GetVar($METHOD,$varname,"int",false,$default);
	}
	
	static function GetFloat($METHOD,$varname,$default=0) {
		return self::GetVar($METHOD,$varname,"float",false,$default);
	}
	
	static function GetBool($METHOD,$varname,$default=false) {
		return self::GetVar($METHOD,$varname,"bool",false,$default);
	}
	
	static function GetCookie($varname,$filter=false,$encode=true,$default=false) {
		return self::GetVar("COOKIE",$varname,$filter,$encode,$default);
	}
	
	static function SetCookie($varname,$value,$expiration=false,$filter=false,$encode=false) {
		if ($expiration==false) $expiration = time()+3600;
		$value = (!empty($value)) ? self::Filter($value,$filter,$encode) : "" ;
		setcookie($varname,$value,$expiration,_COOKIEPATH);
	}
	
	static function GetSession($varname,$filter=false,$encode=true,$default=false) {
		Session::Start();
		return self::GetVar("SESSION",$varname,$filter,$encode,$default);
	}
	static function SetSession($varname,$value) {
		Session::Start();
		return ($_SESSION[$varname]=$value) ? true : false ;
	}
	
	static function GetServer($varname,$filter=false,$encode=true,$default=false) {
		return self::GetVar("SERVER",$varname,$filter,$encode,$default);
	}
	
	static function GetEnv($varname,$filter=false,$encode=true,$default=false) {
		return self::GetVar("ENV",$varname,$filter,$encode,$default);
	}
	
	static function GetFiles($varname) {
		return self::GetVar("FILES",$varname);
	}
	
	static function GetAlnum($METHOD,$varname,$encode=true,$default=false) {
		return self::GetVar($METHOD,$varname,"[^a-zA-Z0-9]",$encode,$default);
	}
	
	static function GetWord($METHOD,$varname,$encode=true,$default=false) {
		return self::GetVar($METHOD,$varname,"[^a-zA-Z]",$encode,$default);
	}
	
	static function GetString($METHOD,$varname,$encode=true,$default=false) {
		return self::GetVar($METHOD,$varname,false,$encode,$default);
	}
	
	static function Filter($var,$filter=false,$encode=true) {
		global $config_sys;
		/*
		Deprecated
		if (get_magic_quotes_gpc()) {
			if (is_array($var)) {
				array_walk_recursive($var,array('self','arrayStripslashes'));
			} else {
				$var = stripslashes($var);
			}
		}
		*/
		
		switch (MB::strtolower($filter)) {
			//Simple
			case "int":
				$var = (int) $var;
				break;
			case "float":
				$var = (float) $var;
				break;
			case "bool":
				$var = (bool) $var;
				break;
			//Content
			case "fullhtml":
				//Advanced (full) html tags allowed
				if (is_array($var)) {
					array_walk_recursive($var,array('self','arrayFullHtmlFilter'));
				} else {
					require_once(_PATH_LIBRARIES._DS."kses"._DS."allowed.php");
					$var = kses($var,$config_sys['allowed_tags_advanced']);
				}
				break;
			case "publichtml":
				//Public html tags allowed
				if (is_array($var)) {
					array_walk_recursive($var,array('self','arrayPublicHtmlFilter'));
				} else {
					require_once(_PATH_LIBRARIES._DS."kses"._DS."allowed.php");
					$var = kses($var,$config_sys['allowed_tags_public']);
				}
				break;
			case "nohtml":
				//No html tags allowed
				if (is_array($var)) {
					array_walk_recursive($var,array('self','arrayNoHtmlFilter'));
				} else {
					$var = kses(strip_tags($var),array());
				}
				break;
			case "addslashes":
				$var = addslashes($var);
			break;
			//Custom or none
			default:
				$var = ($filter!==false) ? preg_replace("`".$filter."`i","",$var) : $var ;
				break;
		}
		
		if ($encode===true) {
			if (is_array($var)) {
				array_walk_recursive($var,array('self','arrayHtmlSpecialCharsOutput'));
				return $var;
			} else {
				return htmlspecialchars($var,ENT_QUOTES,'UTF-8');
			}
		} else {
			return $var;
		}
	}

	static function arrayStripslashes(&$value,$key) {
		$value = stripslashes($value);
	}
	static function arrayNoHtmlFilter(&$value,$key) {
		$value = kses(strip_tags($value),array());
	}
	static function arrayPublicHtmlFilter(&$value,$key) {
		require_once(_PATH_LIBRARIES._DS."kses"._DS."allowed.php");
		$value = kses(strip_tags($value),$config_sys['allowed_tags_public']);
	}
	static function arrayFullHtmlFilter(&$value,$key) {
		require_once(_PATH_LIBRARIES._DS."kses"._DS."allowed.php");
		$value = kses(strip_tags($value),array($config_sys['allowed_tags_advanced']));
	}
	
	static function Output($var,$encode=false) {
		//Note: Convert data if no filter (and no encoding) has been applied on input
		if ($encode==="int") $var = intval($var);
		if ($encode===true) {
			if (is_array($var)) {
				array_walk_recursive($var,array('self','arrayHtmlSpecialCharsOutput'));
				return $var;
			} else {
				return htmlspecialchars($var,ENT_QUOTES,'UTF-8');
			}
		} else {
			return $var;
		}
	}
	
	static function arrayHtmlSpecialCharsOutput(&$value,$key) {
		$value = htmlspecialchars($value,ENT_QUOTES,'UTF-8');
	}
}

?>