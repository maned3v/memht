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

//           I  10 1       B        1 10  E
// Page 7/40 |< << < 4 5 6 7 8 9 10 > >> >|

class Pagination {

	var $page = false;
	var $limit = false;
	var $tot = false;
	var $url = false;
	var $query = false;
	var $content = false;
	
	/*Show the page position label: "Page N of TOT"*/
	var $positon_label = true;
	
	/*Show first and last page links*/
	var $show_limits = true;
	
	public function Show() {
		global $Db;
		
		if ($this->tot===false && $this->query!==false) {
			$row = $Db->GetRow($this->query);
			$this->tot = intval($row['tot']);
		}
		
		$numpages = ceil($this->tot/$this->limit);
		if ($numpages>1) {
			$first = 1;
			$prev_big = $this->page-10;
			$prev = $this->page-1;
			$next = $this->page+1;
			$next_big = $this->page+10;
			$last = $numpages;

			$this->content = "<div style='margin:7px 0;'>\n";

			//Label
			if ($this->positon_label) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'>"._t("PAGE")." ".$this->page."/$numpages</span>\n";
			
			//First page
			if ($this->show_limits AND $first<$prev) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$first,$this->url)."' title='"._t("PAGE")." $first'>"._t("FIRST")."</a></span>\n";
			
			//-1
			if ($prev) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$prev,$this->url)."' title='"._t("PAGE")." $prev'>"._t("PREVIOUS")."</a></span>\n";
			
			//Previous pages
			for ($i=2;$i>0;$i--) {
				$item = $this->page-$i;
				if ($item>0) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$item,$this->url)."' title='"._t("PAGE")." $item'>$item</a></span>\n";
			}
			
			//Actual page
			$this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$this->page,$this->url)."' title='"._t("PAGE")." ".$this->page."'><b>".$this->page."</b></a></span>\n";
			
			//Next pages
			for ($i=1;$i<=2;$i++) {
				$item = $this->page+$i;
				if ($item<=$last) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$item,$this->url)."' title='"._t("PAGE")." $item'>$item</a></span>\n";
			}
			
			//+1
			if ($next<=$last) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$next,$this->url)."' title='"._t("PAGE")." $next'>"._t("NEXT")."</a></span>\n";
			
			//Last page
			if ($this->show_limits AND $last>$next) $this->content .= "<span class='sys_acp_pagination sys_form_button ui-corner-all'><a href='".str_replace("{PAGE}",$last,$this->url)."' title='"._t("PAGE")." $last'>"._t("LAST")."</a></span>\n";
			$this->content .= "</div>\n";
		}
		
		return $this->content;
	}
}

?>