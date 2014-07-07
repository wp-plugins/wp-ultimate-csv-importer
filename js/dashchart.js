jQuery( document ).ready(function() {
pieStats();
lineStats();
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
      //    alert(browser['label']);
        if (browser['label'] == 'No Imports Yet') {
                document.getElementById('pieStats').innerHTML = "<h2 style='color: red;text-align: center;padding-top: 100px;' >No Imports Yet</h2>";
            }
        else {
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
         //var get_cat = val['cat'];
         var line =  [val[0],val[1],val[2],val[3],val[4]];//,val[5],val[6],val[7],val[8],val[9]]; 
	/*{
                name: 'New York',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Berlin',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'London',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]; */
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
                categories:val.cat /* ["Jan", "Feb", 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] */
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

  
