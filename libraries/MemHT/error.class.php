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

class Error {
	static function Trigger($level,$message,$error="") {
		global $config_sys,$Db,$User;

		switch (MB::strtoupper($level)) {
			case "FATAL":
				self::MemErrorHandler(256,$message."<br /><em>$error</em>",false,false);
				break;
			case "WARNING":
				self::MemErrorHandler(512,$message."<br /><em>$error</em>",false,false);
				break;
			case "NOTICE":
				self::MemErrorHandler(1024,$message."<br /><em>$error</em>",false,false);
				break;
			default:
			case "USERERROR":
				echo "<div class='error_user'>\n<div><strong>{$message}</strong></div>";
				if ($User->IsAdmin() && !empty($error)) { echo "<div><em>$error</em></div>"; }
				echo "</div>\n";
				//$log_user = Utils::GetComOption("error_handler","log_user",0);
				//if ($log_user) self::StoreLog("error_usererror",array("query" => 111, "errno" => 111, "error" => 111)); //TODO
				break;
			case "INFO":
				echo "<div class='error_info'>\n<div><strong>{$message}</strong></div>";
				if (!empty($error)) { echo "<div><em>$error</em></div>"; }
				echo "</div>\n";
				break;
			//Mini Info
			case "MINFO":
				echo "<div class='error_minfo'>\n<div>{$message}</div>";
				if (!empty($error)) { echo "<div><em>$error</em></div>"; }
				echo "</div>\n";
				break;
			case "DIALOG":
				echo "<div class='error_dialog'>\n<div><strong>{$message}</strong></div>";
				echo "<div><em>$error</em></div>";
				echo "</div>\n";
				break;
		}
	}

	static function MemErrorHandler($errno,$errstr,$errfile,$errline) {
		global $User,$Db;

		if (error_reporting()==0) return; //Error supressed with @
		if ($errno==2048) return; //Ignore E_STRICT
		$log_sys = @Utils::GetComOption("error_handler","log_sys",1);
		
		$errnos = array(
			1		=> array('E_ERROR','Error'),
			2		=> array('E_WARNING','Warning'),
			4		=> array('E_PARSE','Parse'),
			8		=> array('E_NOTICE','Notice'),
			16		=> array('E_CORE_ERROR','Core Error'),
			32		=> array('E_CORE_WARNING','Core Warning'),
			64		=> array('E_COMPILE_ERROR','Compile Error'),
			128		=> array('E_COMPILE_WARNING','Compile Warning'),
			256		=> array('E_USER_ERROR','User Error'),
			512		=> array('E_USER_WARNING','User Warning'),
			1024	=> array('E_USER_NOTICE','User Notice'),
			2048	=> array('E_STRICT','Strict'),
			4096	=> array('E_RECOVERABLE_ERROR','Recoverable Error'),
			8192	=> array('E_DEPRECATED','Deprecated'),
			16384	=> array('E_USER_DEPRECATED','User Deprecated')
		);

		$errtrace = debug_backtrace();
		if (defined("_ERRHDL")) {
			//Full error display
			if (MB::stripos($errstr,"_fetch_assoc") || MB::stripos($errstr,"_num_rows") || MB::stripos($errstr,"ot a valid MySQL")) {
				//MySQL
				if ($User->IsAdmin() && error_reporting()<>0) {
					echo "<div class='error_sys'>\n";
						echo "<div><strong>MySQL</strong> (".$Db->GetErrno().")</div>\n";
						echo "<div class='e_txt'>".$Db->GetError()."</div>\n";
						echo "<div><strong>Query:</strong> ".$errtrace[2]['args'][0]."</div>\n";
						echo "<div><strong>File:</strong> ".$errtrace[2]['file']."</div>\n";
						echo "<div><strong>Line:</strong> ".$errtrace[2]['line']."</div>\n";
					echo "</div>\n";
				}
				if ($log_sys) self::StoreLog("error_mysql","Message: ".$Db->GetError()."<br />Query: ".$errtrace[2]['args'][0]."<br />File: ".$errtrace[2]['file']."<br />Line: ".$errtrace[2]['line']);
			} else if (in_array($errno,array(256,512,1024))) {
				//User
				if ($User->IsAdmin() && error_reporting()<>0) {
					echo "<div class='error_sys'>\n";
						echo "<div><strong>".$errnos[$errno][1]."</strong> ($errno)</div>\n";
						echo "<div class='e_txt'>$errstr</div>\n";
						echo "<div><strong>File:</strong> ".$errtrace[1]['file']."</div>\n";
						echo "<div><strong>Line:</strong> ".$errtrace[1]['line']."</div>\n";
					echo "</div>\n";
				}
				if ($log_sys) self::StoreLog("error_user","Message: $errstr<br />File: ".$errtrace[1]['file']."<br />Line: ".$errtrace[1]['line']);
			} else if (MB::stripos($errstr,"ysql_real_escape_string")) {
				if ($User->IsAdmin() && error_reporting()<>0) {
					echo "<div class='error_sys'>\n";
						echo "<div><strong>".$errnos[$errno][1]."</strong> ($errno)</div>\n";
						echo "<div class='e_txt'>$errstr</div>\n";
						echo "<div><strong>File:</strong> ".$errtrace[2]['file']."</div>\n";
						echo "<div><strong>Line:</strong> ".$errtrace[2]['line']."</div>\n";
					echo "</div>\n";
				}
				if ($log_sys) self::StoreLog("error_user","Message: $errstr<br />File: ".$errtrace[2]['file']."<br />Line: ".$errtrace[2]['line']);
			} else {
				//Sys
				if ($User->IsAdmin() && error_reporting()<>0) {
					echo "<div class='error_sys'>\n";
						echo "<div><strong>".$errnos[$errno][1]."</strong> ($errno)</div>\n";
						echo "<div class='e_txt'>$errstr</div>\n";
						echo "<div><strong>File:</strong> $errfile</div>\n";
						echo "<div><strong>Line:</strong> $errline</div>\n";
					echo "</div>\n";
				}
				if ($log_sys) self::StoreLog("error_sys","Message: $errstr<br />File: $errfile<br />Line: $errline");
			}
		} else {
			//Simple error display
			echo "<div style='margin:2px;padding:2px;background-color:#FFDFDF;border:1px solid #FFA8A8;'>\n";
				echo "<div style='padding:4px;background-color:#FFF0F0;color:#990000;'>$errstr</div>\n";
			echo "</div>";
			
			if ($log_sys) self::StoreLog("error_sys","Message: $errstr<br />File: $errfile<br />Line: $errline");
		}
	}

	static function ClearLog() {
		global $Db,$User;

		if (!$User->IsAdmin()) return false;
		$Db->Query("TRUNCATE #__log");
		return true;
	}

	static function StoreLog($label,$message) {
		global $Db,$User;

		$label = MB::strtolower($label);
		$uniqueid = md5($label.$message);

		if (!@$Db->GetRow("SELECT uniqueid FROM #__log WHERE uniqueid='".$Db->_e($uniqueid)."'"))
		@$Db->Query("INSERT INTO #__log (label,message,ip,time,uniqueid)
					 VALUES ('".$Db->_e($label)."','".$Db->_e(Io::Filter($message))."','".$Db->_e(Utils::Ip2Num($User->Ip()))."',NOW(),'".$Db->_e($uniqueid)."')");
	}

	static function GetLog($label=false) {
		global $Db,$User;

		if (!$User->IsAdmin()) return false;
		$where = ($label) ? "WHERE label='".$Db->_e($label)."' " : "" ;
		return $Db->GetList("SELECT label,message,ip,time FROM #__log {$where}ORDER BY time DESC");
	}
}

?>