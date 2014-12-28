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

class pluginsController extends pluginsModel {
	public function index() {
		$this->Main();
	}
    public function install() {
        $this->InstallPlugin();
    }
    public function edit() {
        $this->EditPlugin();
    }
    public function delete() {
    	$this->DeletePage();
    }
    public function uninstall() {
        $this->UninstallPlugin();
    }
    //Static page
    public function pages() {
        $this->StaticPages();
    }
    public function createpage() {
        $this->CreateStaticPage();
    }
    public function editpage() {
        $this->EditStaticPage();
    }
    //Menu
    public function menu() {
        $this->MenuEditor();
    }
    public function switchpos() {
        $this->SwitchLinkPosition();
    }
    public function addmenu() {
        $this->AddToMenu();
    }
    public function editmenu() {
        $this->EditMenuLink();
    }
    public function deletemenu() {
        $this->DeleteFromMenu();
    }
    public function resetmenu() {
        $this->ResetMenuPositions();
    }
    //MenuAcp
    public function menuacp() {
        $this->AcpMenuEditor();
    }
    public function editmenuacp() {
    	$this->EditAcpMenuLink();
    }
    //Options
    public function options() {
        $this->PluginOptions();
    }
    public function addoption() {
    	$this->AddPluginOptions();
    }
    public function editoption() {
    	$this->EditPluginOptions();
    }
    public function deleteoption() {
    	$this->DeletePluginOptions();
    }
    //Redirect
    public function redirects() {
    	$this->ListRedirects();
    }
    public function addredirect() {
    	$this->AddRedirection();
    }
    public function editredirect() {
    	$this->EditRedirection();
    }
    public function delredirects() {
    	$this->DeleteRedirects();
    }
}

?>