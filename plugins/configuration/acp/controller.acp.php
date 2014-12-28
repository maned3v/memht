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
 * @author		Paulo Ferreira <sisnox@gmail.com>
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class configurationController extends configurationModel {
	public function index() {
		$this->Main();
	}
	public function save() {
		$this->SaveConfig();
	}
	//Languages
	public function lang() {
		$this->Languages();
	}
	public function addlang() {
		$this->AddLanguage();
	}
	public function editlang() {
		$this->EditLanguage();
	}
	public function dellang() {
		$this->DeleteLanguage();
	}
	//Characters
	public function chars() {
		$this->Characters();
	}
	public function addchars() {
		$this->AddCharacters();
	}
	public function editchars() {
		$this->EditCharacters();
	}
	public function delchars() {
		$this->DeleteCharacters();
	}
	//Options
	public function options() {
		$this->EngineOptions();
	}
	public function setoption() {
		$this->SetEngineOptions();
	}
	public function deleteoption() {
		$this->DeleteEngineOptions();
	}
}

?>