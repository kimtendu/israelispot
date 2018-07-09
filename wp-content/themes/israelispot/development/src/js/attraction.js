'use strict';
jQuery(function ($) {
    var direction;
    if($('body').hasClass('rtl')){
        direction = true;
    } else {
        direction = false;
    }
    $('.activity__slider').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true,
        rtl: direction
    });

    //margin
    var activityHeight = $('.activity__main').height();
    var contentHeight = $('.activity-content').height();
    var sidebarHeight = $('.activity__sidebar').height();
    var clear = $('.clear');

    if($(window).width() > 767){
        if(activityHeight+contentHeight < sidebarHeight){
            clear.css('margin-top', sidebarHeight-activityHeight-contentHeight);
        }
    }
});
