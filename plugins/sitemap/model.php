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

class sitemapModel extends Views {
	
	public function Main() {
		global $Db,$config_sys;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$result = $Db->GetList("SELECT title,name,controller,type FROM #__content WHERE sitemap=1 AND status='active' ORDER BY title");
		foreach ($result as $row) {
			$title		= Io::Output($row['title']);
			$name		= Io::Output($row['name']);
			$controller	= Io::Output($row['controller']);
			$type		= Io::Output($row['type']);
			
			echo "<div><a href='".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$name")."' title='".CleanTitleAtr($title)."'>$title</a></div>\n";
			
			//Plugin specific
			if (file_exists("plugins"._DS.$controller._DS."sitemap.php")) include_once("plugins"._DS.$controller._DS."sitemap.php");
		}
		
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,
											 "showtitle"=>_PLUGIN_SHOWTITLE,
											 "url"=>"index.php?"._NODE."="._PLUGIN,
											 "content"=>Utils::GetBufferContent("clean"),
											 "before"=>_PLUGIN_BEFORE,
											 "after"=>_PLUGIN_AFTER));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	public function XmlMap() {
		global $Db,$config_sys;
		
		header("Content-Type: text/xml");
		
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		echo "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/09/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		
		echo "\t<url>\n";
				echo "\t\t<loc>".$config_sys['site_url']."</loc>\n";
				echo "\t\t<lastmod>"._GMT_DATE."</lastmod>\n";
				echo "\t\t<changefreq>daily</changefreq>\n";
				echo "\t\t<priority>0.9</priority>\n";
			echo "\t</url>\n";
		
		$result = $Db->GetList("SELECT title,name,controller,type FROM #__content WHERE sitemap=1 AND status='active'");
		foreach ($result as $row) {
			$title		= Io::Output($row['title']);
			$name		= Io::Output($row['name']);
			$controller	= Io::Output($row['controller']);
			$type		= Io::Output($row['type']);
			
			$frequency	= ($type=="STATIC") ? "weekly" : "daily" ;
			
			echo "\t<url>\n";
				echo "\t\t<loc>".RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."=$name")."</loc>\n";
				echo "\t\t<lastmod>"._GMT_DATE."</lastmod>\n";
				echo "\t\t<changefreq>$frequency</changefreq>\n";
				echo "\t\t<priority>0.7</priority>\n";
			echo "\t</url>\n";
			
			//Plugin specific
			if (file_exists("plugins"._DS.$controller._DS."sitemap.php")) {
				if (!defined("_XML")) define("_XML",true);
				include_once("plugins"._DS.$controller._DS."sitemap.php");
			}
		}
		
		echo "</urlset>";
	}
}

?>