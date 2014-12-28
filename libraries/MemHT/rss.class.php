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

class Rss {
	function __construct() {		
		header("Content-Type: text/xml; charset=utf-8");
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		echo "<rss version=\"2.0\">\n";
		echo "\t<channel>\n";
	}
	
	function __destruct() {
		echo "\t</channel>\n";
		echo "</rss>";
	}
	
	function Channel($args) {
		global $config_sys;
		
		echo "\t\t<title>".$args['title']."</title>\n";
		echo "\t\t<link>".$args['link']."</link>\n";
		echo "\t\t<description>".$args['description']."</description>\n";
		echo "\t\t<language>".$args['language']."</language>\n";
		echo "\t\t<copyright>".strip_tags($args['copyright'])."</copyright>\n";
		echo "\t\t<generator>".$args['generator']."</generator>\n";
		echo "\t\t<lastBuildDate>".$args['lastbuilddate']."</lastBuildDate>\n";
	}
	
	function Item($args) {
		echo "\t\t<item>\n";
			echo "\t\t\t<title>".$args['title']."</title>\n";
			echo "\t\t\t<link>".$args['link']."</link>\n";
			echo "\t\t\t<guid isPermaLink=\"true\">".$args['permalink']."</guid>\n";
			echo "\t\t\t<comments>".$args['comments']."</comments>\n";
			echo "\t\t\t<description><![CDATA[".$args['description']."]]></description>\n";
			echo "\t\t\t<author>".$args['author']."</author>\n";
			echo "\t\t\t<category>".$args['category']."</category>\n";
			echo "\t\t\t<pubDate>".$args['pubdate']."</pubDate>\n";
		echo "\t\t</item>\n";
	}
}

/*
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
	<channel>
		<title><![CDATA[...]]></title>
		<link></link>
		<description><![CDATA[...]]></description>
		<language></language>
		<copyright></copyright>
		<generator></generator>
		<lastBuildDate></lastBuildDate>
		<item>
			<title><![CDATA[...]]></title>
			<link></link>
			<guid isPermaLink="true"></guid>
			<comments></comments>
			<description><![CDATA[...]]></description>
			<author></author>
			<category></category>
			<pubDate></pubDate>
		</item>
	</channel>
</rss>
*/

?>