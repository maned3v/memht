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

//           I  10 1       B        1 10  E
// Page 7/40 |< << < 4 5 6 7 8 9 10 > >> >|

class BasePagination {

	var $page = false;
	var $limit = false;
	var $tot = false;
	var $url = false;
	var $query = false;
	var $content = false;
	var $getnum = false;
	
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
			
			//Label
			if ($this->positon_label) $this->content['label'] = _t("PAGE")." ". $this->page."/$numpages";
			
			//First page
			if ($this->show_limits AND $first<$prev) $this->content['first'] = "<a href='".str_replace("{PAGE}",$first,$this->url)."' title='"._t("PAGE")." $first'>"._t("FIRST")."</a>";
			
			//-1
			if ($prev) $this->content['minus_one'] = "<a href='".str_replace("{PAGE}",$prev,$this->url)."' title='"._t("PAGE")." $prev'>"._t("PREVIOUS")."</a>";
			
			//Previous pages
			for ($i=2;$i>0;$i--) {
				$item = $this->page-$i;
				if ($item>0) $this->content['prev'][$item] = "<a href='".str_replace("{PAGE}",$item,$this->url)."' title='"._t("PAGE")." $item'>$item</a>";
			}
			
			//Actual page
			$this->content['page'] = "<a href='".str_replace("{PAGE}",$this->page,$this->url)."' title='"._t("PAGE")." ".$this->page."'><b>".$this->page."</b></a>";
			
			//Next pages
			for ($i=1;$i<=2;$i++) {
				$item = $this->page+$i;
				if ($item<=$last) $this->content['next'][$i] = "<a href='".str_replace("{PAGE}",$item,$this->url)."' title='"._t("PAGE")." $item'>$item</a>";
			}
			
			//+1
			if ($next<=$last) $this->content['plus_one'] = "<a href='".str_replace("{PAGE}",$next,$this->url)."' title='"._t("PAGE")." $next'>"._t("NEXT")."</a>";
			
			//Last page
			if ($this->show_limits AND $last>$next) $this->content['last'] = "<a href='".str_replace("{PAGE}",$last,$this->url)."' title='"._t("PAGE")." $last'>"._t("LAST")."</a>";
		}
		
		return $this->content;
	}

	public function Label() {
		global $Db;

		if ($this->tot===false && $this->query!==false) {
			if ($this->getnum) {
				$this->tot = $Db->GetNum($this->query);
			} else {
				$row = $Db->GetRow($this->query);
				$this->tot = intval($row['tot']);
			}
		}

		$numpages = ceil($this->tot/$this->limit);
		if ($numpages>1) {
			$this->content['label'] = "<a href='".$this->url."' title='"._t("BROWSE_ALL_X",MB::strtolower("PAGES"))."'>"._t("BROWSE_ALL_X",MB::strtolower("PAGES"))."</a>";
		}

		return $this->content;
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Pagination")){class Pagination extends BasePagination{}}

?>