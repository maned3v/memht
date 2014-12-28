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

?>

<div class="wide ui-widget-content ui-corner-all" style="margin-top:0;">
	<div class="ui-widget-header"><?php echo _t("QUICK_LINKS"); ?></div>
	<div class="body">
		<?php
		$result = $Db->GetList("SELECT title,url FROM #__menu_acp WHERE submenu=1 AND quickicons=1 AND status='active' ORDER BY title");
		foreach ($result as $row) {
			?>
			<div style="width:146px; margin:0 4px 4px 0; padding:10px; text-align:center; cursor:pointer; float:left;" class="ui-state-default ui-corner-all" onmouseover="javascript:$(this).addClass('ui-state-highlight');" onmouseout="javascript:$(this).removeClass('ui-state-highlight');" onclick="javascript:location='<?php echo Io::Output($row['url']); ?>'">
				<div><?php echo Io::Output($row['title']); ?></div>
			</div>
			<?php
		}
		?>
    </div>
    <div style="clear:both;"></div>
</div>