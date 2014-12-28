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

header('Content-type: text/html; charset=utf-8');

define("_DS","/");
define("_PATH_ROOT",dirname(__FILE__));
define("_PATH_INCLUDES",_PATH_ROOT._DS."includes");
define("_LOAD",true);

//Loat time (start)
list($usec,$sec) = explode(" ",microtime());
$starttime = ((float)$usec+(float)$sec);

//Initialize environment
require_once(_PATH_INCLUDES._DS."init.inc.php");

//Output compression
if ($config_sys['output_compression']==1 && $config_sys['debug']==0) Utils::StartBuffering("ob_gzhandler");

//Nice & SEO urls + Site title
Utils::StartBuffering( ($config_sys['nice_seo_urls']==1) ? "sys_rewrite_full" : "sys_rewrite_core" );

//Content router
$Router = new Router();
$Router->Run();

//Flush buffer and turn it off
if (ob_get_status()) ob_end_flush();

$Ext->RunMext("Index");

//DEBUG
if ($User->IsAdmin() && $config_sys['debug']==1) {
    Utils::Debug(Error::GetLog()); //TODO FIX: Get log from DB, should give errors of the session
    Utils::Debug($Db->GetQueries());
    Utils::Debug($_GET);
    Utils::Debug(Ram::GetAll());
    Utils::Debug($Visitor);
}

?>