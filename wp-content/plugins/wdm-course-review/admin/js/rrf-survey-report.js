
(function($) {

    /**
     * SurveyReport class
     * 
     * All method to submit user survey response
     */

    var SurveyReport = function() {
        $(document)
            .on('click', '.rrf_after_filter_details_question_prev_button', { surveyReport: this }, this.goToPreviousQuestionReport)
            .on('click', '.rrf_after_filter_details_question_next_button', { surveyReport: this }, this.goToNextQuestionReport)
            .on('click', '.rrf_get_report_button', { surveyReport: this }, this.getReports);
    };

    /**
     * Render chart
     * @param {int} question_number 
     */

    SurveyReport.prototype.renderChartData = function( question_number = 0 ) {
        var courser_id = $('.rrf_survey_course_filter').val();
        var from_date = $('.rrf_survey_date_from_filter').val();
        var to_date = $('.rrf_survey_date_to_filter').val();

        if(courser_id == '' || courser_id == null) {
            $('.rrf_after_filter_details').hide();
            $('.question_handling_section').hide();
            alert('Invalid input');
            return;
        }
        
        /**
         * Filter to get data from custom table
         */
        var data ={
            'action': filter_survey_report_ajax.action,
            'course_id': parseInt(courser_id),
            'from_date': from_date,
            'to_date': to_date,
            'security': filter_survey_report_ajax.security
        };
        
        $.post(filter_survey_report_ajax.ajax_url, data, function(response) {
            $('.rrf_after_filter_details').show();
            $('.user_based_report').show();
            $('.question_handling_section').css('display', 'flex');
            $('.rrf_after_filter_details_course_name').text(response.data.course_name);
            $('.rrf_after_filter_details_survey_name').text(response.data.survey_name);
            var question = response.data.question_data[question_number];
            $('.rrf_after_filter_details_question_details').text(question.question_name);
            $('.rrf_after_filter_details_question_details').attr('data-total-question', response.data.question_data.length);
            /**
             * Chart options initiating
             */
            var options = {
                title: {
                    text: ""
                },
                data: [{
                        type: "pie",
                        startAngle: 45,
                        showInLegend: "true",
                        legendText: "{label}",
                        indexLabel: "{label} ({y})",
                        yValueFormatString:"#,##0.#"%"",
                        dataPoints: []
                }]
            };
            var question_bank = {};
            response.data.question_data.forEach(function(option){
                question_bank[option.question_id] = option.question_name;
            })
            question.question_option.forEach(option => {
                options.data[0].dataPoints.push({label: option, y: 0});
            });
            var user_responses = response.data.user_response_data;
            if (user_responses == null) {
                return;
            }
            /**
             * Calculating the number users attempted question and what was the answer
             */
            user_responses.forEach(user_response => {
                if(user_response.user_response.hasOwnProperty(question.question_id)) {
                    options.data[0].dataPoints.forEach(function(dataPoint){
                        if(dataPoint.label == user_response.user_response[question.question_id]) {
                            dataPoint.y = dataPoint.y + 1; 
                        }
                    });
                }                
            });
            $("#rrf_actual_pie_chart").CanvasJSChart(options);
            var tableData = "<table class='rrf_report_table'><tr><th>" + filter_survey_report_ajax.options_i18n + "</th><th>" + filter_survey_report_ajax.no_of_users_i18n + "</th></tr>";
            options.data[0].dataPoints.forEach(function(dataOpt){
                tableData = tableData + '<tr><td>'+dataOpt.label+'</td><td>'+dataOpt.y+'</td></tr>';
            });
            tableData = tableData + '<tr><td><b>' + filter_survey_report_ajax.total_users_i18n + '</b></td><td>'+user_responses.length+'</td></tr>';		
            tableData = tableData + "</table>";
            var tableDataDetailed = "<table class='rrf_detail_report_table'><tr><th>" + filter_survey_report_ajax.user_i18n + "</th><th>" + filter_survey_report_ajax.date_i18n + "</th><th>" + filter_survey_report_ajax.question_i18n + "</th><th>" + filter_survey_report_ajax.user_response_i18n +"</th></tr>";
            user_responses.forEach(function(dataOpt){
                for (var key in dataOpt.user_response) {
                    tableDataDetailed = tableDataDetailed + '<tr><td>'+dataOpt.user_name+'</td><td>'+dataOpt.date_time+'</td>';
                    tableDataDetailed = tableDataDetailed + '<td>'+question_bank[key]+'</td><td>'+dataOpt.user_response[key]+'</td>' + '</tr>';
                };
            });
            tableDataDetailed = tableDataDetailed + "</table>";
            
            $('.rrf_actual_user_based_report').html(tableData);
            $('.rrf_detailed_report').html(tableDataDetailed);
        });
    };

    /**
     * Get report for previous question button. Getting data from attibutes and render the chartData
     * @param  {object} e Windows Event Object
     */

    SurveyReport.prototype.goToPreviousQuestionReport = function( e ) {
        e.preventDefault();
        var preNumber = parseInt($('.rrf_after_filter_details_question_prev_button').attr('data-pre-number'));
        var totalQuestion = parseInt($('.rrf_after_filter_details_question_details').attr('data-total-question'));
        var nextNumber = parseInt($('.rrf_after_filter_details_question_next_button').attr('data-next-number'));
        var currentNumber = parseInt($('.rrf_after_filter_details_question_details').attr('data-current-question-number'));
        if (preNumber == 1) {
            $('.rrf_after_filter_details_question_prev_button').attr('disabled', 'disabled');
        }
        if(preNumber > 0) {
            e.data.surveyReport.renderChartData(preNumber-1);
            $('.rrf_after_filter_details_question_next_button').removeAttr('disabled');
            $('.rrf_after_filter_details_question_next_button').attr('data-next-number', (nextNumber - 1));
            $('.rrf_after_filter_details_question_prev_button').attr('data-pre-number', (preNumber - 1));
            $('.rrf_after_filter_details_question_details').attr('data-current-question-number', (currentNumber - 1));        
        }
    };

    /**
     * Get report for next question button. Getting data from attibutes and render the chartData
     * @param  {object} e Windows Event Object
     */

    SurveyReport.prototype.goToNextQuestionReport = function( e ) {
        e.preventDefault();
        var preNumber = parseInt($('.rrf_after_filter_details_question_prev_button').attr('data-pre-number'));
        var totalQuestion = parseInt($('.rrf_after_filter_details_question_details').attr('data-total-question'));
        var nextNumber = parseInt($('.rrf_after_filter_details_question_next_button').attr('data-next-number'));
        var currentNumber = parseInt($('.rrf_after_filter_details_question_details').attr('data-current-question-number'));
        if (nextNumber == totalQuestion) {
            $('.rrf_after_filter_details_question_next_button').attr('disabled', 'disabled');
        }
        if(nextNumber <= totalQuestion) {
            e.data.surveyReport.renderChartData(nextNumber-1);
            $('.rrf_after_filter_details_question_prev_button').removeAttr('disabled');
            $('.rrf_after_filter_details_question_next_button').attr('data-next-number', (nextNumber + 1));
            $('.rrf_after_filter_details_question_prev_button').attr('data-pre-number', (preNumber + 1));
            $('.rrf_after_filter_details_question_details').attr('data-current-question-number', (currentNumber + 1));
        }
    };

    /**
     * Get reports
     * @param  {object} e Windows Event Object
     */
    SurveyReport.prototype.getReports = function( e ) {
        e.preventDefault();
        e.data.surveyReport.renderChartData();
    };

    $( document ).ready(
		function() {
			var surveyReport = new SurveyReport();
		}
	);
})(jQuery)
