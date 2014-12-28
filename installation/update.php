<?php

error_reporting(E_ALL);
ini_set('error_reporting',E_ALL);
ini_set('log_errors','1');
header('Content-type: text/html; charset=utf-8');

define("_LOAD",true);

@include_once("../includes/config.inc.php"); //Database configuration
include_once("template_update.php");

$step = (isset($_POST["step"])) ? intval($_POST["step"]) : 0 ;

template_header($step);

$versions = array("5.0.0.5"	=> 5005,
				  "5.0.0.9"	=> 5009,
				  "5.0.1.0"	=> 5010);

if (!defined("_INSTALLED")) die("Not installed yet");

switch ($step) {
	default:
	case 0: license(); break;
	case 1: installedversion(); break;
	case 2: tablesupdate(); break;
	case 3:	finish(); break;
}

template_footer();

//License
function license() {
	?>
	<div class="tpl_page_title">License</div>
	<form id="form" name="form" method="post" action="update.php">
		<div><iframe src="license.html" frameborder="0" scrolling="auto" style="width:952px; height:300px; border:3px solid #EEE;"></iframe></div>
		<div class="spacer"></div>
		<div align="right"><input type="submit" name="Submit" value="Accept" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
		<input type="hidden" name="step" value="1">
	</form>
	<?php
}

//Installed version
function installedversion() {
	global $config_db,$versions;
	?>
		<div class="tpl_page_title">Version</div>
		<?php
		$conn = mysqli_connect($config_db['host'],$config_db['user'],$config_db['pass']);
		mysqli_select_db($conn,$config_db['name']);
		
		$row = @mysqli_fetch_assoc(@mysqli_query($conn,"SELECT value FROM ".$config_db['prefix']."_configuration WHERE label='engine_version'"));
		
		if (isset($row['value']) && isset($versions[$row['value']])) {
			$ver = $versions[$row['value']];
			
			echo "<div class='lilstep'>Detected version: <strong>".$row['value']."</strong> <em>(".$versions[$row['value']].")</em><br /><br />You can now proceed hooOoOooOoo =|</div>\n";
			
			echo "<div class='error' style='font-weight:bold;'>Not just yet! Did you make a backup of your database?</div>\n";
			
			?>
			<form id="form" name="form" method="post" action="update.php">
			<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
			<input type="hidden" name="step" value="2">
			<input type="hidden" name="ver" value="<?php echo $ver; ?>">
			</form>
			<?php
		} else {
			echo "<div class='error'>Cannot detect your MemHT version! Please contact the MemHT community and ask for help, i'm sure you'll find some good samaritan.</div>\n";
		}
}

//Tables
function tablesupdate() {
	global $config_db;

	$ver = (isset($_POST["ver"])) ? in($_POST["ver"]) : "" ;
	
	?>
		<div class="tpl_page_title">Tables</div>
		<?php
		$conn = mysqli_connect($config_db['host'],$config_db['user'],$config_db['pass']);
		mysqli_select_db($conn,$config_db['name']);
		
		switch ($ver) {
			case 5005:
				echo "<div class='lilstep'>Ok, let's do it... AaaaAaaaAAAAaaaaa wattaaAAaa argGGGgggghhhaaAAAaaaaa!!!!!!</div>";
				require_once("update5005.php");
				break;
			case 5009:
				echo "<div class='lilstep'>Ok, let's do it... AaaaAaaaAAAAaaaaa wattaaAAaa argGGGgggghhhaaAAAaaaaa!!!!!!</div>";
                require_once("update5009.php");
				break;
			case 5010:
                echo "<div class='error'>Your MemHT seems to be already up to date! Aborting!!!!!</div>";
				exit;
				break;
			default:
				echo "<div class='error'>Your MemHT version cannot be handled by this installer! Aborting!!!!!</div>";
				exit;
				break;
		}
		
		echo "<div class='info'>If you see no errors around, the database has been updated successfully... *coff* *coff* probably</div>\n";
		echo "<div class='error'>Do not refresh or execute this page again for any reason!!! If you see errors, restore your database using the backup you did before and try again!</div>\n";
		
		?>
		<form id="form" name="form" method="post" action="update.php">
		<div align="right"><input type="submit" name="Submit" value="Next" style="margin-top:10px;" class="sys_form_button ui-corner-all"></div>
		<input type="hidden" name="step" value="3">
		</form>
		<?php
}

//Finish
function finish() {
	global $config_db;
	?>
	<div class="tpl_page_title">Finish</div>
	<?php
	
	echo "<div class='lilstep'>Update finished! May the force be with you young admin =)</div>\n";
}

/*
 * FUNCTIONS
 * */

if (version_compare(PHP_VERSION, '6.0.0') >= 0) {
	function get_magic_quotes_gpc(){
		return false;
	}
}

function in($string) {
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return htmlspecialchars($string,ENT_QUOTES,'UTF-8');
}

function dbin($string,$conn) {
	return mysqli_real_escape_string($conn,$string);
}

function parse_mysqli_dump($filename,$prefix="memht",$conn){
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

?>