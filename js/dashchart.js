jQuery( document ).ready(function() {
var get_module = document.getElementById('checkmodule').value;
if(get_module == 'dashboard') {
	pieStats();
	lineStats();
}
});
function pieStats()
{
jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
                    'action'   : 'firstchart',
                    'postdata' : 'firstchartdata',
                },
          dataType: 'json',
          cache: false,
          success: function(data) {
	var browser = JSON.parse(data);
		if (browser['label'] == 'No Imports Yet') {
		document.getElementById('pieStats').innerHTML = "<h2 style='color: red;text-align: center;padding-top: 100px;' >No Imports Yet</h2>";
		return false;
		}
           
              jQuery('#pieStats').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
 }
            }
        },
        series: [{
            type: 'pie',
            name: 'overall statistics',
          //  data: JSON.parse(data),
         data: browser
        }]
    });
}
        });
}
function lineStats()
{
jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
                    'action'   : 'secondchart',
                    'postdata' : 'secondchartdata',
                },
          dataType: 'json',
          cache: false,
         success: function(data) {
         var val = JSON.parse(data);
         var line =  [val[0],val[1],val[2],val[3],val[4],val[5]]; 
         jQuery('#lineStats').highcharts({
            title: {
                text: '',
                x: -5 //center
            },
            subtitle: {
 text: '',
                x: -5
            },
            xAxis: {
                categories:val.cat 
            },
            yAxis: {
                title: {
                text: 'Import (Nos)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' Nos'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series:line   });
    }
            });
}

  
