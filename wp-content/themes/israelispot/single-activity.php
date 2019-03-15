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
while ( have_posts() ) : the_post(); ?>
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
<main class="activity-body">
    <div class="activity">
        <div class="wrap wrap_home wrap_activity">
           
           
            <section class="activity__main">
                <?php
                $thumbnail_id = get_post_thumbnail_id( $ID );
                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                ?>
                <div class="activity__image"
                     title="<?php echo $alt; ?>"
                     alt="<?php echo $alt; ?>"
                     style="background: url(<?php echo get_the_post_thumbnail_url($ID, 'large'); ?>) center / cover"></div>
<!--                <section class="breadcrumbs">-->
<!--                    <a href="#">lorem</a>-->
<!--                    <i class="fa fa-angle-double---><?php //echo $direction; ?><!--" aria-hidden="true"></i>-->
<!--                    <a href="#">lorem</a>-->
<!--                </section>-->
                <h1 class="activity__title"><?php the_title(); ?></h1>
                <section class="custom-content">
                    <?php the_excerpt(); ?>
                </section>
                <section class="home-activities__rating home-activities__rating_activity">
					
                    <?php
                    $rating = 'default';
                    $attraction = get_field('attraction');
                    $commentsRating = [];
                    $comments = get_comments(array(
                        'post_id' => get_the_ID(),
                        'status' => 'approve'
                    ));
					
					$customerSite = get_field('link_url', $attraction);
					$customerSiteUrl = strlen($customerSite) > 1 ? $customerSite : '#order';
					$customerSiteUrlTarget = strlen($customerSite) > 1 ? '_blank' : 'self';

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
                    (<?php echo count($comments) ?>)
                </section>
                <?php
                if( have_rows('phones', $attraction) ): ?>
                    <div class="activity__phone">
                        <?php $count = 0; while ( have_rows('phones', $attraction) ) : the_row(); ?>
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
                <div class="activity__sidebar-container">
                    <h1 class="activity-order__title"><?php pll_e('Activity order'); ?></h1>
                    <span class="activity__discount"><?php the_field('discount'); ?></span>
                    <p class="activity__discount-description"><?php the_field('discount_description'); ?></p>
                    <?php if(get_field('price') !== '') : ?>
                    <section class="activity-prices">
                        <div>
                            <span class="activity-prices__name"><?php pll_e('Adult price'); ?></span>
                            <span class="activity-prices__price">
                                <?php if(get_field('old_price')) : ?>
                                    <span class="number old"><?php the_field('old_price'); ?></span>
                                <?php endif; ?>
                                <?php if(get_field('price')) : ?>
                                    <span class="number"><?php the_field('price'); ?></span> <?php pll_e('NIS'); ?>
                                <?php else: ?>
                                    <span class="number"><?php pll_e('FREE'); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if(get_field('child_price') !== '') : ?>
                        <div>
                            <span class="activity-prices__name"><?php pll_e('Child price'); ?></span>
                            <span class="activity-prices__price">
                                <?php if(get_field('old_child_price')) : ?>
                                <span class="number old"><?php the_field('old_child_price'); ?></span>
                                <?php endif; ?>
                                <?php if(get_field('child_price')): ?>
                                <span class="number"><?php the_field('child_price'); ?></span> <?php pll_e('NIS'); ?>
                                <?php else : ?>
                                    <span class="number"><?php pll_e('FREE'); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <?php if(get_field('price_description')) : ?>
                            <section class="custom-content activity-prices__description">
                                <?php the_field('price_description'); ?>
                            </section>
                        <?php endif;?>
                    </section>
                    <?php endif; ?>
					
                    <a data-id="<?php echo $post->ID; ?>"
                       data-type="now"
                       href="<?php echo $customerSiteUrl ?>"
					   target = "<?php echo $customerSiteUrlTarget ?>"
                       class="activity-order__order-now">
                        <?php pll_e('Order now'); ?>
                    </a>
                    <!--<a data-id="<?php echo $post->ID; ?>"
                       data-type="present"
                       href="<?php echo $customer_site_url ?>"
                       class="activity-order__order-present">
                        <?php pll_e('Order as present'); ?>
                    </a>-->
                </div>
                <div class="activity__sidebar-container">
                    <?php
                    $location = get_field('map', $attraction);
                    $categories = get_the_terms($attraction, 'attraction_categories');
                    $mainCategory = new WPSEO_Primary_Term( 'attraction_categories', $attraction->ID );
                    $mainCategory = $mainCategory->get_primary_term();
                    $mainCategory = get_term( $mainCategory );
                    $image = get_field('map_icon', $mainCategory);
                    $user = get_field('user_map_icon', 'options');
                    ?>
                    <section class="activity-map" id="map">
                        <div class="acf-map">
                            <div class="marker"
                                 data-user="<?php echo $user; ?>"
                                 data-icon="<?php echo $image; ?>"
                                 data-lat="<?php echo $location['lat']; ?>"
                                 data-lng="<?php echo $location['lng']; ?>"></div>
                        </div>
                    </section>
                    <h1 class="activity-map-title"><?php echo $location['address']; ?></h1>
                    <section class="activity-map-links">
                        <a id="directMap" href="<?php the_field('get_directions_link')?>" class="activity-map-directions"><?php pll_e('Get directions'); ?></a>
                        <?php if($attraction) : ?>
                        <a href="<?php echo get_permalink($attraction->ID); ?>" class="activity-map-attraction"><?php pll_e('To the attraction'); ?></a>
                        <?php endif; ?>
                    </section>
                </div>
                <?php if(get_field('time')) : ?>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php the_field('time'); ?>
                    </p>
                </div>
                <?php endif; ?>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo pll__('Credit text').' - '; ?>
                        <?php
                        if(get_field('credit')) {
                            the_field('credit');
                        } else if(get_field('credit', $attraction->ID)) {
                            the_field('credit', $attraction->ID);
                        } else {
                            echo $attraction->post_title;
                        }
                        ?>
                    </p>
                </div>
				<div class="activity__sidebar-container">
                    <p class="activity__time">						
                        המחירים באחריות מפעיל האטרקציה בלבד.						
                    </p>
                </div>				
                <div class="activity__sidebar-container">
                    <?php
                    if($categories) : ?>
                    <ul class="activity-sidebar__list">
                        <?php foreach ($categories as $term) : ?>
                        <li class="activity-sidebar__item">
                            <a href="<?php echo get_term_link($term); ?>" class="activity-sidebar__link">
                                <div class="activity-sidebar__image">
                                    <img src="<?php echo get_field('icon', $term)['url']; ?>" alt="">
                                </div>
                                <span><?php echo $term->name; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if(get_field('gallery')) : ?>
                    <section class="activity-gallery">
                        <h1 class="activity-gallery__title"><?php pll_e('Photo gallery'); ?></h1>
                        <?php
                        $images = get_field('gallery');
                        $size = 'medium';
                        ?>
                        <a href="<?php echo wp_get_attachment_image_url( $images[0]['id'], 'full' ); ?>"
                           data-caption="<?php the_title(); ?>"
                           data-fancybox="gallery"
                           class="activity-gallery__all">
                            <?php pll_e('All photos')?>
                        </a>
                        <ul class="activity-gallery__list">
                            <?php foreach( $images as $key=>$image ): ?>
                                <?php if($key < 9) : ?>
                                <li class="activity-gallery__item">
                                    <a href="<?php echo wp_get_attachment_image_url( $image['id'], 'full' ); ?>"
                                       style="background: url(<?php echo wp_get_attachment_image_url( $image['id'], $size ); ?>) center / cover;"
                                       data-caption="<?php $image['alt']; ?>"
                                       data-fancybox="gallery"
                                       class="activity-gallery__link"
                                       alt="<?php echo pll__('View all images from gallery'); ?>"
                                       title="<?php echo pll__('View all images from gallery'); ?>">
                                    </a>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                    <?php endif; ?>
                </div>
				
            </section>
        </div>
    </div>
    <div class="wrap wrap_home">
       
       
        <section class="activity-content">
            <a href="#all_comments" class="link link_recommendations">
                <div class="link__image">
                    <i class="fa fa-star" aria-hidden="true"></i>
                </div>
                <?php pll_e('Recommendations'); ?>
            </a>
            <?php if(is_user_logged_in()) : ?>
                <button data-id="<?php echo $post->ID; ?>" class="home-activities__favorite link">
                    <?php if(get_field('favorite_activities', 'user_'.get_current_user_id())) : ?>
                        <?php if(in_array($post->ID, get_field('favorite_activities', 'user_'.get_current_user_id()))) : ?>
                            <div class="link__image">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            </div>
                        <?php else : ?>
                            <div class="link__image">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="link__image">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                        </div>
                    <?php endif; ?>
                    <?php pll_e('Favourites'); ?>
                </button>
            <?php endif; ?>

            <section class="custom-content custom-content_activity">
				<div class="fb-like" data-href="<?php echo get_permalink( $post, false ); ?>" data-width="320" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
					<hr/>
                <?php the_content(); ?>
            </section>
            <?php
            $comments = get_comments(array(
                'post_id' => get_the_ID(),
                'status' => 'approve'
            )); ?>
            <span id="all_comments" class="activity-content__review"><?php echo count($comments).' '.pll__('Reviews'); ?></span>
            <?php foreach ($comments as $comment) : ?>
            <section class="activity-user">
                <section class="activity-user__profile">
                    <?php

                    ?>
                    <div class="activity-user__image" style="background: url(<?php echo get_avatar_url($comment->user_id); ?>) center / cover;"></div>
                    <span class="activity-user__name"><?php echo $comment->comment_author; ?></span>
                </section>
                <section class="activity-user__info">
                    <h1 class="activity-user__status">"<?php echo get_comment_meta( $comment->comment_ID, 'title', true ); ?>"</h1>
                    <section class="home-activities__rating">
                        <?php
                            $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
                            $i = 1;
                            while ( $i <= $rating) : ?>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <?php $i++; endwhile; ?>
                        <?php comment_date('d.m.Y', $comment); ?>
                    </section>
                    <p class="activity-user__description"><?php echo $comment->comment_content; ?></p>
                </section>
            </section>
            <?php endforeach; ?>
        </section>
        
        
        <section class="activity-content">
            <?php
            $defaults = array(
                'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . pll__('Your review') . '<span class="required">*</span></label> <textarea id="comment" name="comment" cols="45" rows="15"  aria-required="true" required="required"></textarea></p>',
                'must_log_in'          => '',
                'logged_in_as'         => '',
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
                'id_form'              => 'commentform',
                'id_submit'            => 'submit',
                'class_form'           => 'comment-form',
                'class_submit'         => 'submit',
                'name_submit'          => 'submit',
                'title_reply'          => pll__('Rate and write review'),
                'title_reply_to'       => pll__('Rate and write review'),
                'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
                'title_reply_after'    => '</h3>',
                'cancel_reply_before'  => ' <small>',
                'cancel_reply_after'   => '</small>',
                'cancel_reply_link'    => __( 'Cancel reply' ),
                'label_submit'         => pll__('Review submit'),
                'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
                'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
                'format'               => 'xhtml',
            );

            comment_form( $defaults );
            ?>
        </section>
    </div>
    <div class="wrap wrap_nav">
        <?php
        $prev = get_permalink(get_adjacent_post(false,'',true));
        $next = get_permalink(get_adjacent_post(false,'',false));
        if(get_adjacent_post(false,'',false) == '') {
            $first = get_posts(
                array(
                    'posts_per_page' => 1,
                    'post_type' => get_post_type(),
                    'order' => 'asc',
                )
            );
            $next = get_permalink($first[0]);
        }
        if(get_adjacent_post(false,'',true) == '') {
            $last = get_posts(
                array(
                    'posts_per_page' => 1,
                    'post_type' => get_post_type(),
                    'order' => 'des',
                )
            );
            $prev = get_permalink($last[0]);
        }
        ?>
        <a href="<?php echo $prev; ?>"
           title="<?php pll_e('Previous activity'); ?>"
           class="prev-post">
            <i class="fa fa-sort-desc" aria-hidden="true"></i>
            <?php pll_e('Previous activity'); ?>
        </a>
        <a href="<?php echo $next; ?>"
           title="<?php pll_e('Next activity'); ?>"
           class="next-post">
            <?php pll_e('Next activity'); ?>
            <i class="fa fa-sort-desc" aria-hidden="true"></i>
        </a>
    </div>
</main>
<?php endwhile; ?>
<?php get_footer(); ?>
