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

class Utils {
	static $ErrorLog = array();
	static $comoptions = array();

	static function Debug($var) {
		echo "<pre style='margin:4px; border:2px solid #F00;'>\n";
			echo "<div style='background-color:#F00; color:#FFF;'><strong>DEBUG</strong></div>";
			echo "<div style='padding:4px; background-color:#FFF; color:#F00; overflow:auto;'>";
			print_r($var);
			echo "</div>";
		echo "</pre>\n";
	}

	static function GetMicrotime() {
		list($usec,$sec) = explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}

	static function StartBuffering($function=false) {
		if ($function!==false) {
			ob_start($function);
		} else {
			ob_start();
		}
	}

	static function GetBufferContent($operation=false) {
		$content = ob_get_contents();
		
		switch ($operation) {
			case "clean":
				ob_end_clean();
				break;
			case "flush":
				ob_end_flush();
				break;
		}
		return $content;
	}

	static function Serialize($array) {
		return serialize(is_array($array) ? $array : array());
	}

	static function Unserialize($string) {
		$array = @unserialize($string);
		return ($array!==false) ? $array : array();
	}

	static function GenerateRandomString($size=false,$chars=false) {
		if ($size===false) $size = 10;
		if ($chars!==false) {
			$string = "";
			for($i=0;$i<$size;$i++) $string .= $chars[mt_rand(0,strlen($chars)-1)];			
		} else {
			$string = md5(uniqid(mt_rand(0,time()),true));
		}
		return ($size) ? MB::substr($string,0,$size) : $string;
	}

	static function GenerateToken() {
		global $config_sys;

		$token = self::GenerateRandomString();
		return $token.":".md5($token.$config_sys['uniqueid']);
	}

	static function CheckToken() {
		global $config_sys,$User;
		
		if ($User->IsAdmin()) return true;

		$ctok = Io::GetVar("POST","ctok","[^a-zA-Z0-9]");
		$ftok = Io::GetVar("POST","ftok","[^a-zA-Z0-9]");
		return (empty($ctok) || empty($ftok) || md5($ctok.$config_sys['uniqueid'])!=$ftok) ? false : true ;
	}

	static function Redirect($url,$sec=false) {
		global $config_sys;

		if (MB::strpos($url,"index.php")===0) $url = $config_sys['site_url']."/".$url;
		
		if (!headers_sent() && $sec===false) {
			header("Location: ".$url);
		} else {
			echo '<meta http-equiv="refresh" content="'.$sec.';url='.$url.'" />';
		}
	}

	static function ShowMaintenanceTemplate($message) {
		die($message); //TODO
	}

	static function LoadComOptions() {
		global $Db;

		$out = array();
		$result = $Db->GetList("SELECT * FROM #__options");
		foreach ($result as $row) $out[Io::Output($row['label'])] = Utils::Unserialize(Io::Output($row['data']));
		self::$comoptions = $out;

		return true;
	}

	static function GetComOption($label,$key,$default=false) {
		return (isset(self::$comoptions[$label][$key])) ? self::$comoptions[$label][$key] : $default ;
	}

	static function SetComOption($label,$key,$data) {
		global $Db;

		self::$comoptions[$label][$key] = $data;
		return ($Db->Query("REPLACE INTO #__options (label,data) VALUES ('".$Db->_e($label)."','".$Db->_e(Utils::Serialize(self::$comoptions[$label]))."')")) ? true : false ;
	}

	static function DeleteComOption($label,$key=false) {
		global $Db;

		if ($key==false) {
			//Delete entire option
			$Db->Query("DELETE FROM #__options WHERE label='".$Db->_e($label)."'");
		} else {
			//Delete key in option
			if (isset(self::$comoptions[$label][$key])) unset(self::$comoptions[$label][$key]);
			$Db->Query("UPDATE #__options SET data='".$Db->_e(Utils::Serialize(self::$comoptions[$label]))."' WHERE label='".$Db->_e($label)."'");
		}

		return true;
	}

	static function AddTitleStep($string) {
		$st = Ram::Get('sys_title');
		$st[] = $string;
		Ram::Set('sys_title',$st);
	}

	static function NumToMonth($str,$short=false) {
		switch ($str) {
			case 1:  return (!$short) ? _t("JAN") : self::CutStr(_t("JAN"),3) ; break;
			case 2:  return (!$short) ? _t("FEB") : self::CutStr(_t("FEB"),3) ; break;
			case 3:  return (!$short) ? _t("MAR") : self::CutStr(_t("MAR"),3) ; break;
			case 4:  return (!$short) ? _t("APR") : self::CutStr(_t("APR"),3) ; break;
			case 5:  return (!$short) ? _t("MAY") : self::CutStr(_t("MAY"),3) ; break;
			case 6:  return (!$short) ? _t("JUN") : self::CutStr(_t("JUN"),3) ; break;
			case 7:  return (!$short) ? _t("JUL") : self::CutStr(_t("JUL"),3) ; break;
			case 8:  return (!$short) ? _t("AUG") : self::CutStr(_t("AUG"),3) ; break;
			case 9:  return (!$short) ? _t("SEP") : self::CutStr(_t("SEP"),3) ; break;
			case 10: return (!$short) ? _t("OCT") : self::CutStr(_t("OCT"),3) ; break;
			case 11: return (!$short) ? _t("NOV") : self::CutStr(_t("NOV"),3) ; break;
			case 12: return (!$short) ? _t("DEC") : self::CutStr(_t("DEC"),3) ; break;
		}
	}
	
	static function CutStr($str,$chars=5) {
		return MB::substr($str,0,$chars);
	}

	static function ValidUrl($url) {
		return (preg_match("#^(?:(?:http|https)://)+([\w\d]+)+(?:[\w\d~\._\-\&]+)\.(?:[a-zA-Z]{2,5})+(:\d+)?/*[\w\d\?\.\+\(\)\&\/\=\#%\~_\-]*$#is",$url)) ? true : false ;
	}

	static function ValidEmail($email) {
		return (preg_match("`^(?:[\w\d~\._-]{2,})(@localhost)|(?:@{1}[\w\d~\._-]{1,})(?:\.{1}[a-zA-Z]{2,5})$`is",$email)) ? true : false ;
	}

	static function ValidIp($ip) {
		return (preg_match("`^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$`is",$ip) && @inet_pton($ip)) ? true : false ;
	}

	static function Ip2num($ip) {
		return @inet_pton($ip);
	}

	static function Num2ip($ip) {
		return @inet_ntop($ip);
	}
	
	static function GetXmlFile($file=false) {
		if (!is_readable($file)) return false;
		
		$xmlobj = @simplexml_load_file($file,'SimpleXMLElement',LIBXML_NOCDATA);
		return $xmlobj;
	}
	
	static function EvalSurveys($text) {
		return preg_replace_callback("#{survey_([a-zA-Z0-9]+)}#is","PlaceholderToSurvey",$text);
	}
	
	static function GetDirContent($path,$exclude=array()) {
		$filearray = array();
		$handle = opendir($path);
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file!="index.html" && !in_array($file,$exclude)) $filearray[] = $file;
		}
		closedir($handle);
		if (sizeof($filearray)) {
			sort($filearray);
			reset($filearray);
		}
		return $filearray;
	}
	
	static function Bytes2str($bytes) {
		if ($bytes<1024) {
			return "$bytes byte";
		} else {
			$kb = $bytes / 1024;
			if ($kb<1024) {
				return sprintf("%01.2f", $kb)." Kb";
			} else {
				$mb = $kb / 1024;
				if ($mb<1024) {
					return sprintf("%01.2f", $mb)." Mb";
				} else {
					$gb = $mb / 1024;
						return sprintf("%01.2f", $gb)." Gb";
				}
			}
		}
	}

	static function CleanString($string,$options=array()) {
		global $Db;
		
		$lowercase = (isset($options['lowercase'])) ? 1 : 0 ;

		$result = $Db->GetList("SELECT * FROM #__conv_chars ORDER BY pattern ASC");
		$patterns = array();
		$replaces = array();
		foreach ($result as $row) {
			$patterns[] = $row['pattern'];
			$replaces[] = $row['replace'];
		}

		$string = str_replace($patterns,$replaces,$string);
		$string = preg_replace("#[ ]+#is","-",$string);
		$string = preg_replace("#[^a-zA-Z0-9\-]#is","",$string);
		if ($lowercase) $string = MB::strtolower($string);

		unset($result);
		unset($patterns);
		unset($replaces);

		return $string;
	}

}

?>