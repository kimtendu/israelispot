<?php
get_header(); ?>
    <main class="blog">
        <?php
        $term = get_term(get_queried_object()->term_id);
        $image = get_field('image', $term)['id'];
        $size = 'large';
        ?>
        <section class="blog__header"
                 title="<?php echo $alt; ?>"
                 alt="<?php echo $alt; ?>"
                 style="background: url(<?php echo wp_get_attachment_image_url( $image, $size ); ?>) center / cover">
            <h1 class="blog__title"><?php echo $term->name; ?></h1>
            <?php if(get_field('subtitle', $term)) : ?>
            <h2 class="blog__subtitle"><?php the_field('subtitle', $term); ?></h2>
            <?php endif; ?>
        </section>
        <div class="wrap wrap_home">
            <ul class="home-blog__list home-blog__list_blog">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    $mainCategory = new WPSEO_Primary_Term( 'blog_categories', get_the_ID() );
                    $mainCategory = $mainCategory->get_primary_term();
                    $mainCategory = get_term( $mainCategory );
                    ?>
                    <li class="home-blog__item home-blog__item_blog">
                        <a href="<?php echo $post->guid; ?>" class="home-blog__link">
                            <div class="home-blog__image" style="background: url(<?php echo get_the_post_thumbnail_url($post, 'large'); ?>) center / cover"></div>
                            <h1 class="home-blog__title"><?php echo $post->post_title; ?></h1>
                        </a>
                        <h2 class="home-blog__subtitle">
                            <span><?php echo get_the_date('d M, Y') ?></span>
                            <?php if($mainCategory) : echo ' - '; ?>
                                <span><?php echo $mainCategory->name; ?></span>
                            <?php endif; ?>
                        </h2>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </main>
<?php
get_footer();
