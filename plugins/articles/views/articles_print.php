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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="<?php echo $site_url; ?>/libraries/MemHT/style/print.css" type="text/css" />
        <base href="<?php echo $site_url; ?>/" />
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div style="float:left;"><a href="javascript:void(0);" onClick="window.print();" title="<?php echo _t("PRINT") ?>"><img src="images/core/printer.gif" alt="<?php echo _t("PRINT") ?>" title="<?php echo _t("PRINT") ?>" /></a></div>
        <div style="text-align:right; font-weight:bold; color:#CCC; margin-bottom:10px;"><?php echo $site_name; ?></div>
        <div style="font-weight:bold; margin-bottom:4px;"><?php echo $title; ?></div>
        <div style="font-size:90%;"><?php echo _t("WRITTEN_IN_X_BY_Y_ON_Z","$ctitle","$author","$created"); ?></div>
		<?php
        if ($modified!=$created) { ?><div style="font-size:90%;"><?php echo _t("LAST_UPDATED_ON_X",$modified); ?></div><?php } ?>
        <div style="margin-top:8px; padding-top:12px; border-top:1px solid #DDD;"><?php echo $text; ?></div>
    </body>
</html>