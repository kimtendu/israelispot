'use strict';
jQuery(function ($) {
    var direction;
    if($('body').hasClass('rtl')){
        direction = true;
    } else {
        direction = false;
    }
    //search
    $('.search-button').on("click", function(event) {
        $('.search-modal__close').attr('tabindex', '0');
        $('.search-modal__input').attr('tabindex', '0');
        $('.search-modal__submit').attr('tabindex', '0');
        $('#search-modal').addClass("open");
        $('#search-modal').find('.search-modal__input').focus();
    });

    $("#search-modal, .search-modal__close").on("click", function(event) {
        if (
            event.target == this ||
            event.currentTarget.className == "search-modal__close" ||
            event.keyCode == 27
        ) {
            $(this).removeClass("open");
            $(this).parent().removeClass("open");
        }
        $(this).attr('tabindex', '-1');
        $(this).parent().find('.search-modal__input').attr('tabindex', '-1');
        $(this).parent().find('.search-modal__submit').attr('tabindex', '-1');

        $(this).parent().prev().focus();
    });


    $('.banner').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        rtl: direction,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 20000
    });

    $('.selects__main').click(function (e) {
        $('.selects__main').removeClass('active');
        $('.selects__list').removeAttr('style');
        $(this).next().css('display', 'flex');
        $(this).addClass('active');
    });

    $(document).mouseup(function (e) {
        var container = $('.selects');
        if (container.has(e.target).length === 0){
            $('.selects__main').removeClass('active');
            $('.selects__list').removeAttr('style');
        }
    });

    $('.select__button').click(function () {
        $($(this).parent().parent().parent().find('input')[0]).val($(this).val());
        $($(this).parent().parent().parent().find('.selects__main span')[0]).text($(this).text());
        $('.selects__list').removeAttr('style');
        $('.selects__main').removeClass('active');
    });


    if(window.location.search.indexOf('activate=true') !== -1) {
        $('#login-modal').addClass("open");
    }
        //login
    $('a[href="#login"]').click(function (e) {
        e.preventDefault();
        if(window.location.search.indexOf('gf_activation') !== -1) {
            window.location = window.location.origin+'?activate=true';
        } else {
            $('#login-modal').addClass("open");
        }
    });

    $("#login-modal, #login-modal__close").on("click", function(event) {
        if (
            event.target == this ||
            event.currentTarget.className == "login-modal__close" ||
            event.keyCode == 27
        ) {
            $(this).removeClass("open");
            $(this).parent().parent().parent().removeClass("open");
        }
    });

    $('.login-modal__button_register').click(function () {
        $('.login-modal__button').removeClass('active');
        $('.login-modal__login').removeClass('active');

        $('.login-modal__register').addClass('active');
        $(this).addClass('active');
    });

    $('.login-modal__button_login').click(function () {
        $('.login-modal__button').removeClass('active');
        $('.login-modal__register').removeClass('active');

        $('.login-modal__login').addClass('active');
        $(this).addClass('active');
    });

    setTimeout(function () {
        $('#holiday-modal').addClass("open");
    }, 10000);

    $("#holiday-modal, #holiday-modal__close").on("click", function(event) {
        if (
            event.target == this ||
            event.currentTarget.className == "login-modal__close" ||
            event.keyCode == 27
        ) {
            $(this).removeClass("open");
            $(this).parent().parent().parent().removeClass("open");
        }
    });

    var canBeLoadedActivity = true;
    $('.home-activities__favorite').click(function (e) {
        e.preventDefault();
        var localThis  = $(this);
        var data = {
            'action': 'loadactivity',
            'id':  $(this).data('id')
        };
        if(canBeLoadedActivity){
            jQuery.ajax({
                url: document.location.origin+'/wp-admin/admin-ajax.php',
                data: data,
                type: 'POST',
                beforeSend: function(){
                    canBeLoadedActivity = false;
                    if(localThis.find('i').hasClass('fa-heart-o')){
                        localThis.find('i').removeClass('fa-heart-o');
                        localThis.find('i').addClass('fa-heart');
                    } else {
                        localThis.find('i').addClass('fa-heart-o');
                        localThis.find('i').removeClass('fa-heart');
                    }
                },
                success: function (data) {
                    canBeLoadedActivity = true;
                },
                error: function (data) {
                    if(localThis.find('i').hasClass('fa-heart-o')){
                        localThis.find('i').removeClass('fa-heart-o');
                        localThis.find('i').addClass('fa-heart');
                    } else {
                        localThis.find('i').addClass('fa-heart-o');
                        localThis.find('i').removeClass('fa-heart');
                    }
                    canBeLoadedActivity = true;
                }
            })
        }
    });
});