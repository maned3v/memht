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

global $Db;

$limit = Utils::GetComOption("articles","blk_art_lim",10);
if ($result = $Db->GetList("SELECT a.title,a.name,a.created,c.title AS ctitle,c.name AS cname,c.section AS sid FROM #__articles AS a JOIN #__articles_categories AS c ON a.category=c.id WHERE a.status='published' ORDER BY a.created DESC LIMIT $limit")) {
	$sids = array();
	foreach ($result as $row) {
		$sid = Io::Output($row['sid'],"int");
		$sids[$sid] = $sid;
	}
	$sresult = $Db->GetList("SELECT id,name,title FROM #__articles_sections WHERE id IN (".implode(",",$sids).")");
	$sids = array();
	foreach ($sresult as $srow) $sids[Io::Output($srow['id'],"int")] = array(Io::Output($srow['name']),Io::Output($srow['title']));
	
	//Controller-name match
	$plugmatch = Ram::Get("plugmatch");
	$plugname = isset($plugmatch['articles']) ? $plugmatch['articles'] : "articles" ;

	foreach ($result as $row) {
		$title 	= Io::Output($row['title']);
		$name 	= Io::Output($row['name']);
		$ctitle = Io::Output($row['ctitle']);
		$cname 	= Io::Output($row['cname']);
		$sid	= Io::Output($row['sid'],"int");
		$sname 	= $sids[$sid][0];
		$stitle = $sids[$sid][1];
		$created_o	= Io::Output($row['created']);

		//Split creation date
		$cdate = explode(" ",$created_o);
		$cdate = explode("-",$cdate[0]);
		$cmonth = intval($cdate[1]);
		$cyear = $cdate[0];

		echo "<div class='std_a_list'><a href='index.php?"._NODE."=$plugname&amp;sec=$sname&amp;cat=$cname&amp;year=$cyear&amp;month=$cmonth&amp;title=$name' title='".CleanTitleAtr($title)."'>$title</a></div>\n";
	}
}

?>