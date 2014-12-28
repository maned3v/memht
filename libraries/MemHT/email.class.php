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

class BaseEmail {
	var $mailer = "mail";
	var $smtp_host = "";
	var $smtp_user = "";
	var $smtp_pass = "";
	var $smtp_ssl = 0;
	var $smtp_port = "";
	var $charset = "utf-8";
	var $type = "text";

	private $_emails = array();
	private $_subject = "";
	private $_from_name = "";
	private $_from_email = "";
	private $_content = "";
	private $_content_alt = "";

	private $_errors = array();
	
	var $debugmode = false;

	function __construct() {
		global $config_sys;

		$this->mailer = $config_sys['email_mailer'];
		$this->smtp_host = $config_sys['email_smtp_host'];
		$this->smtp_user = $config_sys['email_smtp_user'];
		$this->smtp_pass = $config_sys['email_smtp_pass'];
		$this->smtp_ssl = $config_sys['email_smtp_ssl'];
		$this->smtp_port = $config_sys['email_smtp_port'];
		$this->charset = $config_sys['email_charset'];
		$this->type = $config_sys['email_type'];

		$this->_subject = $config_sys['site_name'];
		$this->_from_name = $config_sys['site_name'];
		$this->_from_email = $config_sys['site_email'];
	}

	function AddEmail($email,$name="") {
		if (Utils::ValidEmail($email) && !$this->BuggyMan($name)) {
   			$this->_emails[$email] = $name;
		} else {
			$this->_errors[] = "Email not valid: ".Io::Filter($name)." &lt;".Io::Filter($email)."&gt;";
		}
	}

	function SetFrom($email,$name="") {
		if (Utils::ValidEmail($email) && !$this->BuggyMan($name)) {
			$this->_from_email = $email;
			$this->_from_name = $name;
		} else {
			$this->_errors[] = "&quot;From&quot; email not valid: ".Io::Filter($name)." &lt;".Io::Filter($email)."&gt;";
		}
	}

	function SetSubject($subject) {
		if (!$this->BuggyMan($subject)) {
			$this->_subject = $subject;
		} else {
			$this->_errors[] = "Subject not valid: &quot;".Io::Filter($subject)."&quot;";
		}
	}

	function SetContent($content,$alternative="") {
		$this->_content = $content;
		$this->_content_alt = $alternative;
	}
	
	function ClearAddresses() {
		$this->_emails = array();
	}

	function Send() {
		global $User;
		
		if (sizeof($this->_errors)) return false;
		if (!sizeof($this->_emails)) {
			$this->_errors[] = "Emails list empty";
			return false;
		}

		try {
			if ($this->debugmode && $User->IsAdmin()) {
				foreach ($this->_emails as $email => $name) {
					echo "<div style='background-color:#FFF;color:#F00;padding:1px;margin:1px;'>Sending mail to $name &lt;$email&gt;</div>\n";
				}
			} else {
				require_once(_PATH_LIBRARIES._DS."PHPMailer"._DS."class.phpmailer.php");
				$mail = new PHPMailer();
				$mail->From = $this->_from_email;
				$mail->FromName = $this->_from_name;
				$mail->Subject = $this->_subject;
				
				if ($this->mailer=="smtp" && !empty($this->smtp_host) && !empty($this->smtp_user)) {
					//Smtp
					$mail->Mailer = "smtp";
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->Host = $this->smtp_host;
					if ($this->smtp_ssl) {
						$mail->SMTPSecure = 'ssl';
					}
					$mail->Port = $this->smtp_port;
					$mail->Username = $this->smtp_user;
					$mail->Password = $this->smtp_pass;
				} else {
					//Mail
					$mail->Mailer = "mail";
				}
				
				$mail->SingleTo = true;
				$mail->CharSet = $this->charset;
				$mail->WordWrap = 75;
				
				$mail->IsHTML((MB::strtolower($this->type)=="html") ? true : false);
				$mail->Body = $this->_content;
				if (!empty($this->_content_alt)) $mail->AltBody = $this->_content_alt;
				
				foreach ($this->_emails as $email => $name) $mail->AddAddress($email,$name);
				
				$result = $mail->Send();
				
				$mail->ClearAddresses();
				if (!$result) {
					$this->_errors[] = $mail->ErrorInfo;
					return false;
				}
			}			
		} catch(Exception $e) {
			$this->_errors[] = $e->errorMessage();
			return false;
		}

		return true;
	}

	function BuggyMan($string) {
		$deny = array("content-type:","mime-version:","multipart/mixed","Content-Transfer-Encoding:","bcc:","cc:","to:");
		foreach ($deny as $bad) if (preg_match("#".$bad."#i",$string)) return true;
		return false;
	}

	function GetErrors() {
		return $this->_errors;
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Email")){class Email extends BaseEmail{}}

?>