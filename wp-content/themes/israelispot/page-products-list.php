<?php /* Template Name: products list */
get_header('activate');
//get_header();
if (get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};


$list_category = $_GET['category'] ? $_GET['category'] : array();
$list_tag = $_GET['tag'] ? $_GET['tag'] : array();
$chosenPrice = $_GET['price'] ? $_GET['price'] : FALSE;
$chosenRegion = $_GET['region'] ? $_GET['region'] : FALSE;

$postPerPage = 30;
$prices = array('all', '0', 20, 50, 100, 200);
$regions = array(
    'north' => 'צפון',
    'jerusalem' => 'ירושלים והסביבה',
    'south' => 'דרום',
    'eilat' => 'אילת',
    'shomron' => 'יהודה, שומרון ובקעת הירדן',
);
?>

<?php
function TrimStringIfToLong($s)
{
    $maxLength = 60;

    if (strlen($s) > $maxLength) {
        echo wp_trim_words($s, 8, NULL);
    } else {
        echo $s;
    }
}

?>


<script type="text/javascript" src="path_to/jquery.simplePagination.js"></script>

<div class="container">
    <div class="col-xs-12">
        <h1 class="activity__title indexTitle"><?php the_title(); ?></h1></div>
    <hr/>
    <form action="<?php echo site_url() ?>/he/אינדקס-אטרקציות/" method="GET"
          id="filter">
        <div>
            <?php
            if ($prices) :
                echo '<div class="filter-row"><h4>לפי מחיר</h4>';
//          echo '<label class="cb-container" for="cb_price_all">';
//          echo pll_e("All") . '<input class="checkmark" type="radio" name="price" value="all" id="cb_price_all" cheked><span class="checkmark"></span></label>';
                foreach ($prices as $price) {
                    $cheked = ($price == $chosenPrice) ? 'checked' : '';
                    echo '<label class="cb-container" for="cb_price_' . $price . '">';
                    switch ($price) {
                        case 'all':
                            echo pll_e("All");
                            break;
                        case '0':
                            echo 'חינם';
                            break;

                        default:
                            echo pll_e("up to") . ' ' . $price;
                            break;
                    }


//              echo $price == 0 ? 'חינם' : pll_e("up to") . ' ' . $price;
                    echo '<input class="checkmark" type="radio" name="price" value="' . $price . '" id="cb_price_' . $price . '" ' . $cheked . '>
                        <span class="checkmark"></span></label>';
                }
                echo '</div>';
            endif;
            ?>

            <?php
            if ($terms = get_terms('attraction_categories_new', 'orderby=name')) :
                echo '<div class="filter-row"><h4>קטגוריות</h4>';
                foreach ($terms as $term) :
                    $cheked = '';
                    if (in_array($term->term_id, $list_category)) $cheked = 'checked';
                    echo '<label class="cb-container" for="cb_cat_' . $term->term_id . '">' . $term->name .
                        '<input class="checkmark" type="checkbox" value="' . $term->term_id . '" name="category[]" id="cb_cat_' . $term->term_id . '" ' . $cheked . '>
								<span class="checkmark"></span></label>';
                endforeach;
                echo '</div>';
            endif; ?>

            <?php
            if ($terms = get_terms('activity_tags', 'orderby=name')) :
                echo '<div class="filter-row"><h4>למי מתאים?</h4>';
                foreach ($terms as $term) :
                    $cheked = '';
                    if (in_array($term->term_id, $list_tag)) $cheked = 'checked';
                    echo '<label class="cb-container" for="cb_tag_' . $term->term_id . '">' . $term->name .
                        '<input class="checkmark" type="checkbox" value="' . $term->term_id . '" name="tag[]" id="cb_tag_' . $term->term_id . '" ' . $cheked . '>
								<span class="checkmark"></span></label>';
                endforeach;
                echo '</div>';
            endif; ?>

            <?php
            if ($regions) :
                echo '<div class="filter-row"><h4>לפי איזור</h4>';
                foreach ($regions as $region) :
                    $cheked = '';
                    if ($region == $chosenRegion) $cheked = 'checked';
                    echo '<label class="cb-container" for="cb_region_' . $region . '">';
                    echo $region . '<input class="checkmark" type="radio" name="region" value="' . $region . '" id="cb_region_' . $region . '"  ' . $cheked . '>
                        <span class="checkmark"></span></label>';
                endforeach;
                echo '</div>';
            endif;
            ?>

            <button name="submit" class="btn home-activities__link">סנן</button>
            <input type="hidden" name="action" value="myfilter">
        </div>
    </form>

    <div class="categoryTxtTop">
        <!--        <?php the_content(); ?>-->

    </div>


    <div class="search-filter search-filter-full">
        <?php
        $catJoin = '';
        $catWhere = '';
        $priceWhere = '';

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $postPerPage,
            'orderby' => 'meta_value', // we will sort posts by stutus pro user
            'order' => 'ASC', // ASC or DESC
            //'meta_key' => 'is_pro'
            'meta_key' => 'is_pro',

        );


        if (isset($_GET['submit'])) {
            $args['tax_query'] = array();


            //@DUDI: u need to make search for ALL of chosen taxonomys
            if (count($list_category) > 0  || count($list_tag) > 0   ) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                );

            }

            // for taxonomies / categories
            if ($list_category && count(list_category) > 0) {
                $args['tax_query'][]= array(
                        'taxonomy' => 'attraction_categories_new',
                        'field' => 'title',
                        'terms' => $list_category,

                );
            }

            // if only max price is set

            $query['meta_query'] = array();
            if ($chosenRegion || $chosenPrice) {
                $query['meta_query'] = array('relation' => 'AND'); //@DUDI - that why price dosnt work
            }


            if ($chosenRegion) {

                $args['meta_query'][] = array(
                    'key' => 'location',
                    'value' => $chosenRegion,
                );

//                $args['tax_query'] [] = array(
//                    array(
//                        'meta_key' => 'location',
//                        'meta_value' => $list_category,
//                    )
//                );
            }
// var_dump(!isset($_GET['price']));
// var_dump(!empty ($_GET['price']));
            if ($chosenPrice && $chosenPrice != 'all') {
                $args['meta_query'][] = array(
                    'key' => 'price',
                    'value' => $chosenPrice,
                    'type' => 'numeric',
                    'compare' => '<'
                );
            }


            // if post thumbnail is set
            //    if( isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
            //        $args['meta_query'][] = array(
            //        'key' => '_thumbnail_id',
            //        'compare' => 'EXISTS'
            //    );


            if (count($list_tag) > 0) {
                $args['tax_query'] [] = array(
                    array(
                        'taxonomy' => 'activity_tags',
                        'field' => 'title',
                         'terms' => $list_tag,
                    )
                );
                //              $query['tax_query'] = array(
                //                  'relation' => 'AND',
                //                  array(
                //                      'taxonomy' => 'activity_tags',
                //                      'field' => 'title',
                //                      'terms' => $list_tag,
                //
                //                  )
                //              );

                //  $list_category = implode(',', $list_category);

            }


            //          if ($chosenPrice) {
            //              $query['meta_query'] = array('relation' => 'AND');
            //              $query['meta_query'][] = array(
            //                  'key' => '_regular_price',
            //                  'value' => $chosenPrice,
            //                  'type' => 'numeric',
            //                  'compare' => '<'
            //              );
            //          }


            $role = 'subscriber_pro';

        }

//var_dump( $args['meta_query']);

        $wp_query = new WP_Query($args);
        $total = $wp_query->max_num_pages;
        $total_attr = $wp_query->found_posts;

        if (intval($total_attr) > 0) {
            //var_dump($total_attr);
            $postPerPage = $postPerPage < $total_attr ? $postPerPage : $total_attr;
            echo '<h2>מציג ' . $postPerPage . ' מתוך ' . $total_attr . ' אטרקציות</h2>';
        }

        ?>


        <ul class="home-activities__list home-activities__list_search"
            id="activitiesResponse">

            <?php


            // Update the post into the database


            if (have_posts()) : while (have_posts()) : the_post(); ?>

                <li id={this.props.id}
                    class="home-activities__item home-activities__item_small item_<?php echo $post->is_pro == 'pro' ? 'pro' : '' ?>">
                    <a href="<?php the_permalink() ?>"
                       class="home-activities__image"
                       style="background: url(<?php echo get_the_post_thumbnail_url($post, 'medium') ?>) center / cover">
                        <?php if ($post->price) : ?>
                            <span class="home-activities__price"><span
                                    class="home-activities__number"><?php echo $post->_sale_price; ?>
                        </span>₪
                                <?php if ($post->old_child_price) : ?>
                                    <span> | </span> <?php
                                    echo pll_e("Child price") . ' ';
                                    echo $post->child_price ? $post->child_price : old_child_price;
                                    ?>
                                    ₪
                                <?php endif; ?>
                    </span>
                        <?php endif; ?>

                        <?php if ($post->old_price and $post->old_price > $post->price) :

                            // update_post_meta($post->ID, '_regular_price', $post->old_price);
                            ?>
                            <span class="number home-activities__old-price"><span
                                    class="home-activities__number"><?php echo $post->old_price ?></span>ש"ח</span>
                        <?php endif; ?>
                    </a>
                    <section class="home-activities__content">
                        <h2><a href="<?php the_permalink() ?>"
                               class="home-activities__title"><?php TrimStringIfToLong(get_the_title()); ?> </a>
                        </h2>

                        <div class="home-activities__subtitle"></div>
                    </section>
                    <section class="home-activities__bottom">
                        <section class="home-activities__rating">


                        </section>
                        <a href="<?php the_permalink() ?>"
                           class="home-activities__link">
                            <?php pll_e('Order now search'); ?>
                            <i class="fa fa-sort-desc" aria-hidden="true"></i>
                        </a>
                    </section>
                </li>
            <?php endwhile; ?>

            <?php else: ?>

                <p><?php _e('Sorry, no posts published so far.'); ?></p>
            <?php endif; ?>
        </ul>


    </div>


</div>


<?php
/* PAGINATION */

global $wp_query;
global $paged;
$htmlStr = '';
$firstPageingPage = 1;
$lastPageingPage = $total;
$current_page = $paged;
$current_page_css = '';
$prev_page_css = '';
$next_page_css = '';

// only bother with the rest if we have more than 1 page!
if ($total > 1) :

    // get the current page

    !$current_page ? ($current_page = 1) && ($prev_page_css = 'hide') : $prev_page_css = '';
    $next_page_css = $current_page == $total ? 'hide' : '';

    // structure of "format" depends on whether we're using pretty permalinks
    //if( get_option('permalink_structure') ) {
    //$format = '&paged=%#%';
    //} else {
    $format = '/אינדקס-אטרקציות/page/';
    //}

    if ($current_page < 3) {
        $firstPageingPage = 1;
        $lastPageingPage = 5;
    } elseif ($current_page > ($total - 2)) {
        $firstPageingPage = $total - 5;
    } else {
        $firstPageingPage = $current_page - 3;
        $lastPageingPage = $current_page + 3;
    }

    $htmlStr .= '<div class="pagination"><ul>
		<li><a href="' . $format . 1 . '" class="end" aria-label="first">ראשון</a></li>
		<li class="' . $prev_page_css . '"><a href="' . $format . ($current_page - 1) . '" class="end" aria-label="previous">הקודם</a></li>';

    for ($i = $firstPageingPage; $i <= $lastPageingPage; $i++) {
        $current_page_css = $current_page == $i ? 'currentPage' : '';
        $htmlStr .= '<li><a class="' . $current_page_css . '" href="' . $format . $i . '">' . $i . '</a></li>';
    }

    $htmlStr .= '<li class="' . $next_page_css . '"><a href="' . $format . ($current_page + 1) . '" class="end" aria-label="next">הבא</a></li>
				<li><a href="' . $format . $total . '" class="end" aria-label="last">אחרון</a></li></ul></div>';

    echo $htmlStr;

endif;
?>


<footer>
    <div class="wrap wrap_footer">
        <section class="copyright-container"><span class="copyright">נקודה ישראלית - כל הזכויות שמורות 2018 ©</span>
            <i class="fa fa-circle" aria-hidden="true"></i> <span
                class="footer-phone">טלפון: <a href="tel:0733742311"
                                               data-fontsize="14">0733742311</a></span>
            <i class="fa fa-circle" aria-hidden="true"></i> <a target="_blank" href=""
                                                               class="footer-link"
                                                               data-fontsize="14">עיצוב
                ופיתוח אתר</a></section>
        <ul id="menu-footer-he" class="footer__list">
            <li id="nav-menu-item-54"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page">
                <a href="https://www.israelispot.co.il/he/%d7%90%d7%98%d7%a8%d7%a7%d7%a6%d7%99%d7%95%d7%aa/"
                   class="footer__link" data-fontsize="14">כל הפעילויות</a></li>
            <li id="nav-menu-item-55"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-17 current_page_item">
                <a h
                   ref="https://www.israelispot.co.il/he/" class="footer__link"
                   data-fontsize="14">דף הבית</a></li>
            <li id="nav-menu-item-2670"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories">
                <a href="https://www.israelispot.co.il/he/blog_categories/%d7%9b%d7%aa%d7%91%d7%95-%d7%a2%d7%9c%d7%99%d7%a0%d7%95/"
                   class="footer__link" data-fontsize="14">כתבו עלינו</a></li>
            <li id="nav-menu-item-2672"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories">
                <a href="https://www.israelispot.co.il/he/blog_categories/%d7%94%d7%9e%d7%9c%d7%a6%d7%95%d7%aa/"
                   class="footer__link" data-fontsize="14">המלצות</a></li>
            <li id="nav-menu-item-5605"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page">
                <a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a4%d7%a8%d7%a1%d7%9d/"
                   class="footer__link" data-fontsize="14">תקנון בעלי עסקים</a></li>
            <li id="nav-menu-item-5606"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page">
                <a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a9%d7%aa%d7%9e%d7%a9/"
                   class="footer__link" data-fontsize="14">תקנון למשתמש</a></li>
            <li id="nav-menu-item-2666"
                class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page">
                <a href="https://www.israelispot.co.il/he/%d7%a6%d7%95%d7%a8-%d7%a7%d7%a9%d7%a8/"
                   class="footer__link" data-fontsize="14">צור קשר</a></li>
        </ul>
    </div>
</footer>

<script type="text/javascript" type="text/javascript"
        src="https://www.israelispot.co.il/wp-content/themes/israelispot/js/script.main.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/react-google-map@3.1.1/lib/index.min.js"></script>
</body>
</html>