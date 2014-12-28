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

class searchModel extends Views {
	private $found = false;
	private $results = array();
	
	public function Main(){
		global $Db,$Router,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$form = new Form();
		$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=search";
		
		$form->Open();
		
		//Query
		$form->AddElement(array("element"	=>"text",
								"label"		=>_t("TEXT"),
								"name"		=>"query",
								"info"		=>_t("REQUIRED").", "._t("X_CHAR_MIN",4)));

		$location = array();
		//$location[_t("ANYWHERE")] = "all";
		$location[_t("STATIC_PAGES")] = "static";
		$result = $Db->GetList("SELECT title,name FROM #__content WHERE type='PLUGIN' AND searchable=1 AND status='active' ORDER BY title");
		foreach ($result as $row) $location[Io::Output($row['title'])] = Io::Output($row['name']);

		//Location
		$form->AddElement(array("element"	=>"select",
								"label"		=>_t("LOCATION"),
								"name"		=>"location",
								"values"	=>$location));
		
		//Author
		$form->AddElement(array("element"	=>"text",
								"label"		=>_t("AUTHOR"),
								"name"		=>"author",
								"info"		=>_t("X_CHAR_MIN",4)));
		
		//Language
		$result = $Db->GetList("SELECT title,file FROM #__languages ORDER BY title");
		$lang = array();
		$lang[_t("ANY")] = "all";
		foreach ($result as $row) $lang[Io::Output($row['title'])] = Io::Output($row['file']);
		$form->AddElement(array("element"	=>"select",
								"label"		=>_t("LANGUAGE"),
								"name"		=>"language",
								"values"	=>$lang));
		
		//Date ToDo
		
		//Results
		$form->AddElement(array("element"	=>"select",
								"label"		=>_t("RESULTS"),
								"name"		=>"results",
								"values"	=>array(10 => 10,
													20 => 20,
													50 => 50,
													100 => 100),
								"selected"	=>20,
								"width"		=>"70px"));
				
		//Submit
		$form->AddElement(array("element"	=>"submit",
								"name"		=>"submit",
								"captcha"	=>true,
								"value"		=>_t("SEARCH")));

		$form->Close();
				
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
	
	public function SearchQuery(){
		global $Db,$config_sys;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		if (Utils::CheckToken()) {
			if (Captcha::Check()===true) {
				$query = Io::GetVar('POST','query');
				$location = Io::GetVar('POST','location');
				
				if (strlen($query)>=4) {
					switch ($location) {
						case "static":
							if ($result = $Db->GetList("SELECT title,name FROM #__content WHERE type='STATIC' AND searchable=1 AND status='active' AND (content LIKE '%".$Db->_e($query)."%' OR cont_before LIKE '%".$Db->_e($query)."%' OR cont_after LIKE '%".$Db->_e($query)."%')")) {
								$searchres = false;
								foreach ($result as $row) {
									$title	= Io::Output($row['title']);
									$name	= Io::Output($row['name']);
									
									$searchres[] = array('title'	=>$title,
														 'url'		=>"<a href='index.php?"._NODE."=$name' title='".CleanTitleAtr($title)."'>$title</a>",
														 'subtitle'	=>RewriteUrl($config_sys['site_url']."/index.php?"._NODE."=$name"));
								}
								$this->output = $searchres;
								$this->Show("search_results");
							} else {
								Error::Trigger("INFO",_t("NO_RESULTS_FOUND"));
							}
							break;
						default:
							if ($row = $Db->GetRow("SELECT title,name,controller FROM #__content WHERE name='".$Db->_e($location)."' AND type='PLUGIN' AND searchable=1 AND status='active'")) {
								$controller = Io::Output($row['controller']);

								//Plugin specific
								if (file_exists("plugins"._DS.$controller._DS."search.php")) {
									include_once("plugins"._DS.$controller._DS."search.php");
									
									$Sip = new SearchPlugin();

									if (is_callable(array($Sip,"InPlugin"))) {
										$found = $Sip->InPlugin($query);

										if ($found) {
											//Output
											$this->output = $found;
											$this->Show("search_results");
										} else {
											Error::Trigger("INFO",_t("NO_RESULTS_FOUND"));
										}
									} else {
										Error::Trigger("INTERROR","PSM_e002");
									}
								} else {
									Error::Trigger("INTERROR","PSM_e001");
								}
							} else {
								Error::Trigger("USERERROR",_t("X_NOT_FOUND_OR_INACTIVE",_t("PLUGIN")));
							}
							break;
					}
				} else {
					Error::Trigger("USERERROR",_t("X_MUST_BE_MIN_Y_CHARS_LONG",MB::strtolower(_t("TEXT")),4));
				}
			}
		} else {
			Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
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
}

?>