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

class blogModel extends Views {
	public function _index() {
		global $Db,$Router,$User,$config_sys;

		//$Router->SetOption("layout",array("nav"=>1,"extra"=>1)); //DEBUG
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header(array("rating"=>true));
		//Start buffering content
		Utils::StartBuffering();

			//======================================================
						
			//Options
			$order = $Router->GetOption("order","DESC");
			$limit = $Router->GetOption("limit",10);
			$flang = $Router->GetOption("filter_lang",0);
			$plcom = $Router->GetOption("comments",1);
			$glcom = $plcom && $config_sys['comments'];

			//Filter
			$where = array();

			//...by year
			$year = Io::GetVar("GET","year","int",false,0);
			if ($year>0) {
				$where[] = "YEAR(p.created)=".intval($year);
			}

			//...by month
			$month = Io::GetVar("GET","month","int",false,0);
			if ($month>0) {
				$where[] = "MONTH(p.created)=".intval($month);
			}

			//...by category
			$cat = Io::GetVar("GET","cat","[^a-zA-Z0-9\-]");
			if (!empty($cat)) $where[] = "c.name='".$Db->_e($cat)."'";

			//...by language
			$lang = Io::GetVar("GET","language","[^a-zA-Z0-9\-]");
			if (!empty($lang)) $where[] = "p.language='".$Db->_e($lang)."'";

			$where[] = "p.status='published'";
			$where[] = "NOW() BETWEEN p.start AND p.end";
			if ($flang==1 && empty($lang)) $where[] = "p.language='".$Db->_e($config_sys['language'])."'";

			//Tags
			$tag = Io::GetVar("GET","tag","[^a-zA-Z0-9\-]");
			if (!empty($tag)) {
				$tag_q1 = " JOIN #__tags AS t";
				$tag_q2 = " AND t.controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND p.id=t.item AND t.name='".$Db->_e($tag)."'";
				$row = $Db->GetRow("SELECT title FROM #__tags WHERE name='".$Db->_e($tag)."'");
				$tagtitle = Io::Output($row['title']);
			} else {
				$tag_q1 = $tag_q2 = "";
			}

			//Pagination
			$page = Io::GetVar("GET","page","int",false,1);
			if ($page<=0) $page = 1;
			$from = ($page * $limit) - $limit;
			
			$index = (empty($tag_q1)) ? " FORCE INDEX(created)" : "";
			
			//Build query
			$where = (sizeof($where)>0) ? " WHERE ".implode(" AND ",$where) : "" ;
			
			if ($result = $Db->GetList("SELECT p.*,c.name AS cname, c.title AS ctitle, u.name AS author_name, l.title AS langtitle,
										(SELECT ROUND(SUM(rate)/COUNT(id)) AS rating FROM #__ratings WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item=p.id) AS rating
										FROM #__blog_posts AS p{$index} JOIN #__blog_categories AS c JOIN #__user AS u JOIN #__languages AS l{$tag_q1}
										ON p.category=c.id AND p.author=u.uid AND p.language=l.file{$tag_q2}
										{$where}
										ORDER BY p.created $order
										LIMIT ".intval($from).",".intval($limit))) {
				$plugin_blog_index = array();
				foreach ($result as $row) {
					$id			= Io::Output($row['id'],"int");
					$category	= Io::Output($row['category'],"int");
					$cname		= Io::Output($row['cname']);
					$ctitle		= Io::Output($row['ctitle']);
					$title		= Io::Output($row['title']);
					$name		= Io::Output($row['name']);
					$aid		= Io::Output($row['author'],"int");
					$author		= Io::Output($row['author_name']);
					$text		= Io::Output($row['text']);
					$langstr	= Io::Output($row['language']);
					$language	= Io::Output($row['langtitle']);
					$created_o	= Io::Output($row['created']);
					$created	= Time::Output($created_o);
					$modified_o = Io::Output($row['modified']);
					$modified	= Time::Output($modified_o);
					$start		= Io::Output($row['start']);
					$end		= Io::Output($row['end']);
					$options	= Utils::Unserialize(Io::Output($row['options']));
					$usecomments= ($glcom && Io::Output($row['usecomments'],"int")==1) ? true : false ;
					$comments	= Io::Output($row['comments'],"int");
					$hits		= Io::Output($row['hits'],"int");
					$rating		= Io::Output($row['rating'],"int");
					if (empty($rating)) $rating = 0;

					//Read more
					$_text = explode("[[READMORE]]",$text);
					$text = trim($_text[0]);
					if (isset($_text[1])) $text .= "..";

					//Split creation date
					$cdate = explode(" ",$created_o);
					$cdate = explode("-",$cdate[0]);
					$cday = intval($cdate[2]);
					$cmonth = intval($cdate[1]);
					$cyear = $cdate[0];
					
					$plugin_blog_index[] = array(
						//Base
						"id"			=> $id,
						"cname"			=> $cname,
						"ctitle"		=> $ctitle,
						"title"			=> $title,
						"name"			=> $name,
						"aid"			=> $aid,
						"author"		=> $author,						
						"options"		=> $options,
						"text"			=> Utils::EvalSurveys($text),
						"langstr"		=> $langstr,
						"language"		=> $language,
						"created"		=> $created,
						"modified"		=> array("date"	=> ($modified_o!=$created_o) ? $modified : false,
												 "info"	=> ($modified_o!=$created_o) ? _t("LAST_UPDATED_ON_X",$modified) : false),
						"start"			=> $start,
						"end"			=> $end,
						"usecomments"	=> $usecomments,
						"comments"		=> $comments,
						"hits"			=> $hits,
						//Additional
						"more"			=> (isset($_text[1])) ? true : false,
						"year"			=> $cyear,
						"month"			=> $cmonth,
						"smonth"		=> Utils::NumToMonth($cmonth,true),
						"day"			=> $cday,
						"tags"			=> $Db->GetList("SELECT name,title FROM #__tags WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item=".intval($id)),
						"rating"		=> $rating,
						"control"		=> ($User->IsAdmin()) ? " &lt;Edit&gt;" : false ,
						"info"			=> _t("WRITTEN_IN_X_BY_Y_ON_Z",
											  "<a href='index.php?"._NODE."="._PLUGIN."&amp;cat=$cname' title='".CleanTitleAtr($ctitle)."'>$ctitle</a>",
											  "<a href='index.php?"._NODE."=user&amp;op=info&amp;uid=$aid' title='".CleanTitleAtr($author)."'>$author</a>",
											  "$created"),
						"_author"		=> "<a href='index.php?"._NODE."=user&amp;op=info&amp;uid=$aid' title='".CleanTitleAtr($author)."'>$author</a>"
					);
				}

				//Pagination
				include_once(_PATH_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
				$Pag = new Pagination();
				$Pag->page = $page;
				$Pag->limit = $limit;
				$Pag->query = "SELECT COUNT(*) AS tot FROM #__blog_posts WHERE status='published' AND NOW() BETWEEN start AND end";
				$Pag->url = "index.php?"._NODE."="._PLUGIN."&amp;op=browse&amp;page={PAGE}";
				$plugin_pagination = $Pag->Show();
				
				//Output
				$this->plugin_blog_index = $plugin_blog_index;
				$this->plugin_pagination = $plugin_pagination;
				$this->Show("blog".__FUNCTION__);

				$op = Io::GetVar("GET","op","[^a-zA-Z0-9\-]");
				$urlinc = "";
				if (!empty($op)) {
					//Breadcrumb step
					$urlinc .= "&amp;op=".$op;
				}
				if (!empty($lang)) {
					//Breadcrumb step
					$urlinc .= "&amp;language=".$lang;
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN.$urlinc."' title='".CleanTitleAtr($language)."'>$language</a></span>";
					//Site title step
					Utils::AddTitleStep($language);
				}
				if (!empty($cat)) {
					//Breadcrumb step
					$urlinc .= "&amp;cat=".$cat;
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN.$urlinc."' title='".CleanTitleAtr($ctitle)."'>$ctitle</a></span>";
					//Site title step
					Utils::AddTitleStep($ctitle);
				}
				if (!empty($year)) {
					//Breadcrumb step
					$urlinc .= "&amp;year=$year";
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN.$urlinc."' title='".CleanTitleAtr($year)."'>$year</a></span>";
					//Site title step
					Utils::AddTitleStep($year);
				}
				if (!empty($month)) {
					//Breadcrumb step
					$urlinc .= "&amp;month=$month";
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN.$urlinc."' title='".CleanTitleAtr(Utils::NumToMonth($month))."'>".Utils::NumToMonth($month)."</a></span>";
					//Site title step
					Utils::AddTitleStep(Utils::NumToMonth($month));
				}
				if (!empty($tag)) {
					//Breadcrumb step
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'>$tagtitle</span>";
					//Site title step
					Utils::AddTitleStep($tagtitle);
				}
			} else {
				MemErr::Trigger("INFO",_t("LIST_EMPTY"));
			}
			
			//======================================================

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

	public function _view($name) {
		global $Db,$Router,$User,$config_sys;

		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);

			//======================================================

			//Options
			$orrel = $Router->GetOption("related_order","RAND");
			switch ($orrel) {
				default:
				case "RAND":
					$orrel = "RAND()";
					break;
				case "ASC":
					$orrel = "p.id ASC";
					break;
				case "DESC":
					$orrel = "p.id DESC";
					break;
			}
			$lirel = $Router->GetOption("related_limit",5);
			$plcom = $Router->GetOption("comments",1);
			$glcom = ($plcom==1 && $config_sys['comments']==1) ? true : false ;

			if ($row = $Db->GetRow("SELECT p.*,c.name AS cname, c.title AS ctitle, u.name AS author_name, l.title AS langtitle,
									(SELECT ROUND(SUM(rate)/COUNT(id)) AS rating FROM #__ratings WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item=p.id) AS rating
									FROM #__blog_posts AS p FORCE INDEX(created) JOIN #__blog_categories AS c JOIN #__user AS u JOIN #__languages AS l ON p.category=c.id AND p.author=u.uid AND p.language=l.file
									WHERE p.name='".$Db->_e($name)."' AND p.status='published'")) {
				$id			= Io::Output($row['id'],"int");
				$category	= Io::Output($row['category'],"int");
				$cname		= Io::Output($row['cname']);
				$ctitle		= Io::Output($row['ctitle']);
				$title		= Io::Output($row['title']);
				$name		= Io::Output($row['name']);
				$aid		= Io::Output($row['author'],"int");
				$author		= Io::Output($row['author_name']);
				$text		= str_replace("[[READMORE]]","",Io::Output($row['text']));
				$langstr	= Io::Output($row['language']);
				$language	= Io::Output($row['langtitle']);
				$created_o	= Io::Output($row['created']);
				$created	= Time::Output($created_o);
				$modified_o = Io::Output($row['modified']);
				$modified	= Time::Output($modified_o);
				$start		= Io::Output($row['start']);
				$end		= Io::Output($row['end']);
				$options	= Utils::Unserialize(Io::Output($row['options']));
				$roles		= Utils::Unserialize(Io::Output($row['roles']));
				$usecomments= ($glcom && Io::Output($row['usecomments'],"int")==1) ? true : false ;
				$comments	= Io::Output($row['comments'],"int");
				$hits		= Io::Output($row['hits'],"int");
				$rating		= Io::Output($row['rating'],"int");
				if (empty($rating)) $rating = 0;
				
				if ($User->CheckRole($roles) || $User->IsAdmin()) {
					//Increment hits
					$Db->Query("UPDATE #__blog_posts SET hits=hits+1 WHERE id=".intval($id));
	
					//Split creation date
					$cdate = explode(" ",$created_o);
					$cdate = explode("-",$cdate[0]);
					$cday = intval($cdate[2]);
					$cmonth = intval($cdate[1]);
					$cyear = $cdate[0];
	
					//Tags
					$tags = $Db->GetList("SELECT name,title FROM #__tags WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item=".intval($id));
					
					//Rating
					include_once(_PATH_LIBRARIES._DS."MemHT"._DS."content"._DS."rating.class.php");
					$Rate = new Rating();
					$Rate->plugin = _PLUGIN;
					$Rate->controller = _PLUGIN_CONTROLLER;
					$Rate->id = $id;
					$Rate->rank = $rating;
					$rating = $Rate->Show();
					
					$plugin_blog_view = array(
						//Base
						"id"			=> $id,
						"cname"			=> $cname,
						"ctitle"		=> $ctitle,
						"title"			=> $title,
						"name"			=> $name,
						"aid"			=> $aid,
						"author"		=> $author,
						"options"		=> $options,
						"text"			=> Utils::EvalSurveys($text),
						"langstr"		=> $langstr,
						"language"		=> $language,
						"created"		=> $created,
						"modified"		=> array("date"	=> ($modified_o!=$created_o) ? $modified : false,
												 "info"	=> ($modified_o!=$created_o) ? _t("LAST_UPDATED_ON_X",$modified) : false),
						"start"			=> $start,
						"end"			=> $end,
						"usecomments"	=> $usecomments,
						"comments"		=> $comments,
						"hits"			=> $hits,
						//Additional
						"more"			=> false,
						"year"			=> $cyear,
						"month"			=> $cmonth,
						"smonth"		=> Utils::NumToMonth($cmonth,true),
						"day"			=> $cday,
						"tags"			=> $tags,
						"rating"		=> $rating,
						"control"		=> ($User->IsAdmin()) ? " &lt;Edit&gt;" : false ,
						"info"			=> _t("WRITTEN_IN_X_BY_Y_ON_Z",
											  "<a href='index.php?"._NODE."="._PLUGIN."&amp;cat=$cname' title='".CleanTitleAtr($ctitle)."'>$ctitle</a>",
											  "<a href='index.php?"._NODE."=user&amp;op=info&amp;uid=$aid' title='".CleanTitleAtr($author)."'>$author</a>",
											  "$created"),
						"_author"		=> "<a href='index.php?"._NODE."=user&amp;op=info&amp;uid=$aid' title='".CleanTitleAtr($author)."'>$author</a>"
					);
					
					//Related
					$plugin_blog_related = array();
					if ($Router->GetOption("related",1)==1) {
						$tagarr = array();
						foreach ($tags as $tag) { $tagarr[] = $tag['name']; }
						for ($i=0;$i<sizeof($tagarr);$i++) $tagarr[$i] = "t.name='".$Db->_e($tagarr[$i])."'";
						if (sizeof($tagarr)>0) {
							if ($result = $Db->GetList("SELECT p.title,p.name,t.title AS ttag,t.name AS ntag,c.name AS cname
														FROM #__blog_posts AS p JOIN #__blog_categories AS c JOIN #__tags AS t
														ON p.category=c.id AND p.id=t.item AND p.id!=".intval($id)." AND t.controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND (".implode(" OR ",$tagarr).")
														WHERE p.status='published'
														GROUP BY p.id
														ORDER BY $orrel
														LIMIT $lirel")) {
								foreach ($result as $row) {
									$plugin_blog_related['data'][] = array(
										"title"	=> Io::Output($row['title']),
										"name"	=> Io::Output($row['name']),
										"url"	=> "index.php?"._NODE."="._PLUGIN."&amp;cat=".Io::Output($row['cname'])."&amp;title=".Io::Output($row['name']),
										"ttag"	=> Io::Output($row['ttag']),
										"ntag"	=> Io::Output($row['ntag'])
									);
								}
							}
						}
						$plugin_blog_related['info']['status'] = "active";
						$plugin_blog_related['info']['related'] = _t("RELATED_X",MB::strtolower(_t("POSTS")));
					} else {
						$plugin_blog_related['info']['status'] = "inactive";
					}
	
					//Comments
					include_once(_PATH_LIBRARIES._DS."MemHT"._DS."content"._DS."comments.class.php");
					$Com = new Comments();
					$Com->info = array("active"		=> $usecomments,
									   "plugin"		=> _PLUGIN,
									   "controller"	=> _PLUGIN_CONTROLLER,
									   "item"		=> $id,
									   "numcom"		=> $comments,
									   "url"		=> "index.php?"._NODE."="._PLUGIN."&amp;cat=$cname&amp;title=$name");
					$ComResult = $Com->GetCode();
					
					//Pagination
					include_once(_PATH_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
					$Pag = new Pagination();
					$Pag->page = Io::GetVar("GET","compage","int",false,1);
					$Pag->limit = Utils::GetComOption("comments","limit",10);
					$Pag->tot = $comments;
					$Pag->url = "index.php?"._NODE."="._PLUGIN."&amp;cat=$cname&amp;title=$name&amp;compage={PAGE}#comments";
					$plugin_pagination = $Pag->Show();
					
					//Meta data
					if (isset($plugin_blog_view['options']['meta'])) {
						$controller = Ram::Get('controller');
						if (isset($plugin_blog_view['options']['meta']['desc'])) $controller['meta_description'] = $plugin_blog_view['options']['meta']['desc'];
						if (isset($plugin_blog_view['options']['meta']['key'])) $controller['meta_keywords'] = $plugin_blog_view['options']['meta']['key'];
						Ram::Set('controller',$controller);
					}
					
					//Initialize and show site header
					Layout::Header(array("rating"=>true,"comments"=>true));
					//Start buffering content
					Utils::StartBuffering();
					
					//Output
					$this->pbv = $plugin_blog_view;
					$this->pbr = $plugin_blog_related;
					$this->pbc = $ComResult;
					$this->plugin_pagination = $plugin_pagination;
					$this->Show("blog".__FUNCTION__);
					
					//Site title step
					Utils::AddTitleStep($title);
	
					//Breadcrumbs path
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN."&amp;cat=$cname' title='".CleanTitleAtr($ctitle)."'>$ctitle</a></span>";
					$Router->breadcrumbs[] = "<span class='sys_breadcrumb'>$title</span>";
				} else {
					//Initialize and show site header
					Layout::Header();
					//Start buffering content
					Utils::StartBuffering();
					
					MemErr::Trigger("USERERROR",_t("NOT_AUTH_TO_ACCESS_X",MB::strtolower(_t("POST"))));
				}
			} else {
				//Initialize and show site header
				Layout::Header();
				//Start buffering content
				Utils::StartBuffering();
				
				MemErr::Trigger("INFO",_t("X_NOT_FOUND_OR_INACTIVE",_t("POST")));
			}

			//======================================================

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

	public function _print() {
		global $Db,$config_sys;

		$name = Io::GetVar("GET","title","[^a-zA-Z0-9\-]");
		$proceed = true;
		if (empty($name)) {
			$proceed = false;
			$msg = "Title empty"; //TODO: TRANSLATE
		} else if (!$row = $Db->GetRow("SELECT p.*,c.name AS cname, c.title AS ctitle, u.name AS author_name
										FROM #__blog_posts AS p JOIN #__blog_categories AS c JOIN #__user AS u ON p.category=c.id AND p.author=u.uid
										WHERE p.name='".$Db->_e($name)."'")) {
			$proceed = false;
			$msg = _t("X_NOT_FOUND_OR_INACTIVE",_t("POST"));
		}

		if (!$proceed) {
			//Load plugin language
			Language::LoadPluginFile(_PLUGIN_CONTROLLER);
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();

			MemErr::Trigger("USERERROR",$msg);

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
		} else {
			$this->site_name	= $config_sys['site_name'];
			$this->site_url		= $config_sys['site_url'];
			$this->ctitle		= Io::Output($row['ctitle']);
			$this->title		= Io::Output($row['title']);
			$this->author		= Io::Output($row['author_name']);
			$this->text			= str_replace("[[READMORE]]","",Io::Output($row['text']));
			$this->created		= Time::Output(Io::Output($row['created']));
			$this->modified		= Time::Output(Io::Output($row['modified']));

			$this->Show("blog".__FUNCTION__);
		}
	}

	public function _pdf() {
		global $Db,$config_sys;

		$name = Io::GetVar("GET","title","[^a-zA-Z0-9\-]");
		$proceed = true;
		if (empty($name)) {
			$proceed = false;
			$msg = "Title empty"; //TODO: TRANSLATE
		} else if (!$row = $Db->GetRow("SELECT p.*,c.name AS cname, c.title AS ctitle, u.name AS author_name
										FROM #__blog_posts AS p JOIN #__blog_categories AS c JOIN #__user AS u ON p.category=c.id AND p.author=u.uid
										WHERE p.name='".$Db->_e($name)."'")) {
			$proceed = false;
			$msg = _t("X_NOT_FOUND_OR_INACTIVE",_t("POST"));
		}

		if (!$proceed) {
			//Load plugin language
			Language::LoadPluginFile(_PLUGIN_CONTROLLER);
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();

			MemErr::Trigger("USERERROR",$msg);

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
		} else {
			$id			= Io::Output($row['id'],"int");
			$ctitle		= Io::Output($row['ctitle']);
			$title		= Io::Output($row['title']);
			$author		= Io::Output($row['author_name']);
			$text		= str_replace("[[READMORE]]","",Io::Output($row['text']));
			$created_o	= Io::Output($row['created']);
			$created	= Time::Output(Io::Output($row['created']));
			$modified_o = Io::Output($row['modified']);
			$modified	= Time::Output($modified_o);

			require_once(_PATH_LIBRARIES._DS."tcpdf"._DS."config"._DS."lang"._DS."eng.php");
			require_once(_PATH_LIBRARIES._DS."tcpdf"._DS."tcpdf.php");

			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

			// set document information
			$pdf->SetCreator("MemHT Portal");
			$pdf->SetAuthor($author);
			$pdf->SetTitle($title);
			$pdf->SetSubject($title);
			$pdf->SetKeywords("TCPDF, PDF, example, test, guide");

			// header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(true);
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 9));

			//set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			//set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			//set some language-dependent strings
			$pdf->setLanguageArray($l);

			//initialize document
			$pdf->AliasNbPages();

			// add a page
			$pdf->AddPage();

			$pdf->setRTL((_t("LANGDIR")=="rtl") ? true : false);

			// ---------------------------------------------------------

			$pdf->SetCellPadding(0);

			//Header
			$pdf->SetTextColor(200);
			$pdf->SetFont("helvetica", "B", 14);
			$pdf->Cell(0,15,$config_sys['site_name'],0,1,'R');

			//Title
			$pdf->SetTextColor(0);
			$pdf->SetFont("helvetica", "B", 14);
			$pdf->Cell(0,10,$title,0,1,'L');

			//Info
			$pdf->SetFont("helvetica", "", 10);
			$pdf->Cell(0,0,_t("WRITTEN_IN_X_BY_Y_ON_Z","$ctitle","$author","$created"),0,1,'L');

			$mod = ($modified_o!=$created_o) ? true : false ;
			$pdf->Cell(0,0,($mod) ? _t("LAST_UPDATED_ON_X",$modified) : "" ,0,1,'L');

			$pdf->SetDrawColor(200,200,200);
			$pdf->Line(15,($mod) ? 47 : 42.5,195,($mod) ? 47 : 42.5);

			//Spacer
			if ($mod) $pdf->Cell(0,2,"",0,1,'L');
			$pdf->Cell(0,2,"",0,1,'L');

			//Text
			$pdf->writeHTML($text,true);

			// ---------------------------------------------------------

			//Close and output PDF document
			$pdf->Output($name.".pdf", "I");
		}
	}
	
	public function _rss() {
		global $Db,$Router,$config_sys;
		
		$limit = $Router->GetOption("rss_limit",10);
		$cat = Io::GetVar("GET","cat","[^a-zA-Z0-9\-]");
		$lang = Io::GetVar("GET","language","[^a-zA-Z0-9\-]");

		$where = array();
		if (!empty($cat))	$where[] = "c.name='".$Db->_e($cat)."'";
		if (!empty($lang))	$where[] = "p.language='".$Db->_e($lang)."'";
		$where = (sizeof($where)) ? "AND ".implode(" AND ",$where) : "" ;

		$ctitle = "";
		if ($result = $Db->GetList("SELECT p.title,p.name,u.name AS aname,u.email AS aemail,c.name AS cname,c.title AS ctitle,p.text,p.created FROM #__blog_categories AS c JOIN #__blog_posts AS p JOIN #__user AS u ON c.id=p.category AND p.author=u.uid WHERE p.status='published'{$where} ORDER BY p.created DESC LIMIT ".intval($limit))) {
			if (!empty($cat)) $ctitle .= Io::Output($result[0]['ctitle'])." | ";
		}
		
		include_once(_PATH_LIBRARIES._DS."MemHT"._DS."rss.class.php");
		$Rss = new Rss();
		$Rss->Channel(array("title"			=> $ctitle._PLUGIN_TITLE." | ".$config_sys['site_name'],
							"link"			=> $config_sys['site_url'],
							"description"	=> $config_sys['meta_description'],
							"language"		=> str_replace("_","-",_t("LANGID")),
							"copyright"		=> $config_sys['copyright'],
							"generator"		=> "MemHT Portal - Free PHP CMS and Blog",
							"lastbuilddate"	=> (_GMT_DATETIME." ".str_replace(":","",preg_replace("#([+|-])([0-9]+)#is","$1:0:$2",$config_sys['dbserver_timezone'])))));
		
		foreach ($result as $row) {
			$title = Io::Output($row['title']);
			$name = Io::Output($row['name']);
			$aemail = Io::Output($row['aemail']);
			$aname = Io::Output($row['aname']);
			$cname = Io::Output($row['cname']);
			$ctitle = Io::Output($row['ctitle']);
			$text = Io::Output($row['text']);
			$created = Io::Output($row['created']);
			
			$Rss->Item(array("title"		=> $title,
							 "link"			=> RewriteUrl($config_sys['site_url']."/index.php?"._NODE."="._PLUGIN."&amp;cat=$cname&amp;title=$name"),
							 "permalink"	=> RewriteUrl($config_sys['site_url']."/index.php?"._NODE."="._PLUGIN."&amp;cat=$cname&amp;title=$name"),
							 "comments"		=> RewriteUrl($config_sys['site_url']."/index.php?"._NODE."="._PLUGIN."&amp;cat=$cname&amp;title=$name")."#comments",
							 "description"	=> str_replace("[[READMORE]]","",$text),
							 "author"		=> "$aemail ($aname)",
							 "category"		=> $ctitle,
							 "pubdate"		=> (Time::Output($created,"D, j M Y H:i:s"," ",true)." ".str_replace(":","",preg_replace("#([+|-])([0-9]+)#is","$1:0:$2",$config_sys['dbserver_timezone'])))));
		}
	}
	
	public function _email() {
		global $Db,$config_sys;
		
		$name = Io::GetVar("GET","title","[^a-zA-Z0-9\-]");
		$status = Io::GetVar("POST","status","[^a-zA-Z0-9\-]",false,"form");
		
		switch ($status) {
			case "send":
				if (Captcha::Check()===true) {
					$toname		= Io::GetVar("POST","toname");
					$toemail	= Io::GetVar("POST","toemail");
					$fromname	= Io::GetVar("POST","fromname");
					$fromemail	= Io::GetVar("POST","fromemail");
					$message	= Io::GetVar("POST","message");
					$post		= Io::GetVar("POST","post");
					
					$errors = array();
					if (empty($toname)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TONAME"));
					if (empty($toemail)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TOEMAIL"));
					if (empty($fromname)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("FROMNAME"));
					if (empty($fromemail)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("FROMEMAIL"));
					if (empty($message)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("MESSAGE"));
					if (empty($post)) $errors[] = _t("INTERROR","PBM_e001");
					$row = $Db->GetRow("SELECT p.title,p.text,c.name AS cname
										FROM #__blog_posts AS p JOIN #__blog_categories AS c ON c.id=p.category
										WHERE p.status='published' AND p.name='".$Db->_e($post)."' LIMIT 1");
					if (!$row) $errors[] = _t("INTERROR","PBM_e002");
					
					if (!sizeof($errors)) {
						$title = Io::Output($row['title']);
						$text = explode("[[READMORE]]",Io::Output($row['text']));
						$text = trim($text[0]);
						$cname = Io::Output($row['cname']);
						
						//TODO: Template support
						$emessage = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>\n";
						$emessage .= "<div style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;\">$message</div><hr>\n";
						$emessage .= "<div style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold;\"><a href=\"".RewriteUrl($config_sys['site_url']."/index.php?"._NODE."=blog&amp;cat=$cname&amp;title=$post")."\">$title</a></div><br>\n";
						$emessage .= "<div style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;\">$text...<br><br>[<a href=\"".RewriteUrl($config_sys['site_url']."/index.php?"._NODE."=blog&amp;cat=$cname&amp;title=$post")."\">"._t("READ_MORE")."</a>]</div>\n";
						$emessage .= "</body></html>\n";
						
						$Email = new Email();
						$Email->type = "html";
						$Email->AddEmail($toemail,$toname);
						$Email->SetFrom($fromemail,$fromname);
						$Email->SetSubject($title);
						$Email->SetContent($emessage,_t("TO_VIEW_HTML_MEX_COMPAT_VIEWER"));
						$result = $Email->Send();
						
						if ($result) {
							MemErr::Trigger("INFO",_t('MESSAGE_SENT'));
						} else {
							MemErr::StoreLog("error_sys","Message: Email not sent<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
							MemErr::Trigger("USERERROR",_t('MESSAGE_NOT_SENT'),implode(",",$Email->GetErrors()));
						}
					} else {
						MemErr::Trigger("USERERROR",implode("<br />",$errors));
					}
				}
			default:
				$this->site_name	= $config_sys['site_name'];
				$this->site_url		= $config_sys['site_url'];
				$this->title		= $config_sys['site_name'];
				$this->post			= $name;
				
				$this->status = $status;
				$this->Show("blog".__FUNCTION__);
				break;
		}
	}

	public function _comment() {
		global $Db,$User,$config_sys,$Visitor;
		
		if ($Visitor['request_method']!="POST") Utils::Redirect(RewriteUrl($config_sys['site_url']));
		
		$name = Io::GetVar("POST","name");
		$email = Io::GetVar("POST","email");
		$url = Io::GetVar("POST","url");
		$message = Io::GetVar("POST","message");
		$item = Io::GetVar("POST","item","int");
		
		$gucom = Utils::GetComOption("comments","guest_can",0);
		$macom = Utils::GetComOption("comments","moderate_always",1);
		$mscom = Utils::GetComOption("comments","moderate_onspam",1);
		$swcom = Utils::GetComOption("comments","spam_words",array("http","ftp","www","://","sex","porn","viagra","pharmacy","fuck"));
		if (!is_array($swcom)) $swcom = array("http","ftp","www","://","sex","porn","viagra","pharmacy","fuck");
		
		$errors = array();
		if (!Utils::CheckToken()) { $errors[] = _t("INVALID_TOKEN"); }
		if (empty($message)) { $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("MESSAGE")); }
		if (!Utils::ValidEmail($email) && !$User->IsUser()) { $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",_t("EMAIL")); }
		if (empty($name) && !$User->IsUser()) { $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NAME")); }
		if (!$User->IsUser() && !$gucom) { $errors[] = _t("LOGIN_TO_WRITE_COMMENT"); }

		if (!sizeof($errors)) {
			$author_name = ($User->IsUser()) ? "" : $name ;
			$author_email = ($User->IsUser()) ? "" : $email ;

			//Moderation
			$modresult = 0;
			if ($mscom) foreach ($swcom as $i) $modresult += (@MB::substr_count(MB::strtoupper($message),MB::strtoupper($i))>0) ? 1 : 0 ;
			$modresult += ($macom) ? 1 : 0 ;
			$status = ($modresult && !$User->IsAdmin()) ? "waiting" : "published" ;
			
			$row = $Db->GetRow("SELECT c.name AS category,p.name AS post FROM #__blog_posts AS p JOIN #__blog_categories AS c ON p.category=c.id WHERE p.id='".intval($item)."' AND p.status='published' LIMIT 1");
			$category = Io::Output($row['category']);
			$post = Io::Output($row['post']);

			$Db->Query("INSERT INTO #__comments (id,controller,item,author,author_name,author_email,author_site,author_ip,created,text,status)
						VALUES (NULL,'".$Db->_e(_PLUGIN_CONTROLLER)."','".intval($item)."','".intval($User->Uid())."','".$Db->_e($author_name)."','".$Db->_e($author_email)."',
						'".$Db->_e($url)."','".$Db->_e(Utils::Ip2num($User->Ip()))."',NOW(),'".$Db->_e($message)."','".$Db->_e($status)."')");
			$insid = $Db->InsertId();
			//The counter will be increased when the PM will be published
			if ($modresult==0) $Db->Query("UPDATE #__blog_posts SET comments=comments+1 WHERE id='".intval($item)."'");

			$suffixa = ($modresult) ? "&compage=1&mod=$insid" : "" ;
			$suffixb = ($modresult) ? "#comments" : "#comment".$insid ;
			Utils::Redirect(RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN."&cat=$category&title=$post{$suffixa}").$suffixb);
		} else {
			//Load plugin language
			Language::LoadPluginFile(_PLUGIN_CONTROLLER);
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();

			MemErr::Trigger("USERERROR",implode("<br />",$errors));

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
	
	function _delcomment() {
		global $Db,$User;

		if (!$User->IsAdmin()) return;
		
		$id = Io::GetVar("POST","id","int");
		$item = Io::GetVar("POST","item","int");
		
		if ($id==0 || $item==0) return;
		
		$result = $Db->Query("DELETE FROM #__comments WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND id=".intval($id)) ? 1 : 0 ;
		$total = $Db->AffectedRows();
		if ($total) $result = $Db->Query("UPDATE #__blog_posts SET comments=comments-1 WHERE id=".intval($item)) ? 1 : 0 ;

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-Type: text/xml");

		$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
		$xml .= '<response>\n';
			$xml .= '<result>\n';
				$xml .= '<query>'.$result.'</query>\n';
				$xml .= '<rows>'.$total.'</rows>\n';
			$xml .= '</result>\n';
		$xml .= '</response>';
		echo $xml;
	}
	
	public function _rate() {
		global $Db,$User,$Visitor;
		
		if ($Visitor['request_method']!="POST") Utils::Redirect(RewriteUrl($config_sys['site_url']));
		
		$guests = Utils::GetComOption("rating","guests",0);
		if ($User->IsUser() || $guests) {
			$id = Io::GetVar("POST","id","int");
			$vote = Io::GetVar("POST","vote","int");
			
			if ($Db->GetRow("SELECT id FROM #__ratings WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item='".intval($id)."' AND ((uid>0 AND uid='".intval($User->Uid())."') OR (uid=0 AND ip='".$Db->_e(Utils::Ip2num($Visitor['ip']))."')) LIMIT 1")) {
				echo _t("YOU_ALREADY_VOTED");
			} else {
				$Db->Query("INSERT INTO #__ratings (id,controller,item,rate,uid,ip)
							VALUES (null,'".$Db->_e(_PLUGIN_CONTROLLER)."','".intval($id)."','".$Db->_e($vote)."','".$Db->_e($User->Uid())."','".$Db->_e(Utils::Ip2num($Visitor['ip']))."')");
				echo _t("THANKS_FOR_VOTING");
			}
		} else {
			echo "<a href='"._SITEURL._DS."index.php?"._NODE."=user' title='"._t("LOGIN")."'>"._t("LOGIN")."</a>";
			echo " - <a href='"._SITEURL._DS."index.php?"._NODE."=user&amp;op=register' title='"._t("REGISTER")."'>"._t("REGISTER")."</a>";
		}
	}
}

?>