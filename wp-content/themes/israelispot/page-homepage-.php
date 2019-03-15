<?php /* Template Name: Homepage */
get_header('activate');
if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
?>
<?php
$thumbnail_id = get_post_thumbnail_id( $ID );
$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$this_id = get_the_ID();
?>
<?php if(get_field('display_holidays_popup', 'options')) : ?>
<div id="holiday-modal" class="modal login-modal">
    <section class="login-modal__container">
        <div class="login-modal__control">
            <h1 class="login-modal__title"><?php pll_e('Holiday title'); ?></h1>
            <button id="holiday-modal__close" class="login-modal__close"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <section class="login-modal__main login-modal__main_holiday">
            <section class="custom-content">
                <?php the_field('holiday_text_'.$lang, 'options'); ?>
            </section>
            <a class="holiday__link" href="<?php the_field('holiday_link_'.$lang, 'options')?>"><?php pll_e('Holiday link'); ?></a>
        </section>
    </section>
</div>
<?php endif; ?>
<section class="home-header"
         alt="<?php echo $alt; ?>"
         style="background: url(<?php echo get_the_post_thumbnail_url($ID, 'large'); ?>) center / cover">
    <h1 class="home-header__title"><?php the_field('title')?></h1>
    <h2 class="home-header__subtitle"><?php the_field('subtitle')?></h2>
    <div class="wrap wrap_home">
        <section class="main-search">
            <?php
            $args = array(
                'numberposts'   => -1,
                'post_type'        => 'product'
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


            $allTags = [];
            $allCategories = [];
            $allPrices = [];
            $allRegions = [];

            foreach ($posts as $post) {

                $attraction = get_field('attraction', $post);
                if(get_field('date', $attraction)){
                    $date = get_field('date', $attraction);
                } else {
                    $date = get_the_date('Ymd', $attraction);
                    $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
                }

                if($date >= $today){

                    if(get_field('price', $post) !== "0"){
                        $allPrices[] = get_field('price', $post);
                    } else {
                        $allPrices[] = pll__('FREE');
                    }

                    $tags = get_the_terms($post, 'activity_tags');
                    if($tags){
                        foreach ($tags as $tag) {
                            $allTags[] = $tag->name;
                        }
                    }

                    $allRegions[] = get_field('location', $attraction);

                    $categories = get_the_terms($attraction, 'attraction_categories');
                    if($categories){
                        foreach ($categories as $category) {
                            $allCategories[] = $category->name;
                        }
                    }
                }


            }

            $allTags = array_unique($allTags);
            $allCategories = array_unique($allCategories);
            $allPrices = array_unique($allPrices);
            $allRegions = array_unique($allRegions);
            asort($allPrices);

            if(($key = array_search(pll__('FREE'), $allPrices)) !== FALSE){
                unset($allPrices[$key]);
                array_unshift($allPrices, pll__('FREE'));
            }

            if(($key = array_search('', $allPrices)) !== FALSE){
                unset($allPrices[$key]);
            }

            $allPrices = [pll__('FREE'), '10', '20', '40', '50', '100', '200', '201'];

            $page = get_pages(
                array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-search.php'
                )
            );
            ?>
            <form action="<?php echo get_permalink($page[0]); ?>" method="get">
                <section class="selects">
                    <div class="selects__container">
                        <input type="hidden" name="price" value="any">
                        <button type="button" class="selects__main selects__main_price">
                            <span><?php pll_e('Any price'); ?></span>
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <ul class="selects__list">
                            <li class="select__item">
                                <button type="button" value="any" class="select__button"><?php pll_e('Any price'); ?></button>
                            </li>
                            <?php foreach ($allPrices as $price) : ?>
                                <li class="select__item">
                                    <?php if($price == pll__('FREE')) : ?>
                                        <button type="button" value="0" class="select__button">
                                            <?php pll_e('FREE'); ?>
                                        </button>
                                    <?php elseif($price == '201') : ?>
                                        <button type="button" value="<?php echo $price; ?>" class="select__button">
                                            <?php echo pll__('over').' 200 '.pll__('NIS'); ?>
                                        </button>
                                    <?php else : ?>
                                        <button type="button" value="<?php echo $price; ?>" class="select__button">
                                            <?php echo pll__('up to').' '.$price.' '.pll__('NIS'); ?>
                                        </button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="selects__container">
                        <input type="hidden" name="tag" value="any">
                        <button type="button" class="selects__main selects__main_tag">
                            <span><?php pll_e('Any tag'); ?></span>
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <ul class="selects__list">
                            <li class="select__item">
                                <button type="button" value="any" class="select__button"><?php pll_e('Any tag'); ?></button>
                            </li>
                            <?php foreach ($allTags as $tag) : ?>
                                <li class="select__item">
                                    <button type="button" value="<?php echo $tag; ?>" class="select__button"><?php echo $tag; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="selects__container">
                        <input type="hidden" name="category" value="any">
                        <button type="button" class="selects__main selects__main_category">
                            <span><?php pll_e('Any category'); ?></span>
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <ul class="selects__list">
                            <li class="select__item">
                                <button type="button" value="any" class="select__button"><?php pll_e('Any category'); ?></button>
                            </li>
                            <?php foreach ($allCategories as $category) : ?>
                                <li class="select__item">
                                    <button type="button" value="<?php echo $category; ?>" class="select__button"><?php echo $category; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="selects__container">
                        <input type="hidden" name="region" value="any">
                        <button type="button" class="selects__main selects__main_region">
                            <span><?php pll_e('Any region'); ?></span>
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <ul class="selects__list">
                            <li class="select__item">
                                <button type="button" value="any" class="select__button"><?php pll_e('Any region'); ?></button>
                            </li>
                            <?php foreach ($allRegions as $region) : ?>
                                <li class="select__item">
                                    <button type="button" value="<?php echo $region; ?>" class="select__button"><?php echo $region; ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
                <button type="submit" title="<?php pll_e('Search')?>" value="<?php pll_e('Search')?>"><?php pll_e('Search')?> <i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </section>
        <section class="home-header__activities">
            <ul class="home-header__activities-list">
                <?php
                $allCategories = [];
                foreach ($posts as $post) {
                    $attraction = get_field('attraction', $post);

                    if(get_field('date', $attraction)){
                        $date = get_field('date', $attraction);
                    } else {
                        $date = get_the_date('Ymd', $attraction);
                        $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
                    }

                    if($date >= $today){
                        $categories = get_the_terms($attraction, 'attraction_categories');
                        if($categories){
                            foreach ($categories as $category) {
                                $allCategories[] = $category->term_id;
                            }
                        }
                    }
                }
                $allCategories = array_unique($allCategories);
                $terms = [];
                foreach ($allCategories as $category){
                    $terms[] = get_term_by('id', $category,'attraction_categories');
                }
                $termsCount = 0;
                foreach ($terms as $term) : ?>
                <?php if($termsCount < 10) : ?>
                <li class="home-header_activities-item">
                    <a href="<?php echo get_permalink($page[0]).'/?price=any&tag=any&category='.$term->name.'&region=any'; ?>" class="home-header_activities-link">
                        <?php
                        ?>
                        <img src="<?php echo get_field('icon', $term)['url'];?>" alt="">
                        <span><?php echo $term->name; ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php $termsCount++; endforeach; ?>
            </ul>
            <section class="home-header_activities-hint">
                <h2><?php pll_e('Or surf to the promoted categories'); ?></h2>
                <img src="<?php echo get_template_directory_uri().'/img/activities_arrow.svg'; ?>"
                     title="<?php pll_e('Arrow'); ?>"
                     alt="<?php pll_e('Arrow'); ?>">
            </section>
        </section>
    </div>
</section>
<div class="wrap wrap_home">
    <section class="banner">
        <?php while ( have_rows('homepage_banner_1_1', 'options') ) : the_row(); ?>
            <a href="<?php the_sub_field('link'); ?>" target="_blank" class="banner__image" style="background: url(<?php echo wp_get_attachment_image_url( get_sub_field('image')['id'], 'large' ); ?>) center / cover;"></a>
        <?php endwhile; ?>
    </section>
    <h1 class="main-title"><?php pll_e('Categories main title'); ?></h1>
    <h1 class="main-subtitle"><?php pll_e('Categories main subtitle'); ?></h1>
    <ul class="categories">
        <?php
        $postCount = 0;
        foreach ($terms as $key=>$term) :
            if($postCount < 6) :
            $count = 0;
            foreach ($posts as $post){
                $attraction = get_field('attraction', $post);
                $categories = get_the_terms($attraction, 'attraction_categories');
                if($categories){
                    foreach ($categories as $category) {
                        if($category->name == $term->name) $count++;
                    }
                }
            }
        ?>
        <li class="categories__item <?php if($key == 4 || $key == 5) echo 'categories__item_double'?>">
            <a href="<?php echo get_permalink($page[0]).'/?price=any&tag=any&category='.$term->name.'&region=any'; ?>" class="categories__link" style="background: url(<?php echo get_field('image', $term)['url'] ?>) center / cover;">
                <div class="categories__image">
                    <img src="<?php echo get_field('icon', $term)['url']; ?>" alt="">
                    <span class="categories__number"><?php echo $count; ?></span>
                </div>
                <span class="categories__title"><?php echo $term->name; ?></span>
            </a>
        </li>
        <?php endif; $postCount++; endforeach; ?>
    </ul>
</div>
<section class="home-activities">
    <div class="wrap wrap_home">
        <h1 class="main-title"><?php pll_e('Activities main title'); ?></h1>
        <h1 class="main-subtitle"><?php pll_e('Activities main subtitle'); ?></h1>
        <ul class="home-activities__list">
            <?php
            $ActivitiesCount = 0;
            foreach ($posts as $post) :
                if($ActivitiesCount < 9) :
                    $attraction = get_field('attraction', $post);
                    if(get_field('date', $attraction)){
                        $date = get_field('date', $attraction);
                    } else {
                        $date = get_the_date('Ymd', $attraction);
                        $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
                    }
                    if($date >= $today):
            ?>
            <li class="home-activities__item <?php if(!$attraction) echo 'home-activities__item_small'; ?>">
                <a href="<?php echo $post->guid; ?>" class="home-activities__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'medium'); ?>) center / cover;">
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
					<h2><a href="<?php echo $post->guid; ?>" class="home-activities__title"><?php echo $post->post_title; ?></a></h2>
                    <div class="home-activities__subtitle"><?php echo get_the_excerpt($post); ?></div>
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
            <?php $ActivitiesCount++; endif; endif; endforeach; ?>
        </ul>
        <a href="<?php echo get_permalink($page[0]); ?>" class="home-activities__more"><?php pll_e('Read more'); ?> <i class="fa fa-sort-desc" aria-hidden="true"></i></a>
    </div>
</section>
<?php if(get_field('service_pages', $this_id)) : ?>
<section class="home-services">
    <div class="wrap wrap_home">
        <h1 class="main-title"><?php pll_e('Services main title'); ?></h1>
        <h1 class="main-subtitle"><?php pll_e('Services main subtitle'); ?></h1>
        <ul class="home-services__list">
            <?php
                $services = get_field('service_pages', $this_id);
                foreach ($services as $service) :
                    $thumbnail_id = get_post_thumbnail_id( $service->ID );
                    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            ?>
            <li class="home-services__item">
                <a href="<?php echo get_permalink($service); ?>">
                    <div class="home-services__image"
                         title="<?php echo $alt; ?>"
                         alt="<?php echo $alt; ?>"
                         style="background: url(<?php echo get_the_post_thumbnail_url($service->ID, 'medium'); ?>) center / cover">
                    </div>
                    <h1 class="home-services__title"><?php echo $service->post_title; ?></h1>
                    <h2 class="home-services__subtitle"><?php echo $service->post_excerpt; ?></h2>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <section class="banner">
            <?php while ( have_rows('homepage_banner_2_2', 'options') ) : the_row(); ?>
                <a href="<?php the_sub_field('link'); ?>" target="_blank" class="banner__image" style="background: url(<?php echo wp_get_attachment_image_url( get_sub_field('image')['id'], 'large' ); ?>) center / cover;"></a>
            <?php endwhile; ?>
        </section>
    </div>
</section>
<?php endif; ?>
<section class="home-blog">
    <div class="wrap wrap_home">
        <h1 class="main-title"><?php pll_e('Blog main title'); ?></h1>
        <h1 class="main-subtitle"><?php pll_e('Blog main subtitle'); ?></h1>
        <ul class="home-blog__list">
            <?php
            $posts = get_posts(array(
                'post_type' => 'blog',
                'numberposts' => 3
            ));
            foreach ($posts as $post) :
                $mainCategory = new WPSEO_Primary_Term( 'blog_categories', $post->ID );
                $mainCategory = $mainCategory->get_primary_term();
                $mainCategory = get_term( $mainCategory );
            ?>
            <li class="home-blog__item">
                <a href="<?php echo $post->guid; ?>" class="home-blog__link">
                    <div class="home-blog__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'medium'); ?>) center / cover"></div>
                    <h1 class="home-blog__title"><?php echo $post->post_title; ?></h1>
                </a>
                <h2 class="home-blog__subtitle">
                    <span><?php echo get_the_date('d M, Y') ?></span>
                    <?php if($mainCategory) : echo ' - '; ?>
                        <a href="<?php echo get_term_link($mainCategory); ?>"><?php echo $mainCategory->name; ?></a>
                    <?php endif; ?>
                </h2>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?php echo get_post_type_archive_link('blog')?>" class="home-activities__more"><?php pll_e('Read more'); ?> <i class="fa fa-sort-desc" aria-hidden="true"></i></a>
    </div>
</section>

<?php get_footer(); ?>
