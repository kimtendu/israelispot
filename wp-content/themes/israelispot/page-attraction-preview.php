<?php /* Template Name: Attraction preview */
get_header();
if(get_locale() == 'en_GB') {
    $direction = 'right';
    $lang = 'en';
} else {
    $direction = 'left';
    $lang = 'he';
};
$title = $_GET['title'];
$categories = json_decode($_GET['categories']);
$region = $_GET['region'];
$phone = $_GET['phone'];
$phone2 = $_GET['phone2'];
$webUrl = $_GET['webUrl'];
$hours = $_GET['hours'];
$credit = $_GET['credit'];
$excerpt = $_GET['excerpt'];
$map = $_GET['map'];
$map = json_decode(str_replace('\"', '"', $map));
$map = (array)$map;
?>
<main id="preview-page" class="activity-body">
    <div class="activity activity_attraction">
        <section id="preview_gallery"></section>
        <div class="wrap wrap_home wrap_activity">
            <section class="activity__main">
                <h1 class="activity__title"><?php echo $title; ?></h1>
                <section id="preview_excerpt" class="custom-content">
                    <?php echo $excerpt; ?>
                </section>
                <div class="activity__phone">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
                    <?php if($phone2) : ?>
                        | <a href="tel:<?php echo $phone2; ?>"><?php echo $phone2; ?></a>
                    <?php endif; ?>
                </div>
                <div class="activity__phone">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <a href="<?php echo $webUrl; ?>" target="_blank"><?php pll_e('Website'); ?></a>
                </div>
            </section>
            <section class="activity__sidebar">
                <div class="activity__sidebar-container activity__sidebar-container_attraction">
                    <?php
                    $user = get_field('user_map_icon', 'options');
                    $image = get_field('map_icon','attraction_categories_' . $categories[0]);
                    $map['geometry'] = (array)$map['geometry'];
                    ?>
                    <section class="activity-map" id="map">
                        <div class="acf-map">
                            <div class="marker"
                                 data-user="<?php echo $user; ?>"
                                 data-icon="<?php echo $image; ?>"
                                 data-lat="<?php echo $map['geometry']['coordinates'][1]; ?>"
                                 data-lng="<?php echo $map['geometry']['coordinates'][0]; ?>">
                            </div>
                        </div>
                    </section>
                    <h1 class="activity-map-title"><?php pll_e('Attraction map title'); ?></h1>
                    <section class="activity-map-links">
                        <a id="directMap" href="#" class="activity-map-directions"><?php pll_e('Get directions'); ?></a>
                    </section>
                </div>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo $hours; ?>
                    </p>
                </div>
                <div class="activity__sidebar-container">
                    <p class="activity__time">
                        <?php echo pll__('Credit text').' - '; ?>
                        <?php if($credit) {
                            echo $credit;
                        } else {
                            echo $title;
                        } ?>
                    </p>
                </div>
            </section>
        </div>
    </div>
    <div class="wrap wrap_home">
        <section class="activity-content">
            <section id="preview_content" class="custom-content custom-content_activity"></section>
        </section>
    </div>
    <div class="wrap wrap_home clear"></div>
</main>
<?php get_footer(); ?>
