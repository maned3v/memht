<?php

error_reporting(E_ALL);
ini_set('error_reporting',E_ALL);
ini_set('log_errors','1');
header('Content-type: text/html; charset=utf-8');

define("_LOAD",true);

@include_once("../includes/config.inc.php"); //Database configuration
include_once("template.php");

$step = (isset($_POST["step"])) ? intval($_POST["step"]) : 0 ;

template_header($step);

switch ($step) {
	default:
	case 0: if (defined("_INSTALLED")) die("Already installed"); license(); break;
	case 1: if (defined("_INSTALLED")) die("Already installed"); requirements(); break;
	case 2: if (defined("_INSTALLED")) die("Already installed"); databasedata(); break;
	case 3:	if (defined("_INSTALLED")) die("Already installed"); databaseconnection(); break;
	case 4:	adminaccount(); break;
	case 5:	finish(); break;
}

template_footer();

//License
function license() {
	?>
	<div class="tpl_page_title">License</div>
	<form id="form" name="form" method="post" action="install.php">
		<div><iframe src="license.html" frameborder="0" scrolling="auto" style="width:952px; height:300px; border:3px solid #EEE;"></iframe></div>
		<div class="spacer"></div>
		<div align="right"><input type="submit" name="Submit" value="Accept" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
		<input type="hidden" name="step" value="1">
	</form>
	<?php
}

//Requirements
function requirements() {
	?>
		<div class="tpl_page_title">Required components</div>
		<table width="100%" cellpadding="2" cellspacing="0" class="tgrid">
			<thead>
				<tr>
					<th>Description</th>
					<th style="text-align:center;">Required</th>
					<th style="text-align:center;">Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="60%">PHP version</td>
					<td width="20%" style="text-align:center;">5.2.0</td>
					<td width="20%" style="text-align:center;"><?php echo (version_compare(PHP_VERSION, "5.2.0", ">=")) ? "<span class='ok'>Yes</span>" : "<span class='bad'>No</span>" ; ?> (<?php echo PHP_VERSION; ?>)</td>
				</tr>
				<tr>
					<td class="dark">MySQLi/MySQL(legacy)</td>
					<td style="text-align:center;">4.1</td>
					<td style="text-align:center;"><?php echo (extension_loaded("mysqli")) ? "<span class='ok'>MySQLi Available</span>" : "<span class='bad'>MySQLi Unavailable</span>" ; ?> - <?php echo (extension_loaded("mysql")) ? "<span class='ok'>MySQL Available</span>" : "<span class='bad'>MySQL Unavailable</span>" ; ?></td>
				</tr>
				<tr>
					<td>Writable configuration folder (root/includes)</td>
					<td style="text-align:center;">Writable</td>
					<td style="text-align:center;"><?php echo (is_writable("../includes/")) ? "<span class='ok'>Writable</span>" : "<span class='bad'>Unwritable</span>" ; ?></td>
				</tr>
				<tr>
					<td>Writable templates compilation folder (root/templates/compiled)</td>
					<td style="text-align:center;">Writable</td>
					<td style="text-align:center;"><?php echo (is_writable("../templates/compiled/")) ? "<span class='ok'>Writable</span>" : "<span class='bad'>Unwritable</span>" ; ?></td>
				</tr>
			</tbody>
		</table>
		
		<div class="info">In case of unavailable components into the required section, MemHT Portal will not work correctly!<br />Add or fix the missing component before proceeding with the installation.</div>
		
		<div class="tpl_page_title" style="margin-top:10px;">Optional components</div>
		<table width="100%" cellpadding="2" cellspacing="0" class="tgrid">
			<thead>
				<tr>
					<th>Description</th>
					<th style="text-align:center;">Recommended</th>
					<th style="text-align:center;">Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="60%">Multibyte (mbstring) PHP extension</td>
					<td width="20%" style="text-align:center;">Available</td>
					<td width="20%" style="text-align:center;"><?php echo (extension_loaded("mbstring")) ? "<span class='ok'>Available</span>" : "<span class='bad'>Unavailable</span>" ; ?></td>
				</tr>
				<tr>
					<td width="60%">GD graphics library</td>
					<td width="20%" style="text-align:center;">Available</td>
					<td width="20%" style="text-align:center;"><?php echo (extension_loaded("gd")) ? "<span class='ok'>Available</span>" : "<span class='bad'>Unavailable</span>" ; ?></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/sub.gif"> PNG support</td>
					<td style="text-align:center;">Yes</td>
					<td style="text-align:center;"><?php echo ($check["PNG"] = @gd_info()) ? "<span class='ok'>Yes</span>" : "<span class='bad'>No</span>" ; ?></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/sub.gif"> FreeType support</td>
					<td style="text-align:center;">Yes</td>
					<td style="text-align:center;"><?php echo ($check["FreeType Support"] = @gd_info()) ? "<span class='ok'>Yes</span>" : "<span class='bad'>No</span>" ; ?></td>
				</tr>
				<tr>
					<td >Zlib compression</td>
					<td style="text-align:center;">Available</td>
					<td style="text-align:center;"><?php echo (extension_loaded("zlib")) ? "<span class='ok'>Available</span>" : "<span class='bad'>Unavailable</span>" ; ?></td>
				</tr>
			</tbody>
		</table>

		<form id="form" name="form" method="post" action="install.php">
			<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
			<input type="hidden" name="step" value="2">
		</form>
	<?php
}

//Database data
function databasedata() {
	?>
	<script type="text/javascript">
		function validate() {
			if ((document.form.host.value=="") || (document.form.username.value=="") || (document.form.password.value=="") || (document.form.name.value=="") || (document.form.prefix.value=="")) {
				alert("All fields are required");
				return false;
			} else {
				return true;
			}
		}
	</script>
	
	<div class="tpl_page_title">Database data</div>
	
	<form id="form" name="form" method="post" action="install.php" onsubmit="return validate();">
	<table width="100%" cellpadding="2" cellspacing="0" class="tgrid">
		<thead>
			<tr>
				<th>Field</th>
				<th>Value</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="15%" style="vertical-align:middle;">Host</td>
				<td width="20%"><input type="text" name="host"></td>
				<td width="65%" style="vertical-align:middle;">Host name. It's provided by the hosting provider. Example: localhost, sql.example.com</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">Username</td>
				<td><input type="text" name="username"></td>
				<td style="vertical-align:middle;">Database user name. It's provided by the hosting provider or chosen during the database creation.</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">Password</td>
				<td><input type="password" name="password" ></td>
				<td style="vertical-align:middle;">Database password. It's provided by the hosting provider or chosen during the database creation.</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">Name</td>
				<td><input type="text" name="name"></td>
				<td style="vertical-align:middle;">Database name. It's provided by the hosting provider or chosen during the database creation.</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;">Tables prefix</td>
				<td><input type="text" name="prefix" value="memht"></td>
				<td style="vertical-align:middle;">Database tables prefix. It can be used to host multiple MemHT installations in the same database.</td>
			</tr>
		</tbody>
	</table>
	
	<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
	<input type="hidden" name="step" value="3">
	
	</form>
		
	<?php
}

//Database connection
function databaseconnection() {
	?>
	<div class="tpl_page_title">Tables</div>

	<?php
	$host = (isset($_POST["host"])) ? in($_POST["host"]) : "" ;
	$user = (isset($_POST["username"])) ? in($_POST["username"]) : "" ;
	$pass = (isset($_POST["password"])) ? in($_POST["password"]) : "" ;
	$name = (isset($_POST["name"])) ? in($_POST["name"]) : "" ;
	$prefix = (isset($_POST["prefix"])) ? in($_POST["prefix"]) : "memht" ;
	$continue = false;
	if ($db = @mysqli_connect($host,$user,$pass)) {
		if (@mysqli_select_db($db,$name)) {
			$success = true;
			parse_mysql_dump("db.sql",$prefix,$db);
			if ($success) {
				writeConfigFile($host,$user,$pass,$name,$prefix);
				
				$host = "http://".$_SERVER['HTTP_HOST'];
				$dir = pathinfo($_SERVER['SCRIPT_NAME']); $dir = $dir['dirname'];
				$dir = str_replace("/installation","",$dir);
				if ($dir=="/") { $dir = ""; }
				$path = $host.$dir;
				//HTACCESS i
				if ($content = @file_get_contents("../.htaccess")) {
					if (!stristr($content,"RewriteBase {$dir}/")) {
						$content = str_replace("RewriteBase /","RewriteBase {$dir}/",$content);
						$content = str_replace("ErrorDocument 500 /index","ErrorDocument 500 {$dir}/index",$content);
						$content = str_replace("ErrorDocument 404 /index","ErrorDocument 404 {$dir}/index",$content);
						$content = str_replace("ErrorDocument 403 /index","ErrorDocument 403 {$dir}/index",$content);
						$content = str_replace("ErrorDocument 401 /index","ErrorDocument 401 {$dir}/index",$content);
						$content = str_replace("ErrorDocument 400 /index","ErrorDocument 400 {$dir}/index",$content);
						if (is_writable("../.htaccess")) {
							$continue = true;
						} else {
							if (@chmod("../.htaccess",0755)) {
								$continue = true;
							} else {
								$continue = false;
							}
						}
						if ($continue) {
							if ($handle = @fopen("../.htaccess","w")) {
								@fwrite($handle,$content);
								@fclose($handle);
							}
							@chmod("../.htaccess",0644);
						}
					}
				}
				//HTACCESS e

				mysqli_query($db,"INSERT INTO ".$prefix."_configuration (label,value) VALUES ('site_url','".dbin($path,$db)."'),('site_installed',NOW())");

				@mysqli_close($db);
				
				$continue = true;
				
				echo "<div class='info'>Database structure and simple data installed</div>\n";
			} else {
				echo "<div class='info'>Error: Can't write the configuration file</div>\n";
			}
		} else {
			echo "<div class='info'>Error: Cannot select the database <strong>$name</strong></div>\n";
		}
	} else {
		echo "<div class='info'>Error: Cannot connect to the database at <strong>$host</strong></div>\n";
	}
	if ($continue) {
	?>
		<form id="form" name="form" method="post" action="install.php" onsubmit="return validate();">
			<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
			<input type="hidden" name="step" value="4">
		</form>
	<?php
	}
}

//Admin account
function adminaccount() {
	?>
	<script type="text/javascript">
		function validate() {
			if ((document.form.name.value=="") || (document.form.username.value=="") || (document.form.password.value=="") || (document.form.email.value=="")) {
				alert("All fields are required");
				return false;
			} else {
				return true;
			}
		}
	</script>

	<div class="tpl_page_title">Admin account</div>

		<form id="form" name="form" method="post" action="install.php" onsubmit="return validate();">
		<table width="100%" cellpadding="2" cellspacing="0" class="tgrid">
			<thead>
				<tr>
					<th>Field</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="50%" style="vertical-align:middle;">Display name</td>
					<td width="50%"><input type="text" name="name"></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;">Username</td>
					<td><input type="text" name="username"></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;">Password</td>
					<td><input type="text" name="password"></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;">Email</td>
					<td><input type="text" name="email"></td>
				</tr>
			</tbody>
		</table>

			<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
			<input type="hidden" name="step" value="5">
		</form>
	<?php
}

//Finish
function finish() {
	global $config_db;
	?>
	<div class="tpl_page_title">Finish</div>
	<?php
	$conn = mysqli_connect($config_db['host'],$config_db['user'],$config_db['pass']);
	mysqli_select_db($conn,$config_db['name']);

	$name = (isset($_POST["name"])) ? in($_POST["name"]) : "" ;
	$user = (isset($_POST["username"])) ? in($_POST["username"]) : "" ;
	$pass = (isset($_POST["password"])) ? in($_POST["password"]) : "" ;
	$email = (isset($_POST["email"])) ? in($_POST["email"]) : "" ;

	mysqli_query($conn,"INSERT INTO ".$config_db['prefix']."_configuration (label,value) VALUES ('site_email','".dbin($email,$conn)."'),('files_path','".substr((md5(uniqid(mt_rand(0,time()),true))),0,10)."')");
    mysqli_query($conn,"REPLACE INTO ".$config_db['prefix']."_configuration (label,value) VALUES ('maintenance_whiteip','".inet_pton('127.0.0.1')."')");

	mysqli_query($conn,"INSERT INTO ".$config_db['prefix']."_user (uid,user,name,pass,email,regdate,options,roles,status)
				 VALUES ('1','".dbin($user,$conn)."','".dbin($name,$conn)."','".md5($pass)."','".dbin($email,$conn)."',NOW(),'a:1:{s:8:\"prefrole\";s:5:\"ADMIN\";}','a:2:{i:0;s:5:\"ADMIN\";i:1;s:9:\"MODERATOR\";}','active')");

	echo "<div class='info'>Installation finished. Now delete the installation folder.</div>\n";
}

/*
 * FUNCTIONS
 * */

if (version_compare(PHP_VERSION, '6.0.0') >= 0) {
	if (!function_exists("get_magic_quotes_gpc")) {
		function get_magic_quotes_gpc(){
			return false;
		}
	}
}

function in($string) {
	//Deprecated
	//if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return htmlspecialchars($string,ENT_QUOTES,'UTF-8');
}

function dbin($string,$conn) {
	return mysqli_real_escape_string($conn,$string);
}

function parse_mysql_dump($filename,$prefix="memht",$conn){
	global $success;
	$templine = '';

	$lines = file($filename);
	foreach ($lines as $line_num => $line) {
		if (substr($line, 0, 2) != '--' && $line != '') {
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';') {
				$templine = str_replace("#__",$prefix."_",$templine);
				if (!mysqli_query($conn,$templine)) {
					$success = false;
					echo "<div>Error performing query <b>".$templine."</b>:".mysqli_error($conn)."</div>\n";
				}
				$templine = '';
			}
		}
	}
}

function writeConfigFile($host,$username,$password,$name,$prefix) {
		global $success;

		$content = "<?php\n";
		$content .= "\n";
		$content .= "//========================================================================\n";
		$content .= "// MemHT Portal\n";
		$content .= "// \n";
		$content .= "// Copyright (C) 2008-2012 by Miltenovikj Manojlo <dev@miltenovik.com>\n";
		$content .= "// http://www.memht.com\n";
		$content .= "// \n";
		$content .= "// This program is free software; you can redistribute it and/or modify\n";
		$content .= "// it under the terms of the GNU General Public License as published by\n";
		$content .= "// the Free Software Foundation; either version 2 of the License, or\n";
		$content .= "// (at your opinion) any later version.\n";
		$content .= "// \n";
		$content .= "// This program is distributed in the hope that it will be useful,\n";
		$content .= "// but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
		$content .= "// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the\n";
		$content .= "// GNU General Public License for more details.\n";
		$content .= "// \n";
		$content .= "// You should have received a copy of the GNU General Public License along\n";
		$content .= "// with this program; if not, see <http://www.gnu.org/licenses/> (GPLv2)\n";
		$content .= "// or write to the Free Software Foundation, Inc., 51 Franklin Street,\n";
		$content .= "// Fifth Floor, Boston, MA02110-1301, USA.\n";
		$content .= "//========================================================================\n";
		$content .= "\n";
		$content .= "/**\n";
		$content .= " * @author      Miltenovikj Manojlo <dev@miltenovik.com>\n";
		$content .= " * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.\n";
		$content .= " * @license     GNU/GPLv2 http://www.gnu.org/licenses/\n";
		$content .= " */\n";
		$content .= "\n";
		$content .= "//Deny direct access\n";
		$content .= "defined(\"_LOAD\") or die(\"Access denied\");\n";
		$content .= "\n";
		$content .= "//System debug\n";
		$content .= "\$config_sys['debug'] = 0;\n";
		$content .= "\n";
		$content .= "//Properties\n";
		$content .= "\$config_db['type'] = \"mysqli\";\n";
		$content .= "\$config_db['prefix'] = \"$prefix\";\n";
		$content .= "\$config_db['persistent'] = 0;\n";
		$content .= "\n";
		$content .= "//Connection data\n";
		$content .= "\$config_db['host'] = \"$host\";\n";
		$content .= "\$config_db['user'] = \"$username\";\n";
		$content .= "\$config_db['pass'] = \"$password\";\n";
		$content .= "\$config_db['name'] = \"$name\";\n";
		$content .= "\n";
		$content .= "define(\"_INSTALLED\",true);\n";

		$myfile = '../includes/config.inc.php';
		if (is_writable('../includes/')) {
			$handle = fopen($myfile, 'w');
			fwrite($handle, $content);
			fclose($handle);
			$success = true;
		} else {
			$success = false;
			echo "<div class='tpl_page_title'>Warning</div>\n";
			echo "<div class='info'>\n";
				echo "<div><b>The folder root/includes/ does not exist or is not writable!</b></div>\n";
				echo "<div><b>What to do?</b></div>";
				echo "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='images/sub.gif'> Download the configuration file from <a href='output.php?h=$host&u=$user&p=$password&n=$name&prefix=$prefix' target='_blank'><b style='color:#990000;'>HERE</b></a> and upload it into the root/includes/ folder.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The final configuration file path must be: root/includes/config.ing.php<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='images/sub.gif'> When done, continue the installation wizard.</div>\n";
			echo "</div>\n";
		}
}

?>