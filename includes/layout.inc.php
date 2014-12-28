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

class Layout {
	static function Header($args=array()) {
		global $config_sys, $User,$Ext;

		$controller = Ram::Get('controller');
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $config_sys['default_language']; ?>" xml:lang="<?php echo $config_sys['default_language']; ?>" dir="<?php echo _t("LANGDIR") ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $config_sys['site_name']; ?></title>

		<meta name="robots" content="index, follow" />
		<meta name="revisit-after" content="7 days" />
		<meta name="keywords" content="<?php echo (!empty($controller['meta_keywords']) && !_ISHOME) ? $controller['meta_keywords'] : $config_sys['meta_keywords']; ?>" />
		<meta name="description" content="<?php echo (!empty($controller['meta_description']) && !_ISHOME) ? $controller['meta_description'] : $config_sys['meta_description']; ?>" />
		<meta name="generator" content="MemHT Portal - Free PHP CMS and Blog" />
		<base href="<?php echo $config_sys['site_url']; ?>/" />
		<link rel="shortcut icon" href="<?php echo $config_sys['site_url']; ?>/favicon.ico" type="image/x-icon" />

		<!-- Site custom head -->
		<?php if (!empty($config_sys['custom_head'])) echo $config_sys['custom_head']; ?>

		<!-- Site main language -->
		<meta http-equiv="Content-Language" content="<?php echo $config_sys['default_language']; ?>" />

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
            <script type="text/javascript">
				tinyMCE.settings.content_css = "<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>MemHT<?php echo _DS; ?>style<?php echo _DS; ?>editor.css";
            </script>
		<?php } ?>
		
        <!-- Plugin custom head -->
		<?php if (file_exists(_PATH_PLUGINS._DS.$controller['controller']._DS."head.php")) include(_PATH_PLUGINS._DS.$controller['controller']._DS."head.php"); ?>

		<!-- Template custom head -->
		<?php if (file_exists("templates"._DS.$config_sys['template']._DS."head.php")) include("templates"._DS.$config_sys['template']._DS."head.php"); ?>

		<script type="text/javascript">
		<!--
		<?php if (isset($args['comments']) && $args['comments']==true && $User->IsAdmin()) { ?>
			function delcomment(item,id) {
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "<?php echo $config_sys['site_url']._DS."index.php?"._NODE; ?>=<?php echo _PLUGIN; ?>&op=delcomment",
					data: "id="+id+"&item="+item,
					success: function(){
						$("#comment"+id).slideUp();
					}
				});
			}
		<?php } ?>
		<?php if (isset($args['rating']) && $args['rating']==true) { ?>
			/* RATING */
			function rate_item(plugin,id,vote) {
				var glue = "&";
				$.ajax({
					type: "POST",
					url: "<?php echo $config_sys['site_url']._DS."index.php?"._NODE; ?>="+plugin+"&op=rate",
					cache: false,
					data: "id="+id+glue+"vote="+vote,
					success: function(data) {
						$('.tpl_rating_placeholder').html(data);
					}
				});
			}
		<?php } ?>
        <?php if (isset($config_sys['terms_of_use_dialog']) && $config_sys['terms_of_use_dialog']==1 && !isset($_COOKIE['dismisstou'])) { ?>
            /* TERMS OF USE */
            $(document).ready( function() {
                $('#toudialog').show();
                $('#dismisstou').click(function() {
                    var today = new Date();
                    var the_cookie_date = new Date(today.getTime() + (1000 * 60 * 60 * 24 * 365)); //365 days
                    var the_cookie = "dismisstou=true";
                    var the_cookie = the_cookie + ";expires=" + the_cookie_date;
                    document.cookie=the_cookie;
                });
            });
        <?php } ?>
		//-->
		</script>
		
		<?php $Ext->RunMext("Head"); ?>
	</head>
	<body>
		<?php

		$Ext->RunMext("Body");
	}

	static function Footer() {
		global $config_sys,$User,$starttime,$Ext;
		
		if ($config_sys['debug'] && $User->IsAdmin()) {
			//Load time (end)
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = sprintf("%01.3f",($endtime - $starttime));
			echo "<div style='font-size:80%; text-align:center; margin:2px 0;'>Loaded in $totaltime sec</div>";
		}
		
		require_once(_PATH_LIBRARIES._DS."MemHT"._DS."stats.class.php"); //Statistics and spider detection [Ext,Stats]

		//Site custom foot
		if (!empty($config_sys['custom_foot'])) echo $config_sys['custom_foot'];
		
		$Ext->RunMext("Footer");

        //Terms of use
        if (isset($config_sys['terms_of_use_dialog']) && $config_sys['terms_of_use_dialog']==1 && !isset($_COOKIE['dismisstou'])) {
            /* TERMS OF USE */
            ?>
            <div id="toudialog" class="memdialog">
                <div class="title"><?php echo _t('TERMS_OF_USE'); ?></div>
                <div class="notice"><?php echo $config_sys['terms_of_use_notice']; ?></div>
                <div class="button" id="dismisstou"><a href="index.php?<?php echo _NODE; ?>=<?php echo $config_sys['terms_of_use_controller']; ?>"><?php echo _t('TERMS_OF_USE'); ?></a></div>
            </div>
            <?php
        }

		?>

	</body>
</html><?php
	}
}

?>