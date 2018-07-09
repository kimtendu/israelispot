<?php
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
<main class="blog">
    <?php
    $image = get_field('main_image')['id'];
    ?>
    <section class="blog__header"
             title="<?php echo $alt; ?>"
             alt="<?php echo $alt; ?>"
             style="background: url(<?php echo wp_get_attachment_image_url( $image, 'large' ); ?>) center / cover">
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
