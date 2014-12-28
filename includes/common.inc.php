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

//title='xxx' clean from quotes
function CleanTitleAtr($title) {
	return Io::Output($title,true);
}

//TODO: Template support
function PlaceholderToSurvey($var) {
	global $Db,$config_sys;
	
	$label = $var[1];
	
	if ($row = $Db->GetRow("SELECT * FROM #__surveys_questions WHERE type='CONTENT' AND status='active' AND label='".$Db->_e($label)."' LIMIT 1")) {
		$id			= Io::Output($row['id'],"int");
		$question	= Io::Output($row['question']);
		$usecomments= Io::Output($row['usecomments']);
		$comments	= Io::Output($row['comments']);
		
		//Controller-name match
		$plugmatch = Ram::Get("plugmatch");
		$plugname = isset($plugmatch['surveys']) ? $plugmatch['surveys'] : "surveys" ;
		
		$answeres = $Db->GetList("SELECT * FROM #__surveys_answers WHERE surveyid='".intval($id)."' ORDER BY id");
		$token = Utils::GetComOption("surveys","token",1);
		
		if (file_exists(_PATH_TEMPLATES._DS.$config_sys['template']._DS."surveys.php")) {
			include(_PATH_TEMPLATES._DS.$config_sys['template']._DS."surveys.php");
		} else {
			$content = "<div class='sys_survey_incontent'>\n";
				$content .= "<form name='survey_{$label}'  method='post' action='index.php?"._NODE."=$plugname&amp;op=vote'>\n";
					$content .= "<div style='text-align:center;'><strong>$question</strong></div>\n";
					foreach ($answeres as $row) {
						$aid	= Io::Output($row['id'],"int");
						$answer = Io::Output($row['answer']);
						$content .= "<div style='padding:3px 0;'><input name='vote' type='radio' value='$aid' /> $answer</div>\n";
					}
					$content .= "<div style='padding:3px 0; text-align:center;'><input type='submit' name='Submit' value='"._t("SUBMITVOTE")."' /></div>\n";
					$content .= "<div style='text-align:center;'><a href='index.php?"._NODE."=$plugname&amp;op=results&amp;id=$id' title='"._t("SURVEYRESULTS")."'>"._t("SURVEYRESULTS")."</a></div>\n";
					if ($usecomments) {
						$content .= "<div style='text-align:center;'><a href='index.php?"._NODE."=$plugname&amp;op=results&amp;id=$id#comments' title='"._t("COMMENTS")."'>($comments "._t("COMMENTS").")</a></div>\n";
					}
					$content .= "<input type='hidden' name='id' value='$id' />\n";
					
					if ($token) {
						$tok = Utils::GenerateToken();
						$tok = explode(":",$tok);
						
						$content .= "<input name='ctok' type='hidden' value='".$tok[0]."' />\n";
						$content .= "<input name='ftok' type='hidden' value='".$tok[1]."' />\n";
					}
					
				$content .= "</form>\n";
			$content .= "</div>\n";
		}
		
		return $content;
	}
}

function Hours2minutes($hours) {
	//Hours format: h:m
	$time = explode(":",$hours);
	$h = $time[0];
	$m = (isset($time[1])) ? $time[1] : 0;
	$m += ($h*60);
	return $m;
}

?>