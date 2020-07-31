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

/**
 * Site Router
 *
 * Site routing is done by this class. It loads the desired
 * controller according to the query string sent by the user
 */
class BaseRouter {
	protected $controller;
	protected $action;
	protected $cfile;
	protected $mfile;
	var $breadcrumbs = array();

	/**
	 * Router Loader
	 */
	function Run() {
		global $Db,$config_sys,$User,$Visitor;

		$querystring = $Visitor['query_string'];
		if ($querystring==false && !isset($_SERVER['QUERY_STRING']) && !isset($_SERVER['argv']))
			MemErr::Trigger("USERERROR",_t("REQUEST_URL_CANNOT_BE_PROCESSED"),$Visitor['request_uri']);

		//Rebuild query string from rewritten urls
		if (!preg_match("#index.php#i",$Visitor['request_uri']) && !empty($querystring)) {
			//If last part == suffix, delete it
			$sulen = strlen($config_sys['nice_seo_urls_suffix']);
			$querystring = ($config_sys['nice_seo_urls_suffix']==MB::substr($querystring,-($sulen),$sulen)) ? MB::substr($querystring,0,strlen($querystring)-$sulen) : $querystring ;

			//Rebuild controller
			$parts = explode($config_sys['nice_seo_urls_separator'],$querystring);
			$plugname = preg_replace("#[^a-zA-Z0-9\-]#i","",str_replace($config_sys['node']."=","",$parts[0]));
			$_GET[$config_sys['node']] = $plugname;

			if ($plugrow = $Db->GetRow("SELECT id,title,name,controller,type,meta_keywords,showtitle,meta_description,content,cont_before,cont_after,options,roles,sitemap
										FROM #__content
										WHERE name='".$Db->_e($plugname)."' AND status='active'")) {
				define("_PLUGOK",true);
				$controller = Io::Output($plugrow['controller']);

				//Rebuild query map
				if (file_exists("plugins"._DS.$controller._DS."map.php")) {
					include_once("plugins"._DS.$controller._DS."map.php");

					if (sizeof($parts)>1 && isset($map)) {
						$op = (isset($map[$parts[1]])) ? $parts[1] : "index" ;
						if (isset($map[$op])) {
							$limit = ((sizeof($parts)-1)>sizeof($map[$op])) ? sizeof($map[$op]) : (sizeof($parts)-1) ;
							for ($i=0;$i<$limit;$i++) $_GET[$map[$op][$i]] = preg_replace("#[^a-zA-Z0-9\-]#i","",$parts[$i+1]);
						}
					}
				}
			}
		}

		$this->plugname = Io::GetVar("GET",$config_sys['node'],"[^a-zA-Z0-9\-]",true,$config_sys['default_home']);
		$this->action = Io::GetVar("GET","op","[^a-zA-Z0-9\-]",true,"index");
		define("_ISHOME",(isset($_GET[$config_sys['node']])) ? 0 : 1);
		Template::AssignVar("sys_home",_ISHOME);

		if (defined("_PLUGOK")) {
			$row = $plugrow;
		} else {
			$row = $Db->GetRow("SELECT id,title,name,controller,type,meta_keywords,showtitle,meta_description,content,cont_before,cont_after,options,roles,sitemap
								FROM #__content
								WHERE name='".$Db->_e($this->plugname)."' AND status='active'");
		}

		if ($row) {
			$controller = array();
			$controller['id'] = Io::Output($row['id'],"int");
			$controller['title'] = Io::Output($row['title']);
			$controller['name'] = Io::Output($row['name']);
			$controller['controller'] = Io::Output($row['controller']);
			$controller['type'] = Io::Output($row['type']);
			$controller['showtitle'] = Io::Output($row['showtitle']);
			$controller['meta_keywords'] = Io::Output($row['meta_keywords']);
			$controller['meta_description'] = Io::Output($row['meta_description']);
			$controller['content'] = Io::Output($row['content']);
			$controller['cont_before'] = Io::Output($row['cont_before']);
			$controller['cont_after'] = Io::Output($row['cont_after']);
			$controller['options'] = Utils::Unserialize(Io::Output($row['options']));
			$controller['roles'] = Utils::Unserialize(Io::Output($row['roles']));
			$controller['sitemap'] = Io::Output($row['sitemap'],"int");

			//Store controller's information in Ram
			Ram::Set('controller',$controller);

			//Site title step
			$pt = Io::GetVar("GET",$config_sys['node'],"[^a-zA-Z0-9\-]");
			if (!empty($pt)) Utils::AddTitleStep($controller['title']);
			
			//Required role check
			$proceed = true;
			if ($User->CheckRole($controller['roles']) || $User->IsAdmin()) {
				define("_PLUGIN",$controller['name']);
				define("_PLUGIN_CONTROLLER",$controller['controller']);
				define("_PLUGIN_TITLE",$controller['title']);
				define("_PLUGIN_SHOWTITLE",$controller['showtitle']);
				define("_PLUGIN_BEFORE",$controller['cont_before']);
				define("_PLUGIN_AFTER",$controller['cont_after']);
				
				if ($controller['type']=="REDIRECT") {
					if (!Utils::ValidUrl($controller['content'])) {
						$proceed = false;
						$message = "Address not valid"; //TODO: Translate and build something nice?
					}
				}
			} else {
				define("_PLUGIN",false);
				define("_PLUGIN_CONTROLLER",false);
				define("_PLUGIN_TITLE",false);
				define("_PLUGIN_SHOWTITLE",false);
				define("_PLUGIN_BEFORE",false);
				define("_PLUGIN_AFTER",false);
				
				$proceed = false;
				$message = _t("NOT_AUTH_TO_ACCESS_X",MB::strtolower(_t("PAGE"))); //TODO: Build something nice?
			}
			Template::AssignVar("sys_plugin",_PLUGIN);
		} else {
			$proceed = true;
			$this->controller = "error";
			$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."controller.php";
			$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."model.php";

			$controller['controller'] = "error";
			$controller['type'] = "PLUGIN";
			Ram::Set('controller',$controller);
			define("_PLUGIN",false);
			define("_PLUGIN_CONTROLLER",false);
			define("_PLUGIN_TITLE",false);
			define("_PLUGIN_SHOWTITLE",false);
			define("_PLUGIN_BEFORE",false);
			define("_PLUGIN_AFTER",false);
		}
		//Layout
		Template::AssignVar("sys_layout",$this->GetOption("layout",array("nav"=>1,"extra"=>1)));

		if ($proceed==true) {
			switch (MB::strtoupper($controller['type'])) {
				case "INTERNAL":
					define("_INTERNAL",true);
				default:
				case "PLUGIN":
					$this->controller = $controller['controller'];

					$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."controller.php";
					$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."model.php";

					if (file_exists($this->cfile)==false OR file_exists($this->mfile)==false) {
						$this->controller = "error";
						$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."controller.php";
						$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."model.php";
					}

					//Breadcrumbs path
					$this->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php' title='"._t("HOME")."'>"._t("HOME")."</a></span>";
					if (_PLUGIN_TITLE && isset($_GET[_NODE]))
						$this->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN."' title='"._PLUGIN_TITLE."'>"._PLUGIN_TITLE."</a></span>";

					//Load module file
					include($this->mfile);

					//Load controller file
					include($this->cfile);

					//Load controller class
					$class = $this->controller."Controller";
					$controller = new $class();

					$action = (is_callable(array($controller,$this->action))==false) ? "index" : $this->action ;
					
					$controller->$action();
					break;

				case "STATIC":
					//Initialize and show site header
					Layout::Header();

					if (MB::strstr($controller['content'],"[[READMORE]]")) {
						$page = (isset($parts[1])) ? intval($parts[1]) : Io::GetVar("GET","page","int",false,1) ;
						$pieces = explode("[[READMORE]]",$controller['content']);
						$controller['content'] = (isset($pieces[$page-1])) ? $pieces[$page-1] : $controller['content']; //Page not valid?
						
						//Pagination
	                    include_once(_PATH_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
	                    $Pag = new Pagination();
	                    $Pag->page = $page;
	                    $Pag->tot = count($pieces);
	                    $Pag->limit = 1;                        
	                    $Pag->url = "index.php?"._NODE."="._PLUGIN."&amp;page={PAGE}";
	                    $plugin_pagination = $Pag->Show();
                    
						Template::AssignVar("plugin_pagination",$plugin_pagination);
						Template::AssignVar("sys_main_additional",array("pagination"));
					}
					
					//Assign captured content to the template engine and clean buffer
					Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,
														 "showtitle"=>_PLUGIN_SHOWTITLE,
														 "url"=>"index.php?"._NODE."="._PLUGIN,
														 "content"=>$controller['content'],
														 "before"=>_PLUGIN_BEFORE,
														 "after"=>_PLUGIN_AFTER));
					
					//Breadcrumbs path
					$this->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php' title='"._t("HOME")."'>"._t("HOME")."</a></span>";
					if (_PLUGIN_TITLE && isset($_GET[_NODE]))
						$this->breadcrumbs[] = "<span class='sys_breadcrumb'><a href='index.php?"._NODE."="._PLUGIN."' title='"._PLUGIN_TITLE."'>"._PLUGIN_TITLE."</a></span>";
					Template::AssignVar("sys_breadcrumbs",implode(_BREADCRUMB_SEPARATOR,$this->breadcrumbs));

					//Draw site template
					Template::Draw();

					//Initialize and show site footer
					Layout::Footer();
					break;

				case "REDIRECT":
					Utils::Redirect($controller['content']);
					break;
			}
		} else {
			//Error message

			//Initialize and show site header
			Layout::Header();
			//Assign the buffered content to the template engine
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,
												 "showtitle"=>_PLUGIN_SHOWTITLE,
												 "url"=>"index.php?"._NODE."="._PLUGIN,
												 "content"=>$message,
												 "before"=>_PLUGIN_BEFORE,
												 "after"=>_PLUGIN_AFTER));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
	}

	function GetOption($key,$default=false) {
		$controller = Ram::Get("controller");
		return (isset($controller['options'][$key])) ? $controller['options'][$key] : $default ;
	}

	function SetOption($key,$value) {
		global $Db;

		$controller = Ram::Get("controller");
		$controller['options'][$key] = $value;
		Ram::Set("controller",$controller);
		$Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($controller['options']))."' WHERE id='".intval($controller['id'])."'");

		return true;
	}

	function DeleteOption($key) {
		global $Db;

		$controller = Ram::Get("controller");

		if (isset($controller['options'][$key])) {
			unset($controller['options'][$key]);
			Ram::Set("controller",$controller);
			$Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($controller['options']))."' WHERE id='".intval($controller['id'])."'");

			return true;
		} else {
			return false;
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("MVCRouter")){class Router extends BaseRouter{}}

?>