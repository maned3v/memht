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

(function($){
 	$.fn.cloudyTag = function(options){
		var settings = $.extend({
			field	: 'tags[]'
		},options);

    	return this.each(function(){
			var elem = $(this);

			$("#addtag").after($("<div>").attr("id","cloudylist"));
			$("#cloudylist").after($("<div>").css("clear","both")).addClass("cloudyTag");

			$("#addtag").click(function() { addTag(); })
						.ready(function() { addTag(); });

			function addTag() {
				var v = elem.val();
				if (v.length<3) return false;

				var p = v.split(",");
				for (var i in p) {
					var lis = $("<div>").text(p[i]).addClass("ui-state-default ui-corner-all").click(function() { $(this).remove(); });
					var inp = $("<input>").attr("type","hidden").attr("name",settings.field).val(p[i]);
					$("#cloudylist").append(lis);
					lis.append(inp);
				}
				elem.val("");
			}
   		});
	};
})(jQuery);