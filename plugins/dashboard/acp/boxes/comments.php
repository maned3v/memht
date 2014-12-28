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

global $Db,$User;

?>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
    	$('input#comments').click(function() {
    		location = 'admin.php?cont=comments';
    	});
	});
</script>

<div class="ui-widget-content ui-corner-all dwidget" style="height:260px;">
	<div class="ui-widget-header"><?php echo _t("RECENT_COMMENTS"); ?></span></div>
	<div class="body" style="height:218px;overflow:hidden;">
    
    <?php
	
	if ($result = $Db->GetList("SELECT author,created,text FROM #__comments WHERE status='published' ORDER BY id DESC LIMIT 4")) {
        foreach ($result as $row) {
        	echo "<div class='ui-widget-content' style='padding:5px; margin-bottom:5px;'>\n";
	            echo "<div>".$User->Name(Io::Output($row['author']))."</div>\n";
	            echo "<div>".MB::substr(Io::Output($row['text']),0,100).(strlen(Io::Output($row['text']))>100 ? ".." : "")."</div>\n";
	       	echo "</div>\n";
        }
        
    } else {
        echo "<div style='text-align:center;'>"._t("LIST_EMPTY")."</div>\n";
    }
	
	?>
    </div>
    <div style="width:500px;height:35px;background:url(images/core/content_end.png) repeat-x bottom;position:absolute;bottom:5px;left:0;z-index:90;"></div>
    <?php
    echo "<input type='button' value='"._t("COMMENTS")."' style='position:absolute;bottom:4px;right:4px;z-index:99;' class='sys_form_button' id='comments' />\n";
    ?>
</div>