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

$limit = Utils::GetComOption("blog","blk_arch_lim",10);
if ($result = $Db->GetList("SELECT YEAR(created) AS year, MONTH(created) AS month, COUNT(id) AS tot FROM #__blog_posts WHERE status='published' GROUP BY year DESC, month DESC LIMIT $limit")) {

	//Controller-name match
	$plugmatch = Ram::Get("plugmatch");
	$plugname = isset($plugmatch['blog']) ? $plugmatch['blog'] : "blog" ;

	foreach ($result as $row) {
		$year 	= intval($row['year']);
		$month 	= intval($row['month']);
		$tot 	= intval($row['tot']);

		echo "<div class='std_a_list'><a href='index.php?"._NODE."=$plugname&amp;op=archive&amp;year=$year&amp;month=$month' title='".CleanTitleAtr(Utils::NumToMonth($month)." $year ($tot)")."'>".Utils::NumToMonth($month)." $year ($tot)</a></div>\n";
	}
}

?>