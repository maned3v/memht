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

class BaseStats {
	public function Run() {
		global $config_sys,$Ext;

		if (defined("_INTERNAL")) return;
		if ($config_sys['statistics']==0) return;
		
		$this->Visitors();
		if ($config_sys['statistics_full']==1) {
			$this->Pages();
			$Ext->RunMext("Stats");
		}
	}
	public function Visitors() {
		global $Db;

		$uniqvis = (Session::Read("muv")===false) ? ",uniqvis=uniqvis+1" : "" ;
		Session::Write("muv",_DB_DATE);

		$Db->Query("INSERT INTO #__stats_hits (date,hits,uniqvis)
					VALUES (NOW(),1,1)
					ON DUPLICATE KEY UPDATE hits=hits+1".$uniqvis);
	}
	public function Pages() {
		global $Db,$config_sys,$Visitor;

		if (!_ISHOME) {
			$Db->Query("INSERT INTO #__stats_pages (date,page,hits,uniqueid)
						VALUES (NOW(),'".$Db->_e(_PLUGIN)."',1,'".MB::substr(md5(_DB_DATE._PLUGIN),0,10)."')
						ON DUPLICATE KEY UPDATE hits=hits+1");
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Stats")){class Stats extends BaseStats{}}

$Stats = new Stats;
$Stats->Run();

?>