<?php if ( have_posts() ) :

    $activities = [];

    while ( have_posts() ) : the_post();
        if(get_post_type() == 'activity'){
            $activities[] = $post;
        }
    endwhile;

    $today = date('Ymd');

    $dateActivities = [];
    foreach ($activities as $activity){
        if(get_field('date', $activity)){
            $date = get_field('date', $activity);
        } else {
            $date = get_the_date('Ymd', $activity);
            $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
        }

        if($date >= $today){
            $attraction = get_field('attraction', $activity);
            if(get_field('date', $attraction)){
                $date = get_field('date', $attraction);
            } else {
                $date = get_the_date('Ymd', $attraction);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }
            if($date >= $today){
                $dateActivities[] = $activity;

            }
        }
    }
    $finalActivities = [];
    foreach ($dateActivities as $dateActivity){
        $finalActivities[] = $dateActivity->ID;
    }

else :
    $finalActivities = 'nothing';
endif;
$page = get_pages(
    array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-search.php'
    )
);
wp_redirect( get_permalink($page[0]->ID) .'?id='.json_encode($finalActivities) );
exit;