<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2013 by Miltenovikj Manojlo <dev@miltenovik.com>
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

//Get configuration data
$result = $Db->GetList("SELECT label,value FROM #__configuration ORDER BY label");
foreach ($result as $row) $config_sys[Io::Output($row['label'])] = Io::Output($row['value']);

//Fill missing configuration fields
include_once(_PATH_INCLUDES._DS."config_sys.fields.php");
$config_sys = array_merge($config_sys_fields,$config_sys);

//System constants
define("_NODE",$config_sys['node']);
define("_COOKIEPATH",preg_replace("`http://[^/]+`i","",$config_sys['site_url'])."/");
define("_BREADCRUMB_SEPARATOR",$config_sys['breadcrumb_separator']);
define("_SITETITLE_SEPARATOR",$config_sys['site_title_separator']);

if (isset($config_sys['use_ssl_admincp']) && $config_sys['use_ssl_admincp'] && defined("_ADMINCP")) {
	$config_sys['site_url'] = str_replace("http:","https:",$config_sys['site_url']);
}
if (isset($config_sys['use_ssl']) && $config_sys['use_ssl']) {
	$config_sys['site_url'] = str_replace("http:","https:",$config_sys['site_url']);
}

define("_SITEURL",$config_sys['site_url']);
define("_VERSION",$config_sys['engine_version']);

//Database server datetime
$row = $Db->GetRow("SELECT NOW() AS datetime");
$db_datetime = Io::Output($row['datetime']); //2009-10-12 19:12:42
$dt = explode(" ",$db_datetime);
define("_DB_DATETIME",$db_datetime);
define("_DB_DATE",$dt[0]);
define("_DB_TIME",$dt[1]);

//GMT datetime
$gmt_datetime = strtotime(Hours2minutes($config_sys['dbserver_timezone'])*(-1).' minutes',strtotime($db_datetime));
$gmt_datetime = date('Y-m-d H:i:s',$gmt_datetime);
$gmt_dt = explode(" ",$gmt_datetime);
define("_GMT_DATETIME",$gmt_datetime);
define("_GMT_DATE",$gmt_dt[0]);
define("_GMT_TIME",$gmt_dt[1]);

?>