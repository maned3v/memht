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

//Mod-rewrite querystring rebuild map

//index.php?node=plugin&cat=CAT&title=TITLE&compage=COMMENTSPAGE&mod=COMMENTID ("mod" is returned when the submitted comment has been moderated)
$map['index'] = array("cat","title","compage","mod");

//index.php?node=plugin&op=browse&page=PAGE
$map['browse'] = array("op","page");

//index.php?node=plugin&op=archive&year=YEAR&month=MONTH&page=PAGE
$map['archive'] = array("op","year","month","page");

//index.php?node=plugin&op=local&language=LANGUAGE&cat=CAT
$map['local'] = array("op","language","cat");

//index.php?node=plugin&op=related&tag=TAG
$map['related'] = array("op","tag");

//index.php?node=plugin&op=printer&title=TITLE
$map['printer'] = array("op","title");

//index.php?node=plugin&op=rss&cat=CAT&language=LANGUAGE
$map['rss'] = array("op","cat","language");

//index.php?node=plugin&op=pdf&title=TITLE
$map['pdf'] = array("op","title");

//index.php?node=plugin&op=rate&id=POSTID&vote=VOTE&rand=RAND
$map['rate'] = array("op","id","vote","rand");

?>