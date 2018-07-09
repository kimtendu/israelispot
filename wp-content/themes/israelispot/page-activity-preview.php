<?php /* Template Name: Activity preview */
get_header();
if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
$attraction = get_field('attraction', 'user_'.get_current_user_id());

$title = $_GET['title'];
$price = $_GET['price'];
$oldPrice = $_GET['oldPrice'];
$childPrice = $_GET['childPrice'];
$oldChildPrice = $_GET['oldChildPrice'];
$priceDes = $_GET['priceDes'];
$discount = $_GET['discount'];
$discountDes = $_GET['discountDes'];
$hours = $_GET['hours'];
$credit = $_GET['credit'];
$excerpt = $_GET['excerpt'];
?>
<main id="preview-page" class="activity-body">
    <div class="activity">
        <div class="wrap wrap_home">
            <section class="activity__main">
                <div id="image_preview" class="activity__image"></div>
                <h1 id="title_preview" class="activity__title"><?php echo $title; ?></h1>
                <section id="preview_excerpt" class="custom-content">
                    <?php echo $excerpt; ?>
                </section>
                <section class="home-activities__rating home-activities__rating_activity">
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    (0)
                </section>
                <?php
                if( have_rows('phones', $attraction[0]) ): ?>
                    <div class="activity__phone">
                        <?php $count = 0; while ( have_rows('phones', $attraction[0]) ) : the_row(); ?>
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
                    <a href="<?php the_field('link_url', $attraction[0]); ?>" target="_blank"><?php pll_e('Website'); ?></a>
                </div>
            </section>
            <section class="activity__sidebar">
                <div class="activity__sidebar-container">
                    <h1 class="activity-order__title"><?php pll_e('Activity order'); ?></h1>
                    <?php if($discount && $discountDes) : ?>
                    <span class="activity__discount"><?php echo $discount; ?></span>
                    <p class="activity__discount-description"><?php echo $discountDes; ?></p>
                    <?php endif; ?>
                    <?php if($price !== '') : ?>
                        <section class="activity-prices">
                            <div>
                                <span class="activity-prices__name"><?php pll_e('Adult price'); ?></span>
                                <span class="activity-prices__price">
                                <?php if($oldPrice !== '') : ?>
                                    <span class="number old"><?php echo $oldPrice; ?></span>
                                <?php endif; ?>
                                <?php if($price !=0 ) : ?>
                                    <span class="number"><?php echo $price; ?></span> <?php pll_e('NIS'); ?>
                                <?php else: ?>
                                    <span class="number"><?php pll_e('FREE'); ?></span>
                                <?php endif; ?>
                            </span>
                            </div>
                            <?php if($childPrice !== '') : ?>
                                <div>
                                    <span class="activity-prices__name"><?php pll_e('Child price'); ?></span>
                                    <span class="activity-prices__price">
                                <?php if($oldChildPrice !== '') : ?>
                                    <span class="number old"><?php echo $oldChildPrice; ?></span>
                                <?php endif; ?>
                                        <?php if($childPrice != 0): ?>
                                            <span class="number"><?php echo $childPrice; ?></span> <?php pll_e('NIS'); ?>
                                        <?php else : ?>
                                            <span class="number"><?php pll_e('FREE'); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if($priceDes) : ?>
                                <section class="custom-content activity-prices__description">
                                    <?php echo $priceDes; ?>
                                </section>
                            <?php endif;?>
                        </section>
                    <?php endif; ?>
                    <a href="#" class="activity-order__order-now"><?php pll_e('Order now'); ?></a>
                    <a href="#" class="activity-order__order-present"><?php pll_e('Order as present'); ?></a>
                </div>
                <div class="activity__sidebar-container">
                    <?php
                    $location = get_field('map', $attraction[0]);
                    $categories = get_the_terms($attraction[0], 'attraction_categories');
                    $image = get_field('map_icon', $categories[0]);
                    $user = get_field('user_map_icon', 'options');
                    ?>
                    <section class="activity-map" id="map">
                        <div class="acf-map">
                            <div class="marker" data-icon="<?php echo $image; ?>" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                        </div>
                    </section>
                    <h1 class="activity-map-title"><?php pll_e('Activity map title'); ?></h1>
                    <section class="activity-map-links">						
                        <a href="#" class="activity-map-directions"><?php pll_e('Get directions'); ?></a>
                        <a href="<?php echo get_permalink($attraction[0]); ?>" class="activity-map-attraction"><?php pll_e('To the attraction'); ?></a>
                    </section>
                </div>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo $hours; ?>
                    </p>
                </div>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo pll__('Credit text').' - '; ?>
                        <?php
                        if($credit) {
                            echo $credit;
                        } else if(get_field('credit', $attraction[0])){
                            the_field('credit', $attraction[0]);
                        } else {
                            echo $title;
                        }
                        ?>
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
                    <section class="activity-gallery">
                        <h1 class="activity-gallery__title"><?php pll_e('Photo gallery'); ?></h1>
                        <a href="#" data-fancybox="gallery" class="activity-gallery__all">
                            <?php pll_e('All photos')?>
                        </a>
                        <ul id="preview_gallery" class="activity-gallery__list"></ul>
                    </section>
                </div>
            </section>
        </div>
    </div>
    <div class="wrap wrap_home">
        <section class="activity-content">
            <a href="#" class="link">
                <div class="link__image">
                    <i class="fa fa-star" aria-hidden="true"></i>
                </div>
                <?php pll_e('Recommendations'); ?>
            </a>
            <a href="#" class="link">
                <div class="link__image">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                </div>
                <?php pll_e('Favourites'); ?>
            </a>
            <section id="preview_content" class="custom-content custom-content_activity">
                <?php echo $content; ?>
            </section>
            <?php
            $comments = get_comments(array(
                'post_id' => get_the_ID(),
                'status' => 'approve'
            )); ?>
            <span class="activity-content__review"><?php echo count($comments).' '.pll__('Reviews'); ?></span>
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
<?php get_footer(); ?>
