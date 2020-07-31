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

class errorModel extends Views {
	function ErrorPage() {
		global $config_sys;
		
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
			
		MemErr::Trigger("USERERROR","Error 404: Page not found!");
			
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
}

?>