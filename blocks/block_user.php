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

global $Db,$config_sys,$User;

//Controller-name match
$plugmatch = Ram::Get("plugmatch");
$plugname = isset($plugmatch['user']) ? $plugmatch['user'] : "user" ;

if ($User->IsUser()) {
	//Logged in
	echo "<div class='loggedin'>\n";
	echo _t("HI_X",$User->Name(false,true));
	
	$Ext->RunMext("UserBlockLogged");
	
	echo "<br /><a href='index.php?"._NODE."=$plugname&amp;op=logout' title='"._t("LOGOUT")."'>"._t("LOGOUT")."</a>";
	echo "</div>\n";
} else {
	//Guest
	echo "<div>\n";
		echo "<a href='index.php?"._NODE."=$plugname' title='"._t("LOGIN")."'>"._t("LOGIN")."</a> - <a href='index.php?"._NODE."=$plugname&amp;op=register' title='"._t("REGISTER")."'>"._t("REGISTER")."</a>";
	echo "</div>\n";
	
	$Ext->RunMext("UserBlockNotLogged");
	
	if ($config_sys['social_login']) {
		echo "<div style='margin-top:5px;'>\n";
			echo "<a href='index.php?"._NODE."=$plugname&op=social&engine=facebook' title='"._t("SOCIAL_LOGIN_WITH_FACEBOOK")."'><img src='images"._DS."social"._DS."facebook_32.png' /></a>";
			echo " <a href='#' title='"._t("SOCIAL_LOGIN_WITH_FACEBOOK")."'><img src='images"._DS."social"._DS."openid_32.png' /></a>";
		echo "</div>\n";
	}
}

?>