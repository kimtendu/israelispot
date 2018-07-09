<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Israelispot
 */

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
$image_id = get_field('404_image', 'options')['id'];
?>
<section class="home-header home-header_404"
         style="background: url(<?php echo wp_get_attachment_image_url($image_id, 'large'); ?>) center / cover">
    <h1 class="home-header__title home-header__title_404"><?php pll_e('404 title'); ?></h1>
    <h2 class="home-header__subtitle home-header__subtitle_404"><?php pll_e('404 subtitle'); ?></h2>
    <div class="wrap wrap_home">
        <section class="main-search">
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
    </div>
</section>
<?php get_footer(); ?>

