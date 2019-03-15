<?php
/**
 * The template for displaying search results pages.
 *
 * @package stackstar.
 */
 
get_header(); ?>

<section class="blog__header"
             title="<?php echo $alt; ?>"
             alt="<?php echo $alt; ?>"
             style="background: url(<?php echo wp_get_attachment_image_url( $image, 'large' ); ?>) center / cover">
        <h1 class="blog__title"><?php printf( esc_html__( 'תוצאות עבור: %s', stackstar ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </section>

    <div class="search-container">
    <section id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
  <div class="wrap wrap_search">
        <?php if ( have_posts() ) : ?>
 
            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
	  <div class="item">
		  <h2 class="search-post-title"><?php the_title(); ?></h2>
		  <div class="image">
			  <img src="<?php echo get_the_post_thumbnail_url($post, 'small'); ?>" />
		  </div>
          <span class="search-post-excerpt"><?php the_excerpt(); ?></span>
          <span class="search-post-link"><a class="home-activities__link" href="<?php the_permalink(); ?>"><?php pll_e('Read more'); ?></a></span>
		  
	  </div>
 
            <?php endwhile; ?>
 
            <?php //the_posts_navigation(); ?>
 
        <?php else : ?>
 
            <?php //get_template_part( 'template-parts/content', 'none' ); ?>
 
        <?php endif; ?>
 </div>
        </main><!-- #main -->
    </section><!-- #primary -->
</div>
		
<?php //get_sidebar(); ?>
<?php get_footer(); ?>





