<?php
/*********************************************************************************
 * WP Ultimate CSV Importer is a Tool for importing CSV for the Wordpress
 * plugin developed by Smackcoder. Copyright (C) 2014 Smackcoders.
 *
 * WP Ultimate CSV Importer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License version 3
 * as published by the Free Software Foundation with the addition of the
 * following permission added to Section 15 as permitted in Section 7(a): FOR
 * ANY PART OF THE COVERED WORK IN WHICH THE COPYRIGHT IS OWNED BY WP Ultimate
 * CSV Importer, WP Ultimate CSV Importer DISCLAIMS THE WARRANTY OF NON
 * INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * WP Ultimate CSV Importer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program; if not, see http://www.gnu.org/licenses or write
 * to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * WP Ultimate CSV Importer copyright notice. If the display of the logo is
 * not reasonably feasible for technical reasons, the Appropriate Legal
 * Notices must display the words
 * "Copyright Smackcoders. 2014. All rights reserved".
 ********************************************************************************/

require_once('../../../../../../wp-load.php');

?>
<script src= "<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/js/jquery.js" type="text/javascript"></script>
<script src= "<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG;?>/js/jquery.flot.min.js" type="text/javascript"></script>
<script>
var inserted = skipped = updated = [];
</script>
<?php
//require_once('/var/www/newwordpress/wp/wp-load.php');
global $wpdb;
$getDetails = $wpdb->get_results("select * from smackcsv_status_log where sdm_id =".$_REQUEST['sdmid']);
foreach($getDetails as $getDetail){
?>
<script>
inserted.push([<?php echo strtotime($getDetail->imported_on).'000'; ?>,<?php echo $getDetail->inserted; ?>]);
skipped.push([<?php echo strtotime($getDetail->imported_on).'000'; ?>,<?php echo $getDetail->skipped; ?>]);
updated.push([<?php echo strtotime($getDetail->imported_on).'000'; ?>,<?php echo $getDetail->updated; ?>]);
</script>
<?php

}
if(count($getDetails)> 5)
$barWidth = 5184000;
else
$barWidth = 5184000;
?>
<script>
var barWidthsize = <?php echo $barWidth; ?>;
</script>
<script>
var dataset = [
    { label: "Inserted", data: inserted, color: "#0077FF" },
    { label: "Skipped", data: skipped, color: "#7D0096" },
    { label: "Updated", data: updated, color: "#DE000F" }
];

var options1 = {
    series: {
        stack: true,
        bars: {
            show: true
        }
    },
    bars: {
        align: "center",
        barWidth:barWidthsize
    },
    xaxis: {
        mode: "time",
        tickLength: 10,
        color: "black",
        axisLabel: "Date",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxis: {
        color: "black",
        axisLabel: "Imported",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 3,
    },
    grid: {
        hoverable: true,
        borderWidth: 2,
        backgroundColor: { colors: ["#EDF5FF", "#ffffff"] }
    },
    colors:["#613C00","#207800","#004078"]
};

jQuery(document).ready(function () {
//    jQuery.plot(jQuery("#flot-placeholder"), dataset, options);
jQuery.plot(jQuery("#flot-placeholder1"), dataset, options1);
});



//******* Stacked Horizontal Bar Chart
/*
function gd(year, month, day) {
    return new Date(year, month - 1, day).getTime();
}
*/
var previousPoint = null, previousLabel = null;
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

jQuery.fn.UseTooltip = function () {
    jQuery(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                jQuery("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var day = "Jan " + new Date(x).getDate();
                
                showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + day
                         + " : <strong>" + "Add tool tip message here"+
                         "</strong>(Count)");
            }
        } else {
            jQuery("#tooltip").remove();
            previousPoint = null;
        }
    });
};

function showTooltip(x, y, color, contents) {
    jQuery('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 120,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}


jQuery.fn.UseTooltip2 = function () {
    jQuery(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                jQuery("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                var day = "Jan " + new Date(y).getDate();

                showTooltip2(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + day
                         + " : <strong>" + "Add tooltip msg her" +
                         "</strong>(Count)");
            }
        } else {
            jQuery("#tooltip").remove();
            previousPoint = null;
        }
    });
};


function showTooltip2(x, y, color, contents) {
    jQuery('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 60,
        left: x - 120,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}
</script>
<div id="flot-placeholder1" style="width:600px;height:300px;margin:0 auto"></div>

