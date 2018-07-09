<?php
get_header();
if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
?>
<?php
while ( have_posts() ) : the_post(); $this_id = get_the_ID(); ?>
<main class="activity-body">
    <div class="activity activity_attraction">
        <?php
        $images = get_field('slider_images');
        $size = 'large';
		$customerSite = get_field('link_url');
		$customerSiteUrl = strlen($customerSite) > 1 ? $customerSite : '#order';
		$customerSiteUrlTarget = strlen($customerSite) > 1 ? '_blank' : 'self';

		
        if( $images ): ?>
        <section class="activity__slider">
            <?php
            foreach( $images as $image ): ?>
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
        <div class="wrap wrap_home wrap_activity">
            <section class="activity__main">
                <h1 class="activity__title"><?php the_title(); ?></h1>
                <section class="custom-content">
                    <?php the_excerpt(); ?>
                </section>
                <?php if( have_rows('phones') ): ?>
                <div class="activity__phone">
                    <?php $count = 0; while ( have_rows('phones') ) : the_row(); ?>
                        <?php if($count !=0) : ?>
                            | <a href="tel:<?php the_sub_field('phone'); ?>"><?php the_sub_field('phone'); ?></a>
                        <?php else : ?>
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <a href="tel:<?php the_sub_field('phone'); ?>"><?php the_sub_field('phone'); ?></a>
                        <?php endif; ?>
                        <?php $count++; endwhile; ?>
                </div>
                <?php endif; ?>
                <div class="activity__phone">
                    <i class="fa fa-link" aria-hidden="true"></i>					
					<a 
					   href="<?php echo $customerSiteUrl ?>"
					   target = "<?php echo $customerSiteUrlTarget ?>"><?php pll_e('Website'); ?></a>
                </div>
            </section>
            <section class="activity__sidebar">
                <div class="activity__sidebar-container activity__sidebar-container_attraction">
                    <?php
                    $location = get_field('map');
                    $user = get_field('user_map_icon', 'options');
                    $categories = get_the_terms(get_the_ID(), 'attraction_categories');
                    $mainCategory = new WPSEO_Primary_Term( 'attraction_categories', get_the_ID() );
                    $mainCategory = $mainCategory->get_primary_term();
                    $mainCategory = get_term( $mainCategory );
                    $image = get_field('map_icon', $mainCategory);
                    if(!$image){
                        $image = get_field('map_icon',$categories[0]->taxonomy . '_' . $categories[0]->term_id);
                    }
                    ?>
                    <section class="activity-map" id="map">
                        <div class="acf-map">
                            <div class="marker"
                                 data-user="<?php echo $user; ?>"
                                 data-icon="<?php echo $image; ?>"
                                 data-lat="<?php echo $location['lat']; ?>"
                                 data-lng="<?php echo $location['lng']; ?>">
                            </div>
                        </div>
                    </section>
                    <h1 class="activity-map-title"><?php pll_e('Attraction map title'); ?></h1>
                    <section class="activity-map-links">
                        <a id="directMap" href="#" class="activity-map-directions"><?php pll_e('Get directions'); ?></a>
                    </section>
                </div>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php the_field('time'); ?>
                    </p>
                </div>

                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo pll__('Credit text').' - '; ?>
                        <?php if(get_field('credit')) {
                            the_field('credit');
                        } else {
                            the_title();
                        } ?>
                    </p>
                </div>
            </section>
        </div>
    </div>
    <div class="wrap wrap_home">
        <section class="activity-content">
            <section class="custom-content custom-content_activity">
                <?php the_content(); ?>
            </section>
        </section>
    </div>
    <div class="wrap wrap_home clear">
        <h1 class="main-title"><?php pll_e('Our Activities'); ?></h1>
        <h2 class="main-subtitle"><?php pll_e('Come and find out'); ?></h2>
        <ul class="home-activities__list">
            <?php
            $args = array(
                'numberposts'   => -1,
                'post_type'        => 'activity',
            );

            $allPosts = get_posts( $args );
            $posts = [];
            $today = date('Ymd');

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

            foreach ($posts as $post) :
                if(get_field('attraction', $post)->ID == $this_id) :
            ?>
            <li class="home-activities__item home-activities__item_small">
                <a href="<?php echo $post->guid; ?>" class="home-activities__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'large'); ?>) center / cover;">
                    <?php if(get_field('price', $post)) : ?>
                        <span class="home-activities__price">
                        <span class="home-activities__number">
                            <?php the_field('price', $post); ?>
                        </span> <?php pll_e('NIS'); ?>
                            <?php if(get_field('child_price', $post)) : ?>
                                | <span class="home-activities__number">
                            <?php the_field('child_price', $post); ?>
                        </span> <?php pll_e('NIS'); ?>
                        <span>(<?php pll_e('kids'); ?>)</span>
                            <?php endif; ?>
                    </span>
                    <?php elseif (get_field('price', $post) === "0") : ?>
                        <span class="home-activities__price">
                            <?php pll_e('FREE'); ?>
                        </span>
                    <?php else : ?>
                        <span></span>
                    <?php endif; ?>
                    <?php if(is_user_logged_in()) : ?>
                        <button data-id="<?php echo $post->ID; ?>" class="home-activities__favorite">
                            <?php if(get_field('favorite_activities', 'user_'.get_current_user_id())) : ?>
                                <?php if(in_array($post->ID, get_field('favorite_activities', 'user_'.get_current_user_id()))) : ?>
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                <?php else : ?>
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                <?php endif; ?>
                            <?php else :?>
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                </a>
                <section class="home-activities__content">
                    <a href="<?php echo $post->guid; ?>" class="home-activities__title"><?php echo $post->post_title; ?></a>
                    <p class="home-activities__subtitle"><?php echo $post->post_excerpt; ?></p>
                </section>
                <section class="home-activities__bottom">
                    <section class="home-activities__rating">
                        <?php
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
                        $count = 0;
                        if($rating != 'default'){
                            while($count < $rating) {
                                echo '<i class="fa fa-star" aria-hidden="true"></i>';
                                $count++;
                            }
                        } else {
                            while($count < 5) {
                                echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
                                $count++;
                            }
                        }
                        ?>
                        (<?php echo count($comments); ?>)
                    </section>
                    <a href="<?php echo $post->guid; ?>" class="home-activities__link">
                        <?php pll_e('Order now search'); ?>
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    </a>
                </section>
            </li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
</main>

<div id="order-modal" class="modal login-modal">
    <section class="login-modal__container">
        <div class="login-modal__control">
            <h1 class="login-modal__title"><?php pll_e('Order modal title'); ?></h1>
            <button id="order-modal__close" class="login-modal__close"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <section class="login-modal__main">
            <section class="login-modal__login active">
                <section class="custom-content">
                    <?php the_field('order_message_'.$lang, 'options'); ?>
                </section>
            </section>
        </section>
    </section>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>
