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

class SearchPlugin extends searchModel {
	public function InPlugin($query) {
		global $Db;

		//Controller-name match
		$plugmatch = Ram::Get("plugmatch");
		$plugname = isset($plugmatch['articles']) ? $plugmatch['articles'] : "articles" ;

		$results = Io::GetVar('POST','results','int');
		$author = Io::GetVar('POST','author');
		$language = Io::GetVar('POST','language');
		$start = Io::GetVar('POST','start',false,true,'2000-01-01 00:00:00');
		$end = Io::GetVar('POST','end',false,true,'2199-02-01 00:00:00');
		if ($results<=0 || $results>100) $results = 20;

		//Filter
		$where = array();
		//...by query
		$query = $Db->_e($query);
		$where[] = "(a.title LIKE '%".$query."%' OR a.name LIKE '%".$query."%' OR a.text LIKE '%".$query."%')";
		//...by author
		if (strlen($author)>=4) $where[] = "u.name LIKE '%".$Db->_e($author)."%'";
		//...by language
		if (!empty($language) && $language!="all") $where[] = "a.language LIKE '%".$Db->_e($language)."%'";
		//...by date
		if (!empty($start) && !empty($end)) $where[] = "(a.created BETWEEN '".$Db->_e($start)."' AND '".$Db->_e($end)."')";


		$where[] = "a.status='published'";
		$where[] = "NOW() BETWEEN a.start AND a.end";
		//Build query
		$where = " WHERE ".implode(" AND ",$where) ;

		$searchres = false;
		if ($result = $Db->GetList("SELECT a.*,c.name AS cname, c.title AS ctitle, u.name AS author_name, l.title AS langtitle, s.name AS sname, s.title AS stitle
									FROM #__articles AS a USE INDEX(category) JOIN #__articles_categories AS c JOIN #__articles_sections AS s JOIN #__user AS u
									JOIN #__languages AS l ON a.category=c.id AND s.id=c.section AND a.author=u.uid AND a.language=l.file
									{$where}
									ORDER BY a.created DESC
									LIMIT ".intval($results))) {

			foreach ($result as $row) {
				$sname		= Io::Output($row['sname']);
				$stitle		= Io::Output($row['stitle']);
				$cname		= Io::Output($row['cname']);
				$ctitle		= Io::Output($row['ctitle']);
				$title		= Io::Output($row['title']);
				$name		= Io::Output($row['name']);
				$aid		= Io::Output($row['author'],"int");
				$author		= Io::Output($row['author_name']);
				$created_o	= Io::Output($row['created']);
				$created	= Time::Output($created_o,"d");
				$comments	= Io::Output($row['comments'],"int");

				//Split creation date
				$cdate = explode(" ",$created_o);
				$cdate = explode("-",$cdate[0]);
				$month = intval($cdate[1]);
				$year = $cdate[0];

				$searchres[] = array('title'	=>$title,
									 'url'		=>"<a href='index.php?"._NODE."=$plugname&amp;sec=$sname&amp;cat=$cname&amp;year=$year&amp;month=$month&amp;title=$name' title='".CleanTitleAtr($title)."'>$title</a>",
									 'subtitle'	=>_t("WRITTEN_IN_X_BY_Y_ON_Z",
												  $ctitle,
												  "<a href='index.php?"._NODE."=user&amp;op=info&amp;uid=$aid' title='".CleanTitleAtr($author)."'>$author</a>",
												  "$created"),
									 'additional'=>_t("X_COMMENTS",$comments));
			}
		}
		return $searchres;
	}
}

?>