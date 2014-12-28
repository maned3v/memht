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
    	$('input#save').click(function() {
    		$.ajax({
	    		type: "POST",
				dataType: "html",
				url: "admin.php?cont=internal&op=savequicknote",
				data: "text="+$('#quicknote').val(),
				success: function(data,textStatus,XMLHttpRequest){
					$("#infobox").show();
					$('#infobox').html('<img src="images/core/ajax-loader.gif" /> <?php echo _t("SAVED"); ?>');
					$("#infobox").fadeOut(3000);
				},
				error: function(XMLHttpRequest,textStatus,errorThrown) {
					alert('Error: '+textStatus);
				}
    		});
    	});
	});
</script>

<div class="ui-widget-content ui-corner-all dwidget">
	<div class="ui-widget-header"><?php echo _t("QUICKNOTE"); ?></div>
	<div class="body">
    	<textarea name="quicknote" id="quicknote" class="sys_form_textarea" style="width:326px;height:120px;"><?php echo Utils::GetComOption("quicknote",$User->Uid()) ?></textarea>
    </div>
    <?php
    echo "<input type='button' value='"._t("SAVE")."' style='position:absolute;bottom:4px;right:4px;' class='sys_form_button' id='save' />\n";
    ?>
</div>