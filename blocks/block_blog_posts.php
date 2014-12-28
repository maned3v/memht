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

$limit = Utils::GetComOption("blog","blk_posts_lim",10);
if ($result = $Db->GetList("SELECT p.title,p.name,c.title AS ctitle,c.name AS cname FROM #__blog_posts AS p FORCE INDEX(created) JOIN #__blog_categories AS c ON p.category=c.id WHERE p.status='published' ORDER BY p.created DESC LIMIT $limit")) {

	//Controller-name match
	$plugmatch = Ram::Get("plugmatch");
	$plugname = isset($plugmatch['blog']) ? $plugmatch['blog'] : "blog" ;

	foreach ($result as $row) {
		$title 	= Io::Output($row['title']);
		$name 	= Io::Output($row['name']);
		$ctitle = Io::Output($row['ctitle']);
		$cname 	= Io::Output($row['cname']);

		echo "<div class='std_a_list'><a href='index.php?"._NODE."=$plugname&amp;cat=$cname&amp;title=$name' title='".CleanTitleAtr($title)."'>$title</a></div>\n";
	}
}

?>