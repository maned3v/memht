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

class BBCode {	
	static function ToHtml($text,$smiles=true,$newlines=true) {
		global $Db,$opened,$tag,$ids,$Ext;
		
		$placeholders = array('[',
							  ']',
							  '{TEXT}',
							  '{SIMPLETEXT}',
							  '{NUMBER}',
							  '{COLOR}');
		$regex = 		array('\[',
							  '\]',
							  '(.+?)',
							  '([\w]+)',
							  '([\d]+)',
							  '([#\w]+)');
		
		$patterns = array();
		$replaces = array();
		$result = $Db->GetList("SELECT bbcode,html FROM #__bbcode");
		foreach ($result as $row) {
			//Replace placeholders in patterns
			$patterns[] = "`".str_replace($placeholders,$regex,$row['bbcode'])."`is";
			//Replace placeholders in replaces
			$replaces[] = preg_replace("`{([\d]+)}`is","$\\1",$row['html']);
		}
				
		//Convert "standard" BBCode tags
		$text = preg_replace($patterns,$replaces,$text);
		
		//New lines
		if ($newlines) $text = nl2br($text);
		
		//Smiles
		if ($smiles) {
			$result = $Db->GetList("SELECT * FROM #__bbcode_smiles");
			foreach ($result as $row) {
				$name = Io::Output($row['name']);
				$image = Io::Output($row['image']);
				$code = Io::Output($row['code']);
		
				$text = str_replace($code, "<img src='assets/smiles/$image' border='0' title='".CleanTitleAtr($name)."' alt='Smile' />",$text);
			}
		}
		
		//Convert [QUOTE]
		$opened = 0;
		$tag = $ids = array();
		$text = preg_replace_callback('`\[/*quote\]`is','BBCode_Quote',$text);
		foreach ($ids as $id) $text = preg_replace('`\[quote:'.$id.'\](.+?)\[/quote:'.$id.'\]`is','<div class="sys_bbcode_quote">\\1</div>',$text);
		
		//Convert [CODE]
		$opened = 0;
		$tag = $ids = array();
		$text = preg_replace_callback('`\[/*code\]`is','BBCode_Code',$text);
		foreach ($ids as $id) $text = preg_replace_callback('`\[code:'.$id.'\](.+?)\[/code:'.$id.'\]`is','BBCode_CodeOutput',$text);

		$Ext->RunMext("BBCodeToHtml");
		
		return $text;
	}
}

global $Ext;

$Ext->RunMext("BBCode_CodeOutput");
if (!function_exists("BBCode_CodeOutput")) {
	function BBCode_CodeOutput($var) {
		return '<div class="sys_bbcode_code"><pre class="codepre">'.Io::Filter($var[1],"nohtml",false).'</pre></div>';
	}
}

function BBCode_Quote($var) {
	global $opened,$tag,$ids;
	
	$var[0] = MB::strtolower($var[0]);
	if ($var[0]=="[quote]") {
		$opened++;
		$id = Utils::GenerateRandomString(5);
		$var[0] = str_replace("[quote","[quote:$id",$var[0]);
		$ids[] = $tag[$opened] = $id;
	}
	if ($var[0]=="[/quote]" && $opened>0) {
		$id = $tag[$opened];
		$opened--;
		$var[0] = str_replace("[/quote]","[/quote:$id]",$var[0]);
	}

	return $var[0];
}

function BBCode_Code($var) {
	global $opened,$tag,$ids;
	
	$var[0] = MB::strtolower($var[0]);
	if ($var[0]=="[code]") {
		$opened++;
		$id = Utils::GenerateRandomString(5);
		$var[0] = str_replace("[code","[code:$id",$var[0]);
		$ids[] = $tag[$opened] = $id;
	}
	if ($var[0]=="[/code]" && $opened>0) {
		$id = $tag[$opened];
		$opened--;
		$var[0] = str_replace("[/code]","[/code:$id]",$var[0]);
	}

	return $var[0];
}

?>