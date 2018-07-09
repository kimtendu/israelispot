<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
while ( have_posts() ) : the_post(); ?>
<main>
    <?php
    $thumbnail_id = get_post_thumbnail_id( $ID );
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    ?>
    <section class="blog__header"
             title="<?php echo $alt; ?>"
             alt="<?php echo $alt; ?>"
             style="background: url(<?php echo get_the_post_thumbnail_url($ID, 'large'); ?>) center / cover">
        <h1 class="blog__title"><?php the_title(); ?></h1>
        <h2 class="blog__subtitle"><?php the_excerpt() ?></h2>
    </section>
    <section class="blog__content">
        <div class="wrap wrap__home">
            <section class="custom-content custom-content_blog">
                <?php the_content(); ?>
            </section>
        </div>
    </section>
</main>
<?php endwhile; ?>
<?php get_footer(); ?>

