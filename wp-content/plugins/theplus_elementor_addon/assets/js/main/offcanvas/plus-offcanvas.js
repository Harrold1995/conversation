/*offcanvas*/(function(){PlusOffcanvas=function(b){"use strict";this.node=b,this.wrap=b.find(".plus-offcanvas-wrapper"),this.content=b.find(".plus-canvas-content-wrap"),this.button=b.find(".offcanvas-toggle-btn"),this.settings=this.wrap.data("settings"),this.id=this.settings.content_id,this.transition=this.settings.transition,this.esc_close=this.settings.esc_close,this.body_click_close=this.settings.body_click_close,this.direction=this.settings.direction,this.trigger=this.settings.trigger,this.tpageload=this.settings.tpageload,this.tscroll=this.settings.tscroll,this.texit=this.settings.texit,this.tinactivity=this.settings.tinactivity,this.tpageviews=this.settings.tpageviews,this.tprevurl=this.settings.tprevurl,this.textraclick=this.settings.textraclick,this.scrollHeight=this.settings.scrollHeight,this.previousUrl=this.settings.previousUrl,this.extraId=this.settings.extraId,this.extraIdClose=this.settings.extraIdClose,this.inactivitySec=this.settings.inactivitySec,this.duration=500,this.time=0,this.flag=!0,this.destroy(),this.init()},PlusOffcanvas.prototype={id:"",node:"",wrap:"",content:"",button:"",settings:{},transition:"",delaytimeout:"",duration:400,initialized:!1,animations:["slide","slide-along","reveal","push","popup"],init:function(){this.wrap.length&&(jQuery("html").addClass("plus-offcanvas-content-widget"),0===jQuery(".plus-offcanvas-container").length&&(jQuery("body").wrapInner("<div class=\"plus-offcanvas-container\" />"),this.content.insertBefore(".plus-offcanvas-container")),0<this.wrap.find(".plus-canvas-content-wrap").length&&(0<jQuery(".plus-offcanvas-container > .plus-"+this.id).length&&jQuery(".plus-offcanvas-container > .plus-"+this.id).remove(),0<jQuery("body > .plus-"+this.id).length&&jQuery("body > .plus-"+this.id).remove(),jQuery("body").prepend(this.wrap.find(".plus-canvas-content-wrap"))),this.bindEvents())},destroy:function(){this.close(),this.animations.forEach(function(a){jQuery("html").hasClass("plus-"+a)&&jQuery("html").removeClass("plus-"+a)}),jQuery("body > .plus-"+this.id).length},bindEvents:function(){this.trigger&&"yes"==this.trigger&&this.button.on("click",jQuery.proxy(this.toggleContent,this)),this.textraclick&&"yes"==this.textraclick&&this.extraId&&""!=this.extraId&&this.triggerClick(),this.textraclick&&"yes"==this.textraclick&&this.extraIdClose&&""!=this.extraIdClose&&this.triggerECClick(),(this.tpageload&&"yes"==this.tpageload||this.tinactivity&&"yes"==this.tinactivity||this.tprevurl&&"yes"==this.tprevurl)&&this.loadShow(),jQuery(window).on("scroll",jQuery.proxy(this.scrollShow,this)),jQuery(document).on("mouseleave",jQuery.proxy(this.exitInlet,this)),jQuery("body").delegate(".plus-canvas-content-wrap .plus-offcanvas-close","click",jQuery.proxy(this.close,this)),"yes"===this.esc_close&&this.closeESC()},triggerClick:function(){this.textraclick&&"yes"==this.textraclick&&this.extraId&&""!=this.extraId&&this.flag&&jQuery("."+this.extraId).on("click",jQuery.proxy(this.toggleContent,this))},triggerECClick:function(){this.textraclick&&"yes"==this.textraclick&&this.extraIdClose&&""!=this.extraIdClose&&this.flag&&jQuery("."+this.extraIdClose).on("click",jQuery.proxy(this.toggleContent,this))},toggleContent:function(a){a.preventDefault(),jQuery("html").hasClass("plus-open")?this.close():this.show()},exitInlet:function(){this.texit&&"yes"==this.texit&&this.flag?(this.show(),this.flag=!1):""},loadShow:function(){if(this.tpageload&&"yes"==this.tpageload&&this.flag&&setTimeout(()=>{this.show(),this.flag=!1},500),this.tinactivity&&"yes"==this.tinactivity&&this.flag&&this.inactivitySec&&""!=this.inactivitySec){var a;if(this.flag){function b(b){clearTimeout(a),a=setTimeout(function(){b.show(),b.flag=!1},b.inactivitySec)}document.onmousemove=b(this),document.onkeypress=b(this)}}this.tprevurl&&"yes"==this.tprevurl&&this.previousUrl&&document.referrer&&this.previousUrl==document.referrer&&this.flag&&setTimeout(()=>{this.show()},500)},scrollShow:function(){var a=this.scrollHeight,b=jQuery(window).scrollTop();this.tscroll&&"yes"==this.tscroll&&this.flag&&b>=a?(this.show(),this.flag=!1):""},show:function(){jQuery(".plus-"+this.id).addClass("plus-visible"),jQuery("html").addClass("plus-"+this.transition),jQuery("html").addClass("plus-"+this.direction),jQuery("html").addClass("plus-open"),jQuery("html").addClass("plus-"+this.id+"-open"),jQuery("html").addClass("plus-reset"),this.button.addClass("plus-is-active");var b=this,c="";c=this.textraclick&&"yes"==this.textraclick&&this.extraId&&""!=this.extraId&&this.flag?"."+this.extraId:".offcanvas-toggle-btn",jQuery("html.plus-"+this.id+"-open .plus-offcanvas-container").off("click"),jQuery("html.plus-"+this.id+"-open .plus-offcanvas-container").on("click",function(d){jQuery(d.target).is(".plus-canvas-content-wrap")||0<jQuery(d.target).parents(".plus-canvas-content-wrap").length||jQuery(d.target).is(".offcanvas-toggle-btn")||0<jQuery(d.target).parents(".offcanvas-toggle-btn").length||jQuery(d.target).is(c)||0<jQuery(d.target).parents(c).length||b.close()})},close:function(){jQuery(".plus-"+this.id).hasClass("plus-slide-along")?(this.delaytimeout=0,jQuery(".plus-"+this.id).removeClass("plus-visible")):this.delaytimeout=500,setTimeout(jQuery.proxy(function(){jQuery("html").removeClass("plus-reset"),jQuery("html").removeClass("plus-"+this.transition),jQuery("html").removeClass("plus-"+this.direction),jQuery(".plus-"+this.id).hasClass("plus-slide-along")||jQuery(".plus-"+this.id).removeClass("plus-visible"),jQuery("html").removeClass("plus-open"),jQuery("html").removeClass("plus-"+this.id+"-open")},this),this.delaytimeout),this.button.removeClass("plus-is-active")},closeESC:function(){var b=this;""!==b.settings.esc_close&&jQuery(document).on("keydown",function(a){27===a.keyCode&&b.close()})},closeClick:function(){var b=this,c="";c=this.textraclick&&"yes"==this.textraclick&&this.extraId&&""!=this.extraId&&this.flag?"."+this.extraId:".offcanvas-toggle-btn",jQuery(document).on("click",function(d){jQuery(d.target).is(".plus-canvas-content-wrap")||0<jQuery(d.target).parents(".plus-canvas-content-wrap").length||jQuery(d.target).is(".offcanvas-toggle-btn")||0<jQuery(d.target).parents(".offcanvas-toggle-btn").length||jQuery(d.target).is(c)||0<jQuery(d.target).parents(c).length||b.close()})}}})(jQuery),function(a){var b=function(a,c){var d=a.find(".plus-offcanvas-wrapper");if(0<d.length){var f=d.data("settings"),g="pageViewsCount-"+f.content_id,h="pageXTimeView-"+f.content_id,i=!0;if(f.tpageviews!=null&&"yes"==f.tpageviews&&f.tpageviewscount!=null&&""!=f.tpageviewscount){var j=localStorage.getItem(g);if(j){var k=+j+1;localStorage.setItem(g,k)}else localStorage.setItem(g,1);i=!!(+localStorage.getItem(g)>=+f.tpageviewscount)}else localStorage.getItem(g)&&localStorage.removeItem(g);if(null!=f.sr&&"yes"==f.sr&&""!=f.srxtime&&""!=f.srxdays){var j=localStorage.getItem(h);if(j=jQuery.parseJSON(j),null!=j&&null!=j.xtimeView){var k=+j.xtimeView+1;localStorage.setItem(h,JSON.stringify(Object.assign({},j,{xtimeView:k})))}else localStorage.setItem(h,"{ \"xtimeView\": 1 }");if(+jQuery.parseJSON(localStorage.getItem(h)).xtimeView<=+f.srxtime)i=!0;else{var l=new Date,m=new Date,n=m.setDate(l.getDate()+ +f.srxdays),j=localStorage.getItem(h);j=jQuery.parseJSON(j);var o=Object.assign({},j,{Xdate:n});null!=j&&null==j.Xdate&&localStorage.setItem(h,JSON.stringify(o)),i=!1;var p=localStorage.getItem(h);p=jQuery.parseJSON(p),null!=p&&null!=p.Xdate&&new Date(+l)>new Date(+p.Xdate)&&(localStorage.removeItem(h),i=!0)}}else localStorage.getItem(h)&&localStorage.removeItem(h);if(i)d.removeAttr("style"),new PlusOffcanvas(a);else return d.html(""),!1}var q=a.find(".plus-offcanvas-wrapper.scroll-view"),e=a.find(".offcanvas-toggle-btn.position-fixed");0<q.length&&e&&c(window).on("scroll",function(){var d=c(this).scrollTop();q.each(function(){var f=c(this).data("scroll-view"),e=c(this).data("canvas-id"),a=c("."+e);d>f?a.addClass("show"):a.removeClass("show")})})};a(window).on("elementor/frontend/init",function(){elementorFrontend.hooks.addAction("frontend/element_ready/tp-off-canvas.default",b)})}(jQuery);