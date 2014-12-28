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
defined("_ADMINCP") or die("Access denied");

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
			Error::Trigger("USERERROR",_t("REQUEST_URL_CANNOT_BE_PROCESSED"),$Visitor['request_uri']);

		$this->controller = Io::GetVar("GET","cont","[^a-zA-Z0-9\-]",true,"dashboard");
		$this->action = Io::GetVar("GET","op","[^a-zA-Z0-9\-]",true,"index");
		
		if ($row = $Db->GetRow("SELECT id,title,name,controller,type,meta_keywords,showtitle,meta_description,content,cont_before,cont_after,options,sitemap
								FROM #__content
								WHERE controller='".$Db->_e($this->controller)."' AND acp='yes'")) {
			$controller = array();
			$controller['id'] = Io::Output($row['id'],"int");
			$controller['title'] = Io::Output($row['title']);
			$controller['name'] = Io::Output($row['controller']);
			$controller['controller'] = Io::Output($row['controller']);
			$controller['options'] = Utils::Unserialize(Io::Output($row['options']));
			/*$controller['type'] = Io::Output($row['type']);
			$controller['showtitle'] = Io::Output($row['showtitle']);
			$controller['meta_keywords'] = Io::Output($row['meta_keywords']);
			$controller['meta_description'] = Io::Output($row['meta_description']);
			$controller['content'] = Io::Output($row['content']);
			$controller['cont_before'] = Io::Output($row['cont_before']);
			$controller['cont_after'] = Io::Output($row['cont_after']);
			$controller['sitemap'] = Io::Output($row['sitemap'],"int");*/

			//Store controller's information in Ram
			Ram::Set('controller',$controller);

			//Site title step
			$pt = Io::GetVar("GET","cont","[^a-zA-Z0-9\-]");
			if (!empty($pt)) Utils::AddTitleStep($controller['title']);

			define("_PLUGIN",$controller['name']);
			define("_PLUGIN_CONTROLLER",$controller['controller']);
			define("_PLUGIN_TITLE",$controller['title']);

			//Template::AssignVar("sys_plugin",_PLUGIN);

			$proceed = true;
		} else {
			$proceed = true;
			$this->controller = "error";
			$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."controller.acp.php";
			$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."model.acp.php";

			$controller['controller'] = "error";
			Ram::Set('controller',$controller);
			define("_PLUGIN",false);
			define("_PLUGIN_CONTROLLER",false);
			define("_PLUGIN_TITLE",false);
		}
		
		if ($proceed==true) {
			$this->controller = $controller['controller'];

			$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."controller.acp.php";
			$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."model.acp.php";

			if (file_exists($this->cfile)==false OR file_exists($this->mfile)==false) {
				$this->controller = "error";
				$this->cfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."controller.acp.php";
				$this->mfile = _PATH_PLUGINS._DS.$this->controller._DS."acp"._DS."model.acp.php";
			}

			//Load module file
			include($this->mfile);

			//Load controller file
			include($this->cfile);

			//Load controller class
			$class = $this->controller."Controller";
			$controller = new $class();

			$action = (is_callable(array($controller,$this->action))==false) ? "index" : $this->action ;
			$controller->$action();
		} else {
			//Error message

			//Initialize and show site header
			Layout::Header();
			//Assign the buffered content to the template engine
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"index.php?"._NODE."="._PLUGIN,"content"=>$message));
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

	function SetOption($key,$value,$controller=false) {
		global $Db;

		if ($controller) {
			if ($row = $Db->GetRow("SELECT id,options FROM #__content WHERE controller='".$Db->_e($controller)."'")) {
				$opt = Utils::Unserialize(Io::Output($row['options']));
				$opt[$key] = $value;
				if ($Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($opt))."' WHERE id='".intval(Io::Output($row['id']))."'")) {
					return true;
				}
			}
		} else {
			$controller = Ram::Get("controller");
			$controller['options'][$key] = $value;
			Ram::Set("controller",$controller);
			if ($Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($controller['options']))."' WHERE id='".intval($controller['id'])."'")) {
				return true;
			}
		}
		return false;
	}

	function DeleteOption($key,$controller=false) {
		global $Db;

		if ($controller) {
			if ($row = $Db->GetRow("SELECT id,options FROM #__content WHERE controller='".$Db->_e($controller)."'")) {
				$opt = Utils::Unserialize(Io::Output($row['options']));
				unset($opt[$key]);
				if ($Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($opt))."' WHERE id='".intval(Io::Output($row['id']))."'")) {
					return true;
				}
			}
		} else {
			$controller = Ram::Get("controller");
			if (isset($controller['options'][$key])) {
				unset($controller['options'][$key]);
				Ram::Set("controller",$controller);
				$Db->Query("UPDATE #__content SET options='".$Db->_e(Utils::Serialize($controller['options']))."' WHERE id='".intval($controller['id'])."'");
				return true;
			}			
		}
		return false;
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("MVCACPRouter")){class Router extends BaseRouter{}}

?>