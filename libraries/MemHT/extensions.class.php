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

class Extensions {
	public $ext = array();
	public $mext = array();
	
	function __construct() {
		//Load all extensions
		$handle = opendir(_PATH_EXTENSIONS);
		while (false !== ($file = readdir($handle))) {
			if (MB::strpos($file,".ext.xml")) {
				$file = preg_replace("#[^a-z0-9_]#is","",str_replace(".ext.xml","",$file));
				$xmlobj = Utils::GetXmlFile(_PATH_EXTENSIONS._DS.$file.".ext.xml");
				if (isset($xmlobj->hook)) $this->ext[(string)$xmlobj->hook] = (string)$xmlobj->filename;
			} else if (MB::strpos($file,".mext.xml")) {
				$file = preg_replace("#[^a-z0-9_]#is","",str_replace(".mext.xml","",$file));
				$xmlobj = Utils::GetXmlFile(_PATH_EXTENSIONS._DS.$file.".mext.xml");
				if (isset($xmlobj->hook)) $this->mext[(string)$xmlobj->hook][] = (string)$xmlobj->filename;
			}
		}
		closedir($handle);
	}
	
	function InitExt($hook) {
		if (isset($this->ext[$hook])) {
			if (file_exists(_PATH_EXTENSIONS._DS.$this->ext[$hook].".ext.php")) {
				include_once(_PATH_EXTENSIONS._DS.$this->ext[$hook].".ext.php");
				return true;
			}
		}
		return false;
	}
	
	function RunMext($hook,$args=array()) {
		if (isset($this->mext[$hook]) && is_array($this->mext[$hook])) {
			foreach ($this->mext[$hook] as $iext)
			if (file_exists(_PATH_EXTENSIONS._DS.$iext.".mext.php")) {
				include_once(_PATH_EXTENSIONS._DS.$iext.".mext.php");
				if (is_callable($iext)) {
					$iext($args);
				}
			}
			return true;
		}
		return false;
	}
}

$Ext = new Extensions();

?>