<?php /* Template Name: user-activities */

/**
 * The template for displaying all user activities
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#user-activities
 *
 * @package Israelispot
 */

get_header(); ?>


<main id="main" class="activity-body">
   
    <div class="wrap wrap_home wrap_activity">
        <h1 class="activity__title" data-fontsize="48"><?php echo get_the_title(); ?></h1>

        <?php if(is_user_logged_in()) : 
        
        global $current_user; 
        
        $args = new WP_Query(array('author'=>$current_user->ID, 'post_type'=>'product', 'post_status'=>'publish', 'posts_per_page'=>-1)); ?>

        <?php if ( $args->have_posts() ) : ?>

        <table class="table editTbl">            
            <?php while ( $args->have_posts() ) : $args->the_post();
				$id = get_the_ID();
			?>
            <tr>
                <td><?php the_title(); ?> <a href="<?php the_permalink(); ?>" target="_blank" aria-label="הצג"><i class="fa fa-link" aria-hidden="true"></i></a></td>
                <td><a href="/he/עריכת-פעילות/?post_id=<?php echo $id ?>">ערוך</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php wp_reset_postdata(); ?>

        <?php else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>


        <?php endif; ?>


        <section class="regularSidebar" style="display:none;">
            <?php
            get_sidebar();
            ?>
        </section>

    </div><!-- wrap -->
</main><!-- #main -->


<?php
get_footer();
?>