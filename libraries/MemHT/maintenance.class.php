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

class BaseMaintenance {
	public function Run() {
		global $Db,$config_sys;
		if ((strtotime($config_sys['maintenance_last'])+$config_sys['maintenance_pause']*60)<=strtotime(_DB_DATETIME)) {
			$Db->Query("UPDATE #__configuration SET value=NOW() WHERE label='maintenance_last'");
			
			$this->ChangeUniqueId();
			$this->CleanBanned();
			$this->CleanExpiredUsers();
			$this->CleanInvites();
			$this->CleanLog();
			$this->CleanStats();
			$this->OptimizeDb();
			$this->Additional();
		}
	}
	
	public function ChangeUniqueId() {
		global $Db;
		$Db->Query("UPDATE #__configuration SET value='".$Db->_e(Utils::GenerateRandomString(10))."' WHERE label='uniqueid'");
	}
	
	public function CleanBanned() {
		global $Db;
		$Db->Query("DELETE FROM #__banned WHERE expire < NOW()");
	}
	
	public function CleanExpiredUsers() {
		global $Db;
		$Db->Query("DELETE FROM #__user WHERE status='waiting' AND (regdate + INTERVAL 72 HOUR) < NOW()");
	}
	
	public function CleanInvites() {
		global $Db;
		$Db->Query("DELETE FROM #__user_invites WHERE registrations<=0 OR expiration < NOW()");
	}
	
	public function CleanLog() {
		global $Db;
		$Db->Query("DELETE FROM #__log WHERE label!='contact_message' AND (time + INTERVAL 7 DAY) < NOW()");
	}
	
	public function CleanStats() {
		global $Db;
		$Db->Query("DELETE FROM #__stats_hits WHERE (date + INTERVAL 1 MONTH) < NOW()");
		$Db->Query("DELETE FROM #__stats_pages WHERE (date + INTERVAL 1 WEEK) < NOW()");
	}
	
	public function OptimizeDb() {
		global $Db;
		$Db->Query("OPTIMIZE TABLE `#__log`,`#__online`,`#__options`,`#__stats_hits`,`#__stats_pages`,`#__user`");
	}
	
	public function Additional() {
		global $Ext;
		$Ext->RunMext("Maintenance");
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Maintenance")){class Maintenance extends BaseMaintenance{}}

if (!$config_sys['cronjobs'] OR defined("_CRON")) {
	$Maintenance = new Maintenance;
	$Maintenance->Run();
}

?>