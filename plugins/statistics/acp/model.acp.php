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

class statisticsModel {
	function Main() {
		global $Db;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>
		
		<!--Load the AJAX API-->
    	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    	<script type="text/javascript">
    		google.load("visualization", "1", {packages:["corechart"]});
    		/* Pageviews */
        	google.setOnLoadCallback(drawChart);
			function drawChart() {
				<?php
					$pageviews = array();
					$result = $Db->GetList("SELECT date,hits FROM #__stats_hits ORDER BY date DESC LIMIT 31");
					foreach ($result as $row) {
						$pageviews[] = "['".Time::Output(Io::Output($row['date']),"d")."',".Io::Output($row['hits'])."]";
					}
					$pageviews = implode(",",$pageviews);
				?>
					
				var data = new google.visualization.DataTable();
		        data.addColumn('string', '<?php echo _t("DAY"); ?>');
		        data.addColumn('number', '<?php echo _t("PAGEVIEWS"); ?>');
		        data.addRows([
		          <?php echo $pageviews; ?>
		        ]);

		        var options = {
		          width: 950,
		          height: 300,
		          chartArea: {left:55,top:20,width:"92%",height:"70%"},
		          colors: ['#6FA7D1'],
		          focusTarget: 'datum',
		          fontName: 'Verdana',
		          fontSize: 11,
		          legend: {position: 'in', textStyle: {color:'#777', fontSize: 9}},
		          hAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}},
		          vAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}, baselineColor: '#6FA7D1'},
		          lineWidth: 2,
		          pointSize: 4
		        };

		        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
		        chart.draw(data, options);
			}

			/* Visitors */
			google.setOnLoadCallback(drawChartVis);
			function drawChartVis() {
				<?php
					$visits = array();
					$result = $Db->GetList("SELECT date,uniqvis FROM #__stats_hits ORDER BY date DESC LIMIT 31");
					foreach ($result as $row) {
						$visitors[] = "['".Time::Output(Io::Output($row['date']),"d")."',".Io::Output($row['uniqvis'])."]";
					}
					$visitors= implode(",",$visitors);
				?>
					
				var data = new google.visualization.DataTable();
		        data.addColumn('string', '<?php echo _t("DAY"); ?>');
		        data.addColumn('number', '<?php echo _t("VISITORS"); ?>');
		        data.addRows([
		          <?php echo $visitors; ?>
		        ]);

		        var options = {
		          width: 950,
		          height: 300,
		          chartArea: {left:55,top:20,width:"92%",height:"70%"},
		          colors: ['#F30'],
		          focusTarget: 'datum',
		          fontName: 'Verdana',
		          fontSize: 11,
		          legend: {position: 'in', textStyle: {color:'#777', fontSize: 9}},
		          hAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}},
		          vAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}, baselineColor: '#F30'},
		          lineWidth: 2,
		          pointSize: 4/*,
		          series: {1: {type: "bars"}}*/
		        };

		        var chart = new google.visualization.AreaChart(document.getElementById('chart_divVis'));
		        chart.draw(data, options);
			}

			/* Pages */
			google.setOnLoadCallback(drawChartPag);
		    function drawChartPag() {
		    	<?php
		    	  	$pages = array(); 
					//Pages
					$result = $Db->GetList("SELECT *,SUM(hits) AS hits FROM #__stats_pages WHERE (date + INTERVAL 1 WEEK) >= NOW() GROUP BY page ORDER BY page");
					foreach ($result as $row) {
						$page = Io::Output($row['page']);
						$page = empty($page) ? "index" : $page ;
						$pages[] = "['".$page."',".Io::Output($row['hits'])."]";
					}
					$pages= implode(",",$pages);
				?>
		        var data = new google.visualization.DataTable();
		        data.addColumn('string', '<?php echo _t("PAGE"); ?>');
		        data.addColumn('number', '<?php echo _t("HITS")." ("._t("LAST_WEEK").")"; ?>');
		        data.addRows([
		    		<?php echo $pages; ?>
		    	]);

		        var options = {
		          width: 950,
		          height: 300,
		          chartArea: {left:55,top:20,width:"92%",height:"70%"},
		          colors: ['#84A63A'],
		          focusTarget: 'datum',
		          fontName: 'Verdana',
		          fontSize: 11,
		          legend: {position: 'in', textStyle: {color:'#777', fontSize: 9}},
		          hAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}},
		          vAxis: {textPosition: 'out', textStyle: {color:'#777', fontSize: 9}, baselineColor: '#84A63A'},
		          lineWidth: 2,
		          pointSize: 4
		        };

		        var chart = new google.visualization.ColumnChart(document.getElementById('chart_divPag'));
		        chart.draw(data, options);
		      }
	    </script>
		
		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
		   	<tr>
				<td style="vertical-align:top;">
		           	<div class="widget ui-widget-content ui-corner-all">
		            <div class="ui-widget-header"><?php echo _t("STATISTICS"); ?></div>
		               <div class="body">
							<div id="chart_div"></div>
							<div id="chart_divVis"></div>
							<div id="chart_divPag"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
                
        <?php
		
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
}

?>