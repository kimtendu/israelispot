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


$postPerPage = 30;
$list_category = '';
	
	

	if(isset($_GET['category'])) :
            $list_category = $_GET['category'] ? $_GET['category'] : '';

	endif;
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

<div class="container">
	<div class="col-xs-12"><h1 class="activity__title indexTitle"><?php the_title(); ?></h1></div>
	<hr/>
	
	
	<form action="<?php echo site_url() ?>/he/אינדקס-אטרקציות/" method="GET" id="filter">
		<div>
			<?php
			if($terms = get_terms( 'attraction_categories', 'orderby=name' )) : // to make it simple I use default categories
				echo '<select name="categoryfilter"><option>כל הקטגוריות</option>';
		  // pll_e('Any category')
				foreach ( $terms as $term ) :
					echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
				endforeach;
				echo '</select>';
			endif;
			?>

			<input type="text" name="price_min" placeholder="ממחיר" />
			<input type="text" name="price_max" placeholder="עד מחיר" />

			<label>
				<input type="radio" name="date" value="ASC" /> לפי תאריך: חדש
			</label>
			<label>
				<input type="radio" name="date" value="DESC" selected="selected" />  ישן
			</label>

			<label>
				<input type="checkbox" name="featured_image" /> רק פעילויות עם תמונה
			</label>
			<button>Apply filter</button>
			<input type="hidden" name="action" value="myfilter">
		</div>
	</form>	
	
	<div class="categoryTxtTop">
		<?php the_content(); ?>
	</div>
	<div class="search-filter search-filter-full">
	
<?php
	//$query = array('posts_per_page' => $postPerPage, 'post_type' => 'activity', 'orderby' => 'date', 'cat' => 110);   //, 'cat' => 'אירוח'
		
				
	$query = array(
        'posts_per_page' => -1,
        'post_type' => 'activity',
		'post_status' => 'publish',
        'tax_query' => array(
			'relation' => 'AND',
            array(
                'taxonomy' => 'attraction_categories_new',
                'field' => 'tag_ID',
                'terms' => '-1',
				
            )
        )	

	);
		
		/*
		 *
		 * SELECT p.* FROM wp_posts p, wp_usermeta u
                                 WHERE    p.post_type     = 'post'
                                 AND      p.post_status   = 'publish'
                                 AND      u.user_id       = p.`post_author`
                                 AND      u.meta_key      = 'wp_capabilities'
                                 AND      u.meta_value    LIKE '%\"subscriber-pro\"%'


		 *
		 */
	
	$wp_query = new WP_Query($query);
	$total = $wp_query->max_num_pages;	
	$total_attr = $wp_query->found_posts;
		
	//var_dump($total_attr);
	echo '<h2>נמצאו ' . $total_attr . ' אטרקציות</h1>';
?>
		
	<ul class="home-activities__list home-activities__list_search" id="activitiesResponse">
			
<?php
	
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<li id={this.props.id} class="home-activities__item home-activities__item_small">
		<a href="<?php the_permalink() ?>" class="home-activities__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'medium') ?>) center / cover" >
			<?php if($post->price) : ?>
				<span class="home-activities__price"><span class="home-activities__number"><?php echo $post->price; ?>
				</span>₪</span>

				<?php if($post->old_price and $post->old_price > $post->price) : ?>							
						<span class="number home-activities__old-price"><span class="home-activities__number"><?php echo $post->old_price ?></span>ש"ח</span>
				<?php endif; ?>			
			<?php endif; ?>			
		</a>
		
		
			<section class="home-activities__content">
					<h2><a href="<?php the_permalink() ?>" class="home-activities__title"><?php TrimStringIfToLong(get_the_title()); ?> </a></h2>
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
	
	
	
	
	
<?php endwhile; else: ?>
	
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
$current_page  = $paged;
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

     if($current_page < 3){
		$firstPageingPage = 1;
		 $lastPageingPage = 5;
	 }elseif($current_page > ($total - 2)){
		$firstPageingPage = $total - 5;		 
	}else{
		$firstPageingPage = $current_page - 3;
		 $lastPageingPage =  $current_page + 3;
	}
	
	$htmlStr .= '<div class="pagination"><ul>
		<li><a href="' . $format . 1 . '" class="end" aria-label="first">ראשון</a></li>
		<li class="'. $prev_page_css .'"><a href="' . $format . ($current_page - 1) . '" class="end" aria-label="previous">הקודם</a></li>';

	for($i=$firstPageingPage; $i <= $lastPageingPage; $i++ ){
		$current_page_css = $current_page == $i ? 'currentPage' : '';
		$htmlStr .= '<li><a class="' .$current_page_css. '" href="' . $format . $i .'">' . $i . '</a></li>';
	}

	$htmlStr .= '<li class="'. $next_page_css .'"><a href="' . $format . ($current_page + 1) . '" class="end" aria-label="next">הבא</a></li>
				<li><a href="' . $format . $total . '" class="end" aria-label="last">אחרון</a></li></ul></div>';
			
	echo $htmlStr;
	
endif;
?>










<footer><div class="wrap wrap_footer"><section class="copyright-container"> <span class="copyright">נקודה ישראלית - כל הזכויות שמורות 2018 ©</span> <i class="fa fa-circle" aria-hidden="true"></i> <span class="footer-phone">טלפון: <a href="tel:0733742311" data-fontsize="14">0733742311</a></span> <i class="fa fa-circle" aria-hidden="true"></i> <a target="_blank" href="" class="footer-link" data-fontsize="14">עיצוב ופיתוח אתר</a></section><ul id="menu-footer-he" class="footer__list"><li id="nav-menu-item-54" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%90%d7%98%d7%a8%d7%a7%d7%a6%d7%99%d7%95%d7%aa/" class="footer__link" data-fontsize="14">כל הפעילויות</a></li><li id="nav-menu-item-55" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-17 current_page_item"><a h
ref="https://www.israelispot.co.il/he/" class="footer__link" data-fontsize="14">דף הבית</a></li><li id="nav-menu-item-2670" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories"><a href="https://www.israelispot.co.il/he/blog_categories/%d7%9b%d7%aa%d7%91%d7%95-%d7%a2%d7%9c%d7%99%d7%a0%d7%95/" class="footer__link" data-fontsize="14">כתבו עלינו</a></li><li id="nav-menu-item-2672" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-taxonomy menu-item-object-blog_categories"><a href="https://www.israelispot.co.il/he/blog_categories/%d7%94%d7%9e%d7%9c%d7%a6%d7%95%d7%aa/" class="footer__link" data-fontsize="14">המלצות</a></li><li id="nav-menu-item-5605" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a4%d7%a8%d7%a1%d7%9d/" class="footer__link" data-fontsize="14">תקנון בעלי עסקים</a></li><li id="nav-menu-item-5606" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%aa%d7%a7%d7%a0%d7%95%d7%9f-%d7%9c%d7%9e%d7%a9%d7%aa%d7%9e%d7%a9/" class="footer__link" data-fontsize="14">תקנון למשתמש</a></li><li id="nav-menu-item-2666" class="footer__item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page"><a href="https://www.israelispot.co.il/he/%d7%a6%d7%95%d7%a8-%d7%a7%d7%a9%d7%a8/" class="footer__link" data-fontsize="14">צור קשר</a></li></ul></div></footer>

<script type="text/javasc
<script type="text/javascript" src="https://www.israelispot.co.il/wp-content/themes/israelispot/js/script.main.min.js">
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/react-google-map@3.1.1/lib/index.min.js"></script>
</body>
</html>