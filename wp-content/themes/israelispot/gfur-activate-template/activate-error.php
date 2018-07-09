<?php

global $gw_activate_template;

$result = $gw_activate_template->result;

// if the blog is already active or if the blog is taken, display respective messages
if ( $gw_activate_template->is_blog_already_active( $result ) || $gw_activate_template->is_blog_taken( $result ) ):
    $signup = $result->get_error_data();
    ?>

    <h1 class="home-header__title home-header__title_404"><?php _e('Your account is now active!'); ?></h1>

<?php else: ?>

    <h1 class="home-header__title home-header__title_404"><?php _e('An error occurred during the activation'); ?></h1>

<?php endif; ?>