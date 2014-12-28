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

class BaseComments {
	var $info = array();
	
	public function GetCode() {
		global $Db,$User;
		
		$orcom = Utils::GetComOption("comments","order","ASC");
		$licom = Utils::GetComOption("comments","limit",10);
		$szcom = Utils::GetComOption("comments","avatar_size",40);
		$gucom = Utils::GetComOption("comments","guest_can",0);
		$macom = Utils::GetComOption("comments","moderate_always",1);
		$mscom = Utils::GetComOption("comments","moderate_onspam",1);
		
		$plugin_comments = array();
		if ($this->info['active']==1) {
			$plugin_comments['info']['status'] = "active";
			$plugin_comments['info']['item'] = $this->info['item'];
			$plugin_comments['info']['comments'] = $this->info['numcom'];
			$plugin_comments['info']['action'] = "index.php?"._NODE."=".$this->info['plugin']."&amp;op=comment";
			$plugin_comments['info']['canwrite'] = ($User->IsUser() || $gucom) ? 1 : 0 ;
			$plugin_comments['info']['moderated'] = ($macom || $mscom) ? 1 : 0 ;
			$plugin_comments['info']['retinfo'] = (Io::GetVar("GET","mod","int",false,0)) ? _t("YOUR_COMMENT_MODERATED_BEFORE_PUBLISHED") : 0 ;
			$plugin_comments['info']['url'] = $this->info['url'];

			//Token
			$tok = Utils::GenerateToken();
			$tok = explode(":",$tok);
			$plugin_comments['info']['ctok'] = $tok[0];
			$plugin_comments['info']['ftok'] = $tok[1];
			
			$compage = Io::GetVar("GET","compage","int",false,1);
			if ($compage<=0) $compage = 1;
			$from = ($compage * $licom) - $licom;
			
			$plugin_comments['data'] = $Db->GetList("SELECT c.*,u.name,u.options
														  FROM #__comments AS c USE INDEX(PRIMARY) LEFT JOIN #__user AS u ON c.author=u.uid
														  WHERE c.controller='".$Db->_e($this->info['controller'])."' AND c.item=".$this->info['item']." AND (c.status='published' OR c.status='approved')
														  ORDER BY id $orcom
														  LIMIT $from,$licom");
			for ($i=0;$i<sizeof($plugin_comments['data']);$i++) {
				$plugin_comments['data'][$i] = Io::Output($plugin_comments['data'][$i]); //Apply Io::Output to all fields
				$plugin_comments['data'][$i]['text'] = BBCode::ToHtml($plugin_comments['data'][$i]['text']); //Convert BBCode to HTML
				$plugin_comments['data'][$i]['created'] = Time::Output($plugin_comments['data'][$i]['created']); //Convert the comment's creation date
				$plugin_comments['data'][$i]['isadmin'] = ($User->IsAdmin($plugin_comments['data'][$i]['author'])) ? 1 : 0 ; //The author is an admin?
				$plugin_comments['data'][$i]['isguest'] = ($plugin_comments['data'][$i]['author']==0) ? 1 : 0 ; //The author is a guest?
				$plugin_comments['data'][$i]['karma'] = ($plugin_comments['data'][$i]['points']>2) ? 1 : (($plugin_comments['data'][$i]['points']<-2) ? 2 : 0) ; //Comment karma (0 = neutral, 1 = good, 2 = bad)
				if ($plugin_comments['data'][$i]['isguest']==0) $plugin_comments['data'][$i]['author_name'] = $plugin_comments['data'][$i]['name']; //If the author is a guest, fix the author's name
				//Show Gravatar using the user's email if no avatar has been selected
				//TODO: Add option to disable the automatic Gravatar selection
				$override = array("selector"=>"Gravatar","value"=>$plugin_comments['data'][$i]['author_email'],"name"=>$plugin_comments['data'][$i]['author_name']);
				$plugin_comments['data'][$i]['avatar'] = $User->DisplayAvatar($plugin_comments['data'][$i]['author'],$szcom,true,$override); //Author's avatar
			}
		} else {
			$plugin_comments['info']['status'] = "inactive";
		}
		
		return $plugin_comments;
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Comments")){class Comments extends BaseComments{}}

?>