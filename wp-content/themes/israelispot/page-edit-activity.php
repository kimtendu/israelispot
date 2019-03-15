<?php /* Template Name: Edit activity */
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


        <?php
        $user = wp_get_current_user();
        $userId = $user -> ID;

		$id = (int)strip_tags($_GET['post_id']) ?  (int)strip_tags($_GET['post_id']) : false ;


        if (isset($_GET['post_id']) && $id) :			

            $post_id = $id;
            $current_post = get_post($post_id);
            $post_author_id = $current_post->post_author;
			$post_content = $current_post->post_content;
	
			$current_post_title = esc_html($current_post->post_title);
			$current_post_excerpt = esc_html($current_post->post_excerpt);
			$current_post_content = esc_html($current_post->post_content);
		
		
            if($post_author_id == $userId) : ?>
                <h1 class="custom-activity__title">עריכת פעילות: "<?php echo $current_post_title ?>"</h1>
            <?php endif; ?>

            <div class="custom-activity__container">
                <?php 
                if($lang == 'he'){
                    echo do_shortcode('[gravityform id="16" title="false" description="false" ajax="false" tabindex="0" field_values="post_title=' . $current_post_title . '&post_excerpt=' . $current_post_excerpt . '&post_content=' . $current_post_content . '" update="'.$post_id.'"]');
                } else {
                    //echo do_shortcode('[gravityform id="10" title="false" description="false" ajax="true" tabindex="0"]');
                }
                ?>

       		<a id="preview" class="preview" href="<?php echo get_permalink($page[0]->ID); ?>"><?php pll_e('Preview'); ?></a>
            </div>
<?php $gallery = get_field('gallery', $post_id) ; ?>
            <?php if ($gallery) : ?>
            	<div id="gallery">
            	<?php foreach ($gallery as $key): ?>
            		<img src="<?=$key['url'];?>" />
            	<?php endforeach ?>
            	</div>
            <?php endif; ?>

		<?php endif; ?>
 <?php // var_dump($gallery); ?>

	<?php else : ?>
		<h1 class="custom-activity__title">למה הגעת לפה בכלל??</h1>        
	<?php endif; ?>




    </div>
</main>
<!-- <?php echo get_the_post_thumbnail_url($current_post, 'medium') ?> -->
<script>
	/*jQuery(function(){
		
		
		var imgTemplate = '<div id="mainImg"><img src="<?php echo get_the_post_thumbnail_url($current_post, 'medium') ?>"/></div>';
		jQuery('#field_16_16').html(imgTemplate);

		//jQuery('#mainImg>img').attr('src', '');
		
		function readURL(input) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
				//jQuery('#mainImg').show();
				reader.onload = function (e) {
					jQuery('#mainImg>img').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

	})

	jQuery(document).on("change","#input_16_3", function(){
		//readURL(this);
	});*/
</script>


<?php get_footer(); ?>
