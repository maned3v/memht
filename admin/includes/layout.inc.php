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
defined("_ADMINCP") or die("Access denied");

class Layout {
	static function Header($args=array()) {
		global $config_sys;

		$controller = Ram::Get('controller');
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $config_sys['default_language']; ?>" xml:lang="<?php echo $config_sys['default_language']; ?>" dir="<?php echo _t("LANGDIR") ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo _t("ADMINISTRATION")." "._SITETITLE_SEPARATOR." ".$config_sys['site_name']; ?></title>

		<meta name="generator" content="MemHT Portal - Free PHP CMS and Blog" />
		<!-- Site base (relative) path -->
		<base href="<?php echo $config_sys['site_url']; ?>/" />

		<!-- Stylesheet -->
		<link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>MemHT<?php echo _DS; ?>style<?php echo _DS; ?>common.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colortips<?php echo _DS; ?>colortips.css" type="text/css" />

        <!-- JavaScript -->
		<script src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colortips<?php echo _DS; ?>colortips.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>MemHT<?php echo _DS; ?>common.js" type="text/javascript" charset="utf-8"></script>
		<?php if (isset($args['editor']) && $args['editor']==true) { ?>
			<script src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>TinyMCE<?php echo _DS; ?>tinymce.min.js" type="text/javascript" charset="utf-8"></script>
            <script src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>TinyMCE<?php echo _DS; ?>init.js" type="text/javascript" charset="utf-8"></script>
		<?php } ?>
        
        <link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>admin<?php echo _DS; ?>templates<?php echo _DS.$config_sys['admincp_template']._DS; ?>style.css" type="text/css" />
		
		<script type="text/javascript" charset="utf-8">		
		$(document).ready(function() {
			$("#selectall").click(function () {
				$('.cb:checkbox').prop('checked', this.checked);
			});
			$(".cb:checkbox").click(function () {
				if (!$(this).is(':checked'))
				$("#selectall").prop('checked', false);
			});
		});		
		</script>
				
		<!-- Template custom head -->
		<?php if (file_exists("admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."head.php")) include("admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."head.php"); ?>
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo $config_sys['site_url']; ?>/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<?php
	}

	static function Footer() {
		global $starttime;

		//Load time (end)
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = sprintf("%01.3f",($endtime - $starttime));
		echo "<div style='font-size:80%; text-align:center; margin:2px 0;'>Loaded in $totaltime sec</div>";
		
		?>

	</body>
</html><?php
	}
}

?>