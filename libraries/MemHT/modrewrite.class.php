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

function sys_rewrite_full($string) {
	$string = preg_replace_callback('#href=([\'|"])index.php\?([^\'|"]+)([\'|"])#is','_sys_modrewrite',sys_rewrite_core($string));
	
	//TODO: HTML code minimization???
	//$string = preg_replace("#\n|\r|\n\r#","",$string);
	//$string = preg_replace("#\s{2,}#","",$string);
	
	return $string;
}

function _sys_modrewrite($matches) {
	global $config_sys;
	
	$anchor = preg_match("#\#[a-zA-Z0-9]+#is",$matches[2],$ancmatch) ? $ancmatch[0] : "" ;
	$matches[2] = str_replace("&amp;","&",$matches[2]);
	$pieces = explode("&",preg_replace("#\#[a-zA-Z0-9]+#is","",$matches[2],-1,$count));
	foreach ($pieces as $piece) $rewritten[] = preg_replace("#[a-zA-Z0-9]+=#is","",$piece);
	$rewritten = implode($config_sys['nice_seo_urls_separator'],$rewritten);
	$suffix = ($rewritten!="index.php") ? $config_sys['nice_seo_urls_suffix'] : "";
	return "href=".$matches[1].$config_sys['site_url']."/".$rewritten.$suffix.$anchor.$matches[3];
}

function sys_rewrite_core($string) {
	global $config_sys;
	
	$st = Ram::Get('sys_title');
	if ($config_sys['site_title_order']=="ASC") $st = array_reverse($st);	
	return (sizeof($st)>0) ? str_replace("<title>".$config_sys['site_name']."</title>","<title>".implode(" "._SITETITLE_SEPARATOR." ",$st)."</title>",$string) : $string ;
}

//Manual rewrite
function RewriteUrl($url) {
	global $config_sys;
	
	if ($url=="index.php") return $config_sys['site_url'];
	if ($config_sys['nice_seo_urls']==0) return $url; //ModRewrite inactive
	if ($config_sys['site_url']==$url || $config_sys['site_url']."/index.php"==$url) return $url; //No rewrite needed

	preg_match('#index.php\?([^\'|"]+)#is',$url,$match);
	$url = explode(preg_match("#&amp;#is",$match[1]) ? "&amp;" : "&" ,$match[1]);
	$rewritten = array();
	foreach ($url as $piece) $rewritten[] = preg_replace("#[a-zA-Z0-9]+=#is","",$piece);
	$url = implode($config_sys['nice_seo_urls_separator'],$rewritten);
	$url .= ($rewritten!="index.php") ? $config_sys['nice_seo_urls_suffix'] : "";
	return $config_sys['site_url']."/".$url;
}

?>