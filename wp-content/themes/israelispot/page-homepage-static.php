<?php /* Template Name: category new */
get_header('activate');
//get_header();
if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
?>





<?php

function TrimStringIfToLong($s) {
    $maxLength = 60;

    if (strlen($s) > $maxLength) {
        echo substr($s, 0, $maxLength - 5) . ' ...';
    } else {
        echo $s;
    }
}

?>

<ul>
<?php
$query = array( 'posts_per_page' => 20, 'post_type' => 'activity','orderby' => 'rand' );
$wp_query = new WP_Query($query);

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<li>
    <a href="<?php the_permalink() ?>" title="Link to <?php the_title_attribute() ?>">
        <?php the_time( 'Y-m-d' ) ?> 
        <?php TrimStringIfToLong(get_the_title()); ?>
    </a>
</li>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts published so far.'); ?></p>
<?php endif; ?>
</ul>


<!-- then the pagination links -->
<?php next_posts_link( '&larr; Older posts', $wp_query ->max_num_pages); ?>
<?php previous_posts_link( 'Newer posts &rarr;' ); ?>













<footer><div class="wrap wrap_footer"><section class="copyright-container"> <span class="copyright">נקודה ישראלית - כל הזכויות שמורות 2018 ©</span> <i class="fa fa-circle" aria-hidden="true"></i> <span class="footer-phone">טלפון: <a href="tel:0733742311" data-fontsize="14">0733742311</a></span> <i class="fa fa-circle" aria-hidden="true"></i> <a target="_blank" href="" class="footer-link" data-fontsize="14">עיצוב ופיתוח אתר</a></section><ul id="menu-footer-he" class="footer__list"><li id="nav-menu-item-54" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%90%d7%98%d7%a8%d7%a7%d7%a6%d7%99%d7%95%d7%aa/" class="footer__link" data-fontsize="14">כל הפעילויות</a></li><li id="nav-menu-item-55" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-17 current_page_item"><a href="https://www.israelispot.co.il/he/" class="footer__link" data-fontsize="14">דף הבית</a></li><li id="nav-menu-item-2670" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories"><a href="https://www.israelispot.co.il/he/blog_categories/%d7%9b%d7%aa%d7%91%d7%95-%d7%a2%d7%9c%d7%99%d7%a0%d7%95/" class="footer__link" data-fontsize="14">כתבו עלינו</a></li><li id="nav-menu-item-2672" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories"><a href="https://www.israelispot.co.il/he/blog_categories/%d7%94%d7%9e%d7%9c%d7%a6%d7%95%d7%aa/" class="footer__link" data-fontsize="14">המלצות</a></li><li id="nav-menu-item-5605" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a4%d7%a8%d7%a1%d7%9d/" class="footer__link" data-fontsize="14">תקנון בעלי עסקים</a></li><li id="nav-menu-item-5606" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a9%d7%aa%d7%9e%d7%a9/" class="footer__link" data-fontsize="14">תקנון למשתמש</a></li><li id="nav-menu-item-2666" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%a6%d7%95%d7%a8-%d7%a7%d7%a9%d7%a8/" class="footer__link" data-fontsize="14">צור קשר</a></li></ul></div></footer>

<script type="text/javasc
<script type="text/javascript" src="https://www.israelispot.co.il/wp-content/themes/israelispot/js/script.main.min.js">
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/react-google-map@3.1.1/lib/index.min.js"></script>
</body>
</html>