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

class BaseUser {
	protected $userdata = array();
	protected $error = "";
	
	function __construct() {
		global $Db,$config_sys;
		//Verify login and get user data
		
		$success = false;
		$cookie_data = Io::GetCookie("user","[^a-zA-Z0-9_]",false,false);
		if ($cookie_data!==false) {
			$cookie_piece = explode("_",$cookie_data);
			$uid = (isset($cookie_piece[0])) ? $cookie_piece[0] : 0 ;
			$row = $Db->GetRow("SELECT * FROM #__user WHERE uid=".intval($uid)." AND status='active'");

			if ($cookie_data==Io::Output($row['uid'])."_".md5(Io::Output($row['user']).Io::Output($row['pass']).Io::Output($row['cookiesalt']))) {
				$this->userdata = $row;

				//Unserialize user options
				$this->userdata['options'] = Utils::Unserialize($this->userdata['options']);

				//Remove unnecessary data
				unset($this->userdata['pass'],$this->userdata['code'],$this->userdata['status'],$this->userdata['cookiesalt']);
				
				//Get roles
				$preroles = Ram::Get("roles");
				$this->userdata['roles'] = array("REGISTERED"=>$preroles['REGISTERED']);
				$roles = Utils::Unserialize(Io::Output($row['roles']));
				if (isset($roles[0])) foreach ($roles as $role) $this->userdata['roles'][$role] = $preroles[$role];
				//Store user roles in ram
				Ram::Set("useroles_".$uid,$this->userdata['roles']);
				
				//Preferred role
				$options = Utils::Unserialize(Io::Output($row['options']));
				$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;
				//Store preferred role in ram
				Ram::Set("userprefrole_".$uid,$prefrole);
				
				//Captcha
				if ($config_sys['captcha_for_users']==0) $config_sys['captcha'] = 0;
				
				$Db->Query("UPDATE #__user SET lastseen=NOW(),lastip='".$Db->_e(Utils::Ip2num($this->Ip()))."' WHERE uid=".intval($this->userdata['uid']));
				$success = true;
			}
		}
		if ($success==false) $this->Logout();
	}

	function Authenticate() {
		global $Db,$config_sys;

		//Get POST data
		$username 	= Io::GetVar("POST","username");
		$password 	= Io::GetVar("POST","password");

		//Check token
		if (!Utils::CheckToken()) {
			$this->error = _t("INVALID_TOKEN");
			return false;
		}

		//Check access
		if (empty($username)) {
			$this->error = _t("THE_FIELD_X_IS_REQUIRED",_t("USERNAME"));
			return false;
		}

		if (empty($password)) {
			$this->error = _t("THE_FIELD_X_IS_REQUIRED",_t("PASSWORD"));
			return false;
		}

		if (!$row = $Db->GetRow("SELECT * FROM #__user WHERE user='".$Db->_e($username)."' AND status='active' LIMIT 1")) {
			$this->error = _t("USER_AND_PASS_NOT_VALID");
			return false;
		}

		if (md5($password)==Io::Output($row['pass'])) {
			//Authentication successful

			//Get user data from db
			$this->userdata['uid'] = Io::Output($row['uid']);
			$this->userdata['user'] = Io::Output($row['user']);
			$this->userdata['name'] = strip_tags(Io::Output($row['name']));
			$this->userdata['email'] = Io::Output($row['email']);
			$this->userdata['regdate'] = Io::Output($row['regdate']);
			$this->userdata['options'] = Io::Output($row['options']);
			$this->userdata['lastseen'] = Io::Output($row['lastseen']);
			$this->userdata['lastip'] = Utils::Num2Ip(Io::Output($row['lastip']));
			
			//Get roles
			$preroles = Ram::Get("roles");
			$this->userdata['roles'] = array("REGISTERED"=>$preroles['REGISTERED']);
			$roles = Utils::Unserialize(Io::Output($row['roles']));
			if (isset($roles[0])) foreach ($roles as $role) $this->userdata['roles'][$role] = $preroles[$role];
			//Store user roles in ram
			Ram::Set("useroles_".$this->userdata['uid'],$this->userdata['roles']);
			
			//Preferred role
			$options = Utils::Unserialize(Io::Output($row['options']));
			$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;
			//Store preferred role in ram
			Ram::Set("userprefrole_".$this->userdata['uid'],$prefrole);
			
			$salt = intval(mt_rand(1000000,9999999));
			$Db->Query("UPDATE #__user SET lastseen=NOW(),lastip='".$Db->_e(Utils::Ip2num($this->Ip()))."',cookiesalt='".intval($salt)."' WHERE uid=".intval($this->userdata['uid']));

			//Create cookie
			$cookie_expire = (intval(Io::GetVar("POST","remember","int"))==1) ? time()+(86400*$config_sys['login_cookie_expire']) : 0 ;
			$cookie_value = $this->userdata['uid']."_".md5($username.md5($password).$salt);
			setcookie("user",$cookie_value,$cookie_expire,_COOKIEPATH);

			return true;
		};

		$this->error = _t("USER_AND_PASS_NOT_VALID");
		return false;
	}

	function GetError() {
		return $this->error;
	}

	function Logout() {
		setcookie("user","",time()-31536000,_COOKIEPATH);

		$this->userdata['uid'] = 0;
		$this->userdata['user'] = false;
		$this->userdata['name'] = "Guest";
		$this->userdata['email'] = false;
		$this->userdata['regdate'] = false;
		$this->userdata['options'] = array();
		$this->userdata['lastseen'] = false;
		$this->userdata['lastip'] = false;
		$this->userdata['additional'] = array();
		$preroles = Ram::Get("roles");
		$this->userdata['roles'] = array("GUEST"=>$preroles['GUEST']);
		
		Session::Regenerate();
		
		return true;
	}
	
	function IsUser($uid=false) {
		global $Db;
		
		if ($uid===false) return ($this->userdata['uid']>0) ? true : false ;
		else return ($Db->GetNum("SELECT uid FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")>0) ? true : false ;
	}
	
	function IsAdmin($uid=false) {
		return $this->CheckRole('ADMIN',$uid);
	}

	function CheckRole($crole,$uid=false) {
		global $Db;
		
		if (empty($crole) || $crole=="GUEST") return true;
		
		if ($uid===false) {
			if (is_array($crole)) {
				foreach ($crole as $rol) if (isset($this->userdata['roles'][MB::strtoupper($rol)])) return true;
			} else {
				return (isset($this->userdata['roles'][MB::strtoupper($crole)])) ? true : false ;
			}
		} else {
			//Check by uid
			$useroles = Ram::Get("useroles_".$uid);
			if (!empty($useroles)) {
				//Data available in ram
				if (is_array($crole)) {
					foreach ($crole as $crol) if (in_array($crol,$useroles)) return true;
				} else {
					return (in_array($crole,$useroles)) ? true : false ;
				}
			} else if ($row = $Db->GetRow("SELECT roles FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")) {
				$roles = Utils::Unserialize(Io::Output($row['roles']));
				$roles[] = "REGISTERED"; //TODO: Load complete data, not labels only?
				//Store data in ram
				Ram::Set("useroles_".$uid,$roles);
				if (isset($roles[0])) {
					if (is_array($crole)) {
						foreach ($crole as $crol) if (in_array($crol,$roles)) return true;
					} else {
						return (in_array($crole,$roles)) ? true : false ;
					}
				}
			}
			return false;
		}
	}

	function GetRole($uid=false) {
		global $Db;
		
		if ($uid===false) {
			if (isset($this->userdata['options']['prefrole'])) {
				return $this->userdata['options']['prefrole'];
			} else {
				return (isset($this->userdata['roles']['REGISTERED'])) ? "REGISTERED" : "GUEST" ;
			}
		} else {
			//Return by uid
			$prefrole = Ram::Get("userprefrole_".$uid);
			if (!empty($prefrole)) {
				//Data available in ram
				return $prefrole;
			} else if ($row = $Db->GetRow("SELECT options,roles FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")) {
				$options = Utils::Unserialize(Io::Output($row['options']));
				$roles = Utils::Unserialize(Io::Output($row['roles']));
				$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;
				//Store data in ram
				Ram::Set("userprefrole_".$uid,$prefrole);
				return $prefrole;
			}
			return "GUEST";
		}
	}

	function GetRoleName($uid=false) {
		global $Db;
		
		if ($uid===false) {
			if (isset($this->userdata['options']['prefrole'])) {
				return $this->userdata['roles'][$this->userdata['options']['prefrole']]['name'];
			} else {
				return (isset($this->userdata['roles']['REGISTERED'])) ? $this->userdata['roles']['REGISTERED']['name'] : $this->userdata['roles']['GUEST']['name'] ;
			}
		} else {
			//Return by uid
			$prefrole = self::GetRole($uid);
			$roles = Ram::Get("roles");
			return $roles[$prefrole]['name'];
		}
	}

	function Uid() {
		return (isset($this->userdata['uid'])) ? $this->userdata['uid'] : 0 ;
	}

	function Name($uid=false,$clean=false) {
		global $Db;
		
		if ($uid===false) {
			$name = $this->userdata['name'];
			if (isset($this->userdata['options']['prefrole'])) {
				$options = ($this->userdata['roles'][$this->userdata['options']['prefrole']]['options']);
			} else {
				$options = ($this->IsUser()) ? $this->userdata['roles']['REGISTERED']['options'] : array() ;
			}
		} else {
			//Return by uid
			$roles = Ram::Get("roles");
			/*$prefrole = Ram::Get("userprefrole_".$uid); //TODO
			$useroles = Ram::Get("useroles_".$uid);
			if (!empty($prefrole) && !empty($useroles)) {
				$name = ??????
				$options = $useroles[$prefrole]['options'];
			} else*/
			if ($row = $Db->GetRow("SELECT name,options FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")) {
				$name = Io::Output($row['name']);
				$options = Utils::Unserialize(Io::Output($row['options']));
				if (isset($options['prefrole'])) {
					Ram::Set("userprefrole_".$uid,$options['prefrole']);
					$options = $roles[$options['prefrole']]['options'];
				} else {
					$options = $roles['REGISTERED']['options'];
				}
			} else {
				$name = $roles['GUEST']['name'];
				$options = $roles['GUEST']['options'];
			}
		}
		if ($clean===false) {
			$style = array();
			if (isset($options['style']) && $options['style']=="bold") $style[] = "font-weight:bold;";
			if (isset($options['style']) && $options['style']=="italic") $style[] = "font-style:italic;";
			if (isset($options['color'])) $style[] = "color:".$options['color'].";";
			if (sizeof($style)) $name = "<span style='".implode("",$style)."'>$name</span>";
		}
		
		return $name;
	}
	
	function GetInfo($key,$default=false) {
		return (isset($this->userdata[$key])) ? $this->userdata[$key] : $default ;
	}
	
	function GetOption($key,$default=false) {
		return (isset($this->userdata['options'][$key])) ? $this->userdata['options'][$key] : $default ;
	}
	function GetOptionUid($uid,$key,$default=false) {
		global $Db;

		if ($row = $Db->GetRow("SELECT options FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")) {
			$options = Utils::Unserialize(Io::Output($row['options']));
			return (isset($options[$key])) ? $options[$key] : $default ;
		} else {
			return $default;
		}
	}

	function SetOption($key,$value) {
		global $Db;

		$this->userdata['options'][$key] = $value;
		$Db->Query("UPDATE #__user SET options='".$Db->_e(Utils::Serialize($this->userdata['options']))."' WHERE uid='".intval($this->Uid())."'");

		return true;
	}

	function DeleteOption($key) {
		global $Db;

		if (isset($this->userdata['options'][$key])) {
			unset($this->userdata['options'][$key]);
			$Db->Query("UPDATE #__user SET options='".$Db->_e(Utils::Serialize($this->userdata['options']))."' WHERE uid='".intval($this->Uid())."'");

			return true;
		} else {
			return false;
		}
	}

	function DisplayAvatar($uid=false,$size=false,$return=false,$override=false) {
		global $Db,$SysData;
		
		if ($size===false) $size = 60;
		
		if ($uid===false) {
			$name = $this->userdata['name'];
			$avatar = $this->GetOption("avatar",array("selector"=>"MemHT","value"=>"blank.jpg"));
		} else {
			if ($row = $Db->GetRow("SELECT name,options FROM #__user WHERE uid='".intval($uid)."' AND status='active' LIMIT 1")) {
				$name = Io::Output($row['name']);
				$options = Utils::Unserialize(Io::Output($row['options']));
				$avatar = (isset($options["avatar"])) ? $options["avatar"] : array("selector"=>"MemHT","value"=>"blank.jpg") ;
			} elseif ($override!==false) {
				$name = $override['name'];
				$avatar = array("selector"=>$override['selector'],"value"=>$override['value']);
			} else {
				$name = _t("UNKNOWN");
				$avatar = array("selector"=>"MemHT","value"=>"blank.jpg");
			}
		}
		
		$path = $SysData['class']['Avatar']->GetPath($avatar,$size);
		$image = "<img src='$path' width='$size' height='$size' title='".CleanTitleAtr($name)."' alt='".CleanTitleAtr($name)."' />";
		
		if ($return===false) {
			echo $image;
		} else {
			return $image;
		}
	}

	//User information
	function Ip() {
		return GetIp();
	}
	
	function Data() {
		return $this->userdata;
	}
}

//Initialize extension
global $Ext,$config_sys;
if (!$Ext->InitExt("User")){class User extends BaseUser{}}

//Users management
$User = new User();

if (isset($_GET['logout'])) {
    $User->Logout();
    Utils::Redirect($config_sys['site_url']);
}

$guest = ($User->IsUser()) ? 0 : 1 ;
$Db->Query("REPLACE INTO #__online (ip,uid,guest,date) VALUES ('".$Db->_e(Utils::Ip2num($Visitor['ip']))."','".$Db->_e($User->Uid())."','".intval($guest)."',NOW())");
$Db->Query("DELETE FROM #__online WHERE (date + INTERVAL 5 MINUTE) < NOW()");

?>