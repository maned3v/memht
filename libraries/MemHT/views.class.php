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

class BaseViews {
	var $vars = array();
		
	public function __set($name,$value) {
		$this->vars[$name] = $value;
	}

	function Show($name) {
		global $config_sys;
		
		$controller = explode("_",$name);
		$path = _PATH_PLUGINS._DS.$controller[0]._DS."views"._DS.$name.".php";
	
		if (file_exists($path)==false) {
			MemErr::Trigger("WARNING","View <i>`$name`</i> not found","The path should be ".$path); //TODO: TRANSLATE
			return false;
		}
		
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		
		include($path);
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("MVCViews")){class Views extends BaseViews{}}

?>