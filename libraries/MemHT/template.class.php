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

include_once(_PATH_LIBRARIES._DS."Smarty"._DS."Smarty.class.php");

class Template {
	static function Initialize() {
		global $tpl,$config_sys;

		//DO NOT CACHE THE TEMPLATE
		$tpl = new Smarty();
		$tpl->template_dir = _PATH_TEMPLATES;
		$tpl->compile_dir = _PATH_TEMPLATES._DS."compiled";
		//$tpl->debugging = true;
		$tpl->compile_check = true;
		//$tpl->testInstall(); //version 3.1 +
	}

	static function AssignVar($var,$content) {
		global $tpl;

		//Assign variable to template
		$tpl->assign($var,$content);
	}

	static function Draw($file="home") {
		global $tpl,$Router,$config_sys;

		//Breadcrumbs path
		Template::AssignVar("sys_breadcrumbs",implode(_BREADCRUMB_SEPARATOR,$Router->breadcrumbs));

		//Draw template
		try {
			$tpl->display($config_sys['template']._DS.$file.".tpl");
		} catch (Exception $e) {
			Error::Trigger("WARNING","Template Exception",$e->getMessage());
		}
	}
}

//Template configuration
if ($config_sys['lock_template']) {
	$config_sys['template'] = $config_sys['default_template'];
	if ($Visitor['mobile']['is_mobile']) {
		$config_sys['template'] = $config_sys['default_mobiletemplate'];
	}
} else {
	$cookie_tpl = Io::GetCookie("template","[^a-zA-Z0-9_]");
	$config_sys['template'] = (!empty($cookie_tpl)) ? $cookie_tpl : $config_sys['default_template'];
	
	if ($User->IsUser()) {
		$user_tpl = $User->GetOption('template');
		$config_sys['template'] = (!empty($user_tpl) && file_exists(_PATH_TEMPLATES._DS.$user_tpl._DS."home.tpl")) ? $user_tpl : $config_sys['template'];
	}
}

//Load template
Template::Initialize();

Template::AssignVar("sys_node",_NODE);
Template::AssignVar("sys_site_name",$config_sys['site_name']);
Template::AssignVar("sys_site_url",$config_sys['site_url']);
Template::AssignVar("sys_memht",base64_decode("UG93ZXJlZCBieSA8YSBocmVmPSdodHRwOi8vd3d3Lm1lbWh0LmNvbScgdGl0bGU9J01lbUhUJyByZWw9J2V4dGVybmFsJz5NZW1IVDwvYT4="));
Template::AssignVar("sys_template",$config_sys['template']);
Template::AssignVar("sys_copyright",$config_sys['copyright']);

//User
Template::AssignVar("sys_user",array("role"		=> $User->GetRole(),
									 "rolename"	=> $User->GetRoleName(),
									 "isuser"	=> intval($User->IsUser()),
									 "isadmin"	=> intval($User->IsAdmin())));

//Menu
$sys_menu = array();
$order_field = Utils::GetComOption("sys_menu","order_field","title");
$order_dir = Utils::GetComOption("sys_menu","order_dir","DESC");
$result = $Db->GetList("SELECT title,url,zone,roles FROM #__menu ORDER BY $order_field $order_dir");
foreach ($result as $row) {
	$title = Io::Output($row['title']);
	$url = Io::Output($row['url']);
	$zone = Io::Output($row['zone']);
	$roles = Utils::Unserialize(Io::Output($row['roles']));
	
	if ($User->CheckRole($roles) || $User->IsAdmin()) {
		$url = str_replace("{NODE}",_NODE,$url);
	
		$sys_menu[$zone][] = array(
			"url"	=> $url,
			"title"	=> $title
		);
	}
}
Template::AssignVar("sys_menu",$sys_menu);
unset($sys_menu);

//Controller-name match
$plugmatch = Ram::Get("plugmatch");
$advplugname = isset($plugmatch['adv']) ? $plugmatch['adv'] : "adv" ;

//Advertising: Categories
$sys_ids = array();
if ($result = $Db->GetList("SELECT id,label FROM #__adv_categories WHERE status='active' LIMIT 50")) {
	foreach ($result as $crow) {
		$cid	= Io::Output($crow['id'],"int");
		$label	= MB::strtolower(Io::Output($crow['label']));
		
		if ($row = $Db->GetRow("SELECT * FROM #__adv_banners WHERE status='active' AND NOW() BETWEEN start AND end
								AND (unlimited='yes' OR impressions < todo_imp OR clicks < todo_clicks) AND catadv='yes' AND cid='".intval($cid)."' ORDER BY RAND() LIMIT 1")) {
			$id			= Io::Output($row['id'],"int");
			$bname		= Io::Output($row['name']);
			$type		= MB::strtolower(Io::Output($row['type']));
			$img_path	= Io::Output($row['img_path']);
			$img_url	= Io::Output($row['img_url']);
			$content	= Io::Output($row['content']);
			$options	= Utils::Unserialize(Io::Output($row['options']));
			$roles		= Utils::Unserialize(Io::Output($row['roles']));
			
			//Show ads to selected roles only (and admins)
			if ($User->CheckRole($roles) || $User->IsAdmin()) {
				switch ($type) {
					case "imd":
						//Direct link
						$rel = array();
						if (isset($options['external']) || isset($options['nofollow'])) {
							$rel[] = " rel='";
							if (isset($options['external'])) { $rel[] = "external"; $ext = true; } else { $ext = false; }
							if (isset($options['nofollow'])) { if ($ext) { $rel[] = " "; } $rel[] = "nofollow"; }
							$rel[] = "'";
						}
						$rel = implode("",$rel);
						$content = "<a href='$img_url' title='".CleanTitleAtr($bname)."'{$rel}><img src='$img_path' alt='$label' /></a>";
						break;
					case "imi":
						//Internal link
						$content = "<a href='index.php?"._NODE."=$advplugname&amp;id=$id' title='".CleanTitleAtr($bname)."' rel='external nofollow'><img src='$img_path' alt='$label' /></a>";
						break;
				}
				
				//Do not count impressions generated by admins
				if (!$User->IsAdmin()) $sys_ids[] = $id;
				Ram::Set("sys_adv_".$label,$content);
				Template::AssignVar("sys_adv_".$label,$content);
			}
		}
	}
	if (sizeof($sys_ids)) {
		$Db->Query("UPDATE #__adv_banners SET impressions=impressions+1 WHERE id IN (".implode(",",$sys_ids).")");
	}
}
unset($sys_ids);

//Advertising: Single banners
$sys_ids = array();
if ($result = $Db->GetList("SELECT * FROM #__adv_banners WHERE NOW() BETWEEN start AND end AND (unlimited='yes' OR impressions < todo_imp OR clicks < todo_clicks) AND status='active' AND catadv='no' LIMIT 50")) {
	foreach ($result as $row) {
		$id			= Io::Output($row['id'],"int");
		$bname		= Io::Output($row['name']);
		$type		= MB::strtolower(Io::Output($row['type']));
		$label		= MB::strtolower(Io::Output($row['label']));
		$img_path	= Io::Output($row['img_path']);
		$img_url	= Io::Output($row['img_url']);
		$content	= Io::Output($row['content']);
		$options	= Utils::Unserialize(Io::Output($row['options']));
		$roles		= Utils::Unserialize(Io::Output($row['roles']));
		
		//Show ads to selected roles only (and admins)
		if ($User->CheckRole($roles) || $User->IsAdmin()) {
			switch ($type) {
				case "imd":
					//Direct link
					$rel = array();
					if (isset($options['external']) || isset($options['nofollow'])) {
						$rel[] = " rel='";
						if (isset($options['external'])) { $rel[] = "external"; $ext = true; } else { $ext = false; }
						if (isset($options['nofollow'])) { if ($ext) { $rel[] = " "; } $rel[] = "nofollow"; }
						$rel[] = "'";
					}
					$rel = implode("",$rel);
					$content = "<a href='$img_url' title='".CleanTitleAtr($bname)."'{$rel}><img src='$img_path' alt='$label' /></a>";
					break;
				case "imi":
					//Internal link
					$content = "<a href='index.php?"._NODE."=$advplugname&amp;id=$id' title='".CleanTitleAtr($bname)."' rel='external nofollow'><img src='$img_path' alt='$label' /></a>";
					break;
			}

			//Do not count impressions generated by admins
			if (!$User->IsAdmin()) $sys_ids[] = $id;
			Ram::Set("sys_adv_".$label,$content);
			Template::AssignVar("sys_adv_".$label,$content);
		}
	}
	if (sizeof($sys_ids)) {
		$Db->Query("UPDATE #__adv_banners SET impressions=impressions+1 WHERE id IN (".implode(",",$sys_ids).")");
	}
}
unset($sys_ids);

//Template Surveys
$token = Utils::GetComOption("surveys","token",1);
if ($result = $Db->GetList("SELECT * FROM #__surveys_questions WHERE type='TEMPLATE' AND status='active' LIMIT 50")) {
	//Controller-name match
	$plugmatch = Ram::Get("plugmatch");
	$surveysplugname = isset($plugmatch['surveys']) ? $plugmatch['surveys'] : "surveys" ;
	
	foreach ($result as $row) {
		$id			= Io::Output($row['id'],"int");
		$question	= Io::Output($row['question']);
		$label		= Io::Output($row['label']);
		$usecomments= Io::Output($row['usecomments']);
		$comments	= Io::Output($row['comments']);
		$roles		= Utils::Unserialize(Io::Output($row['roles']));
		
		if ($User->CheckRole($roles) || $User->IsAdmin()) {
			$content = "<form method='post' action='index.php?"._NODE."=$surveysplugname&amp;op=vote'>\n";
				$content .= "<div style='text-align:center;'><strong>$question</strong></div>\n";
				$result = $Db->GetList("SELECT * FROM #__surveys_answers WHERE surveyid='".intval($id)."' ORDER BY id");
				foreach ($result as $row) {
					$aid	= Io::Output($row['id'],"int");
					$answer = Io::Output($row['answer']);
					$content .= "<div style='padding:3px 0;'><input name='vote' type='radio' value='$aid' /> $answer</div>\n";
				}
				$content .= "<div style='padding:3px 0; text-align:center;'><input type='submit' name='Submit' value='"._t("SUBMITVOTE")."' /></div>\n";
				$content .= "<div style='text-align:center;'><a href='index.php?"._NODE."=$surveysplugname&amp;op=results&amp;id=$id' title='"._t("SURVEYRESULTS")."'>"._t("SURVEYRESULTS")."</a></div>\n";
				if ($usecomments) {
					$content .= "<div style='text-align:center;'><a href='index.php?"._NODE."=$surveysplugname&amp;op=results&amp;id=$id#comments' title='"._t("COMMENTS")."'>($comments "._t("COMMENTS").")</a></div>\n";
				}
				$content .= "<input type='hidden' name='id' value='$id' />\n";
				
				if ($token) {
					$tok = Utils::GenerateToken();
					$tok = explode(":",$tok);
					
					$content .= "<input name='ctok' type='hidden' value='".$tok[0]."' />\n";
					$content .= "<input name='ftok' type='hidden' value='".$tok[1]."' />\n";
				}
			$content .= "</form>\n";
		} else {
			$content = "";
		}
		Template::AssignVar("sys_survey_".$label,$content);
	}
}

//Stickers
$result = $Db->GetList("SELECT label,content,roles FROM #__stickers LIMIT 50");
foreach ($result as $row) {
	$label = Io::Output($row['label']);
	$content = Io::Output($row['content']);
	$roles = Utils::Unserialize(Io::Output($row['roles']));
	
	if (!$User->CheckRole($roles) && !$User->IsAdmin()) $content = "";
	Template::AssignVar("sys_sticker_".$label,$content);
}

//Blocks
$sys_blocks = array();
$result = $Db->GetList("SELECT * FROM #__blocks WHERE NOW() BETWEEN start AND end AND status='display' ORDER BY position LIMIT 50");
$_plugin = explode($config_sys['nice_seo_urls_separator'],Io::GetVar("GET",$config_sys['node'],false,true,$config_sys['default_home']));
$_plugin = $_plugin[0];
foreach ($result as $row) {
	$btitle		= Io::Output($row['title']);
	$blabel		= Io::Output($row['label']);
	$bshowtitle	= Io::Output($row['showtitle'],"int");
	$bcontent	= Io::Output($row['content']);
	$boptions	= Utils::Unserialize(Io::Output($row['options']));
	$broles		= Utils::Unserialize(Io::Output($row['roles']));
	
	if ($User->CheckRole($broles) || $User->IsAdmin()) {
		if ($bshowtitle==0) $btitle = "";
	
		$showincontent = true;
		if (isset($boptions['showincontent'])) { if (!@in_array($_plugin,$boptions['showincontent'])) $showincontent = false; }
		if (MB::strtolower(Io::Output($row['status']))=="display" && $showincontent) {
			$btype		= MB::strtolower(Io::Output($row['type']));
			$bzone		= MB::strtolower(Io::Output($row['zone']));
			$bfile		= Io::Output($row['file']);
	
			if ($btype=="file") {
				if (file_exists("blocks/block_$bfile.php")) {
					Utils::StartBuffering();
					include("blocks/block_$bfile.php");
					$bcontent = Utils::GetBufferContent("clean");
				} else {
					$bcontent = _t("FILE_NOT_FOUND");
				}
			}
	
			if ($bzone=="sticker") {
				Template::AssignVar("sys_sticker_".MB::strtolower($blabel),array("title"	=> $btitle,
																			 "content"	=> $bcontent,
																			 "options"	=> $boptions));
			} else {
				$sys_blocks[$bzone][] = array(
					"title"		=> $btitle,
					"content"	=> $bcontent,
					"options"	=> $boptions
				);
			}
		}
	}
}
Template::AssignVar("sys_blocks",$sys_blocks);
unset($sys_blocks);

?>