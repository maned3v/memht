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

class articlesController extends articlesModel {
	public function index() {
		$this->Main();
	}
	//Posts
	public function createarticle() {
		$this->CreateArticles();
	}
	public function editarticle() {
		$this->EditArticles();
	}
	public function trashcan() {
		$this->TrashArticlesData();
	}
	public function sendtotrash() {
		$this->SendArticleToTrash();
	}
	public function restore() {
		$this->RestoreArticles();
	}
	public function switchart() {
		$this->SwitchArticles();
	}
	public function revisions() {
		$this->ArticlesRevisions();
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
		$this->ShowArticlesComments();
	}
	public function delcomments() {
		$this->DeleteComments();
	}
	//Sections
	public function sections() {
		$this->ArticlesSections();
	}
	public function deletesec() {
		$this->DeleteArticlesSection();
	}
	public function createsec() {
		$this->CreateArticlesSection();
	}
	public function editsec() {
		$this->EditArticlesSection();
	}
	//Categories
	public function categories() {
		$this->ArticlesCategories();
	}
	public function deletecat() {
		$this->DeleteArticlesCategory();
	}
	public function createcat() {
		$this->CreateArticlesCategory();
	}
	public function editcat() {
		$this->EditArticlesCategory();
	}
}

?>