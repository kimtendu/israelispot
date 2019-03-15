<?php /* Template Name: products list */
get_header('activate');
//get_header();
if (get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
}
else {
    $direction = 'left';
    $lang = 'he';
};


$list_category = $_GET['category']? $_GET['category'] : array() ;
$list_tag = $_GET['tag']? $_GET['tag'] : array() ;
$chosenPrice = $_GET['price'] ? $_GET['price'] : FALSE;
$chosenRegion = $_GET['region'] ? $_GET['region'] : FALSE;

$postPerPage = 30;
$prices = array(0, 20, 50, 100, 200);
$regions = array(
    'north' => 'צפון',
    'jerusalem' => 'ירושלים והסביבה',
    'south' => 'דרום',
    'eilat' => 'אילת',
    'shomron' => 'יהודה, שומרון ובקעת הירדן',
);
?>

<?php
function TrimStringIfToLong($s) {
    $maxLength = 60;

    if (strlen($s) > $maxLength) {
        echo wp_trim_words($s, 8, NULL);
    }
    else {
        echo $s;
    }
}

?>


<script type="text/javascript" src="path_to/jquery.simplePagination.js"></script>

<div class="container">
  <div class="col-xs-12"><h1
        class="activity__title indexTitle"><?php the_title(); ?></h1></div>
  <hr/>
  <form action="<?php echo site_url() ?>/he/אינדקס-אטרקציות/" method="GET"
        id="filter">
    <div>
        <?php
        if ($prices) :
            echo '<div class="filter-row"><h4>לפי מחיר</h4>';
            foreach ($prices as $price) :
                $cheked ='';
                if($price==$chosenPrice) $cheked = 'checked';
                echo '<label class="cb-container" for="cb_price_' . $price . '">';
                echo pll_e("up to") . ' ' . $price . '<input class="checkmark" type="radio" name="price" value="' . $price . '" id="cb_price_' . $price . '" '.$cheked.'>
                        <span class="checkmark"></span></label>';
            endforeach;
            echo '</div>';
        endif;
        ?>

        <?php
        if ($terms = get_terms('attraction_categories_new', 'orderby=name')) :
            echo '<div class="filter-row"><h4>קטגוריות</h4>';
            foreach ($terms as $term) :
                $cheked ='';
                if(in_array($term->term_id, $list_category)) $cheked = 'checked';
                echo '<label class="cb-container" for="cb_cat_' . $term->term_id . '">' . $term->name .
                    '<input class="checkmark" type="checkbox" value="' . $term->term_id . '" name="category[]" id="cb_cat_' . $term->term_id . '" '.$cheked.'>                       
								<span class="checkmark"></span></label>';
            endforeach;
            echo '</div>';
        endif; ?>

        <?php
        if ($terms = get_terms('activity_tags', 'orderby=name')) :
            echo '<div class="filter-row"><h4>למי מתאים?</h4>';
            foreach ($terms as $term) :
                $cheked ='';
                if(in_array($term->term_id, $list_tag)) $cheked = 'checked';
                echo '<label class="cb-container" for="cb_tag_' . $term->term_id . '">' . $term->name .
                    '<input class="checkmark" type="checkbox" value="' . $term->term_id . '" name="tag[]" id="cb_tag_' . $term->term_id . '" '.$cheked.'>                      
								<span class="checkmark"></span></label>';
            endforeach;
            echo '</div>';
        endif; ?>

        <?php
        if ($regions) :
            echo '<div class="filter-row"><h4>לפי איזור</h4>';
            foreach ($regions as $region) :
                $cheked ='';
                if($region==$chosenRegion) $cheked = 'checked';
                echo '<label class="cb-container" for="cb_region_' . $region . '">';
                echo $region . '<input class="checkmark" type="radio" name="region" value="' . $region . '" name="region" id="cb_region_' . $region . '"  '.$cheked.'>  
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
      $query = array(
          'post_type' => 'product',
          'posts_per_page' => $postPerPage,
          'post_status' => 'publish',

      );


      if (isset($_GET['submit'])) {


          $catJoin ='';
          $catWhere='';
          $priceWhere='';

          if (!empty($list_category)) {
//              $query['tax_query'] = array(
//                  'relation' => 'AND',
//                  array(
//                      'taxonomy' => 'attraction_categories_new',
//                      'field' => 'title',
//                      'terms' => $list_category,
//
//                  )
//              );

              $joinCat = '';
          }


          if (!empty($list_tag)) {
//              $query['tax_query'] = array(
//                  'relation' => 'AND',
//                  array(
//                      'taxonomy' => 'activity_tags',
//                      'field' => 'title',
//                      'terms' => $list_tag,
//
//                  )
//              );

              $list_category = implode(',', $list_category);

              $catJoin = " LEFT JOIN {$wpdb->term_relationships} tr ON (ps.ID = tr.object_id)";
              $catWhere = " OR tr.term_taxonomy_id IN ({$list_category})";

          }

          if ($chosenPrice) {

              $priceWhere = " AND ( psm.meta_key = '_regular_price' AND CAST(psm.meta_value AS SIGNED) < '{$chosenPrice}' )";
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


      }
      $role = 'subscriber_pro';

      global $wpdb;

      $wp_query_pro = $wpdb->get_results( "
SELECT ps.* FROM {$wpdb->posts} ps "
          ." LEFT JOIN {$wpdb->usermeta} ur ON( ur.user_id = ps.post_author)"
          .$catJoin
          ." INNER JOIN {$wpdb->postmeta} psm ON ( ps.ID = psm.post_id )"
          ." WHERE    ps.post_type     = 'product'"
          .$catWhere
          .$priceWhere
          ." AND      ps.post_status   = 'publish'"
          ." AND      ur.meta_key      = 'wp_capabilities'"
          ." AND      ur.meta_value    LIKE '%\"{$role}\"%' LIMIT 30" );
//      $wp_query = $wpdb->get_results("
//
//SELECT p.* FROM {$wpdb->posts} p, {$wpdb->usermeta} u
//      WHERE    p.post_type     = 'product'
//      AND      p.post_status   = 'publish'
//
//
//
//"
//      );
//      var_dump($wp_query);
//      var_dump($wp_query);
//      die();

      $wp_query_not_pro = $wpdb->get_results( "
SELECT ps.* FROM {$wpdb->posts} ps "
          ." LEFT JOIN {$wpdb->usermeta} ur ON( ur.user_id = ps.post_author)"
          .$catJoin
          ." INNER JOIN {$wpdb->postmeta} psm ON ( ps.ID = psm.post_id )"
          ." WHERE    ps.post_type     = 'product'"
          .$catWhere
          .$priceWhere
          ." AND      ps.post_status   = 'publish'"
          ." AND      ur.meta_key      = 'wp_capabilities'"
          ." AND      ur.meta_value    NOT LIKE '%\"{$role}\"%' LIMIT 30" );

      /**
       *
       * SELECT ps.* FROM wp_posts ps
      LEFT JOIN wp_usermeta ur ON( ur.user_id = ps.post_author)
      LEFT JOIN wp_term_relationships tr ON (ps.ID = tr.object_id)
      INNER JOIN wp_postmeta psm ON ( ps.ID = psm.post_id )
      WHERE
      ps.post_type     = 'product'
      AND tr.term_taxonomy_id IN (218)
      AND (( psm.meta_key = '_regular_price' AND CAST(psm.meta_value AS SIGNED) < '100' )
      AND      ps.post_status   = 'publish'
      AND      ur.meta_key      = 'wp_capabilities'
      AND      ur.meta_value    LIKE '%"subscriber_pro"%'
       */

      /**
       *
       *
       * SELECT p.* FROM wp_posts p, wp_usermeta u
      WHERE    p.post_type     = 'post'
      AND      p.post_status   = 'publish'
      sAND      u.user_id       = p.post_author`
      AND      u.meta_key      = 'wp_capabilities'
      AND      u.meta_value    LIKE '%\"subscriber-pro\"%'

      SELECT SQL_CALC_FOUND_ROWS  wp_posts.ID FROM wp_posts
       * LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
       * INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )
       * WHERE 1=1
       * AND (
      wp_term_relationships.term_taxonomy_id IN (84)
      ) AND (
      ( wp_postmeta.meta_key = '_regular_price' AND CAST(wp_postmeta.meta_value AS SIGNED) < '100' )
      ) AND wp_posts.post_type = 'product' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 30
       *
       */

      //var_dump($wp_query_pro);
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


        if (count($wp_query_not_pro) != 0 && count($wp_query_not_pro) != 0) :
            foreach ($wp_query_not_pro as $post): ?>
              <li id={this.props.id}
                  class="home-activities__item home-activities__item_small">
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

                        update_post_meta($post->ID, '_regular_price', $post->old_price);
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
            <?php endforeach; ?>
            <?php foreach ($wp_query_not_pro as $post): ?>

          <li id={this.props.id}
              class="home-activities__item home-activities__item_small">
            <a href="<?php the_permalink() ?>" class="home-activities__image"
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

                    update_post_meta($post->ID, '_regular_price', $post->old_price);
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
              <a href="<?php the_permalink() ?>" class="home-activities__link">
                  <?php pll_e('Order now search'); ?>
                <i class="fa fa-sort-desc" aria-hidden="true"></i>
              </a>
            </section>
          </li>


        <?php endforeach; ?>
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
    }
    elseif ($current_page > ($total - 2)) {
        $firstPageingPage = $total - 5;
    }
    else {
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

<script type="text/javasc
              <script type="
        text/javascript" src="https://www.israelispot.co.il/wp-content/themes/israelispot/js/script.main.min.js">
</script>
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/react-google-map@3.1.1/lib/index.min.js"></script>
</body>
</html>