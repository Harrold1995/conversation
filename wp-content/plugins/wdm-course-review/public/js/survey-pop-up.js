(function($) {

    /**
     * SurveyPopUp class
     * 
     * All method to submit user survey response
     */

    var SurveyPopUp = function() {
        $(document)
            .on('click', '#open_survey_pop_up', { surveyPopUp: this }, this.openSurveyModal)
            .on('click', '.survey_pre_question_button', { surveyPopUp: this }, this.goToPreviousQuestion)
            .on('click', '.survey_next_question_button', { surveyPopUp: this }, this.goToNextQuestion)
            .on('click', '.survey_submit_button button', { surveyPopUp: this }, this.submitSurvey);
    };

    /**
     * Open survey modal from shortcode
     * @param  {object} e Windows Event Object
     */

    SurveyPopUp.prototype.openSurveyModal = function( e ) {
        $( "#survey_pop_main_div" ).rrfmodal();
        var totalQuestion = parseInt($('#survey_pop_main_div').attr('data-total-question'));
        var current_question_number = parseInt($('#survey_pop_main_div').attr('data-current-question-number'));
        for(var i=2; i <= totalQuestion; i++) {
            $('.survey_question_number_'+i).hide();
        }
        if(totalQuestion == 1) {
            $('.survey_submit_button button').show();
            $('.survey_next_question_div button').hide();
        }
        $('.survey_question_number_'+current_question_number).show();
    };

    /**
     * Go to previous question of survey
     * @param  {object} e Windows Event Object
     */

    SurveyPopUp.prototype.goToPreviousQuestion = function( e ) {
        e.preventDefault();
        var preNumber = parseInt($('.survey_pre_question_button').attr('data-pre-number'));
        var totalQuestion = parseInt($('#survey_pop_main_div').attr('data-total-question'));
        var nextNumber = parseInt($('.survey_next_question_button').attr('data-next-number'));
        var currentNumber = parseInt($('#survey_pop_main_div').attr('data-current-question-number'));
        if(preNumber > 0) {
            $('.easy-navigation .breadcrumb.index-' + currentNumber).removeClass('attempting');
            for(var i=1; i <= totalQuestion; i++) {
                if(i == preNumber) {
                    $('.survey_question_number_'+i).show();
                } else {
                    $('.survey_question_number_'+i).hide();
                }
            }
            $('.survey_submit_button button').hide();
            $('.survey_next_question_div button').show();
            $('.survey_next_question_button').attr('data-next-number', (nextNumber -1));
            $('.survey_pre_question_button').attr('data-pre-number', (preNumber -1));
            $('#survey_pop_main_div').attr('data-current-question-number', (currentNumber -1));
            
        }
    };

    /**
     * Go to next question of survey
     * @param  {object} e Windows Event Object
     */

    SurveyPopUp.prototype.goToNextQuestion = function( e ) {
        e.preventDefault();
        var preNumber = parseInt($('.survey_pre_question_button').attr('data-pre-number'));
        var totalQuestion = parseInt($('#survey_pop_main_div').attr('data-total-question'));
        var nextNumber = parseInt($('.survey_next_question_button').attr('data-next-number'));
        var currentNumber = parseInt($('#survey_pop_main_div').attr('data-current-question-number'));
        if(nextNumber <= totalQuestion) {
            $('.easy-navigation .breadcrumb.index-' + nextNumber).addClass('attempting');
            for(var i=1; i <= totalQuestion; i++) {
                if(i == nextNumber) {
                    $('.survey_question_number_'+i).show();
                } else {
                    $('.survey_question_number_'+i).hide();
                }
            }
            if(nextNumber == totalQuestion) {
                $('.survey_submit_button button').show();
                $('.survey_next_question_div button').hide();
            } else {
                $('.survey_submit_button button').hide();
                $('.survey_next_question_div button').show();
            }
            $('.survey_next_question_button').attr('data-next-number', (nextNumber + 1));
            $('.survey_pre_question_button').attr('data-pre-number', (preNumber + 1));
            $('#survey_pop_main_div').attr('data-current-question-number', (currentNumber + 1));
        }
    };

    /**
     * Submit survey response
     * @param  {object} e Windows Event Object
     */
    SurveyPopUp.prototype.submitSurvey = function( e ) {
        e.preventDefault();
        var form_value = $('.survey_question_form').serializeArray();
        var user_response = {};
        var post_id;
        var survey_id;
        var user_id;
        form_value.forEach(function(value){
            if(value.name == "survey_id") {
                survey_id = value.value;
            } else if(value.name == "post_id") {
                post_id = value.value;
            } else if(value.name == "user_id") {
                user_id = value.value;
            } else {
                user_response[value.name] = value.value;
            }
        });
        /**
         * Submit data to custom table
         */
        var data = {
            'action': 'save_user_survey_response',
            'post_id': parseInt(post_id),
            'user_id': parseInt(user_id),
            'survey_id': parseInt(survey_id),
            'user_response': user_response,
            'security' : save_survey_response_ajax.nonce,
        };
        $.post(save_survey_response_ajax.ajax_url, data, function(response) {
            $.rrfmodal.close();
            $('.open_survey_pop_up_div').hide();
            $( "#survey_thank_you_pop_up" ).rrfmodal();
        });
    };

    $( document ).ready(
		function() {
			var surveyPopUp = new SurveyPopUp();
		}
	);
})(jQuery)
