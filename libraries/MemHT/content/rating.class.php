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

class BaseRating {
	var $plugin = false; //Plugin name
	var $controller = false; //Controller
	var $id = 0; //Item id
	var $rank = 0;
	
	function Show() {
		global $config_sys;
		
		$enabled = Utils::GetComOption("rating","enabled",0);
		
		if ($enabled) {
			$this->rank = @round($this->rank);
			$this->p_rank = $this->rank * 20;
			
			$ret = "<div class='inline-rating'>";
			$ret .= "<ul class='star-rating'>";
			$ret .= "<li class='current-rating' style='width:".$this->p_rank."%;'>".$this->rank."</li>";
			$ret .= "<li><a href='javascript:void(0);' title='1/5' class='one-star' onclick=\"rate_item('".$this->plugin."','".$this->id."','1');\">1</a></li>";
			$ret .= "<li><a href='javascript:void(0);' title='2/5' class='two-stars' onclick=\"rate_item('".$this->plugin."','".$this->id."','2');\">2</a></li>";
			$ret .= "<li><a href='javascript:void(0);' title='3/5' class='three-stars' onclick=\"rate_item('".$this->plugin."','".$this->id."','3');\">3</a></li>";
			$ret .= "<li><a href='javascript:void(0);' title='4/5' class='four-stars' onclick=\"rate_item('".$this->plugin."','".$this->id."','4');\">4</a></li>";
			$ret .= "<li><a href='javascript:void(0);' title='5/5' class='five-stars' onclick=\"rate_item('".$this->plugin."','".$this->id."','5');\">5</a></li>";
			$ret .= "</ul>";
			$ret .= "</div>";
			$ret .= "<div class='tpl_rating_placeholder'>&nbsp;</div>";
			
			return $ret;
		} else {
			return false;
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Rating")){class Rating extends BaseRating{}}

?>