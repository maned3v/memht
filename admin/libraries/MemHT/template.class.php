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
defined("_ADMINCP") or die("Access denied");

include_once(_PATH_LIBRARIES._DS."Smarty"._DS."Smarty.class.php");

class Template {
	static function Initialize() {
		global $tpl,$config_sys;

		//DO NOT CACHE THE TEMPLATE
		$tpl = new Smarty;
		$tpl->template_dir = _PATH_ACP_TEMPLATES;
		$tpl->compile_dir = _PATH_TEMPLATES._DS."compiled";
		//$tpl->debugging = true;
		$tpl->compile_check = true;
	}

	static function AssignVar($var,$content) {
		global $tpl;

		//Assign variable to template
		$tpl->assign($var,$content);
	}

	static function Draw($file="home.acp") {
		global $tpl,$Router,$config_sys;

		//Draw template
		$tpl->display($config_sys['template']._DS.$file.".tpl");
	}
}

//Template configuration
$config_sys['template'] = $config_sys['admincp_template'];

//Load template
Template::Initialize();

Template::AssignVar("sys_node",_NODE);
Template::AssignVar("sys_site_name",$config_sys['site_name']);
Template::AssignVar("sys_site_url",$config_sys['site_url']);
Template::AssignVar("sys_memht",base64_decode("UG93ZXJlZCBieSA8YSBocmVmPSdodHRwOi8vd3d3Lm1lbWh0LmNvbScgdGl0bGU9J01lbUhUJyByZWw9J2V4dGVybmFsJz5NZW1IVDwvYT4="));
Template::AssignVar("sys_template",$config_sys['template']);
Template::AssignVar("sys_copyright",$config_sys['copyright']);

//User
Template::AssignVar("sys_user",array("name"		=> $User->Name(false,true),
									 "role"		=> $User->GetRole(),
									 "rolename"	=> $User->GetRoleName(),
									 "isadmin"	=> intval($User->IsAdmin())));

//Events
$row = $Db->GetRow("SELECT COUNT(id) AS tot FROM #__log");
$events = Io::Output($row['tot'],"int");

//Comments
$row = $Db->GetRow("SELECT COUNT(id) AS tot FROM #__comments WHERE status='waiting'");
$events += Io::Output($row['tot'],"int");

//Waiting users
$row = $Db->GetRow("SELECT COUNT(uid) AS tot FROM #__user WHERE status='moderate'");
$waitusers = Io::Output($row['tot'],"int");

$Ext->RunMext("AdminCP_Notifications",array(&$events));

Template::AssignVar("sys_events",$events);
Template::AssignVar("sys_waitusers",$waitusers);

//AdminCP menus
//Thanks to Paulo Ferreira for the idea and the original code
$result = $Db->GetList("SELECT * FROM #__menu_acp WHERE status='active' ORDER BY menu ASC, submenu ASC, title ASC");
$acpmenu = array();
foreach ($result as $row) {
	$title		= Io::Output($row['title']);
	$url		= Io::Output($row['url']);
	$icon		= Io::Output($row['icon']);
	$menu		= Io::Output($row['menu']);
	$submenu	= Io::Output($row['submenu'],"int");

	if ($submenu==0) {
		//Main menu
		$acpmenu[$menu]['main'] = array("title"	=> $title,
										"url"	=> "javascript:void(0);",
										"icon"	=> $icon);
	} else {
		//Submenu
		$acpmenu[$menu]['sub'][] = array("title"=> $title,
										 "url"	=> $url,
										 "icon"	=> $icon);
	}
}
Template::AssignVar("sys_acpmenu",$acpmenu);

?>