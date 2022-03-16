/*accordion*/
(function($) {
	"use strict";
	var WidgetAccordionHandler = function($scope, $) {
        let container = $scope[0].querySelectorAll('.theplus-accordion-wrapper'),
		    AccordionType = container[0].dataset.accordionType,
			$accordionSpeed = container[0].dataset.toogleSpeed,
            Connection = container[0].dataset.connection,
            RBGConnection = container[0].dataset.rowBgConn,
            accrodionList = container[0].querySelectorAll('.theplus-accordion-item'),
			$PlusAccordionListHeader = container[0].querySelectorAll('.theplus-accordion-item .plus-accordion-header'),
            hash = window.location.hash;

            accrodionList.forEach(function(self){
               let AccHeader = self.querySelector('.plus-accordion-header');

                    if( AccHeader.classList.contains('active-default') ) {
                        AccHeader.classList.add('active');
						if(self.querySelectorAll('.plus-accordion-content').length > 0){
							let AdContent = self.querySelector('.plus-accordion-content');
								AdContent.classList.add('active')
								AdContent.style.cssText = "display: block;"
								$(AdContent).slideDown($accordionSpeed);
								
							let tab_index = self.querySelector('.plus-accordion-content.active').dataset.tab;

								if( Connection && document.querySelectorAll('.'+Connection).length ){
									setTimeout(function(){
										accordion_tabs_connection( tab_index, Connection );
									}, 150);
								}
								if( $(self).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").length ){
									$(self).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").slick('setPosition');
								} 
								if( RBGConnection && document.querySelectorAll('#'+RBGConnection).length ){
									background_accordion_tabs_conn( tab_index, RBGConnection );
								}
						}
                    }
            });

            if( AccordionType == 'accordion' ) {
                $($PlusAccordionListHeader).on('click', function() {
                    if( this.classList.contains('active') ) {
                        this.classList.remove('active');
						if(this.nextElementSibling){
							this.nextElementSibling.classList.remove('active')
							$(this.nextElementSibling).slideUp($accordionSpeed);
						}                        
                    }else {
                        accrodionList.forEach(function(self){
                            if( self.children[0].classList.contains('active') ){
                                self.children[0].classList.remove('active')
                            }
							
                            if(  self.children[1] && self.children[1].classList.contains('active') ){
                                self.children[1].classList.remove('active')
                                $(self.children[1]).slideUp($accordionSpeed);
                            }
                        });

                        this.classList.toggle("active");
						if(this.nextElementSibling){
							this.nextElementSibling.classList.toggle("active");
							$(this.nextElementSibling).slideToggle($accordionSpeed);
						}
                        

                        if( $(this).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").length ){
                            $(this).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").slick('setPosition');
                        }
                        let tab_index = this.dataset.tab;
                        if( Connection && document.querySelectorAll('.'+Connection).length ){
                            accordion_tabs_connection(tab_index, Connection);
                        }
                        if( RBGConnection &&  document.querySelectorAll('#'+RBGConnection).length ){
                            background_accordion_tabs_conn(tab_index, RBGConnection);
                        }
                    }
                });			
            }else if( AccordionType == 'hover' ) {
                $($PlusAccordionListHeader).on('mouseover', function() {
                    if( this.classList.contains('active') ) {
                        //	$(this).removeClass('active');
                    }else{
                        let ActiveNone = container[0].querySelectorAll('.plus-accordion-header.active'),
                            tab_index = this.dataset.tab;
                        if( ActiveNone.length > 0 ){
                            ActiveNone[0].classList.remove('active')
							if(ActiveNone[0].nextElementSibling){
								ActiveNone[0].nextElementSibling.classList.remove('active')
								$(ActiveNone[0].nextElementSibling).slideUp($accordionSpeed);  
							}                            
                        }

                        this.classList.toggle("active");
						if(this.nextElementSibling){
							this.nextElementSibling.classList.toggle("active");
							$(this.nextElementSibling).slideToggle($accordionSpeed);
						}
                        if( $(this).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").length ){
                            $(this).next('.plus-accordion-content').find(" .list-carousel-slick > .post-inner-loop").slick('setPosition');
                        }

                        if( Connection && document.querySelectorAll('.'+Connection).length ){
                            accordion_tabs_connection(tab_index, Connection);
                        }
                        if( RBGConnection && document.querySelectorAll('#'+RBGConnection).length ){
                            background_accordion_tabs_conn(tab_index, RBGConnection);
                        }
                    }
                });			
            }else if( AccordionType == 'toggle' ) {
                $($PlusAccordionListHeader).on('click', function(){
                    if( this.classList.contains('active') ) {
                        this.classList.remove('active');
						if(this.nextElementSibling){
							this.nextElementSibling.classList.remove('active')
							$(this.nextElementSibling).slideUp($accordionSpeed);
						}                        
                    }else {
                        this.classList.toggle("active");
						if(this.nextElementSibling){
							this.nextElementSibling.classList.toggle("active");
							$(this.nextElementSibling).slideToggle($accordionSpeed);
						}
                    }
                });
            }

            if( hash && !$(hash).hasClass("active") && $(hash).length ){
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 1500);
                $(hash+".plus-accordion-header").trigger("click");
            }
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/tp-accordion.default', WidgetAccordionHandler);
	});
})(jQuery);