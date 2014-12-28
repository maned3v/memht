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

class BasePreloader {
	public function Run() {
		global $Db;

		//Roles
		$roles = array();
		$result = $Db->GetList("SELECT rid,label,title,options,static FROM #__rba_roles");
		foreach ($result as $row) {
			$roles[Io::Output($row['label'])] = array('rid'		=> Io::Output($row['rid'],'int'),
													  'label'	=> Io::Output($row['label']),
													  'name'	=> Io::Output($row['title']),
													  'options'	=> Utils::Unserialize(Io::Output($row['options'])),
													  'static'	=> Io::Output($row['static']));
		}
		Ram::Set("roles",$roles);

		//Plugin controller-name matches
		$plugmatch = array();
		$result = $Db->GetList("SELECT name,controller FROM #__content WHERE type IN ('PLUGIN','INTERNAL') AND status='active'");
		foreach ($result as $row) $plugmatch[Io::Output($row['controller'])] = Io::Output($row['name']);
		Ram::Set("plugmatch",$plugmatch);
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Preloader")){class Preloader extends BasePreloader{}}

$Preloader = new Preloader;
$Preloader->Run();

$Ext->RunMext("Preloader");

?>