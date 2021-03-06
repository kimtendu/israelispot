<?php
/**
 * Israelispot functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Israelispot
 */

/**get activities fields
 */




    
add_action( 'wp_loaded', function() {
    global $pagenow;
    if(
        defined( 'IN_MAINTENANCE' )
        && IN_MAINTENANCE
        && $pagenow !== 'wp-login.php'
        && ! is_user_logged_in()
    ) {
        
        header( 'HTTP/1.1 Service Unavailable', true, 503 );
        header( 'Content-Type: text/html; charset=utf-8' );
        header( 'Retry-After: 3600' );
        if ( file_exists( ABSPATH . 'maintenance.php' ) ) {
            require_once( ABSPATH . 'maintenance.php' );
        }
        
    }
});
    

require_once get_template_directory().'/fields/activities-fields.php';

if (!function_exists('israelispot_setup')) :
/**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
function israelispot_setup()
{
    /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Israelispot, use a find and replace
         * to change 'israelispot' to the name of your theme in all the template files.
         */
    load_theme_textdomain('israelispot', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
    add_theme_support('title-tag');

    /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
    add_theme_support('post-thumbnails');
    add_image_size('map-marker', 30, 30, false);
    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'menu-1' => esc_html__('Primary', 'israelispot'),
    ));

    /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('israelispot_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
    add_theme_support('custom-logo', array(
        'height' => 250,
        'width' => 250,
        'flex-width' => true,
        'flex-height' => true,
    ));
}
endif;
add_action('after_setup_theme', 'israelispot_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function israelispot_content_width()
{
    $GLOBALS['content_width'] = apply_filters('israelispot_content_width', 640);
}

add_action('after_setup_theme', 'israelispot_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function israelispot_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'israelispot'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'israelispot'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'israelispot_widgets_init');

/**
 * Enqueue scripts and styles.
 */

function israelispot_scripts()
{
    wp_enqueue_style('Slick', "https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css");
    wp_enqueue_style('Fancybox', "https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css");
    wp_enqueue_style('style.main.min', get_template_directory_uri() . "/css/style.main.min.css");


    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'israelispot_scripts');

function add_footer_scripts()
{

    //AIzaSyBZ14kKyuTcKwK62n_MlXS_iWc-WEw3Ufo
    //AIzaSyBDfB7lBEU6fn8l1Ec3RLDeE2j14hlfW34
    //AIzaSyCM6qCcmyo7frVefqy1ofKO_mTAf8bE128
    wp_enqueue_script('googlemaps',
                      "https://maps.googleapis.com/maps/api/js?sensor=false&amp;language=" . pll_current_language() . "&key=AIzaSyAwkB0h-Uy7JSb-Vu_KhhearlVyyIE-7Uk",
                      ['jquery']
                     );
    wp_enqueue_script('googlemapsClusters', "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js", ['jquery']);

    wp_enqueue_script('fontawesome', "https://use.fontawesome.com/f9ccda9b6b.js");
    //	wp_enqueue_script('fontawesome', "https://use.fontawesome.com/releases/v5.0.9/js/all.js");
    wp_enqueue_script('Fancybox', "https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js");
    wp_enqueue_script('Lodash', "https://cdn.jsdelivr.net/npm/lodash@4.17.5/lodash.min.js");
    wp_enqueue_script('Browser', "https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js");
    wp_enqueue_script('React', "https://cdnjs.cloudflare.com/ajax/libs/react/15.3.2/react.min.js");
    wp_enqueue_script('ReactDom', "https://cdnjs.cloudflare.com/ajax/libs/react/15.3.2/react-dom.min.js");
    wp_enqueue_script('googleMapsReact', "https://cdn.jsdelivr.net/npm/react-google-map@3.1.1/lib/index.min.js");
    wp_enqueue_script('script.main.min', get_template_directory_uri() . "/js/script.main.min.js", ['jquery']);


    //	wp_enqueue_style( 'israelispot-style', get_stylesheet_uri() );

    wp_enqueue_script('israelispot-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

    wp_enqueue_script('israelispot-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);


}

add_action('wp_footer', 'add_footer_scripts');


function load_admin_style()
{
    wp_enqueue_style('style.main.min.css', get_template_directory_uri() . '/css/custom-admin.css');
}

add_action('admin_enqueue_scripts', 'load_admin_style');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/static-strings.php';
require get_template_directory() . '/inc/custom-menu.php';
require get_template_directory() . '/inc/order-stats-widget.php';

function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');


add_filter('wp_dropdown_users', 'MySwitchUser');
function MySwitchUser($output)
{

    //global $post is available here, hence you can check for the post type here
    $users = get_users([ 'role__in' => [ 'subscriber', 'Subscriber pro', 'Administrator'] ] );
    $output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\">";
    global $post;
    
    foreach($users as $user)
    {
        $sel = ($post->post_author == $user->ID)?"selected='selected'":'';
        $output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->user_login.'</option>';
    }
    $output .= "</select>";

    return $output;
}

function add_author_support_to_posts() {
    add_post_type_support( 'product', 'author' ); 
    add_post_type_support( 'attraction', 'author' );
}
add_action( 'init', 'add_author_support_to_posts' );



if (function_exists('acf_add_options_page')) {
    acf_add_options_sub_page(array(
        'page_title' => 'General',
        'menu_title' => __('General'),
        'menu_slug' => "general",
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Social',
        'menu_title' => __('Social'),
        'menu_slug' => "social",

    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Static values',
        'menu_title' => __('Static values'),
        'menu_slug' => "static_values",

    ));
}

//google maps
function my_acf_google_map_api($api)
{
    //AIzaSyBZ14kKyuTcKwK62n_MlXS_iWc-WEw3Ufo
    //AIzaSyCM6qCcmyo7frVefqy1ofKO_mTAf8bE128
    $api['key'] = 'AIzaSyAwkB0h-Uy7JSb-Vu_KhhearlVyyIE-7Uk';
    return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

add_filter('comment_form_default_fields', 'extend_comment_custom_default_fields');
function extend_comment_custom_default_fields($fields)
{

    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    $fields['author'] = '<p class="comment-form-author">' .
        '<label for="author">' . pll__('Review name') . ($req ? '<span class="required">*</span>' : '') . '</label>' .
        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) .
        '" size="30"' . $aria_req . ' /></p>';

    $fields['email'] = '<p class="comment-form-email">' .
        '<label for="email">' . pll__('Review email') . ($req ? '<span class="required">*</span>' : '') . '</label>' .
        '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
        '" size="30"' . $aria_req . ' /></p>';

    return $fields;
}

add_filter('comment_form_default_fields', 'website_remove');

add_action('comment_form_logged_in_after', 'extend_comment_custom_fields');
add_action('comment_form_before_fields', 'extend_comment_custom_fields');
function extend_comment_custom_fields()
{

    $status = array(
        1 => pll__('Very bad'),
        2 => pll__('Bad'),
        3 => pll__('Normal'),
        4 => pll__('Good'),
        5 => pll__('Very good'),
    );

    echo '<p class="comment-form-rating">' .
        '<label for="rating">' . pll__('Your rating') . '<span class="required">*</span></label>
			  <section class="home-activities__rating">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= 4) {
            echo '<i class="fa fa-star" aria-hidden="true"></i>';
        } else {
            echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
        }
    }
    for ($i = 1; $i <= 5; $i++) {
        if ($i == 4) {
            echo '<input checked style="display: none;" type="radio" name="rating" data-status="' . $status[$i] . '" value="' . $i . '"/>';
        } else {
            echo '<input style="display: none;" type="radio" name="rating" data-status="' . $status[$i] . '" value="' . $i . '"/>';
        }
    }
    echo '<span class="rating-status">' . $status[4] . '</span></section></p>';

    echo '<p class="comment-form-title">' .
        '<label for="title">' . pll__('Review title') . '</label>' .
        '<input id="title" name="title" type="text" size="30"/></p>';
}


add_action('comment_post', 'save_extend_comment_meta_data');
function save_extend_comment_meta_data($comment_id)
{

    if (!empty($_POST['title'])) {
        $title = sanitize_text_field($_POST['title']);
        add_comment_meta($comment_id, 'title', $title);
    }

    if (!empty($_POST['rating'])) {
        $rating = intval($_POST['rating']);
        add_comment_meta($comment_id, 'rating', $rating);
    }

}

add_action('add_meta_boxes_comment', 'extend_comment_add_meta_box');
function extend_comment_add_meta_box()
{
    add_meta_box('title', __('Comment Metadata - Extend Comment'), 'extend_comment_meta_box', 'comment', 'normal', 'high');
}

// Отображаем наши поля
function extend_comment_meta_box($comment)
{
    $title = get_comment_meta($comment->comment_ID, 'title', true);
    $rating = get_comment_meta($comment->comment_ID, 'rating', true);

    wp_nonce_field('extend_comment_update', 'extend_comment_update', false);
?>
<p>
    <label for="title" style="display: block; margin-bottom: 10px; "><?php _e('Comment Title'); ?></label>
    <input type="text" name="title" value="<?php echo esc_attr($title); ?>" class="widefat"/>
</p>
<p>
    <label for="rating"><?php _e('Rating: '); ?></label>
    <span class="commentratingbox">
        <?php
    for ($i = 1; $i <= 5; $i++) {
        echo '
		  <span class="commentrating">
			<input type="radio" name="rating" id="rating" value="' . $i . '" ' . checked($i, $rating, 0) . '/>
		  </span>';
    }
        ?>
    </span>
</p>
<?php
}

add_action('edit_comment', 'extend_comment_edit_meta_data');
function extend_comment_edit_meta_data($comment_id)
{
    if (!isset($_POST['extend_comment_update']) || !wp_verify_nonce($_POST['extend_comment_update'], 'extend_comment_update'))
        return;

    if (!empty($_POST['title'])) {
        $title = sanitize_text_field($_POST['title']);
        update_comment_meta($comment_id, 'title', $title);
    } else
        delete_comment_meta($comment_id, 'title');

    if (!empty($_POST['rating'])) {
        $rating = intval($_POST['rating']);
        update_comment_meta($comment_id, 'rating', $rating);
    } else
        delete_comment_meta($comment_id, 'rating');

}

function website_remove($fields)
{
    if (isset($fields['url']))
        unset($fields['url']);
    return $fields;
}

class JDN_Create_Media_File
{

    var $post_id;
    var $image_url;
    var $wp_upload_url;
    var $attachment_id;

    /**
     * Setup the class variables
     */
    public function __construct($image_url, $post_id = 0)
    {

        // Setup class variables
        $this->image_url = esc_url($image_url);
        $this->post_id = absint($post_id);
        $this->wp_upload_url = $this->get_wp_upload_url();
        $this->attachment_id = $this->attachment_id ?: false;

        return $this->create_image_id();

    }

    /**
     * Set the upload directory
     */
    private function get_wp_upload_url()
    {
        $wp_upload_dir = wp_upload_dir();
        return isset($wp_upload_dir['url']) ? $wp_upload_dir['url'] : false;
    }

    /**
     * Create the image and return the new media upload id.
     *
     * @see https://gist.github.com/hissy/7352933
     *
     * @see http://codex.wordpress.org/Function_Reference/wp_insert_attachment#Example
     */
    public function create_image_id()
    {

        if ($this->attachment_id)
            return $this->attachment_id;

        if (empty($this->image_url) || empty($this->wp_upload_url))
            return false;

        $filename = basename($this->image_url);

        $upload_file = wp_upload_bits($filename, null, file_get_contents($this->image_url));

        if (!$upload_file['error']) {
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $this->post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $this->post_id);

            if (!is_wp_error($attachment_id)) {

                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);

                $this->attachment_id = $attachment_id;

                return $attachment_id;
            }
        }

        return false;

    } // end function get_image_id
}

//get address by coordinates
function IsNullOrEmptyString($question)
{
    return (!isset($question) || trim($question) === '');
}

function getAddress($latitude, $longitude)
{

    $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false&token=AIzaSyAwkB0h-Uy7JSb-Vu_KhhearlVyyIE-7Uk";
    $response = file_get_contents($url);
    $json = json_decode($response, TRUE); //set json response to array based
    $address_arr = $json['results'][0]['address_components'];
    $address = "";

    foreach ($address_arr as $arr1) {
        if (strcmp($arr1['types'][0], "street_number") == 0) {
            $address .= $arr1['long_name'] . " ";
            continue;
        }

        if (strcmp($arr1['types'][0], "route") == 0) {
            $address .= $arr1['long_name'];
            continue;
        }

        if (strcmp($arr1['types'][0], "locality") == 0) {
            $city = $arr1['long_name'];
            continue;
        }

        if (strcmp($arr1['types'][0], "administrative_area_level_1") == 0) {
            $state = $arr1['long_name'];
            continue;
        }

        if (strcmp($arr1['types'][0], "administrative_area_level_2") == 0) {
            $state2 = $arr1['long_name'];
            continue;
        }

        if (strcmp($arr1['types'][0], "postal_code") == 0) {
            $zip_code = $arr1['long_name'];
            continue;
        }

        if (strcmp($arr1['types'][0], "country") == 0) {
            $country = $arr1['long_name'];
            continue;
        }
    }

    if (!IsNullOrEmptyString($state)) {
        $response = array("address" => $address, "city" => $city, "state" => $state, "zipcode" => $zip_code, "country" => $country); //level_1 administrative data exist
    } else {
        $response = array("address" => $address, "city" => $city, "state" => $state2, "zipcode" => $zip_code, "country" => $country); //level_1 administrative data not exist
    }
    return $response;
}

//hide admin bar
add_filter('show_admin_bar', '__return_false');

//upload and edit attraction
if (get_locale() == 'en_GB') {
    $upload_attraction_id = 8;
    $upload_activity_id = 10;
} else {
    $upload_attraction_id = 9;
    $upload_activity_id = 11;
    $edit_activity_id = 16;
};


add_filter("gform_after_submission_{$upload_attraction_id}", 'jdn_set_post_acf_gallery_field', 10, 2);

function jdn_set_post_acf_gallery_field($entry, $form)
{
    $gf_images_field_id = 4;
    $acf_field_id = 'field_5aa4dda280949';
    //	$acf_field_id = 'field_5a9692f6eb7e5'; activity gallery

    if (isset($entry['post_id'])) {
        $post = get_post($entry['post_id']);
        if (is_null($post)) return;
        update_field('field_5abcfb69f3a3f', [$post->ID], 'user_' . get_current_user_id());

        //map
        $map_field = 'field_5aa4e4e1fdea5';
        $map_form = json_decode($entry[17], true);
        $map_value = get_field($map_field, $post->ID);
        $map_value['lat'] = $map_form['geometry']['coordinates'][1];
        $map_value['lng'] = $map_form['geometry']['coordinates'][0];
        $map_address = getAddress($map_form['geometry']['coordinates'][1], $map_form['geometry']['coordinates'][0]);
        $map_value['address'] = $map_address['address'] . ', ' . $map_address['city'] . ', ' . $map_address['zipcode'] . ', ' . $map_address['country'];
        update_field($map_field, $map_value, $post->ID);
        // phones
        $field_key = 'field_5aa4e4664e0e8';
        $value = get_field($field_key, $post->ID);
        $value[] = array('phone' => $entry[10]);
        if (isset($entry[11])) {
            $value[] = array('phone' => $entry[11]);
        }
        update_field($field_key, $value, $post->ID);

    } else {
        return;
    }

    if (isset($entry[$gf_images_field_id])) {
        $images = stripslashes($entry[$gf_images_field_id]);
        $images = json_decode($images, true);
        if (!empty($images) && is_array($images)) {
            $gallery = array();
            foreach ($images as $key => $value) {

                $image_url = $value;
                $create_image = new JDN_Create_Media_File($image_url);
                $image_id = $create_image->attachment_id;

                if ($image_id) {
                    $gallery[] = $image_id;
                }
            }
        }
    }

    // Update gallery field with array
    if (!empty($gallery)) {
        update_field($acf_field_id, $gallery, $post->ID);

        // Updating post
        wp_update_post($post);
    }
}




//edit activity
add_filter("gform_after_submission_{$edit_activity_id}", 'edit_activity_by_user', 10, 2);

function edit_activity_by_user($entry, $form){
    return $entry->post_id;
    /*
$gf_images_field_id = 12;
    $acf_field_id = 'field_5a9692f6eb7e5';

    if (isset($entry['post_id'])) {
        $post = get_post($entry['post_id']);
        if (is_null($post)) return;
        $attraction = get_field('attraction', 'user_' . get_current_user_id());

        update_field('field_5ab627cc356ef', $attraction[0], $post->ID);
    } else {
        return;
    }

    if (isset($entry[$gf_images_field_id])) {
        $images = stripslashes($entry[$gf_images_field_id]);
        $images = json_decode($images, true);
        if (!empty($images) && is_array($images)) {
            $gallery = array();
            foreach ($images as $key => $value) {

                $image_url = $value;
                $create_image = new JDN_Create_Media_File($image_url);
                $image_id = $create_image->attachment_id;

                if ($image_id) {
                    $gallery[] = $image_id;
                }
            }
        }
    }

    // Update gallery field with array
    if (!empty($gallery)) {
        update_field($acf_field_id, $gallery, $post->ID);

        // Updating post
        wp_update_post($post);
    }	
	*/
}






//upload activity
add_filter("gform_after_submission_{$upload_activity_id}", 'upload_activity_by_user', 10, 2);

function upload_activity_by_user($entry, $form)
{
    $gf_images_field_id = 12;
    $acf_field_id = 'field_5a9692f6eb7e5';

    if (isset($entry['post_id'])) {
        $post = get_post($entry['post_id']);
        if (is_null($post)) return;
        $attraction = get_field('attraction', 'user_' . get_current_user_id());

        update_field('field_5ab627cc356ef', $attraction[0], $post->ID);
    } else {
        return;
    }

    if (isset($entry[$gf_images_field_id])) {
        $images = stripslashes($entry[$gf_images_field_id]);
        $images = json_decode($images, true);
        if (!empty($images) && is_array($images)) {
            $gallery = array();
            foreach ($images as $key => $value) {

                $image_url = $value;
                $create_image = new JDN_Create_Media_File($image_url);
                $image_id = $create_image->attachment_id;

                if ($image_id) {
                    $gallery[] = $image_id;
                }
            }
        }
    }

    // Update gallery field with array
    if (!empty($gallery)) {
        update_field($acf_field_id, $gallery, $post->ID);

        // Updating post
        wp_update_post($post);
    }
}


add_action('init', 'my_robots');
function my_robots()
{
    if (!is_admin()) {
        if (get_field('show_only_hebrew', 'options')) {
            copy('Robots-hide.txt', 'Robots.txt');
        } else {
            copy('Robots-show.txt', 'Robots.txt');
        }
    }
}

add_action('template_redirect', 'my_callback');
function my_callback()
{
    $id = get_the_ID();
    $lang = get_locale();
    if ($lang == "en_GB" && get_field('show_only_hebrew', 'options')) {
        wp_redirect(get_permalink(pll_get_post($id, 'he')), 301);
        exit();
    }
}

function add_persian_to_acf_google_map($api)
{
    $api["language"] = "he";
    return $api;
}

add_filter("acf/fields/google_map/api", "add_persian_to_acf_google_map");

add_post_type_support('page', 'excerpt');


function true_load_activity()
{

    $id = $_POST['id'];

    $activities = get_field('favorite_activities', 'user_' . get_current_user_id());

    if ($activities) {
        if (in_array($id, $activities)) {
            if (($key = array_search($id, $activities)) !== FALSE) {
                unset($activities[$key]);
            }
        } else {
            array_unshift($activities, $id);
        }
    } else {
        $activities[] = $id;
    }


    update_field('field_5abcfb47f3a3e', $activities, 'user_' . get_current_user_id());


}

add_action('wp_ajax_loadactivity', 'true_load_activity');
add_action('wp_ajax_nopriv_loadactivity', 'true_load_activity');

function update_activity_click()
{

    $id = $_POST['id'];
    $type = $_POST['type'];
    if ($type == 'present') {
        $meta_key = 'clicked_order_present';
    } else {
        $meta_key = 'clicked_order';
    }

    $activity = get_post($id);
    $value = (int)$activity->{$meta_key};

    update_post_meta($activity->ID, $meta_key, $value + 1);
    wp_update_post($activity);;
}

add_action('wp_ajax_addclick', 'update_activity_click');
add_action('wp_ajax_nopriv_addclick', 'update_activity_click');


add_action('admin_init', 'disable_dashboard');

function disable_dashboard()
{
    if (current_user_can('subscriber')) {
        wp_redirect(home_url());
        exit;
    }
}

add_action('after_setup_theme', 'disable_admin_bar');

function disable_admin_bar()
{
    if (current_user_can('subscriber')) {
        show_admin_bar(false);
    }
}

add_action('after_setup_theme', 'add_post_meta_key');

function add_post_meta_key()
{

    $args = array(
        'numberposts' => -1,
        'post_type' => 'product',
    );

    $activities = get_posts($args);

    foreach ($activities as $activity) {
        $meta_key = 'clicked_order';
        $meta_key_present = 'clicked_order_present';

        if (!$activity->{$meta_key}) {
            add_post_meta($activity->ID, $meta_key, 0, TRUE);
        }

        if (!$activity->{$meta_key_present}) {
            add_post_meta($activity->ID, $meta_key_present, 0, TRUE);
        }
    }
}

add_action('wp', 'custom_maybe_activate_user', 9);
function custom_maybe_activate_user()
{

    $template_path = STYLESHEETPATH . '/gfur-activate-template/activate.php';
    $is_activate_page = isset($_GET['page']) && $_GET['page'] == 'gf_activation';

    if (!file_exists($template_path) || !$is_activate_page)
        return;

    require_once($template_path);

    exit();
}

add_filter('gform_user_registration_login_args', 'registration_login_args', 10, 1);
function registration_login_args($args)
{
    $args['login_redirect'] = rgpost('login_redirect') ? rgpost('login_redirect') : RGForms::get('HTTP_REFERER', $_SERVER);
    return $args;
}

add_filter('wp_nav_menu_items', 'dynamic_label_change', 10, 2);

function dynamic_label_change($items, $args)
{
    if (is_user_logged_in()) {
        $items = str_replace("התחברות", wp_get_current_user()->display_name, $items);
    }
    return $items;
}


add_action('rest_api_init', 'mainpage_form');
add_action('rest_api_init', 'mainpage_categories');
add_action('rest_api_init', 'attraction_activities');
add_action('rest_api_init', 'attraction_activities_paginations');
add_action('rest_api_init', 'attraction_activities_zone');
/**
 * Register the /wp-json/myplugin/v1/foo route
 */
function mainpage_form()
{
    register_rest_route('mainpage/v1', 'foo', array(
        'methods' => 'POST',
        'callback' => 'mainpage_form_route',
    ));
}

function mainpage_categories()
{
    register_rest_route('mainpage/v1', 'categories', array(
        'methods' => 'POST',
        'callback' => 'mainpage_categories_route',
    ));
}

function attraction_activities()
{
    register_rest_route('attraction/v1', 'activities', array(
        'methods' => 'POST',
        'callback' => 'attraction_activities_route',
    ));
}

function attraction_activities_paginations()
{
    register_rest_route('attraction/v1', 'activitiespag', array(
        'methods' => 'POST',
        'callback' => 'attraction_activities_pagination_route',
    ));
}



function attraction_activities_zone()
{
    register_rest_route('attraction/v1', 'activitieszone', array(
        'methods' => 'POST',
        'callback' => 'attraction_activities_zone_route',
    ));
}





/**
 * Generate results for the /wp-json/myplugin/v1/foo route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function mainpage_form_route(WP_REST_Request $request)
{
    if (wp_cache_get('api_mainpage') != false ) {
        return wp_cache_get('api_mainpage');
    } else {

        $start = microtime(true);
        $args = array(
            'numberposts' => -1,
            'post_type' => 'product',
        );

        $allPosts = get_posts($args);
        $posts = [];
        $today = date('Ymd');
        //var_dump($allPosts);
        foreach ($allPosts as $allPost) {
            if (get_field('date', $allPost)) {
                $date = get_field('date', $allPost);
            } else {
                $date = get_the_date('Ymd', $allPost);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }

            if ($date >= $today) {
                $posts[] = $allPost;
            }
        }
        //    echo  '<span style="display:none">microtime allPosts as allPost' .round(microtime(true) - $start, 4).' <span>';

        $allTags = [];
        $allCategories = [];
        $allPrices = [];
        $allRegions = [];
        //    echo  '<span style="display:none">Count' .Count($posts).' <span>';
        foreach ($posts as $post) {
            $postId = $post->ID;
            $attraction = get_field('attraction', $post);
            //        var_dump($attraction)
            $attractionID = $attraction->ID;

            if (get_field('date', $attractionID)) {
                $date = get_field('date', $attractionID);
            } else {
                $date = get_the_date('Ymd', $attractionID);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }

            if ($date >= $today) {

                if (get_field('price', $postId) !== "0") {
                    $allPrices[] = get_field('price', $postId);
                } else {
                    $allPrices[] = pll__('FREE');
                }

                $tags = get_the_terms($postId, 'activity_tags');
                if ($tags) {
                    foreach ($tags as $tag) {
                        $allTags[] = $tag->name;
                    }
                }

                $allRegions[] = get_field('location', $attractionID);

                $categories = get_the_terms($attractionID, 'attraction_categories');
                if ($categories) {
                    foreach ($categories as $category) {
                        $allCategories[] = $category->name;
                    }
                }
            }


        }
        //    echo '<span style="display:none"> microtime posts as post'.round(microtime(true) - $start, 4).' <span>';
        $allTags = array_unique($allTags);
        $allCategories = array_unique($allCategories);
        $allPrices = array_unique($allPrices);
        //    var_dump($allPrices);
        $allRegions = array_unique($allRegions);
        asort($allPrices);

        if (($key = array_search(pll__('FREE'), $allPrices)) !== FALSE) {
            unset($allPrices[$key]);
            array_unshift($allPrices, pll__('FREE'));
        }

        if (($key = array_search('', $allPrices)) !== FALSE) {
            unset($allPrices[$key]);
        }

        $allPrices = [pll__('FREE'), '10', '20', '40', '50', '100', '200', '201'];

        $page = get_pages(
            array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-search.php'
            )
        );

        $return = [
            'allTags' => $allTags,
            'allPrices' => $allPrices,
            'allCategories' => $allCategories,
            'allRegions' => $allRegions,
        ];
        //    var_dump($return); api_mainpage
        wp_cache_add('api_mainpage' , rest_ensure_response($return) );
        return rest_ensure_response($return);
    }
}

function mainpage_categories_route(WP_REST_Request $request)
{
    if (wp_cache_get('api_mainpage_categories') != false) {
        return wp_cache_get('api_mainpage_categories');
    } else {


        $returnActivites = [];
        $returnPostCount = [];

        $page = get_pages(
            array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-create-activity.php'
            )
        );

        $args = array(
            'numberposts' => -1,
            'post_type' => 'product',
        );

        $allPosts = get_posts($args);
        $posts = [];
        $today = date('Ymd');

        foreach ($allPosts as $allPost) {
            if (get_field('date', $allPost)) {
                $date = get_field('date', $allPost);
            } else {
                $date = get_the_date('Ymd', $allPost);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }

            if ($date >= $today) {
                $posts[] = $allPost;
            }
        }

        $allCategories = [];


        foreach ($posts as $post) {
            $attraction = get_field('attraction', $post);

            if (get_field('date', $attraction)) {
                $date = get_field('date', $attraction);
            } else {
                $date = get_the_date('Ymd', $attraction);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }

            if ($date >= $today) {
                $categories = get_the_terms($attraction, 'attraction_categories');
                if ($categories) {
                    foreach ($categories as $category) {
                        $allCategories[] = $category->term_id;
                    }
                }
            }
        }

        $allCategories = array_unique($allCategories);
        $terms = [];
        foreach ($allCategories as $category) {
            $terms[] = get_term_by('id', $category, 'attraction_categories');
        }

        $termsCount = 0;
        foreach ($terms as $term) {
            if ($termsCount < 10) {
                $returnActivites[] = [
                    'permalink' => '/?price=any&tag=any&category=' . $term->name . '&region=any',
                    'src' => get_field('icon', $term)['url'],
                    'name' => $term->name
                ];
            }
            $termsCount++;
        }


        $postCount = 0;
        foreach ($terms as $key => $term) {
            if ($postCount < 6) {

                $count = 0;
                //			foreach ($posts as $post){
                //				$attraction = get_field('attraction', $post->ID);
                //				$categories = get_the_terms($attraction->ID, 'attraction_categories');
                //				if($categories){
                //					foreach ($categories as $category) {
                //						if($category->name == $term->name) $count++;
                //					}
                //				}
                //			}

                switch ($key) {
                    case 0:
                        $count = 262;
                        break;
                    case 1:
                        $count = 92;
                        break;
                    case 2:
                        $count = 154;
                        break;
                    case 3:
                        $count = 210;
                        break;
                    case 4:
                        $count = 9;
                        break;
                    case 5:
                        $count = 176;
                        break;
                }

                $returnPostCount[] = [
                    'key' => $key,
                    'href' => '/?price=any&tag=any&category=' . $term->name . '&region=any',
                    'icon' => get_field('icon', $term)['url'],
                    'count' => $count,
                    'name' => $term->name,
                    'image' => get_field('image', $term)['url']
                ];

            }
            $postCount++;
        }

        $return = [
            'Activites' => $returnActivites,
            'PostCount' => $returnPostCount
        ];

        //api_mainpage_categories
        wp_cache_add('api_mainpage_categories' , rest_ensure_response($return) );
        return rest_ensure_response($return);

    }

    //
}

function attraction_activities_route_old(WP_REST_Request $request) {
    //    if (get_locale() == 'en_GB') {
    //        $direction = 'right';
    //        $lang = 'en';
    //    } else {
    //        $direction = 'left';
    //        $lang = 'he';
    //    };

    if (wp_cache_get('api_activities') != false ) {
        return wp_cache_get('api_activities');
    } else {


        $start = microtime(true);

        $lang = 'he';

        $posts = get_tommorow_posts('rand');
        $today = date('Ymd');
        $activities = [];
        foreach ($posts as $post) {
            $attraction = get_field('attraction', $post);
            if (get_field('date', $attraction)) {
                $date = get_field('date', $attraction);
            } else {
                $date = get_the_date('Ymd', $attraction);
                $date = date("Ymd", strtotime(date("Ymd", strtotime($date)) . " + 1 year"));
            }
            if ($date >= $today) {
                $activities[] = $post;
            }
        }

        //    $return = [];
        $Activities = [];
        $Cooperations = [];
        //    $Params = [];

        foreach ($activities as $post) {
            $post_id = $post->ID;
            $oldprice = get_field('old_price', $post_id);
            if (get_field('price', $post_id)) {
                $price = get_field('price', $post_id);

            } else if (get_field('price', $post_id) === '0') {
                $price = 0;
            } else {
                $price = '';
            }

            if (get_field('old_price', $post_id)) {
                $oldprice = get_field('old_price', $post_id);
            } else if (get_field('old_price', $post_id) === '0') {
                $oldprice = 0;
            } else {
                $oldprice = '';
            }

            $rating = 'default';
            $commentsRating = [];
            $comments = get_comments(array(
                'post_id' => $post->ID,
                'status' => 'approve'
            ));

            if (count($comments) != 0) {
                foreach ($comments as $comment) {
                    $commentsRating[] = get_comment_meta($comment->comment_ID, 'rating', true);
                }
                $rating = round(array_sum($commentsRating) / count($commentsRating));
            }

            $tags = get_the_terms($post, 'activity_tags');
            $tagsNames = [];
            if ($tags) {
                foreach ($tags as $tag) {
                    $tagsNames[] = $tag->name;
                }
            }

            //        echo "/*microtime Activities before attraction ".round(microtime(true) - $start, 4).' sek*/';
            $attraction = get_field('attraction', $post_id);


            $tagsAttractionNames = [];
            $location = get_field('location', $post_id);

            if ($attraction) {
                $attractionID = $attraction->ID;
                $attractionLink = esc_js(get_permalink($attraction));
                //                $guide = $attraction->post_title;
                //                $location = get_field('location', $attractionID);
                //                $image = get_the_post_thumbnail_url($attractionID, 'medium');
                $tags = get_the_terms($attractionID, 'attraction_tags');
                if ($tags) {
                    foreach ($tags as $tag) {
                        $tagsAttractionNames[] = $tag->name;
                    }
                }
            } else {
                $attractionID = false;
                $attractionLink = "";
                $guide = "";
                $location = "";
                $image = "";
                $tagsAttractionNames = [];
            }
            //        echo "/*microtime Activities attraction ".round(microtime(true) - $start, 4).' sek*/';
            $categories = get_the_terms($attractionID, 'attraction_categories');
            $categoriesNames = [];
            if ($categories) {
                foreach ($categories as $category) {
                    $categoriesNames[] = $category->name;
                }
            }
            //        echo "/*microtime Activities categories ".round(microtime(true) - $start, 4).' sek*/';
            $mainCategory = new WPSEO_Primary_Term('attraction_categories', $attractionID);
            $mainCategory = $mainCategory->get_primary_term();
            $mainCategory = get_term($mainCategory);
            $mainCategoryImage = get_field('map_icon', $mainCategory);

            if ($mainCategoryImage == '') {
                $mainCategoryImage = get_field('map_icon', $categories[0]);
            }

            $Activities[] = [
                'id' => $post->ID,
                'price' => $price,
                'oldprice' => $oldprice,
                'childPrice' => get_field('child_price', $post_id),
                'currency' => pll_translate_string('NIS', $lang),
                'rating' => $rating,
                'region' => $location,
                'subImage' => $image,
                'guide' => esc_js($guide),
                'attractionLink' => $attractionLink,
                'title' => esc_js($post->post_title),
                'mainCategory' => $mainCategory->name,
                'mainCategoryIcon' => $mainCategoryImage,
                'link' => esc_js(get_permalink($post)),
                'excerpt' => esc_js(wp_trim_words(get_the_excerpt($post), 15, '...')),
                'image' => get_the_post_thumbnail_url($post, 'medium'),
                'lat' => get_field('map', $post_id)['lat'],
                'lng' => get_field('map', $post_id)['lng'],
                'tags' => json_encode($tagsNames),
                'tagsAttraction' => json_encode($tagsAttractionNames),
                'categories' => json_encode($categoriesNames),
                'comments' => count($comments),
                'attractionId' => $attractionID
            ];

        }

        foreach (get_field('cooperations_' . $lang, 'options') as $term) {

            $Cooperations[] = [
                'name' => $term->name,
                'image' => get_field('image', $term)['url']
            ];

        }

        $return = [
            'Activities' => $Activities,
            'Cooperations' => $Cooperations,
            'Time' => round(microtime(true) - $start, 4)
        ];

        wp_cache_add('api_activities' , rest_ensure_response($return) );
        return rest_ensure_response($return);
    }
}


function attraction_activities_route(WP_REST_Request $request) {

    global $wpdb;

    $start = microtime(true);


    $posts = $wpdb->get_results("    
    SELECT P.ID, P.post_title, P.post_content, P.post_author,
	MAX(IF(PM.meta_key = 'attraction', PM.meta_value, NULL)) AS 'attraction',
    MAX(IF(PM.meta_key = 'old_price', PM.meta_value, 0)) AS 'old_price',
    MAX(IF(PM.meta_key = 'price', PM.meta_value, 0)) AS 'price',
    MAX(IF(PM.meta_key = 'location', PM.meta_value, NULL)) AS 'location',
    MAX(IF(PM.meta_key = 'subimage', PM.meta_value, NULL)) AS 'subimage',
    MAX(IF(PM.meta_key = 'child_price', PM.meta_value, 0)) AS 'child_price',
    MAX(IF(PM.meta_key = 'date', PM.meta_value, NULL)) AS 'date',
    MAX(IF(PM.meta_key = 'map', PM.meta_value, NULL)) AS 'map',
    COUNT(CM.comment_ID) as comments
FROM wp_posts AS P
LEFT JOIN wp_postmeta AS PM on PM.post_id = P.ID 
LEFT JOIN wp_comments AS CM on CM.comment_post_ID = P.ID 
WHERE P.post_type = 'product' and P.post_status = 'publish' 
GROUP BY  P.ID
ORDER BY P.ID ASC

");
    var_dump($posts);

    $lang = 'he';

    $day = date('Ymd');


    $today = date('Ymd');
    $activities = [];

    $Activities = [];
    $Cooperations = [];


    foreach ($posts as $post) {

        $post_id = $post->ID;
        //            var_dump($post_id);

        $oldprice = $post->old_price;
        $price = $post->price;

        $rating = 'default';
        //            $commentsRating = [];
        //            $comments = get_comments(array(
        //                'post_id' => $post->ID,
        //                'status' => 'approve'
        //            ));
        //
        //            if (count($comments) != 0) {
        //                foreach ($comments as $comment) {
        //                    $commentsRating[] = get_comment_meta($comment->comment_ID, 'rating', true);
        //                }
        //                $rating = round(array_sum($commentsRating) / count($commentsRating));
        //            }

        $tags = get_the_terms($post_id, 'activity_tags');
        $tagsNames = [];
        if ($tags) {
            foreach ($tags as $tag) {
                $tagsNames[] = $tag->name;
            }
        }

        //        echo "/*microtime Activities before attraction ".round(microtime(true) - $start, 4).' sek*/';
        $attraction = $post->attraction;


        $tagsAttractionNames = [];


        //            $location = get_field('location', $post_id);

        $location = $post->location;
        $loc = unserialize($post->location);
        if ($loc !== false){
            $location = $loc;

        }
        $image = $post->subimage;
        $guide = $post->location;

        //        echo "/*microtime Activities attraction ".round(microtime(true) - $start, 4).' sek*/';
        $categories = get_the_terms($post->ID, 'attraction_categories_new');
        $categoriesNames = [];
        if ($categories) {
            foreach ($categories as $category) {
                $categoriesNames[] = $category->name;
            }
        }
        $tags = get_the_terms($post->ID, 'attraction_tags_new');
        if ($tags) {
            foreach ($tags as $tag) {
                $tagsAttractionNames[] = $tag->name;
            }
        }
        //        echo "/*microtime Activities categories ".round(microtime(true) - $start, 4).' sek*/';
        $mainCategory = new WPSEO_Primary_Term('attraction_categories_new', $post->ID);
        $mainCategory = $mainCategory->get_primary_term();
        $mainCategory = get_term($mainCategory);
        $mainCategoryImage = get_field('map_icon', $mainCategory);

        if ($mainCategoryImage == '') {
            $mainCategoryImage = get_field('map_icon', $categories[0]);
        }

        if ($post->map) {
            $map = unserialize($post->map);
            $lat = $map['lat'];
            $lng = $map['lng'];
        } else {
            $lat = get_field('map', $attraction)['lat'];
            $lng = get_field('map', $attraction)['lng'];
        }

        $Activities[] = [
            'id' => $post->ID,
            'price' => $price,
            'oldprice' => $oldprice,
            'childPrice' => $post->child_price,
            'currency' => pll_translate_string('NIS', $lang),
            'rating' => $rating,
            'region' => $location,
            'subImage' => $image,
            'guide' => esc_js($guide),
            'attractionLink' => esc_js(get_permalink($attraction)),
            'title' => esc_js($post->post_title),
            'mainCategory' => $mainCategory->name,
            'mainCategoryIcon' => $mainCategoryImage,
            'link' => esc_js(get_permalink($post)),
            'excerpt' => esc_js(wp_trim_words(get_the_excerpt($post), 15, '...')),
            'image' => get_the_post_thumbnail_url($post, 'medium'),
            'lat' => $lat,
            'lng' => $lng,
            'tags' => json_encode($tagsNames),
            'tagsAttraction' => json_encode($tagsAttractionNames),
            'categories' => json_encode($categoriesNames),
            'comments' => $post->comments,
            'attractionId' => $attraction
        ];

    }

    foreach (get_field('cooperations_' . $lang, 'options') as $term) {

        $Cooperations[] = [
            'name' => $term->name,
            'image' => get_field('image', $term)['url']
        ];

    }

    $return = [
        'Activities' => $Activities,
        'Cooperations' => $Cooperations,
        'Time' => round(microtime(true) - $start, 4)
    ];
    //    global $wpdb;
    //    var_dump($wpdb->last_query);
    //        wp_cache_add('api_activities' , rest_ensure_response($return) );
    return rest_ensure_response($return);
    //    }
}


function get_tommorow_posts($orderby){
    $orderby ? $order = $orderby : $order = 'ID';
    $day = date('Ymd');
    $the_query = new WP_Query( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'orderby' => $order,
        //        'meta_query' => array(
        //            array(
        //                'key' => 'date',
        //                'value' => $day,
        //                'compare' => '>=',
        //                'type' => 'DATE'
        //            ))
    ));
    return $the_query->posts;
}




//attraction filters by wp_Query
function misha_filter_function(){
    $args = array(
        'orderby' => 'date', // we will sort posts by date
        'order'	=> $_POST['date'] // ASC or DESC
    );

    // for taxonomies / categories
    if( isset( $_POST['categoryfilter'] ) )
        $args['tax_query'] = array(
        array(
            'taxonomy' => 'product',
            'field' => 'id',
            'terms' => $_POST['categoryfilter']
        )
    );

    // create $args['meta_query'] array if one of the following fields is filled
    if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] || isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
        $args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true

    // if both minimum price and maximum price are specified we will use BETWEEN comparison
    if( isset( $_POST['price_min'] ) && $_POST['price_min'] && isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
        $args['meta_query'][] = array(
            'key' => 'price',
            'value' => array( $_POST['price_min'], $_POST['price_max'] ),
            'type' => 'numeric',
            'compare' => 'between'
        );
    } else {
        // if only min price is set
        if( isset( $_POST['price_min'] ) && $_POST['price_min'] )
            $args['meta_query'][] = array(
            'key' => 'price',
            'value' => $_POST['price_min'],
            'type' => 'numeric',
            'compare' => '>'
        );

        // if only max price is set
        if( isset( $_POST['price_max'] ) && $_POST['price_max'] )
            $args['meta_query'][] = array(
            'key' => 'price',
            'value' => $_POST['price_max'],
            'type' => 'numeric',
            'compare' => '<'
        );
    } 

    // if post thumbnail is set
    if( isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
        $args['meta_query'][] = array(
        'key' => '_thumbnail_id',
        'compare' => 'EXISTS'
    );

    $query = new WP_Query( $args );

    if( $query->have_posts() ) :
    while( $query->have_posts() ): $query->the_post();
    echo '<h2>' . $query->post->post_title . '</h2>';
    endwhile;
    wp_reset_postdata();
    else :
    echo 'No posts found';
    endif;

    die();
}


add_action('wp_ajax_myfilter', 'misha_filter_function'); 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');

//attraction filters by wp_Query END

$gravity_form_id = 16; // gravity form id, or replace {$gravity_form_id} below with this number
add_filter("gform_after_submission_{$gravity_form_id}", 'jdn_set_post_acf_gallery_field_post_update', 10, 2);

function jdn_set_post_acf_gallery_field_post_update($entry, $form)
{
    $gf_images_field_id = 12; // the upload field id
    //$acf_field_id = 'field_5a9692f6eb7e5'; // the acf gallery field id
    $acf_field_id = 'gallery'; // the acf gallery field id

    // get post

    if (isset($entry['post_id'])) {
        $post = get_post($entry['post_id']);
        if (is_null($post)) return;
    }
    else {
        return;
    }

    // Clean up images upload and create array for gallery field

    if (isset($entry[$gf_images_field_id])) {
        $images = stripslashes($entry[$gf_images_field_id]);
        $images = json_decode($images, true);
        if (!empty($images) && is_array($images)) {
            $gallery = array();
            foreach($images as $key => $value) {

                // NOTE: this is the other function you need: https://gist.github.com/joshuadavidnelson/164a0a0744f0693d5746

                if (function_exists('jdn_create_image_id')) $image_id = jdn_create_image_id($value, $post->ID);
                if ($image_id) {
                    $gallery[] = $image_id;
                }
            }
        }
    }

    // Update gallery field with array

    if (!empty($gallery)) {
        // var_dump($gallery);

        $old_gall = get_field('gallery', $post->ID);
        // var_dump($old_gall);
        if ($old_gall && count($old_gall) > 0) {

            foreach ($old_gall as $key) {
                $gallery[] = $key['id'];
            }


        }
        // var_dump($gallery);
        // var_dump($gallery);
        update_field($acf_field_id, $gallery, $post->ID);

        // Updating post
        wp_update_post($post);
    }
}


function jdn_create_image_id( $image_url, $parent_post_id = null ) {

    if( !isset( $image_url ) )
        return false;

    // Cache info on the wp uploads dir
    $wp_upload_dir = wp_upload_dir();
    // get the file path
    $path = parse_url( $image_url, PHP_URL_PATH );

    // File base name
    $file_base_name = basename( $image_url );

    // Full path
    if( site_url() != home_url() ) {
        $home_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
    } else {
        $home_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );
    }

    $home_path = untrailingslashit( $home_path );
    $uploaded_file_path = $home_path . $path;
    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( $file_base_name, null );

    // error check
    if( !empty( $filetype ) && is_array( $filetype ) ) {
        // Create attachment title
        $post_title = preg_replace( '/\.[^.]+$/', '', $file_base_name );

        // Prepare an array of post data for the attachment.
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename( $uploaded_file_path ), 
            'post_mime_type' => $filetype['type'],
            'post_title'     => esc_attr( $post_title ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Set the post parent id if there is one
        if( !is_null( $parent_post_id ) )
            $attachment['post_parent'] = $parent_post_id;
        // Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $uploaded_file_path );
        //Error check
        if( !is_wp_error( $attach_id ) ) {
            //Generate wp attachment meta data
            if( file_exists( ABSPATH . 'wp-admin/includes/image.php') && file_exists( ABSPATH . 'wp-admin/includes/media.php') ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                $attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );
            } // end if file exists check
        } // end if error check

        return $attach_id; 

    } else {
        return false;
    } // end if $$filetype
} // end function get_image_id



//woocommerce




remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
remove_action( 'woocommerce_after_single_product', 'bbloomer_custom_action', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );


remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
remove_action( 'pre_get_posts', 'bbloomer_remove_products_from_shop_page' );

add_action( 'woocommerce_after_single_product_summary', 'add_woo_sidebar' );
add_action( 'woocommerce_product_thumbnails', 'add_woo_right', 10 );




function add_woo_right() {
    include("inc/activity-right.php");
} 

function add_woo_sidebar() {
    $attraction = get_field('attraction');
    $location = get_field('map', $attraction);
    $categories = get_the_terms($attraction, 'attraction_categories');
    $mainCategory = new WPSEO_Primary_Term( 'attraction_categories', $attraction->ID );
    $mainCategory = $mainCategory->get_primary_term();
    $mainCategory = get_term( $mainCategory );
    $image = get_field('map_icon', $mainCategory);
    $user = get_field('user_map_icon', 'options');
    include("inc/activity-sidebar.php");    

}


add_action('get_footer', 'child_remove_mr_tailor_do_footer'); 
function child_remove_mr_tailor_do_footer() {
    if (is_product_category()) 
    {
        remove_action('mr_tailor_footer', 'mr_tailor_do_footer');
        remove_action('mr_tailor_footer', 'mr_tailor_footer_markup_open', 5);
        remove_action('mr_tailor_footer', 'mr_tailor_footer_markup_close', 15);
    }
}


function adding_custom_data_in_order_items_meta($cart_item_data, $product_id, $variation_id) {

    if (isset($_GET['tickets_amount_child'])) {
        $label = $_GET['tickets_amount_child'];
        $cart_item_data["quantity_child"] = $label;
        return $cart_item_data;
    }
}

add_action('woocommerce_add_cart_item_data', 'adding_custom_data_in_order_items_meta', 10, 3);



add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 20, 1);
function add_custom_price( $cart_obj ) {

        // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items
    foreach ( $cart_obj->get_cart() as $cart_item ) {

//        $productPrice =  get_post_meta($cart_item['product_id'],'_sale_price')[0] ? get_post_meta($cart_item['product_id'],'_sale_price')[0] : get_post_meta($cart_item['product_id'],'_regular_price')[0];

//         $cart_item['data']->set_price( $productPrice );

        $subtotal = change_item_subtotal(null,$cart_item, null );
        $quantity = $cart_item['quantity'];
        $product_price = $subtotal/$quantity;
        $cart_item['data']->set_price($product_price);
//        $cart_item['quantity'] = $quantity;


    }
 }

function getChildrenPrice($id) {
  $children_price = false;
    if (get_post_meta($id, 'cgwc-children-price')) {
        $children_price = get_post_meta($id, 'cgwc-children-price')[0];
    } elseif ( get_the_terms( $id, 'child_price') ) {
        $children_price =  get_post_meta($id, 'child_price')[0];
    }

    return $children_price;
}


add_filter( 'woocommerce_cart_item_subtotal', 'change_item_subtotal', 10, 3);
function change_item_subtotal( $subtotal, $cart_item = null, $cart_item_key = null) {
    if ($cart_item) {
        $children_price = getChildrenPrice($cart_item['product_id']);
//        $children_price = get_post_meta($cart_item['product_id'], 'child_price')[0];
        $productPrice = get_post_meta($cart_item['product_id'], '_sale_price')[0] ? get_post_meta($cart_item['product_id'], '_sale_price')[0] : get_post_meta($cart_item['product_id'], '_regular_price')[0];

        $subtotal = $cart_item['quantity_child'] * $children_price + $cart_item['quantity'] * $productPrice;
    }
    return $subtotal;
}


#add_action('woocommerce_cart_subtotal', 'calculate_totals', 10, 1);

function calculate_totals( $cart ){
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    foreach ( $cart->get_cart() as $key => $cart_item ) {
        $subtotal = change_item_subtotal(null,$cart_item, null );
        $quantity = $cart_item['quantity_child'] +  $cart_item['quantity'];
        $product_price = $subtotal/$quantity;
        $cart_item['data']->set_price($product_price);
        $cart_item['quantity'] = $quantity;
    }

//your code
}

function cfwc_create_custom_field() {
    $args = array(
        'id' => 'cgwc-children-price',
        'label' => __( 'Children price', 'cfwc' ),
        'class' => 'cfwc-custom-field',
        'desc_tip' => true,
        'description' => __( 'Enter children prcie.', 'ctwc' ),
    );
    $args2 = array(
        'id' => 'cgwc-children-sale-price',
        'label' => __( 'Children sale price', 'cfwc' ),
        'class' => 'cfwc-custom-field',
        'desc_tip' => true,
        'description' => __( 'Enter children sale prcie.', 'ctwc' ),
    );
    woocommerce_wp_text_input( $args);
    woocommerce_wp_text_input( $args2 );
}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field' );


/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_custom_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $title = isset( $_POST['cgwc-children-price'] ) ? $_POST['cgwc-children-price'] : '';
    $product->update_meta_data( 'cgwc-children-price', sanitize_text_field( $title ) );
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );

/**
 * Add the text field as item data to the cart object
 * @since 1.0.0
 * @param Array 		$cart_item_data Cart item meta data.
 * @param Integer   $product_id     Product ID.
 * @param Integer   $variation_id   Variation ID.
 * @param Boolean  	$quantity   		Quantity
 */
function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
    if( ! empty( $_POST['cgwc-children-price'] ) ) {
        // Add the item data
        $cart_item_data['cgwc-children-price'] = $_POST['cgwc-children-price'];
    }
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'cfwc_add_custom_field_item_data', 10, 4 );

//?quantity=1&add-to-cart=6031&custom_options=מבוגר&tickets_amount_child=1&child_price=&submit=