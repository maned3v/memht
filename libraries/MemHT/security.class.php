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

class BaseSecurity {
	//Verify access
	function __construct() {
		global $Db,$User,$config_sys,$Ext;

		$ip		= Utils::Ip2num($User->Ip());
		$uid	= ($User->IsUser()) ? $User->Uid() : 0 ;
		$uidchk	= (($uid>0)) ? "uid=".intval($uid)." OR " : "" ;

		if ($row = $Db->GetRow("SELECT * FROM #__banned FORCE INDEX(iit) WHERE {$uidchk}((iprange=0 AND ip='".$Db->_e($ip)."') OR (iprange=1 AND '".$Db->_e($ip)."' BETWEEN ip and toip)) ORDER BY id DESC LIMIT 1")) {
			//Visitor banned
			$expire		= Io::Output($row['expire']);
			$reason		= Io::Output($row['reason']);
			$author		= Io::Output($row['author'],"int");
			$bandate	= Io::Output($row['bandate']);
			$db_ip		= Io::Output($row['ip']);

			if ($uid>0 && $ip!=$db_ip) {
				//Ban the new ip address (user logged in with different ip address)
				$Db->Query("INSERT INTO #__banned (uid,ip,iprange,expire,reason,author,bandate)
							VALUES ('".intval($uid)."','".$Db->_e($ip)."','0','".$Db->_e($expire)."','User (".intval($uid).") rebanned with a new ip address.','".intval($author)."',NOW())");
				MemErr::StoreLog("sys_ban","User (".intval($uid).") rebanned with a new ip address: ".Utils::Num2Ip($ip));
			}

			//Show ban screen
			$Ext->RunMext("SecurityBanMessage");
			die("You have been banned!");
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Security")){class Security extends BaseSecurity{}}

//Security filter
$Security = new Security();

?>