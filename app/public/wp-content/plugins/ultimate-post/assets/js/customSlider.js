(function ($) {
	'use strict';
	$.fn.UltpSlider = function (options) {

    /*Merge options and default options*/
    let opts = $.extend({}, $.fn.UltpSlider.defaults, options);
		/*Functions Scope*/
		let thisTicker = $(this), intervalID, timeoutID, isPause = false;
    let pauseVal  = true;

    /*Always wrap, used in many place*/
    thisTicker.wrap("<div class='acmeticker-wrap'></div>");
		/*Wrap is always relative*/
		thisTicker.parent().css({
			position: 'relative'
		})

		/* ADD active class to first item*/
    thisTicker.children().first().addClass("active");  

    // working only horizontal , vertical and typewriter
    if(opts.type == 'horizontal'|| opts.type == 'vertical' || opts.type == 'typewriter') {
      // typewriter
      let typeAutoPlay = ""
      if(opts.type == 'typewriter'){
        typeAutoPlay  =  setInterval(function(){
          slidePlay();
        }, opts.speed);
      }
      // typewriter

      let sliderAutoPlay = "";
        if(opts.type == 'horizontal'|| opts.type == 'vertical' ) {
          sliderAutoPlay =  setInterval(function(){
            slidePlay();
          }, opts.speed);
        }

        // Prev Func  
        $(opts.controls.prev).click(function () {
            if(opts.type == 'horizontal'|| opts.type == 'vertical' ) {
                  clearInterval(sliderAutoPlay);
                  handleSlider("prev");
                  if(pauseVal){
                    sliderAutoPlay =  setInterval(function(){
                      slidePlay();
                    }, opts.speed);
                  }
            } 
            if(opts.type == 'typewriter'){
              clearInterval(typeAutoPlay);
                handleSlider("prev");
                if(pauseVal){
                  typeAutoPlay =  setInterval(function(){
                    slidePlay();
                  }, opts.speed);
                }
            }
        });
        // Next Func  
        $(opts.controls.next).click(function () {
          if(opts.type == 'horizontal'|| opts.type == 'vertical') { 
            clearInterval(sliderAutoPlay);
            handleSlider("next");
            if(pauseVal){
              sliderAutoPlay =  setInterval(function(){
                slidePlay();
              }, opts.speed);
            }
          }
          if(opts.type == 'typewriter'){
            clearInterval(typeAutoPlay);
            handleSlider("next");
            if(pauseVal){
              typeAutoPlay =  setInterval(function(){
                slidePlay();
              }, opts.speed);
            }
          }
        });

        // Pause Button Func  
        $(opts.controls.toggle).click(function () {
          if(opts.type == 'horizontal' || opts.type == 'vertical'){
              if(pauseVal){
                pauseVal = false;
                clearInterval(sliderAutoPlay)
              } else {
                pauseVal = true;
                clearInterval(sliderAutoPlay)
                sliderAutoPlay =  setInterval(function(){
                    slidePlay();
                }, opts.speed);
            }
          }
          if(opts.type == 'typewriter'){
            if(pauseVal){
              pauseVal = false;
              clearInterval(typeAutoPlay)
            } else {
              pauseVal = true;
              clearInterval(typeAutoPlay)
              typeAutoPlay =  setInterval(function(){
                  slidePlay();
              }, opts.speed);
            }
          }
        });
        // hover in pause
        if(opts.pauseOnHover){

          thisTicker.hover(function(){
            if(opts.type == "typewriter"){
              clearInterval(typeAutoPlay)
            }
            if(opts.type == "horizontal" ||  opts.type == "vertical"){
              clearInterval(sliderAutoPlay)
            }
            }, function(){
              if(opts.type == "typewriter" && pauseVal){
                typeAutoPlay  =  setInterval(function(){
                  slidePlay();
                }, opts.speed);
              }
              if(opts.type == "horizontal" ||  opts.type == "vertical" && pauseVal){
                sliderAutoPlay =  setInterval(function(){
                  slidePlay();
                }, opts.speed);
              }
            })
        }
    } 
    if(opts.type == 'marquee') {
      let marqueeSpeed = 9.00;
       let i = 0;
       let mainWidth;
       let dir = opts.direction;
       let contentWidth = thisTicker.outerWidth();
       let wrapperWidth = $('.ultp-newsTicker-wrap').outerWidth();
      if(dir == "right"){
        mainWidth = wrapperWidth;
      }
      if(dir == "left"){
        mainWidth = thisTicker.outerWidth();
      }
      let marqueeSlide = setInterval(function(){
        if(mainWidth < i && dir == "left") {
              i = -wrapperWidth;
          }
        if(-(mainWidth)  > i && dir == "left"){
            i = wrapperWidth;
        }
        if(mainWidth < i && dir == "right") {
          i = -contentWidth;
        }
        thisTicker.css(dir, -i )
          i++;
      }, marqueeSpeed);
      // Prev Button Control
      $(opts.controls.prev).click(function () {
        if(!pauseVal){
          // If Slide Pause
          pauseVal = true
          marqueeSlide = setInterval(function(){
                if(mainWidth < i && dir == "left") {
                  i = -wrapperWidth;
                }
                if(-(mainWidth)  > i && dir == "left"){
                    i = wrapperWidth;
                }
                if(mainWidth < i && dir == "right") {
                  i = -contentWidth;
                }
                thisTicker.css(dir, -i )
                  i++;
              }, marqueeSpeed);
        } else {
          if(-(mainWidth)  > i && dir == "right"){
            i = $(window).width();
          }
          let childWidth = thisTicker.outerWidth() / thisTicker.children().length;
          i = i - childWidth;
        }
      });
      // Next Button Control
      $(opts.controls.next).click(function () {
            if(!pauseVal){
              // If Slide Pause
              pauseVal = true
              marqueeSlide = setInterval(function(){
                if(mainWidth < i && dir == "left") {
                  i = -wrapperWidth;
                }
                if(-(mainWidth)  > i && dir == "left"){
                    i = wrapperWidth;
                }
                if(mainWidth < i && dir == "right") {
                  i = -contentWidth;
                }
                    thisTicker.css(dir, -i )
                      i++;
                  }, marqueeSpeed);
            } else {
              let ChildWidth = thisTicker.outerWidth() / thisTicker.children().length;
              i = i + ChildWidth;
            }
      });
      // Pause Control Button
      $(opts.controls.toggle).click(function () {
        if(pauseVal){
          pauseVal = false;
          clearInterval(marqueeSlide);
        } else {
          pauseVal = true;
          marqueeSlide = setInterval(function(){
            if(mainWidth < i && dir == "left") {
                  i = -wrapperWidth;
              }
            if(-(mainWidth)  > i && dir == "left"){
                i = wrapperWidth;
            }
            if(mainWidth < i && dir == "right") {
              i = -contentWidth;
            }
                thisTicker.css(dir, -i )
                  i++;
              }, marqueeSpeed);
        }
      });
      //  Hover Pause Control Button
      if(opts.pauseOnHover){
        thisTicker.hover(function() {
          clearInterval(marqueeSlide)
        },function() {
          if(pauseVal){
            marqueeSlide = setInterval(function(){
              if(mainWidth < i && dir == "left") {
                    i = -wrapperWidth;
                }
              if(-(mainWidth)  > i && dir == "left"){
                  i = wrapperWidth;
              }
              if(mainWidth < i && dir == "right") {
                i = -contentWidth;
              }
                  thisTicker.css(dir, -i )
                    i++;
                }, marqueeSpeed);
          }
        })
      }
    }
    // Next Prev Slide Control Func  
    function handleSlider(val) {
      let slideIndex = thisTicker.find(".active").index();
      if (slideIndex < 0) {
        slideIndex = 0;
      }
      let index = 1;
      if (val == "prev") {
        thisTicker.children().eq(slideIndex).removeClass("active");
        thisTicker
          .children()
          .eq(slideIndex - index)
          .addClass("active");
      }
      if (val == "next") {
        thisTicker.children().eq(slideIndex).removeClass("active");
        if (slideIndex == thisTicker.children().length - 1) {
          index = -(thisTicker.children().length - 1);
        }
        thisTicker
          .children()
          .eq(slideIndex + index)
          .addClass("active");
      }
    }
    // Auto Slide Function 
    function slidePlay() {
      let index = 1;
      let slideIndex = thisTicker.find(".active").index();
        if (slideIndex < 0) {
            slideIndex = 0;
        }
        thisTicker.children().eq(slideIndex).removeClass("active");
        if (slideIndex == thisTicker.children().length - 1) {
          index = -(thisTicker.children().length - 1);
        }
        thisTicker
          .children()
          .eq(slideIndex + index)
          .addClass("active");
    }
  }
  $.fn.UltpSlider.defaults = {
		/*Note: Marquee only take speed not autoplay*/
		type: 'horizontal',/*vertical/horizontal/marquee/typewriter*/
		autoplay: 2000,/*true/false/number*/ /*For vertical/horizontal 4000*//*For typewriter 2000*/
		speed: 50,/*true/false/number*/ /*For vertical/horizontal 600*//*For marquee 0.05*//*For typewriter 50*/
		direction: 'up',/*up/down/left/right*//*For vertical up/down*//*For horizontal/marquee right/left*//*For typewriter direction doesnot work*/
		pauseOnFocus: true,
		pauseOnHover: true,
		controls: {
			prev: '',/*Can be used for vertical/horizontal/typewriter*//*not work for marquee*/
			next: '',/*Can be used for vertical/horizontal/typewriter*//*not work for marquee*/
			toggle: ''/*Can be used for vertical/horizontal/marquee/typewriter*/
		}
	}; 
})(jQuery);
