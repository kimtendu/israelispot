<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Israelispot
 */

get_header(); ?>
<main class="blog">
	<?php
	$image = get_field('blog_archive_image', 'options')['id'];
	$size = 'large';
	?>
	<section class="blog__header"
			 title="<?php echo $alt; ?>"
			 alt="<?php echo $alt; ?>"
			 style="background: url(<?php echo wp_get_attachment_image_url( $image, $size ); ?>) center / cover">
		<h1 class="blog__title"><?php pll_e('OUR BLOG'); ?></h1>
		<h2 class="blog__subtitle"><?php pll_e('Our blog subtitle'); ?></h2>
	</section>
	<div class="wrap wrap_home">
		<ul class="home-blog__list home-blog__list_blog">
			<?php while ( have_posts() ) : the_post(); ?>
                <?php
                    $categories = get_the_terms($post, 'blog_categories');
                    if(count($categories) >= 1) {
                        $mainCategory = new WPSEO_Primary_Term( 'blog_categories', get_the_ID() );
                        $mainCategory = $mainCategory->get_primary_term();
                        $mainCategory = get_term( $mainCategory );
                    } else {
                        $mainCategory = $categories[0];
                    }
                ?>
				<li class="home-blog__item home-blog__item_blog">
					<a href="<?php echo $post->guid; ?>" class="home-blog__link">
						<div class="home-blog__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'large'); ?>) center / cover"></div>
						<h1 class="home-blog__title"><?php echo $post->post_title; ?></h1>
					</a>
                    <h2 class="home-blog__subtitle">
                        <span><?php echo get_the_date('d M, Y') ?></span>
                        <?php if($mainCategory) : echo ' - '; ?>
                            <a href="<?php echo get_term_link($mainCategory); ?>"><?php echo $mainCategory->name; ?></a>
                        <?php endif; ?>
                    </h2>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</main>
<?php
get_footer();
