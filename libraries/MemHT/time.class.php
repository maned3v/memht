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

class Time {
	static function Output($datetime=false,$element="dt",$separator=" ",$gmt=false) {
		global $config_sys,$User;
		
		if ($datetime===false) $datetime = _DB_DATETIME;
		
		//Db -> GMT
		$datetime = strtotime(Hours2minutes($config_sys['dbserver_timezone'])*(-1).' minutes',strtotime($datetime));
		$datetime = date('Y-m-j H:i:s',$datetime);
		
		$datestamp = $User->GetOption("datestamp",$config_sys['default_datestamp']);
		$timestamp = $User->GetOption("timestamp",$config_sys['default_timestamp']);
		$timezone  = $User->GetOption("timezone" ,0);
		
		switch ($element) {
			case "dt": //Date + Time
				$output = $datestamp.$separator.$timestamp;
				break;
			case "d": //Date
				$output = $datestamp;
				break;
			case "t": //Time
				$output = $timestamp;
				break;
			default: //Custom
				$output = $element;
				break;
		}
		
		//GMT -> Output
		if ($gmt!==false) $timezone = 0;
		if (MB::strstr($output,'%')===false) { $output = "%A %d %b %Y %H:%M"; }
		return utf8_encode(strftime($output,strtotime(Hours2minutes($timezone).' minutes',strtotime($datetime))));
	}

	static function DateEmpty($date) {
		return (empty($date) || preg_match("#0000-00-00#i",$date) || preg_match("#1970#i",$date)) ? true : false ;
	}
}

?>