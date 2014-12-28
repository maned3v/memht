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

$result = $Db->GetList("SELECT a.title AS atitle,a.name AS aname,c.name AS cname, s.name AS sname, s.title AS stitle,a.created,a.modified
					   	FROM #__articles AS a FORCE INDEX(created) JOIN #__articles_categories AS c JOIN #__articles_sections AS s ON a.category=c.id AND s.id=c.section WHERE a.status='published' ORDER BY a.created DESC LIMIT 0,20");

//Controller-name match
$plugmatch = Ram::Get("plugmatch");
$plugname = isset($plugmatch['articles']) ? $plugmatch['articles'] : "articles" ;

if (defined("_XML")) {
	foreach ($result as $row) {
		$sname = Io::Output($row['sname']);
		$stitle = Io::Output($row['stitle']);
		$aname = Io::Output($row['aname']);
		$cname = Io::Output($row['cname']);
		$created_o = Io::Output($row['created']);
		$modified = Io::Output($row['modified']);
		
		$lastmod = Time::Output($modified==$created_o ? $created_o : $modified ,"%Y-%m-%d"," ",true);
		
		//Split creation date
		$cdate = explode(" ",$created_o);
		$cdate = explode("-",$cdate[0]);
		$month = intval($cdate[1]);
		$year = $cdate[0];
		
		echo "\t<url>\n";
			echo "\t\t<loc>".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$plugname&amp;sec=$sname&amp;cat=$cname&amp;year=$year&amp;month=$month&amp;title=$aname")."</loc>\n";
			echo "\t\t<lastmod>$lastmod</lastmod>\n";
			echo "\t\t<changefreq>weekly</changefreq>\n";
			echo "\t\t<priority>0.5</priority>\n";
		echo "\t</url>\n";
	}
} else {
	foreach ($result as $row) {
		$atitle = Io::Output($row['atitle']);
		$aname = Io::Output($row['aname']);
		$cname = Io::Output($row['cname']);
		$sname = Io::Output($row['sname']);
		$created_o = Io::Output($row['created']);
		
		//Split creation date
		$cdate = explode(" ",$created_o);
		$cdate = explode("-",$cdate[0]);
		$month = intval($cdate[1]);
		$year = $cdate[0];
		
		echo "<div>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$plugname&amp;sec=$sname&amp;cat=$cname&amp;year=$year&amp;month=$month&amp;title=$aname")."' title='".CleanTitleAtr($atitle)."'>$atitle</a></div>\n";
	}
}

?>