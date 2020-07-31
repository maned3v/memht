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

//Directories
define("_PATH_PLUGINS",_PATH_ROOT._DS."plugins");
define("_PATH_ADMINISTRATION",_PATH_ROOT._DS."admin");
define("_PATH_BLOCKS",_PATH_ROOT._DS."blocks");
define("_PATH_EXTENSIONS",_PATH_ROOT._DS."extensions");
define("_PATH_INSTALLATION",_PATH_ROOT._DS."installation");
define("_PATH_LANGUAGES",_PATH_ROOT._DS."languages");
define("_PATH_LIBRARIES",_PATH_ROOT._DS."libraries");
define("_PATH_TEMPLATES",_PATH_ROOT._DS."templates");

//AdminCP
define("_PATH_ACP_LIBRARIES",_PATH_ROOT._DS."admin"._DS."libraries");
define("_PATH_ACP_TEMPLATES",_PATH_ROOT._DS."admin"._DS."templates");

//Files
define("_PATH_CONFIGFILE",_PATH_ROOT._DS."includes"._DS."config.inc.php");
define("_PATH_SYSCONFIGFILE",_PATH_ROOT._DS."includes"._DS."config_sys.inc.php");

$config_sys['timezone_list'] = array(
      "-12:00"	=>	"(GMT -12:00) Eniwetok, Kwajalein",
      "-11:00"	=>	"(GMT -11:00) Midway Island, Samoa",
      "-10:00"	=>	"(GMT -10:00) Hawaii",
      "-9:00"	=>	"(GMT -9:00) Alaska",
      "-8:00"	=>	"(GMT -8:00) Pacific Time (US &amp; Canada)",
      "-7:00"	=>	"(GMT -7:00) Mountain Time (US &amp; Canada)",
      "-6:00"	=>	"(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
      "-5:00"	=>	"(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
      "-4:00"	=>	"(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
      "-3:30"	=>	"(GMT -3:30) Newfoundland",
      "-3:00"	=>	"(GMT -3:00) Brazil, Buenos Aires, Georgetown",
      "-2:00"	=>	"(GMT -2:00) Mid-Atlantic",
      "-1:00"	=>	"(GMT -1:00 h) Azores, Cape Verde Islands",
      "+0:00"	=>	"(GMT) Western Europe Time, London, Lisbon, Casablanca",
      "+1:00"	=>	"(GMT +1:00 h) Brussels, Copenhagen, Madrid, Paris",
      "+2:00"	=>	"(GMT +2:00) Kaliningrad, South Africa",
      "+3:00"	=>	"(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
      "+3:30"	=>	"(GMT +3:30) Tehran",
      "+4:00"	=>	"(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
      "+4:30"	=>	"(GMT +4:30) Kabul",
      "+5:00"	=>	"(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
      "+5:30"	=>	"(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
      "+5:45"	=>	"(GMT +5:45) Kathmandu",
      "+6:00"	=>	"(GMT +6:00) Almaty, Dhaka, Colombo",
      "+7:00"	=>	"(GMT +7:00) Bangkok, Hanoi, Jakarta",
      "+8:00"	=>	"(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
      "+9:00"	=>	"(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
      "+9:30"	=>	"(GMT +9:30) Adelaide, Darwin",
      "+10:00"	=>	"(GMT +10:00) Eastern Australia, Guam, Vladivostok",
      "+11:00"	=>	"(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
      "+12:00"	=>	"(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"
);

if (version_compare(PHP_VERSION, '6.0.0') >= 0) {
	if (!function_exists("get_magic_quotes_gpc")) {
		function get_magic_quotes_gpc(){
			return false;
		}
	}
}


?>