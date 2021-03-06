<?php /* Template Name: Search */
get_header();

isset($_GET['id']) ? $getId = $_GET['id'] : $getId = false;
isset($_GET['favorites']) ? $getfavorites = $_GET['favorites'] : $getfavorites = false;

if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
$start = microtime(true);

?>
<div id="search-filter"></div>



<?php
    if($getId){
        if($_GET['id'] != 'nothing'){
            $args = array(
                'numberposts'   => -1,
                'post_type'     => 'product',
                'post__in'      => json_decode($_GET['id']),
                'orderby'       => 'rand'
            );
        } else {
            $args = [];
        }
    } else if($getfavorites == 'true') {
        $favorites = [];
        if(get_field('favorite_activities', 'user_'.get_current_user_id())) {
            foreach (get_field('favorite_activities', 'user_'.get_current_user_id()) as $fav){
                $favorites[] = $fav;
            }
            $args = array(
                'numberposts'   => -1,
                'post_type'     => 'product',
                'post__in'      => $favorites,
                'lang'          => $lang,
                'orderby'       => 'rand'
            );
        } else {
            $args = array(

            );
        }
    } else {
        $args = array(
            'numberposts'   => -1,
            'post_type'        => 'product',
            'orderby'          => 'rand'
        );
    }

    $allPosts = get_posts( $args );
    $posts = [];
    $today = date('Ymd');

    echo "<!--microtime get_posts".round(microtime(true) - $start, 4).' sek-->';


    foreach ($allPosts as $allPost){
        if(get_field('date', $allPost)){
            $date = get_field('date', $allPost);
        } else {
            $date = get_the_date('Ymd', $allPost);
            $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
        }

        if($date >= $today){
            $posts[] = $allPost;
        }
    }

    $activities = [];
    foreach ($posts as $post) {
        $attraction = get_field('attraction', $post);
        if(get_field('date', $attraction)){
            $date = get_field('date', $attraction);
        } else {
            $date = get_the_date('Ymd', $attraction);
            $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
        }
        if($date >= $today){
            $activities[] = $post;
        }
    }
?>
 <?php echo "<!--microtime after foreach".round(microtime(true) - $start, 4).' sek-->'; ?>
<script type="text/babel">

    <?php
        if( is_user_logged_in() ){
            $userFav = [];
            if(get_field('favorite_activities', 'user_'.get_current_user_id())) {
                foreach (get_field('favorite_activities', 'user_'.get_current_user_id()) as $fav){
                    $userFav[] = $fav;
                }
            } else {
                $userFav = 0;
            }

        } else {
            $userFav = 0;
        }
    ?>
    <?php echo "/*microtime after is_user_logged_in".round(microtime(true) - $start, 4).' sek*/'; ?>
    var logged = '<?php echo is_user_logged_in(); ?>';
    var userFav = '<?php echo json_encode($userFav); ?>';
    <?php echo "/*microtime before Activities".round(microtime(true) - $start, 4).' sek*/'; ?>
    var Activities = [
        <?php foreach ($activities as $post) :
        $post_id = $post->ID;
        if(get_field('price', $post_id)){
            $price = get_field('price', $post_id);

        } else if (get_field('price', $post_id) === '0'){
            $price = 0;
        } else {
            $price = '';
        }
        $rating = 'default';
        $commentsRating = [];
        $comments = get_comments(array(
            'post_id' => $post->ID,
            'status' => 'approve'
        ));

        if(count($comments) !=0 ){
            foreach ($comments as $comment){
                $commentsRating[] = get_comment_meta( $comment->comment_ID, 'rating', true );
            }
            $rating = round(array_sum($commentsRating)/count($commentsRating));
        }

        $tags = get_the_terms($post, 'activity_tags');
        $tagsNames = [];
        if($tags){
            foreach ($tags as $tag) {
                $tagsNames[] = $tag->name;
            }
        }

        echo "/*microtime Activities before attraction ".round(microtime(true) - $start, 4).' sek*/';
        $attraction = get_field('attraction', $post_id);




        $tagsAttractionNames = [];

        if($attraction){
            $attractionID = $attraction->ID;
            $attractionLink = esc_js(get_permalink($attraction));
            $guide = $attraction->post_title;
            $location = get_field('location', $attractionID);
            $image = get_the_post_thumbnail_url( $attractionID, 'medium');
            $tags = get_the_terms($attractionID, 'attraction_tags');
            if($tags){
                foreach ($tags as $tag) {
                    $tagsAttractionNames[] = $tag->name;
                }
            }
        } else {
            $attractionID = false;
            $attractionLink = "";
            $guide = "";
            $location = "";
            $image = "";
            $tagsAttractionNames= [];
        }
        echo "/*microtime Activities attraction ".round(microtime(true) - $start, 4).' sek*/';
        $categories = get_the_terms($attractionID, 'attraction_categories');
        $categoriesNames = [];
        if($categories){
            foreach ($categories as $category) {
                $categoriesNames[] = $category->name;
            }
        }
        echo "/*microtime Activities categories ".round(microtime(true) - $start, 4).' sek*/';
        $mainCategory = new WPSEO_Primary_Term( 'attraction_categories', $attractionID );
        $mainCategory = $mainCategory->get_primary_term();
        $mainCategory = get_term( $mainCategory );
        $mainCategoryImage = get_field('map_icon', $mainCategory);

        if($mainCategoryImage == ''){
            $mainCategoryImage = get_field('map_icon', $categories[0]);
        }
        ?>
        {
            id: <?php echo $post->ID; ?>,
            price: '<?php echo $price; ?>',
            childPrice: '<?php the_field('child_price', $post_id); ?>',
            currency: '<?php pll_e('NIS'); ?>',
            rating: '<?php echo $rating; ?>',
            region: '<?php echo $location; ?>',
            subImage: '<?php echo $image; ?>',
            guide: '<?php echo esc_js($guide); ?>',
            attractionLink: '<?php echo $attractionLink; ?>',
            title: '<?php echo esc_js($post->post_title); ?>',
            mainCategory: '<?php echo $mainCategory->name; ?>',
            mainCategoryIcon: '<?php echo $mainCategoryImage; ?>',
            link: '<?php echo esc_js(get_permalink($post)); ?>',            
			excerpt: '<?php echo esc_js(wp_trim_words(get_the_excerpt($post), 15, '...')); ?>',
            image: '<?php echo get_the_post_thumbnail_url($post, 'medium') ?>',
            lat: '<?php echo get_field('map', $attractionID)['lat']; ?>',
            lng: '<?php echo get_field('map', $attractionID)['lng']; ?>',
            tags: <?php echo json_encode($tagsNames); ?>,
            tagsAttraction: <?php echo json_encode($tagsAttractionNames); ?>,
            categories: <?php echo json_encode($categoriesNames); ?>,
            comments: <?php echo count($comments); ?>,
            attractionId: <?php echo $attractionID; ?>
        },
        <?php endforeach; ?>
    ];
    <?php echo "/*microtime Activities".round(microtime(true) - $start, 4).' sek*/'; ?>
    var Cooperations = [
        <?php foreach (get_field('cooperations_'.$lang, 'options') as $term): ?>
        {
          name: '<?php echo $term->name; ?>',
          image: '<?php echo get_field('image', $term)['url']; ?>'
        },
        <?php endforeach; ?>
    ];
    <?php echo "/*microtime after Cooperations".round(microtime(true) - $start, 4).' sek*/'; ?>
    var Params = {
        price: '<?php echo $_GET['price']; ?>',
        tag: '<?php echo $_GET['tag']; ?>',
        category: '<?php echo $_GET['category']; ?>',
        region: '<?php echo $_GET['region']; ?>',
        cooperation: '<?php echo $_GET['cooperation']; ?>'
    };
    <?php echo "/*microtime after Params".round(microtime(true) - $start, 4).' sek*/'; ?>
    var ListItem = React.createClass({
        changeFavorite: function (event) {
            event.preventDefault();
            var data = {
                'action': 'loadactivity',
                'id':  event.currentTarget.dataset.id
            };
            jQuery.ajax({
                url: document.location.origin+'/wp-admin/admin-ajax.php',
                data: data,
                type: 'POST',
                beforeSend: function(){
                    if(jQuery(event.target).hasClass('fa-heart-o')){
                        jQuery(event.target).removeClass('fa-heart-o');
                        jQuery(event.target).addClass('fa-heart');
                    } else {
                        jQuery(event.target).addClass('fa-heart-o');
                        jQuery(event.target).removeClass('fa-heart');
                    }
                },
                success: function (data) {
                },
                error: function (data) {
                    if( jQuery(event.target).hasClass('fa-heart-o')){
                        jQuery(event.target).removeClass('fa-heart-o');
                        jQuery(event.target).addClass('fa-heart');
                    } else {
                        jQuery(event.target).addClass('fa-heart-o');
                        jQuery(event.target).removeClass('fa-heart');
                    }
                }
            })

        },
        handleLink: function (e) {
            localStorage.setItem('currentActivity', jQuery(e.target).parent().parent().attr('id'));
        },
        render: function() {
            var rating = [];
            if(this.props.rating !== 'default'){
                for (var i = 0; i < this.props.rating; i++) {
                    rating.push(<i className="fa fa-star" aria-hidden="true"></i>);
                }
            } else {
                for (var i = 0; i < 5; i++) {
                    rating.push(<i className="fa fa-star-o" aria-hidden="true"></i>);
                }
            }

            var mainStyle = {
                background: 'url('+this.props.image+') center / cover'
            };
            var secondStyle = {
                background: 'url('+this.props.subImage+') center / cover'
            };

            var item;
            var price = this.props.price;
            if(price === '0') {
                price = (<span className="home-activities__price"><?php pll_e("FREE")?></span>);
            } else if (price === ''){
                price = (<span></span>);
            } else {
                if(this.props.childPrice === '0'){
                    price = (<span className="home-activities__price"><span className="home-activities__number">{price}</span> {this.props.currency} | <span className="home-activities__number"> <?php pll_e("FREE")?> </span><span>(<?php pll_e("kids"); ?>)</span></span>);
                } else if(this.props.childPrice === '') {
                    price = (<span className="home-activities__price"><span className="home-activities__number">{price}</span> {this.props.currency}</span>);
                } else {
                    price = (<span className="home-activities__price"><span className="home-activities__number">{price}</span> {this.props.currency} | <span className="home-activities__number"> {this.props.childPrice} </span> {this.props.currency} <span>(<?php pll_e("kids"); ?>)</span></span>);
                }
            }

            var localThis = this;
            var heart = false;
            if(logged === '1'){
                if(userFav !=='0'){
                    JSON.parse(userFav).map(function (el) {
                        if(el === localThis.props.id) {
                            heart = true;
                        }
                    });
                } else {
                    heart = false;
                }

                if(heart) {
                    heart = (
                        <button data-id={localThis.props.id} onClick={localThis.changeFavorite} className="home-activities__favorite">
                            <i className="fa fa-heart" aria-hidden="true"></i>
                        </button>
                    )
                } else {
                    heart = (
                        <button data-id={localThis.props.id} onClick={localThis.changeFavorite} className="home-activities__favorite">
                            <i className="fa fa-heart-o" aria-hidden="true"></i>
                        </button>
                    )
                }

            } else {
                heart = (<span></span>);
            }



            if(this.props.attractionLink == ''){
                item = (
                    <li id={this.props.id} className="home-activities__item home-activities__item_small">
                        <a href={this.props.link} className="home-activities__image" style={mainStyle} >
                            {price}
                            {heart}
                        </a>
                        <section className="home-activities__content">
                            <a href={this.props.link} className="home-activities__title">{this.props.title.replace(/&quot;/g,'"').replace(/&#039;/g,"'")}</a>
                            <h2 className="home-activities__subtitle">{this.props.excerpt.replace(/&quot;/g,'"').replace(/&#039;/g,"'")}</h2>
                        </section>
                        <section className="home-activities__bottom">
                            <section className="home-activities__rating">
                                {
                                    rating.map(function (el) {
                                        return el;
                                    })
                                }
                                ({this.props.comments})
                            </section>
                            <a href={this.props.link} onClick={this.handleLink} className="home-activities__link">
                                <?php pll_e('Order now search'); ?>
                                <i className="fa fa-sort-desc" aria-hidden="true"></i>
                            </a>
                        </section>
                    </li>
                )
            } else {
                item = (
                    <li id={this.props.id} className="home-activities__item">
                        <a href={this.props.link} className="home-activities__image" style={mainStyle} >
                            {price}
                            {heart}
                        </a>
                        <section className="home-activities__content">
                            <a href={this.props.link} className="home-activities__title">{this.props.title.replace(/&quot;/g,'"').replace(/&#039;/g,"'")}</a>
                            <h2 className="home-activities__subtitle">{this.props.excerpt.replace(/&quot;/g,'"').replace(/&#039;/g,"'")}</h2>
                        </section>
                        <section className="home-activities__bottom">
                            <section className="home-activities__rating">
                                {
                                    rating.map(function (el) {
                                        return el;
                                    })
                                }
                                ({this.props.comments})
                            </section>
                            <a href={this.props.link} onClick={this.handleLink} className="home-activities__link">
                                <?php pll_e('Order now search'); ?>
                                <i className="fa fa-sort-desc" aria-hidden="true"></i>
                            </a>
                        </section>
                    </li>
                )
            }


            return item;
        }
    });
    <?php echo "/*microtime after ListItem".round(microtime(true) - $start, 4).' sek*/'; ?>
    var SearchFilter = React.createClass({
        getInitialState: function () {
            var prices = [];
            var tags = [];
            var categories = [];
            var regions = [];
            var price = 'any';
            var tag = 'any';
            var category = 'any';
            var region = 'any';
            var cooperation = false;
            var cooperationImage = "";

            if(Params.cooperation){
                Cooperations.map(function (el) {
                    if(el.name == Params.cooperation){
                        cooperationImage = el.image;
                    }
                });
                cooperation = true;
            }


            var newActivities = Activities.filter(function (el) {
                if(Params.cooperation){
                    return _.indexOf(el.tagsAttraction, Params.cooperation) != -1;
                } else {
                    var price = false;
                    var tag = false;
                    var category = false;
                    var region = false;

                    if(Params.price != 'any' && Params.price != ''){
                        if(Params.price === '201'){
                            if(parseInt(el.price) >= 201) price = true;
                        } else {
                            if(parseInt(el.price) <= parseInt(Params.price)) price = true;
                        }
                    } else {
                        price = true;
                    }

                    if(Params.region != 'any' && Params.region != ''){
                        if(el.region == Params.region) region = true;
                    } else {
                        region = true;
                    }

                    if(Params.tag != 'any' && Params.tag != ''){
                        if(el.tags.indexOf(Params.tag) !== -1) tag = true;
                    } else {
                        tag = true;
                    }

                    if(Params.category != 'any' && Params.category != ''){
                        if(el.categories.indexOf(Params.category) !== -1) category = true;
                    } else {
                        category = true;
                    }

                    return price && region && tag && category;
                }
            });

            Activities.map(function (el) {
                prices.push(parseInt(el.price));
                regions.push(el.region);

                el.tags.map(function (t) {
                    tags.push(t);
                });

                el.categories.map(function (c) {
                    categories.push(c);
                })
            });

            prices = _.uniq(prices);
            prices = _.sortBy(prices);
            if(prices[0] === 0){
                prices[0] = '<?php echo pll__('FREE'); ?>';
            }
            if(isNaN(prices[prices.length-1])){
                prices.pop();
            }
            tags = _.uniq(tags);
            categories = _.uniq(categories);
            regions = _.uniq(regions);

            if(Params.price != 'any' && Params.price != ''){
                price = Params.price;
            }

            if(Params.tag != 'any' && Params.tag != ''){
                tag = Params.tag;
            }

            if(Params.category != 'any' && Params.category != ''){
                category = Params.category;
            }

            if(Params.region != 'any' && Params.region != ''){
                region = Params.region;
            }

            prices = ['<?php pll_e('FREE');?>', 10, 20, 40, 50, 100, 200, 201];

            return{
                searchList: newActivities,
                prices: prices,
                tags: tags,
                categories: categories,
                regions: regions,
                price: price,
                tag: tag,
                category: category,
                region: region,
                cooperation: cooperation,
                cooperationImage: cooperationImage,
                updateMap : true
            }
        },
        handleSearch: function (event) {
            var priceValue = this.refs.price.value;
            var tagValue = this.refs.tag.value;
            var categoryValue = this.refs.category.value;
            var regionValue = this.refs.region.value;

            var newActivities = Activities.filter(function (el) {
                var price = false;
                var tag = false;
                var category = false;
                var region = false;

                if(priceValue != 'any'){
                    if(priceValue === '201'){
                        if(parseInt(el.price) >= 201) price = true;
                    } else {
                        if(parseInt(el.price) <= parseInt(priceValue)) price = true;
                    }
                } else {
                    price = true;
                }

                if(regionValue != 'any'){
                    if(el.region == regionValue) region = true;
                } else {
                    region = true;
                }

                if(tagValue != 'any'){
                    if(el.tags.indexOf(tagValue) !== -1) tag = true;
                } else {
                    tag = true;
                }

                if(categoryValue != 'any'){
                    if(el.categories.indexOf(categoryValue) !== -1) category = true;
                } else {
                    category = true;
                }

                return price && region && tag && category;
            });

            jQuery('.home-second-menu__item_cop').removeClass('active');


            this.setState({
                searchList: newActivities,
                price: priceValue,
                tag: tagValue,
                category: categoryValue,
                region: regionValue,
                cooperation: false,
                cooperationImage: '',
                updateMap : true
            });

        },
        cooperationSearch: function (e) {
            e.preventDefault();
            var newActivities = Activities.filter(function (el) {
                return _.indexOf(el.tagsAttraction, e.target.text) != -1;
            });

            this.refs.price.value = 'any';
            this.refs.tag.value = 'any';
            this.refs.category.value = 'any';
            this.refs.region.value = 'any';

            var cooperationImage = '';

            jQuery('.home-second-menu__item_cop').removeClass('active');
            Cooperations.map(function (el, index) {
               if(el.name == e.target.text){
                   cooperationImage = el.image;
                   jQuery(jQuery('.home-second-menu__item_cop')[index]).addClass('active');
               }
            });

            this.setState({
                searchList: newActivities,
                price: 'any',
                tag: 'any',
                category: 'any',
                region: 'any',
                cooperation: true,
                cooperationImage: cooperationImage
            });

        },
        openSelect: function (event) {
            jQuery('.selects__main').removeClass('active').removeAttr('style');
            jQuery('.selects__list').removeAttr('style');

            jQuery(event.currentTarget).addClass('active');
            
            jQuery(event.currentTarget).css({
                borderRadius: '5px 5px 0 0'
            });
            
            jQuery(event.currentTarget.nextSibling).css({
                display: 'block'
            });


        },
        handleSetSelect: function (event) {
            jQuery('.selects__main').removeClass('active').removeAttr('style');
            jQuery('.selects__list').removeAttr('style');
            if(event.currentTarget.value == '<?php pll_e("FREE")?>'){
                jQuery(jQuery(event.currentTarget).parent().parent().parent().find('input')[0]).val(0);
            } else {
                jQuery(jQuery(event.currentTarget).parent().parent().parent().find('input')[0]).val(event.currentTarget.value);
            }
            jQuery(jQuery(event.currentTarget).parent().parent().parent().find('.selects__main span')[0]).text(event.currentTarget.textContent);
            this.handleSearch();
        },
        handleClick: function (event) {

            var isFocused = (document.activeElement.className.indexOf('selects__main'));

            if(isFocused === -1){
                jQuery('.selects__main').removeClass('active').removeAttr('style');
                setTimeout(function () {
                    jQuery('.selects__list').removeAttr('style');
                }, 150);
            }
        },
        markerSearch: function (name) {

            var newActivities = Activities.filter(function (el) {
                return name === el.guide;
            });

            this.setState({
                searchList: newActivities,
                price: 'any',
                tag: 'any',
                category: 'any',
                region: 'any',
                cooperation: false,
                cooperationImage: '',
                updateMap : false
            });
        },
        componentDidUpdate: function () {
            var localThis = this;

            if(this.state.updateMap){
                function addYourLocationButton(map, marker) {
                    var controlDiv = document.createElement('div');

                    var firstChild = document.createElement('button');
                    firstChild.style.backgroundColor = '#fff';
                    firstChild.style.border = 'none';
                    firstChild.style.outline = 'none';
                    firstChild.style.width = '28px';
                    firstChild.style.height = '28px';
                    firstChild.style.borderRadius = '2px';
                    firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
                    firstChild.style.cursor = 'pointer';
                    firstChild.style.marginRight = '10px';
                    firstChild.style.padding = '0px';
                    firstChild.title = 'Your Location';
                    controlDiv.appendChild(firstChild);

                    var secondChild = document.createElement('div');
                    secondChild.style.margin = '5px';
                    secondChild.style.width = '18px';
                    secondChild.style.height = '18px';
                    secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
                    secondChild.style.backgroundSize = '180px 18px';
                    secondChild.style.backgroundPosition = '0px 0px';
                    secondChild.style.backgroundRepeat = 'no-repeat';
                    secondChild.id = 'you_location_img';
                    firstChild.appendChild(secondChild);

                    google.maps.event.addListener(map, 'dragend', function() {
                        jQuery('#you_location_img').css('background-position', '0px 0px');
                    });

                    firstChild.addEventListener('click', function() {
                        var imgX = '0';
                        var animationInterval = setInterval(function(){
                            if(imgX == '-18') imgX = '0';
                            else imgX = '-18';
                            jQuery('#you_location_img').css('background-position', imgX+'px 0px');
                        }, 500);
                        if(navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                                marker.setPosition(latlng);
                                map.setCenter(latlng);
                                clearInterval(animationInterval);
                                jQuery('#you_location_img').css('background-position', '-144px 0px');
                            });
                        }
                        else{
                            clearInterval(animationInterval);
                            jQuery('#you_location_img').css('background-position', '0px 0px');
                        }
                    });

                    controlDiv.index = 1;
                    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
                }

                function initMap() {

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 8,
                        center: {
                            lat: 32.77009956632245,
                            lng: 35.00244140625
                        },
                        zoomControl: true,
                        zoomControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_TOP
                        },
                        streetViewControl: false,
                        streetViewControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_TOP
                        },
                        fullscreenControl: false,
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
                    });

                    var infoWindows = [];
                    var markers = locations.map(function(location, i) {
                        var icon = {
                            url: location.icon,
                        };
                        var infoWindow = new google.maps.InfoWindow({
                            name: location.guide,
                            content: '<div class="map-window">'+
                            '<div class="map-window__image" style="background: url('+location.image+') center / cover"></div>'+
                            '<h1 class="map-window__title">'+location.guide+'</h1>'+
                            '</div>'
                        });
                        infoWindows.push(infoWindow);
                        return new google.maps.Marker({
                            position: location,
                            icon: icon
                        });
                    });

                    markers.map(function (el, index) {
                        el.addListener('click', function() {
                            map.setZoom(12);
                            map.setCenter(el.getPosition());
                            infoWindows[index].open(map, el);
                            localThis.markerSearch(infoWindows[index].name);
                        });
                    });

                    addYourLocationButton(map, markers[0]);


                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < markers.length; i++) {
                        bounds.extend(markers[i].getPosition());
                    }

                    if( markers.length == 1 ) {
                        map.setCenter( bounds.getCenter() );
                        map.setZoom( 8 );
                    }
                    else {
                        map.fitBounds( bounds );
                    }

                    var mcOptions = {
                        styles:[{
                            url: document.location.origin+'/wp-content/themes/israelispot/img/m1.svg',
                            width: 64,
                            height: 64,
                            textSize: 16,
                            textColor:"#fc4457"
                        }]

                    };
                    var markerCluster = new MarkerClusterer(map, markers, mcOptions);
                }

                var locations = [];
                this.state.searchList.map(function (el) {
                    locations.push({
                        lat: Number(el.lat),
                        lng: Number(el.lng),
                        icon: el.mainCategoryIcon,
                        guide: el.guide,
                        image: el.subImage
                    });
                });

                locations = _.uniqBy(locations, 'guide');

                initMap();
            }

            if(this.state.cooperation || !this.state.updateMap) {
                var defaultPrice = '<?php pll_e('Any price'); ?>';
                var defaultCategory = '<?php pll_e('Any category'); ?>';
                var defaultTag = '<?php pll_e('Any tag'); ?>';
                var defaultRegion = '<?php pll_e('Any region'); ?>';
                jQuery('.selects__main_price span').text(defaultPrice);
                jQuery('.selects__main_tag span').text(defaultTag);
                jQuery('.selects__main_category span').text(defaultCategory);
                jQuery('.selects__main_region span').text(defaultRegion);
            }
        },
        componentDidMount: function () {
            var localThis = this;

            if(Params.cooperation) {
                Cooperations.map(function (el, index) {
                    if(el.name == Params.cooperation){
                        jQuery(jQuery('.home-second-menu__item_cop')[index]).addClass('active');
                    }
                });
            }

            function addYourLocationButton(map) {
                var controlDiv = document.createElement('div');

                var firstChild = document.createElement('button');
                firstChild.style.backgroundColor = '#fff';
                firstChild.style.border = 'none';
                firstChild.style.outline = 'none';
                firstChild.style.width = '28px';
                firstChild.style.height = '28px';
                firstChild.style.borderRadius = '2px';
                firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
                firstChild.style.cursor = 'pointer';
                firstChild.style.marginRight = '10px';
                firstChild.style.padding = '0px';
                firstChild.title = 'Your Location';
                controlDiv.appendChild(firstChild);

                var secondChild = document.createElement('div');
                secondChild.style.margin = '5px';
                secondChild.style.width = '18px';
                secondChild.style.height = '18px';
                secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
                secondChild.style.backgroundSize = '180px 18px';
                secondChild.style.backgroundPosition = '0px 0px';
                secondChild.style.backgroundRepeat = 'no-repeat';
                secondChild.id = 'you_location_img';
                firstChild.appendChild(secondChild);

                google.maps.event.addListener(map, 'dragend', function() {
                    jQuery('#you_location_img').css('background-position', '0px 0px');
                });

                firstChild.addEventListener('click', function() {
                    var imgX = '0';
                    var animationInterval = setInterval(function(){
                        if(imgX == '-18') imgX = '0';
                        else imgX = '-18';
                        jQuery('#you_location_img').css('background-position', imgX+'px 0px');
                    }, 500);
                    if(navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                            var markerUser = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: '<?php echo get_field('user_map_icon', 'options'); ?>'
                            });
                            map.setCenter(latlng);
                            clearInterval(animationInterval);
                            jQuery('#you_location_img').css('background-position', '-144px 0px');
                        });
                    }
                    else{
                        clearInterval(animationInterval);
                        jQuery('#you_location_img').css('background-position', '0px 0px');
                    }
                });

                controlDiv.index = 1;
                map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
            }

            function initMap() {

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: {
                        lat: 32.77009956632245,
                        lng: 35.00244140625
                    },
                    zoomControl: true,
                    zoomControlOptions: {
                        position: google.maps.ControlPosition.RIGHT_TOP
                    },
                    streetViewControl: false,
                    streetViewControlOptions: {
                        position: google.maps.ControlPosition.RIGHT_TOP
                    },
                    fullscreenControl: false,
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
                });

                var infoWindows = [];
                var markers = locations.map(function(location, i) {
                    var icon = {
                        url: location.icon,
                    };
                    var infoWindow = new google.maps.InfoWindow({
                        name: location.guide,
                        content: '<div class="map-window">'+
                                    '<div class="map-window__image" style="background: url('+location.image+') center / cover"></div>'+
                                    '<h1 class="map-window__title">'+location.guide+'</h1>'+
                                 '</div>'
                    });
                    infoWindows.push(infoWindow);
                    return new google.maps.Marker({
                        position: location,
                        icon: icon
                    });
                });

                markers.map(function (el, index) {
                    el.addListener('click', function() {
                        map.setZoom(12);
                        map.setCenter(el.getPosition());
                        infoWindows[index].open(map, el);
                        localThis.markerSearch(infoWindows[index].name);
                    });
                });



                addYourLocationButton(map);


                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < markers.length; i++) {
                    bounds.extend(markers[i].getPosition());
                }

                if( markers.length == 1 ) {
                    map.setCenter( bounds.getCenter() );
                    map.setZoom( 8 );
                }
                else {
                    map.fitBounds( bounds );
                }

                var mcOptions = {
                    styles:[{
                        url: document.location.origin+'/wp-content/themes/israelispot/img/m1.svg',
                        width: 64,
                        height: 64,
                        textSize: 16,
                        textColor:"#fc4457"
                    }]

                };
                var markerCluster = new MarkerClusterer(map, markers, mcOptions);
            }

            var locations = [];
            this.state.searchList.map(function (el) {
                locations.push({
                    lat: Number(el.lat),
                    lng: Number(el.lng),
                    icon: el.mainCategoryIcon,
                    guide: el.guide,
                    image: el.subImage,
                    attractionId: el.attractionId,
                    id: el.id
                });
            });

            locations = _.uniqBy(locations, 'guide');

            // console.log(locations.length);
            //
            // locations.map(function(el, index){
            //     console.log(index+'-----------------------------');
            //     console.log('icon '+el.icon);
            //     console.log('attraction '+el.attractionId);
            //     console.log('activity '+el.id);
            //     console.log(index+'-----------------------------');
            // });

            initMap();

            if(this.state.cooperation) {
                var defaultPrice = '<?php pll_e('Any price'); ?>';
                var defaultCategory = '<?php pll_e('Any category'); ?>';
                var defaultTag = '<?php pll_e('Any tag'); ?>';
                var defaultRegion = '<?php pll_e('Any region'); ?>';
                jQuery('.selects__main_price span').text(defaultPrice);
                jQuery('.selects__main_tag span').text(defaultTag);
                jQuery('.selects__main_category span').text(defaultCategory);
                jQuery('.selects__main_region span').text(defaultRegion);
            }


            var direction;
            if(jQuery('body').hasClass('rtl')){
                direction = true;
            } else {
                direction = false;
            }

            jQuery('.banner_miotix').slick({
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                rtl: direction,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 20000
            });

            var currentActivity = jQuery('#'+localStorage.getItem('currentActivity'));
            if(localStorage.getItem('currentActivity') && currentActivity.length !==0){
                var searchContainer = jQuery('.activities-search-container');
                searchContainer.animate({ scrollTop: searchContainer.scrollTop()+currentActivity.offset().top-searchContainer.offset().top }, { duration: 'medium', easing: 'swing' });
                console.log(searchContainer.offset().top);
            }
        },
        render: function () {
            var localThis = this;
            var defaultPrice = '<?php pll_e('Any price'); ?>';
            var defaultCategory = '<?php pll_e('Any category'); ?>';
            var defaultTag = '<?php pll_e('Any tag'); ?>';
            var defaultRegion = '<?php pll_e('Any region'); ?>';

            if(Params.price != 'any' && Params.price != ''){
                if(Params.price == 0){
                    defaultPrice = '<?php pll_e("FREE"); ?>';
                } else if(Params.price == 201) {
                    defaultPrice = '<?php pll_e('over'); ?> 200 <?php pll_e('NIS');?>';
                } else {
                    defaultPrice = '<?php pll_e('up to'); ?> '+Params.price+' <?php pll_e('NIS');?>';
                }
            }

            if(Params.category != 'any' && Params.category != ''){
                defaultCategory = Params.category;
            }

            if(Params.tag != 'any' && Params.tag != ''){
                defaultTag = Params.tag;
            }

            if(Params.region != 'any' && Params.region != ''){
                defaultRegion = Params.region;
            }

            var mainStyle = {
                background: 'url('+this.state.cooperationImage+') center / cover'
            };

            var bannerImages = [];
            var bannerLinks = [];
            <?php while ( have_rows('search_results_banner_2', 'options') ) : the_row(); ?>
                bannerLinks.push('<?php the_sub_field('link'); ?>');
                bannerImages.push({
                    background: 'url('+'<?php echo wp_get_attachment_image_url( get_sub_field('image')["id"], $size); ?>'+') center / cover'
                });
            <?php endwhile; ?>
            return(
                <main className="search-main" onClick={this.handleClick}>
                    <section className="home-second-menu home-second-menu_search">
                        <ul className="home-second-menu__list home-second-menu__list_search">
                            <li className="home-second-menu__item home-second-menu__item_title">
                                <span className="home-second-menu__link"><?php pll_e('Our Partners'); ?></span>
                            </li>
                            {
                                Cooperations.map(function (el) {
                                    return (
                                        <li className="home-second-menu__item home-second-menu__item_cop">
                                            <a href="#" className="home-second-menu__link" onClick={localThis.cooperationSearch}>{el.name}</a>
                                        </li>
                                    )
                                })
                            }
                        </ul>
                        <span className="home-second-menu__star"><i className="fa fa-star" aria-hidden="true"></i></span>
                    </section>
                    <div className="search-filter">
                        <div className="search-wrapper">
                            <div className="selects__container">
                                <input type="hidden"  value={this.state.price} ref="price" />
                                <button type="button" className="selects__main selects__main_price" onClick={this.openSelect} >
                                    <span>{defaultPrice}</span>
                                    <i className="fa fa-sort-down"></i>
                                </button>
                                <ul className="selects__list">
                                    <li className="select__item">
                                        <button type="button"
                                                value="any"
                                                className="select__button"
                                                onClick={this.handleSetSelect}><?php pll_e('Any price'); ?></button>
                                    </li>
                                    {
                                        this.state.prices.map(function (el) {
                                                var title;
                                                if(el === "<?php pll_e('FREE'); ?>"){
                                                    title = '<?php pll_e("FREE")?>';
                                                } else if(el === 201){
                                                    title = '<?php pll_e("over"); ?> 200 <?php pll_e("NIS"); ?>';
                                                } else {
                                                    title = '<?php pll_e("up to"); ?> '+el+' <?php pll_e("NIS"); ?>';
                                                }
                                                return(
                                                    <li className="select__item">
                                                        <button type="button"
                                                                value={el}
                                                                className="select__button"
                                                                onClick={localThis.handleSetSelect}>{title}</button>
                                                    </li>
                                                )
                                        })
                                    }
                                </ul>
                            </div>
                            <div className="selects__container">
                                <input type="hidden"  value={this.state.tag} ref="tag" />
                                <button type="button" className="selects__main selects__main_tag" onClick={this.openSelect} >
                                    <span>{defaultTag}</span>
                                    <i className="fa fa-sort-down"></i>
                                </button>
                                <ul className="selects__list">
                                    <li className="select__item">
                                        <button type="button"
                                                value="any"
                                                className="select__button"
                                                onClick={this.handleSetSelect}><?php pll_e('Any tag'); ?></button>
                                    </li>
                                    {
                                        this.state.tags.map(function (el) {
                                            return(
                                                <li className="select__item">
                                                    <button type="button"
                                                            value={el}
                                                            className="select__button"
                                                            onClick={localThis.handleSetSelect}>{el}</button>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                            </div>
                            <div className="selects__container">
                                <input type="hidden"  value={this.state.category} ref="category" />
                                <button type="button" className="selects__main selects__main_category" onClick={this.openSelect} >
                                    <span>{defaultCategory}</span>
                                    <i className="fa fa-sort-down"></i>
                                </button>
                                <ul className="selects__list">
                                    <li className="select__item">
                                        <button type="button"
                                                value="any"
                                                className="select__button"
                                                onClick={this.handleSetSelect}><?php pll_e('Any category'); ?></button>
                                    </li>
                                    {
                                        this.state.categories.map(function (el) {
                                            return(
                                                <li className="select__item">
                                                    <button type="button"
                                                            value={el}
                                                            className="select__button"
                                                            onClick={localThis.handleSetSelect}>{el}</button>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                            </div>
                            <div className="selects__container">
                                <input type="hidden"  value={this.state.region} ref="region" />
                                <button type="button" className="selects__main selects__main_region" onClick={this.openSelect} >
                                    <span>{defaultRegion}</span>
                                    <i className="fa fa-sort-down"></i>
                                </button>
                                <ul className="selects__list">
                                    <li className="select__item">
                                        <button type="button"
                                                value="any"
                                                className="select__button"
                                                onClick={this.handleSetSelect}><?php pll_e('Any region'); ?></button>
                                    </li>
                                    {
                                        this.state.regions.map(function (el) {
                                            return(
                                                <li className="select__item">
                                                    <button type="button"
                                                            value={el}
                                                            className="select__button"
                                                            onClick={localThis.handleSetSelect}>{el}</button>
                                                </li>
                                            )
                                        })
                                    }
                                </ul>
                            </div>
							
                        </div>
						
                        <div className="activities-search-container">
							<div className="col-xs-12">
								<h1 className="activity__title indexTitle">אינדקס אטרקציות – אטרקציות בצפון, בדרום ובמרכז</h1>
							</div>
                            <ul className="home-activities__list home-activities__list_search">
                                {
                                    this.state.searchList.map(function (el) {
                                        return <ListItem
                                            key={el.id}
                                            id={el.id}
                                            price={el.price}
                                            childPrice={el.childPrice}
                                            currency={el.currency}
                                            rating={el.rating}
                                            subImage={el.subImage}
                                            guide={el.guide}
                                            region={el.region}
                                            title={el.title}
                                            link={el.link}
                                            excerpt={el.excerpt}
                                            image={el.image}
                                            comments={el.comments}
                                            attractionLink={el.attractionLink}
                                        />;
                                    })
                                }
                            </ul>
                        </div>
                    </div>
                    <div className="search-map">
                        <div id="map" className="search-google-map"></div>
                        <div style={mainStyle} className="search-map__cooperation"></div>
                        <section className="banner_miotix">
                            {
                                bannerImages.map(function (el, index) {
                                    return(
                                        <a href={bannerLinks[index]} target="_blank" className="banner__image" style={el}></a>
                                    )
                                })
                            }
                        </section>
                    </div>
                </main>
            )
        }
    });

    <?php echo "/*microtime after SearchFilter".round(microtime(true) - $start, 4).' sek*/'; ?>
    ReactDOM.render(
        <div>
            <SearchFilter />
        </div>,
        document.getElementById('search-filter')
    );
</script>
<?php echo "<!--microtime after script".round(microtime(true) - $start, 4).' sek-->'; ?>
<?php get_footer(); ?>