jQuery(function ($) {
    function render_map( $el ) {

        var $markers = $el.find('.marker');
        var args = {
            zoom : 16,
            center : new google.maps.LatLng(0, 0),
            mapTypeId : google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            styles: [
                {
                    "featureType": "administrative.country",
                    "elementType": "labels.text",
                    "stylers": [
                        {
                            "lightness": "29"
                        }
                    ]
                },
                {
                    "featureType": "administrative.province",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "lightness": "-12"
                        },
                        {
                            "color": "#796340"
                        }
                    ]
                },
                {
                    "featureType": "administrative.locality",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "lightness": "15"
                        },
                        {
                            "saturation": "15"
                        }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#fbf5ed"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#fbf5ed"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi.attraction",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "lightness": "30"
                        },
                        {
                            "saturation": "-41"
                        },
                        {
                            "gamma": "0.84"
                        }
                    ]
                },
                {
                    "featureType": "poi.attraction",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.business",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi.business",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi.medical",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#fbd3da"
                        }
                    ]
                },
                {
                    "featureType": "poi.medical",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#b0e9ac"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "hue": "#68ff00"
                        },
                        {
                            "lightness": "-24"
                        },
                        {
                            "gamma": "1.59"
                        }
                    ]
                },
                {
                    "featureType": "poi.sports_complex",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.sports_complex",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "saturation": "10"
                        },
                        {
                            "color": "#c3eb9a"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "lightness": "30"
                        },
                        {
                            "color": "#e7ded6"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "saturation": "-39"
                        },
                        {
                            "lightness": "28"
                        },
                        {
                            "gamma": "0.86"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffe523"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "saturation": "0"
                        },
                        {
                            "gamma": "1.44"
                        },
                        {
                            "color": "#fbc28b"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "saturation": "-40"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#fed7a5"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "gamma": "1.54"
                        },
                        {
                            "color": "#fbe38b"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "visibility": "on"
                        },
                        {
                            "gamma": "2.62"
                        },
                        {
                            "lightness": "10"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "weight": "0.50"
                        },
                        {
                            "gamma": "1.04"
                        }
                    ]
                },
                {
                    "featureType": "transit.station.airport",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#dee3fb"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "saturation": "46"
                        },
                        {
                            "color": "#a4e1ff"
                        }
                    ]
                }
            ]
        };

        var map = new google.maps.Map( $el[0], args);

        map.markers = [];

        $markers.each(function(){
            add_marker( $(this), map );
        });
        center_map( map );

        //////////////
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        directionsDisplay.setMap(map);

        var onChangeHandler = function(e) {
            e.preventDefault();
            calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        jQuery('#directMap').click(onChangeHandler);

        function calculateAndDisplayRoute(directionsService, directionsDisplay) {
            var pos = {
                lat: +$($markers[0]).attr('data-lat'),
                lng: +$($markers[0]).attr('data-lng')
            };
            if (navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(function(position) {
                    pos.lat = +position.coords.latitude;
                    pos.lng = +position.coords.longitude;
                });
            }

            directionsService.route({
                origin: {
                    lat: pos.lat,
                    lng: pos.lng
                },
                destination: {
                    lat: +$($markers[0]).attr('data-lat'),
                    lng: +$($markers[0]).attr('data-lng')
                },
                travelMode: 'DRIVING'
            }, function(response, status) {
                if (status === 'OK') {
                    // directionsDisplay.setDirections(response);
                    new google.maps.DirectionsRenderer({
                        map: map,
                        directions: response,
                        suppressMarkers: true
                    });
                    var leg = response.routes[0].legs[0];

                    function makeMarker(position, icon, map) {
                        new google.maps.Marker({
                            position: position,
                            map: map,
                            icon: icon
                        });
                    }

                    makeMarker(leg.start_location, $($markers[0]).attr('data-user'), map);
                    makeMarker(leg.end_location, $($markers[0]).attr('data-icon'), map);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }
    }
    function add_marker( $marker, map ) {

        var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
        var icon = {
            url: $marker.attr('data-icon')
        };
        var marker = new google.maps.Marker({
            position : latlng,
            map : map,
            icon : icon
        });

        map.markers.push( marker );

        google.maps.event.addListener(marker, 'click', function() {
            map.setZoom(12);
            map.setCenter(marker.getPosition());
        });

        if( $marker.html() )
        {
            var infowindow = new google.maps.InfoWindow({
                content : $marker.html()
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open( map, marker );
                map.setZoom(12);
                map.setCenter(marker.getPosition());
            });
        }
    }
    function center_map( map ) {

        var bounds = new google.maps.LatLngBounds();

        $.each( map.markers, function( i, marker ){

            var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

            bounds.extend( latlng );

        });

        if( map.markers.length == 1 )
        {
            map.setCenter( bounds.getCenter() );
            map.setZoom( 12 );
        }
        else
        {
            map.fitBounds( bounds );
        }

    }

    $(document).ready(function(){

        $('.acf-map').each(function(){

            render_map( $(this) );

        });

    });

    var ratingChecked;
    var ratingRadio = $('input[name="rating"]');
    var ratingStatus = $('.rating-status');
    var stars = $('.comment-form .home-activities__rating i');

    stars.hover(function () {
        var index = $(this).index();
        stars.removeClass('fa-star').addClass('fa-star-o');
        stars.each(function (el) {
            if(el <= index){
                $(stars[el]).removeClass('fa-star-o').addClass('fa-star');
            }
        });
        ratingStatus.text($($(ratingRadio)[index]).data('status'));
    }, function () {
        if(typeof ratingChecked !== "undefined"){
            stars.each(function (el) {
                $(stars[el]).removeClass('fa-star').addClass('fa-star-o');
                if(el <= ratingChecked){
                    $(stars[el]).removeClass('fa-star-o').addClass('fa-star');
                }
            });
            ratingStatus.text($($(ratingRadio)[ratingChecked]).data('status'));
        } else {
            stars.removeClass('fa-star').addClass('fa-star-o');
            ratingStatus.text($($(ratingRadio)[0]).data('status'));
        }
    });

    stars.click(function () {
        var index = $(this).index();
        $($(ratingRadio)[index]).attr("checked", true);
        ratingChecked = index;
        stars.each(function (el) {
            if(el <= index){
                $(stars[el]).removeClass('fa-star-o').addClass('fa-star');
            }
        });
        ratingStatus.text($($(ratingRadio)[index]).data('status'));
    });

    //order
    var canBeLoadedClick = true;
    $('a[href="#order"]').click(function (e) {
        e.preventDefault();
        $('#order-modal').addClass("open");

        var localThis  = $(this);
        var data = {
            'action': 'addclick',
            'id':  $(this).data('id'),
            'type': $(this).data('type')
        };
        if(canBeLoadedClick){
            jQuery.ajax({
                url: document.location.origin+'/wp-admin/admin-ajax.php',
                data: data,
                type: 'POST',
                beforeSend: function(){
                    canBeLoadedClick = false;
                },
                success: function (data) {
                    canBeLoadedClick = true;
                },
                error: function (data) {
                    canBeLoadedClick = true;
                }
            })
        }
    });

    $("#order-modal, #order-modal__close").on("click", function(event) {
        if (
            event.target == this ||
            event.currentTarget.className == "login-modal__close" ||
            event.keyCode == 27
        ) {
            $(this).removeClass("open");
            $(this).parent().parent().parent().removeClass("open");
        }
    });

    //recommendation scroll
    var hashTagActive = "";
    $(".link_recommendations").on("click touchstart" , function (event) {
        event.preventDefault();
        if(hashTagActive != this.hash) {
            var dest = 0;
            if ($(this.hash).offset().top > $(document).height() - $(window).height()) {
                dest = $(document).height() - $(window).height();
            } else {
                dest = $(this.hash).offset().top;
            }
            //go to destination
            $('html,body').animate({
                scrollTop: dest
            }, 500, 'swing');
            hashTagActive = this.hash;
        }
    });

});