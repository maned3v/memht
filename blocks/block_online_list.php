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

global $Db,$User;

$limit = Utils::GetComOption("block","online",10);
if ($result = $Db->GetList("SELECT o.ip,o.uid,o.guest,o.date,u.name FROM #__online AS o LEFT JOIN #__user AS u ON o.uid=u.uid ORDER BY o.guest,o.date DESC LIMIT $limit")) {

	//Controller-name match
	$plugmatch = Ram::Get("plugmatch");
	$plugname = isset($plugmatch['user']) ? $plugmatch['user'] : "user" ;

	foreach ($result as $row) {
		$ip 	= Utils::Num2ip(Io::Output($row['ip']));
		$uid 	= Io::Output($row['uid'],"int");
		$guest	= Io::Output($row['guest'],"int");
		$date 	= Time::Output(Io::Output($row['date']),"t");
		$name 	= Io::Output($row['name']);

		//TODO: Hide me option in profile? Global admin hide option in config.
		//TODO: Add scroll bars if the list is too long
		echo "<div class='std_a_list'>".(($guest) ? ($User->IsAdmin() ? "<a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>$ip</a>" : _t("GUEST")) : "<a href='index.php?"._NODE."=$plugname&amp;op=info&amp;uid=$uid' title='".CleanTitleAtr($name)."'>$name</a>")."</div>\n";
}
}

?>