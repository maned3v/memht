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

$result = $Db->GetList("SELECT p.title AS ptitle,p.name AS pname,c.name AS cname,p.created,p.modified
					   	FROM #__blog_posts AS p FORCE INDEX(created) JOIN #__blog_categories AS c ON p.category=c.id WHERE p.status='published' ORDER BY p.created DESC LIMIT 0,20");

//Controller-name match
$plugmatch = Ram::Get("plugmatch");
$plugname = isset($plugmatch['blog']) ? $plugmatch['blog'] : "blog" ;

if (defined("_XML")) {
	foreach ($result as $row) {
		$pname = Io::Output($row['pname']);
		$cname = Io::Output($row['cname']);
		$created = Io::Output($row['created']);
		$modified = Io::Output($row['modified']);
		
        $lastmod = Time::Output($modified==$created ? $created : $modified ,"%Y-%m-%d"," ",true);
		
		echo "\t<url>\n";
			echo "\t\t<loc>".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$plugname&cat=$cname&title=$pname")."</loc>\n";
			echo "\t\t<lastmod>$lastmod</lastmod>\n";
			echo "\t\t<changefreq>weekly</changefreq>\n";
			echo "\t\t<priority>0.5</priority>\n";
		echo "\t</url>\n";
	}
} else {
	foreach ($result as $row) {
		$ptitle = Io::Output($row['ptitle']);
		$pname = Io::Output($row['pname']);
		$cname = Io::Output($row['cname']);
		
		echo "<div>&nbsp;&nbsp;&nbsp;&nbsp;<a href='".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$plugname&cat=$cname&title=$pname")."' title='".CleanTitleAtr($ptitle)."'>$ptitle</a></div>\n";
	}
}

?>