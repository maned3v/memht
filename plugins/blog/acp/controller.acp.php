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

class blogController extends blogModel {
	public function index() {
		$this->Main();
	}
	//Posts
	public function createpost() {
		$this->CreateBlogPost();
	}
	public function editpost() {
		$this->EditBlogPost();
	}
	public function trashcan() {
		$this->TrashBlogData();
	}
	public function sendtotrash() {
		$this->SendPostToTrash();
	}
	public function restore() {
		$this->RestorePost();
	}
	public function switchpost() {
		$this->SwitchBlogPost();
	}
	public function revisions() {
		$this->PostRevisions();
	}
	public function restorerev() {
		$this->RestoreRevision();
	}
	public function deleterev() {
		$this->DeleteRevision();
	}
	public function deleteallrev() {
		$this->DeleteAllRevisions();
	}
	public function comments() {
		$this->ShowPostComments();
	}
	public function delcomments() {
		$this->DeleteComments();
	}
	//Categories
	public function categories() {
		$this->BlogCategories();
	}
	public function deletecat() {
		$this->DeleteBlogCategory();
	}
	public function createcat() {
		$this->CreateBlogCategory();
	}
	public function editcat() {
		$this->EditBlogCategory();
	}
}

?>