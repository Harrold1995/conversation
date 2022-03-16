(function($) {

    /**
     * SurveyReport class
     * 
     * All method to submit user survey response
     */

    var SurveyAddQuestion = function() {
        $(document)
            .on('click', '.decrease_page_count', { surveyAddQuestion: this }, this.decreasePageCount)
            .on('click', '.increase_page_count', { surveyAddQuestion: this }, this.increasePageCount)
            .on('click', '.rff_question_move_right', { surveyAddQuestion: this }, this.moveQuestionRight)
            .on('click', '.rff_question_move_left', { surveyAddQuestion: this }, this.moveQuestionLeft)
            .on('click', '.rrf_survey_question_div_assigned', { surveyAddQuestion: this }, this.selectQuestionForMoveLeft)
            .on('click', '.rrf_survey_question_div', { surveyAddQuestion: this }, this.selectQuestionForMoveRight);
        $('#question_search_input').on('input', this.delayFunction(this.searchQuestions, 2000));
        $( window ).on( 'load', { reviewsProcessor: this }, this.assignDragAndDrop );
        $( window ).on( 'load', { reviewsProcessor: this }, this.addSelect2 );
    };

    SurveyAddQuestion.prototype.delayFunction = function( fn, ms ) {
        let timer = 0;
        return function( ...args ) {
            $('.question-search-loader-gif img').css('visibility', 'visible');
            clearTimeout( timer );
            timer = setTimeout( fn.bind( this, ...args ), ms || 0 );
        };
    }

    /**
     * Create payload to search question post
     */

    SurveyAddQuestion.prototype.createPayload = function() {
        var page_number = parseInt($('#page_number_input').val());
        var assigned_questions = $("input[name='assigned_question[]']").map(function(){return $(this).val();}).get();
        var search_text_value = $("#question_search_input").val();
        var data = {
            'action': 'filter_question',
            'filter_page_number': page_number,
            'limit': 10,
            'exclude': assigned_questions,
            'search_text': search_text_value,
            'security': filter_question_ajax.security
        };
        return data;
    };

    /**
     * Function to assign drag and drop for assigned and unassinged section of questions
     * @param  {object} classObject
     */

    SurveyAddQuestion.prototype.assignDragAndDrop = function() {
        $("#rrf_survey_unassigned_question_section .rrf_survey_question_div").draggable({
            revert: "invalid",
            refreshPositions: true,
            drag: function (event, ui) {
                ui.helper.addClass("draggable");
            },
            stop: function (event, ui) {
                ui.helper.removeClass("draggable");
            }
        });
    
        $("#rrf_survey_assigned_question_section").droppable({
            drop: function (event, ui) {
                if ($("#rrf_survey_assigned_question_section .rrf_survey_question_div").length == 0) {
                    $("#rrf_survey_assigned_question_section").html("");
                }
                var post_id = ui.draggable.attr('data-post-id');
                ui.draggable.addClass("dropped");
                $("#rrf_survey_assigned_question_section").append(ui.draggable);
                if(ui.draggable.children('input').length <= 0) {
                    ui.draggable.append('<input type="hidden" name="assigned_question[]" value='+post_id+'>');
                }
                var data = SurveyAddQuestion.prototype.createPayload.call(this);
                SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
            }
        });
    
        $("#rrf_survey_assigned_question_section .rrf_survey_question_div").draggable({
            revert: "invalid",
            refreshPositions: true,
            drag: function (event, ui) {
                ui.helper.addClass("draggable");
            },
            stop: function (event, ui) {
                ui.helper.removeClass("draggable");
            }
        });
    
        $("#rrf_survey_unassigned_question_section").droppable({
            drop: function (event, ui) {
                if ($("#rrf_survey_unassigned_question_section .rrf_survey_question_div").length == 0) {
                    $("#rrf_survey_unassigned_question_section").html("");
                }
                ui.draggable.addClass("dropped");
                $("#rrf_survey_unassigned_question_section").append(ui.draggable);
                ui.draggable.children('input').remove();
                var data = SurveyAddQuestion.prototype.createPayload.call(this);         
                SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
            }
        });
    };

    /**
     * Update question section after applying filter
     * @param  {object} data
     */

    SurveyAddQuestion.prototype.updateQuestionSection = function( data ) {
        $.post(filter_question_ajax.ajax_url, data, function(response) {
            var allData = response.data;
            if(allData.length) {
                $("#rrf_survey_unassigned_question_section").empty();
                allData.forEach(function(post){
                    $("#rrf_survey_unassigned_question_section").append('<div class="rrf_survey_question_div" data-post-id="'+post.ID+'"><div class="rrf_survey_question_div_drag_drop_icon"></div><div class="rrf_survey_question_div_title">'+post.post_title+'</div></div>');
                });
                SurveyAddQuestion.prototype.assignDragAndDrop.call(this);
            } else {
                if ( data.search_text !== '' ) {
                    $("#rrf_survey_unassigned_question_section").empty();
                }
                var page_number = parseInt($('#page_number_input').val());
                if(page_number != 1) {
                    $('#page_number_input').val(page_number-1);
                }
            }
            if(allData.length < 10) {
                $(".increase_page_count").css("pointer-events","none");
            } else {
                $(".increase_page_count").css("pointer-events","auto");
            }
        });
    };

    /**
     * Decrease page count
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.decreasePageCount = function( e ) {
        var page_number = parseInt($('#page_number_input').val());
        if(page_number > 1) {
            $('#page_number_input').val(page_number - 1);
            var data = e.data.surveyAddQuestion.createPayload();
            SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
        }
    };

    /**
     * Increase page count
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.increasePageCount = function( e ) {
        var page_number = parseInt($('#page_number_input').val());
        $('#page_number_input').val(parseInt(page_number) + 1);
        var data = e.data.surveyAddQuestion.createPayload();
        SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
    };

    /**
     * Search filter for question
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.searchQuestions = function( e ) {
        $('.question-search-loader-gif img').css('visibility', 'visible');
        $('#page_number_input').val(1);
        var data = SurveyAddQuestion.prototype.createPayload.call(this);
        SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
        $('.question-search-loader-gif img').css('visibility', 'hidden');
    };

    /**
     * Select questions for right move
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.selectQuestionForMoveRight = function( e ) {
        if($(this).hasClass('rrf_move_question_right')) {
            $(this).removeClass('rrf_move_question_right');
        } else {
            $(this).addClass('rrf_move_question_right');
        }
    };

    /**
     * Select questions for left move
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.selectQuestionForMoveLeft = function( e ) {
        if($(this).hasClass('rrf_move_question_left')) {
            $(this).removeClass('rrf_move_question_left');
        } else {
            $(this).addClass('rrf_move_question_left');
        }
    };

    /**
     * Move questions to right
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.moveQuestionRight = function( e ) {
        $('.rrf_move_question_right').each(function(i, obj){
            $(this).remove();
            $("#rrf_survey_assigned_question_section").append('<div class="rrf_survey_question_div" data-post-id="'+$(this).attr('data-post-id')+'"><div class="rrf_survey_question_div_drag_drop_icon"></div><div class="rrf_survey_question_div_title">'+$('.rrf_survey_question_div_title', this).html()+'</div><input type="hidden" name="assigned_question[]" value='+$(this).attr('data-post-id')+'></div>');
        });
        SurveyAddQuestion.prototype.assignDragAndDrop.call(this);
        var data = SurveyAddQuestion.prototype.createPayload.call(this);
        SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
    };

    /**
     * Move questions to left
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.moveQuestionLeft = function( e ) {
        $('.rrf_move_question_left').each(function(i, obj){
            $(this).remove();
        });
        SurveyAddQuestion.prototype.assignDragAndDrop.call(this);
        var data = SurveyAddQuestion.prototype.createPayload.call(this);
        SurveyAddQuestion.prototype.updateQuestionSection.call(this, data);
    };

    /**
     * Assign select2 for course select section
     * @param  {object} e Windows Event Object
     */
    SurveyAddQuestion.prototype.addSelect2 = function( e ) {
        $('#assign_surevey_select').select2({
            theme: 'learndash',
            ajax: null,
            allowClear: true,
            width: 'resolve',
            dir: (window.isRtl) ? 'rtl' : '',
            dropdownAutoWidth: true,
            placeholder: filter_question_ajax.placeholder
        });
    };

    $( document ).ready(
		function() {
            var surveyAddQuestion = new SurveyAddQuestion();
		}
	);
})(jQuery)
