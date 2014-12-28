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

require_once(_PATH_INCLUDES._DS."defines.inc.php");
@include_once(_PATH_CONFIGFILE); //Database configuration

if (!defined("_INSTALLED") || filesize(_PATH_CONFIGFILE) < 100) {
	if (file_exists("installation"._DS."install.php")) {
		header('Location: installation'._DS.'install.php');
		exit;
	} else {
		die("The installation cannot be started");
	}
}

if (@$config_sys['debug']==1) {
    //Debug-Development mode
    error_reporting(E_ALL ^ E_STRICT);
    @ini_set('error_reporting', 'on');
} else {
    //Production mode
    error_reporting(0);
    @ini_set('error_reporting', 'off');
}
@ini_set('log_errors','1');

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
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."preloader.class.php"); //Preloader [Ext,Preloader]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."session.class.php"); //Sessions [Ext,SessionDb]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."time.class.php"); //Date and Time
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."user.class.php"); //User login engine [Ext,User]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."email.class.php"); //Email [Ext,Email]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."maintenance.class.php"); //Site maintenance [Ext,Maintenance]
define("_ERRHDL",true);
$handler = set_error_handler(array('Error','MemErrorHandler'));
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."avatar.class.php"); //Avatar [Ext,Avatar]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."security.class.php"); //Security filter [Ext,Security]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."i18n.class.php"); //Localization
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."router.class.php"); //Site router [Ext,MVCRouter]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."bbcode.class.php"); //BBcode
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."views.class.php"); //Output views [Ext,MVCViews]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."modrewrite.class.php"); //Nice & SEO urls
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."template.class.php"); //Template engine
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."captcha.class.php"); //Security captcha [Ext,Captcha]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."form.class.php"); //Forms and Text editor [Ext,Form]
require_once(_PATH_INCLUDES._DS."layout.inc.php"); //Site layout structure

//Site in maintenance?
if ($config_sys['maintenance']==1 && !$User->IsAdmin() && $User->Ip()!=Utils::Num2ip($config_sys['maintenance_whiteip'])) Utils::ShowMaintenanceTemplate($config_sys['maintenance_message']);

//Site title step
Utils::AddTitleStep($config_sys['site_name']);

?>