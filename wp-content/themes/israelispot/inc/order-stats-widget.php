<?php
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'Activity order stats', 'activity_order_stats');
}

function activity_order_stats() {

    $meta_key = 'clicked_order';
    $meta_key_present = 'clicked_order_present';

    $args = array(
        'numberposts'   => -1,
        'post_type'        => 'activity',
        'meta_key' => 'clicked_order',
        'orderby' => 'meta_value_num'
    );



    $activities = get_posts( $args );
    echo '<ul class="admin-stats__list">
            <li class="admin-stats__item">
                <span class="admin-stats__name">'.pll__('Activity').'</span>
                <span class="admin-stats__number admin-stats__number_person">'.pll__('Person').'</span>
                <span class="admin-stats__number">'.pll__('Present').'</span>
            </li>';
    foreach ($activities as $activity){

//        update_post_meta( $activity->ID, $meta_key, 0 );
//        update_post_meta( $activity->ID, $meta_key_present, 0 );
//        wp_update_post($activity);

        echo '<li class="admin-stats__item">
                    <a href="'.get_permalink($activity->ID).'" class="admin-stats__name">'.$activity->post_title.'</a>
                    <span class="admin-stats__number admin-stats__number_person">'.$activity->{$meta_key}.'</span>
                    <span class="admin-stats__number">'.$activity->{$meta_key_present}.'</span>
                </li>';
    }
    echo '</ul>';
}
