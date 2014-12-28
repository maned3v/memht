<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2013 by Miltenovikj Manojlo <dev@miltenovik.com>
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
 * @copyright	Copyright (C) 2008-2013 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_ADMINCP") or die("Access denied");

global $config_sys;

?>

<div class="ui-widget-content ui-corner-all dwidget" style="height:260px;">
	<div class="ui-widget-header"><?php echo _t("NEWS_FROM_MEMHTCOM"); ?></div>
	<div class="body"><iframe src="http://serv.memht.com/news.php?ver=<?php echo $config_sys['engine_version']; ?>" scrolling="no" style="border:0;width:100%;height:222px;" frameborder="0"></iframe></div>
</div>