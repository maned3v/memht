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

class BaseCaptcha {
	static function Display() {
		global $config_sys;
		if ($config_sys['captcha']==0 || !@extension_loaded('gd')) return true;
		
		echo "<div>\n";
			echo "<div style='float:left; padding-right:5px;'>\n";
				echo "<img src='index.php?"._NODE."=captcha&amp;c=".mt_rand()."' alt='Captcha' title='"._t("TYPE_CHARS_YOU_SEE")."' />\n";
			echo "</div>\n";
			echo "<div>\n";
				echo "<input name='ccap' type='text' style='margin:0 0 2px 0; width:100px;' />\n";
				echo "<div style='font-size:10px;'>"._t("TYPE_CHARS_YOU_SEE")."</div>\n";
			echo "</div>\n";
		echo "</div>\n";
	}
	
	static function Check($return=false) {
		global $User,$config_sys;
		if ($config_sys['captcha']==0 || !@extension_loaded('gd')) return true;
		if ($User->IsAdmin()) return true;
		
		$ccap = MB::strtolower(Io::GetVar("POST","ccap","[^a-zA-Z0-9]"));
		$fcap = Io::GetSession("fcap","[^a-zA-Z0-9]");
		Io::SetSession("fcap",$ccap);
		
		if (!empty($ccap) && !empty($fcap) && md5($ccap.$config_sys['uniqueid'])==$fcap) {
			return true;
		} else if ($return) {
			return _t("WRONG_CAPTCHA_TEXT");
		} else {
			MemErr::Trigger("USERERROR",_t("WRONG_CAPTCHA_TEXT"));
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Captcha")){class Captcha extends BaseCaptcha{}}

?>