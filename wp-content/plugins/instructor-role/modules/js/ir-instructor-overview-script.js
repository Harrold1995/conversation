jQuery( document ).ready(
	function(){
		createCoursePieChart( ir_data.chart_data );
		createEarningsDonutChart( ir_data.earnings );

		jQuery( '#instructor-courses-select' ).on(
			'change',
			function(){
				jQuery( '.ir-course-reports .ir-ajax-overlay' ).show();
				$select = jQuery( this );
				jQuery.ajax(
					{
						type: 'post',
						dataType: 'JSON',
						url: ir_data.ajax_url,
						data: {
							action : 'ir-update-course-chart',
							course_id : $select.val()
						},
						success: function(chart_data) {
							jQuery( '.ir-course-reports .ir-ajax-overlay' ).hide();
							createCoursePieChart( chart_data );
						}
					}
				);
			}
		);

		// Setup Datatables
		if ( ! jQuery( '.ir-assignments-table .ir-no-data-found' ).length ) {
			jQuery( '.ir-assignments-table' ).DataTable(
				{
					'language' : {
						'paginate' : {
							'previous' : '',
							'next' : ''
						}
					},
					"columnDefs": [
					{ "width": "20%", "targets": 3 }
					],
					"order": [ 3, 'desc' ]
				}
			);
		}
	}
);

function createCoursePieChart(chart_data)
{
	jQuery('#ir-course-pie-chart-div').empty();
	var not_started_per = chart_data.not_started;
	var in_progress_per = chart_data.in_progress;
	var completed_per   = chart_data.completed;
	var graph_heading   = chart_data.title;

	if ( 0 === not_started_per + in_progress_per + completed_per ) {
		jQuery('#ir-course-pie-chart-div').html( ir_data.empty_reports );
		return;
	}

	jQuery( '.ir-tab-links' ).on(
		'click',
		function(){
			var selector = jQuery( this ).data( 'tab' );
			jQuery( '.ir-tab-content' ).hide();
			jQuery( '.ir-tab-links' ).removeClass( 'tab-active' );
			jQuery( this ).addClass( 'tab-active' );
			jQuery( '#' + selector ).show().addClass( 'tab-active' );
		}
	);

	var options = {
		series: [ not_started_per, in_progress_per, completed_per ],
		labels: [ chart_data.not_started_label, chart_data.in_progress_label, chart_data.completed_label ],
		chart: {
			type: 'pie',
			height: 650,
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			pie: {
				donut: {
					labels: {
						show: false,
					}
				}
			}
		},
		colors: chart_data.colors,
		dataLabels: {
			enabled: true,
			dropShadow: {
				enabled: true,
			}
		}
	};
	var chart = new ApexCharts(document.querySelector("#ir-course-pie-chart-div"), options);
	chart.render();
}

function createEarningsDonutChart(earnings)
{
	var paid_per      = earnings.paid;
	var un_paid_per   = earnings.unpaid;
	var total         = earnings.total;
	var graph_heading = earnings.title;


	var options = {
		series: [{
			name: earnings.default_units_value,
			data: [paid_per, un_paid_per]
		}],
		chart: {
			type: 'bar',
			height: 200,
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			bar: {
				borderRadius: 0,
				horizontal: true,
				barHeight: '50%',
				distributed: true,
				dataLabels: {
					position: 'bottom'
				},
			}
		},
		colors: earnings.colors,
		dataLabels: {
			enabled: false
		},
		xaxis: {
			categories: [earnings.paid_label, earnings.unpaid_label],
			showAlways: false,
			labels: {
				show: false
			}
		},
		legend: {
			show: false
		}
	};

	var chart = new ApexCharts(document.querySelector("#ir-earnings-pie-chart-div"), options);
	chart.render();
}
