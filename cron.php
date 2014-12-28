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
define("_CRON",true);

require_once(_PATH_INCLUDES._DS."defines.inc.php");
@include_once(_PATH_CONFIGFILE); //Database configuration

if (!defined("_INSTALLED") || filesize(_PATH_CONFIGFILE) < 100) {
	exit;
}

require_once(_PATH_LIBRARIES._DS."MemHT"._DS."io.class.php"); //Input and output management
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."mb.class.php"); //Multibyte support for UTF-8
MB::Initialize();
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."utils.class.php"); //Misc utilities
require_once(_PATH_INCLUDES._DS."scout.inc.php"); //Scout
require_once(_PATH_INCLUDES._DS."common.inc.php"); //Common
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."error.class.php"); //Error handler
require_once(_PATH_INCLUDES._DS."ram.inc.php"); //Memory
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."extensions.class.php"); //Load extensions
require_once(_PATH_INCLUDES._DS."db.inc.php"); //Create database connection [Ext,DbLayer]
Utils::LoadComOptions(); //Load options
require_once(_PATH_SYSCONFIGFILE); //Site configuration
if (!$config_sys['cronjobs']) die();
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."maintenance.class.php"); //Site maintenance [Ext,Maintenance]

?>