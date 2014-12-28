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

$config_sys['allowed_tags_advanced'] = array(//TODO: Script, Object & Frame
	'a'		=> array(
		'class'	=>	1,
		'href'	=>	1,
		'id'	=>	1,
		'name'	=>	1,
		'rel'	=>	1,
		'title'	=>	1),
	'abbr'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'acronym'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'address'	=> array(
		'class'	=>	1),
	'blockquote'=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'id'	=>	1,
		'title'	=>	1),
	'big'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'br'	=> array(),
	'caption'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'cite'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'code'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'del'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'datetime'=>1,
		'title'	=>	1),
	'div'	=> array(
		'class'	=>	1,
		'id'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'em'	=> array(),
	'embed'	=> array( // This tag isn't (X)HTML Valid
		'src'	=>	1,
		'type'	=>	1,
		'width'	=>	1,
		'height'=>	1),
	'fieldset'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h1'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h2'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h3'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h4'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h5'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h6'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'hr'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'iframe'=> array( //This tag isn't (X)HTML 1.0 Strict Valid
		'src'	=> 1,
		'width'	=> 1,
		'height'=> 1,
		'frameborder'=> 1,
		'allowfullscreen'=>1),
	'img'	=> array(
		'alt'	=>	1,
		'class'	=>	1,
		'height'=>	1,
		'longdesc'=>1,
		'src'	=>	1,
		'title'	=>	1,
		'style'	=>	1,
		'align'	=>	1,
		'width'	=>	1),
	'ins'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'datetime'=>1,
		'title'	=>	1),
	'li'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'noscript'=> array(),
	'object'=> array(
		'class' =>	1,
		'data'	=>	1,
		'dir'	=>	1,
		'lang'	=>	1,
		'height'=>	1,
		'width'	=>	1),
	'ol'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'p'	=> array(
		'class'	=>	1,
		'id'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'param' => array(
		'name'	=>	1,
		'value'	=>	1),
	'pre'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'q'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'title'	=>	1),
	'small'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'script'=> array(
		'src'	=>	1,
		'type'	=>	1,
		'charset'=>	1,
		'defer'	=>	1),
	'span'	=> array(
		'class'	=>	1,
		'id'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'strong'=> array(),
	'sub'	=> array(),
	'sup'	=> array(),
	'table'	=> array(
		'border'=>	1,
		'cellpadding'=>1,
		'cellspacing'=>1,
		'class'	=>	1,
		'frame'	=>	1,
		'rules'	=>	1,
		'summary'=>	1,
		'style'=>	1,
		'width'	=>	1),
	'tbody'	=> array(
		'align'	=>	1,
		'char'	=>	1,
		'class'	=>	1,
		'charoff'=>	1,
		'valign'=>	1),
	'td'	=> array(
		'abbr'	=>	1,
		'align'	=>	1,
		'axis'	=>	1,
		'char'	=>	1,
		'charoff'=>	1,
		'class'	=>	1,
		'colspan'=>	1,
		'headers'=>	1,
		'rowspan'=>	1,
		'scope'	=>	1,
		'valign'=>	1,
		'style'=>	1,
		'width'=>	1),
	'textarea'	=> array(
		'class'	=>	1,
		'cols'	=>	1,
		'disabled'=>1,
		'id'	=>	1,
		'name'	=>	1,
		'readonly'=>1,
		'rows'	=>	1),
	'tfoot'	=> array(
		'align'	=>	1,
		'char'	=>	1,
		'class'	=>	1,
		'charoff'=>	1,
		'valign'=>	1),
	'thead'	=> array(
		'align'	=>	1,
		'char'	=>	1,
		'class'	=>	1,
		'charoff'=>	1,
		'valign'=>	1),
	'tr'	=> array(
		'align'	=>	1,
		'char'	=>	1,
		'charoff'=>	1,
		'class'	=>	1,
		'valign'=>	1),
	'ul'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'var'	=> array()
);

$config_sys['allowed_tags_public'] = array(
	'a'		=> array(
		'href'	=>	1,
		'id'	=>	1,
		'name'	=>	1,
		'rel'	=>	1,
		'title'	=>	1),
	'abbr'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'acronym'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'address'	=> array(
		'class'	=>	1),
	'blockquote'=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'id'	=>	1,
		'title'	=>	1),
	'big'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'br'	=> array(),
	'caption'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'cite'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'code'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'del'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'datetime'=>1,
		'title'	=>	1),
	'em'	=> array(),
	'h1'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h2'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h3'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h4'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h5'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'h6'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'hr'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'img'	=> array(
		'alt'	=>	1,
		'class'	=>	1,
		'height'=>	1,
		'longdesc'=>1,
		'src'	=>	1,
		'title'	=>	1,
		'width'	=>	1),
	'ins'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'datetime'=>1,
		'title'	=>	1),
	'li'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'ol'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'p'	=> array(
		'class'	=>	1,
		'id'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'pre'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'q'	=> array(
		'cite'	=>	1,
		'class'	=>	1,
		'title'	=>	1),
	'small'	=> array(
		'class'	=>	1,
		'title'	=>	1),
	'strong'	=> array(),
	'sub'	=> array(),
	'sup'	=> array(),
	'ul'	=> array(
		'class'	=>	1,
		'style'	=>	1,
		'title'	=>	1),
	'var'	=> array()
);

?>