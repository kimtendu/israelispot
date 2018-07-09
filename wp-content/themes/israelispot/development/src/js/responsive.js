jQuery(function ($) {
    $('.menu__mobile-button').click(function () {
        $(this).toggleClass('active');
        $('.home-menu__list').toggleClass('active');
    });

    var menuArrow = $('.menu__arrow');
    var menuLink = $('.home-menu__link');
    var submenuList = $('.submenu__list');
    menuArrow.click(function () {
        menuArrow.removeClass('active');
        menuLink.removeClass('active');
        submenuList.slideUp('slow');
        if(!$(this).parent().parent().find('.submenu__list').is(":visible")){
            $(this).parent().parent().find('.submenu__list').slideDown('slow');
            $(this).parent().find('.home-menu__link').addClass('active');
            $(this).addClass('active');
        }
    });

    //black
    $('.menu__black-button').click(function () {
        $(this).toggleClass('active');
        $('.home-second-menu__list').toggleClass('active');
    });

    function dynamicVideoHeight() {
        var iframeVideos = $('.custom-content iframe');
        iframeVideos.css({height: iframeVideos.width()*0.75});
    }

    dynamicVideoHeight();


    jQuery(window).resize(function () {
        dynamicVideoHeight();
    });

    jQuery( window ).on( "orientationchange", function() {
        dynamicVideoHeight();
    });
});