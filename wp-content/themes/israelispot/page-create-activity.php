<?php /* Template Name: Add activity */
get_header();
if(get_locale() == 'en_GB') {
    $lang = 'en';
} else {
    $lang = 'he';
};
?>
<main class="custom-activity">
    <div class="wrap wrap_home">
        <?php if(is_user_logged_in()) : ?>
            <?php if(get_field('attraction', 'user_'.get_current_user_id())) : ?>
            <h1 class="custom-activity__title"><?php pll_e('Add your activity'); ?></h1>
            <div class="custom-activity__container">
                <?php
                if($lang == 'he'){
                    echo do_shortcode('[gravityform id="11" title="false" description="false" ajax="true" tabindex="0"]');
                } else {
                    echo do_shortcode('[gravityform id="10" title="false" description="false" ajax="true" tabindex="0"]');
                }
                ?>
                <?php
                $page = get_pages(
                    array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-activity-preview.php'
                    )
                );
                ?>
                <a id="preview" class="preview" href="<?php echo get_permalink($page[0]->ID); ?>"><?php pll_e('Preview'); ?></a>
            </div>
            <?php else : ?>
                <h1 class="custom-activity__title"><?php pll_e('First need add your attraction'); ?></h1>
                <div class="custom-activity__container">
                    <?php
                    if($lang == 'he'){
                        echo do_shortcode('[gravityform id="9" title="false" description="false" ajax="false" tabindex="0"]');
                    } else {
                        echo do_shortcode('[gravityform id="8" title="false" description="false" ajax="false" tabindex="0"]');
                    }
                    ?>
                    <?php
                    $page = get_pages(
                        array(
                            'meta_key' => '_wp_page_template',
                            'meta_value' => 'page-attraction-preview.php'
                        )
                    );
                    ?>
                    <a id="preview-attraction" class="preview" href="<?php echo get_permalink($page[0]->ID); ?>"><?php pll_e('Preview'); ?></a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h1 class="custom-activity__title"><?php pll_e('You must be a registered user to add activity'); ?></h1>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
