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

//Ip address
function GetIp() {	
	$ip = Io::GetServer("HTTP_CLIENT_IP","nohtml");
	if (empty($ip)) {
		$ip = Io::GetServer("HTTP_X_FORWARDED_FOR","nohtml");
		if (empty($ip)) {
			$ip = Io::GetServer("REMOTE_ADDR","nohtml");
		}
	}
	$ip = str_replace("::1","127.0.0.1",$ip);
	if (MB::stripos($ip,",")) {
		$ip = explode(",",$ip);
		$ip = (isset($ip[0])) ? $ip[0] : false ;
	}
	return (Utils::ValidIp($ip)) ? $ip : "0.0.0.0" ;
}
$Visitor['ip'] = GetIp();

//Query
if (isset($_SERVER['QUERY_STRING'])) { $querystring = Io::GetServer('QUERY_STRING','nohtml');
} else if (isset($_SERVER['argv'])) { $querystring = Io::GetServer('argv','nohtml');
} else { $querystring = false; }
$Visitor['query_string'] = $querystring;
if (is_array($Visitor['query_string'])) $Visitor['query_string'] = $Visitor['query_string'][0];

//Requested uri
$Visitor['request_uri'] = Io::GetServer('REQUEST_URI','nohtml');
$Visitor['request_uri'] = (!empty($Visitor['request_uri'])) ? $Visitor['request_uri'] : false ;

//Agent
$Visitor['user_agent'] = Io::GetServer('HTTP_USER_AGENT','nohtml');

//Referer
$Visitor['referer'] = (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['HTTP_REFERER'])) ? (!preg_match("#".$_SERVER['HTTP_HOST']."#i",$_SERVER['HTTP_REFERER']) ? Io::GetServer('HTTP_REFERER','nohtml') : false ) : false ;

//Mobile
include_once(_PATH_LIBRARIES._DS."MobileDetect"._DS."Mobile_Detect.php");
$mobiledetect = new Mobile_Detect();
$Visitor['mobile']['is_mobile'] = ($mobiledetect->isMobile()) ? true : false ;
$Visitor['mobile']['is_tablet'] = ($mobiledetect->isTablet()) ? true : false ;
if ($Visitor['mobile']['is_mobile']) {
	$Visitor['mobile']['is_android'] = ($mobiledetect->isAndroidOS()) ? true : false ;
	$Visitor['mobile']['android']['version'] = $mobiledetect->version('Android');
	
	$Visitor['mobile']['is_ios'] = ($mobiledetect->isiOS()) ? true : false ;
	$Visitor['mobile']['ios']['iphone']['version'] = $mobiledetect->version('iPhone');
	$Visitor['mobile']['ios']['ipad']['version'] = $mobiledetect->version('iPad');
}

//Security: Bad queries //TODO: ADD ENABLE/DISABLE OPTION
$blacklist_query = array('#http://#i','#&cmd#i','#\?cmd#i','#\#exec#i','#/bin/#i','#&\##i','#javascript:#i','#<#i','#>#i','#%3C#i','#%3E#i',
						 '#\+union\+#i','#%20union%20#i','#\*/union/\*#i','#\+outfile\+#i','#%20outfile%20#i','#\*/outfile/\*#i');
foreach ($blacklist_query as $elm) if (preg_match($elm,$Visitor['query_string'])) die('Illegal operation!');

//Security: Bad user agents //TODO: ADD ENABLE/DISABLE OPTION
$blacklist_agent = array('#libwww-perl#i','#MiniRedir#i');
foreach ($blacklist_agent as $elm) if (preg_match($elm,$Visitor['user_agent'])) die('Access denied!');

//Security: Deny POSTs from external hosts //TODO: ADD ENABLE/DISABLE OPTION
$Visitor['request_method'] = MB::strtoupper(Io::GetServer('REQUEST_METHOD','nohtml'));
if ($Visitor['request_method']=="POST" && isset($_SERVER['HTTP_REFERER'])) if (!preg_match("#".$_SERVER['HTTP_HOST']."#i",$_SERVER['HTTP_REFERER'])) die('Illegal operation!');

?>