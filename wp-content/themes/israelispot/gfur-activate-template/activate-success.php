<?php

global $gw_activate_template;

extract( $gw_activate_template->result );

$url = is_multisite() ? get_blogaddress_by_id( (int) $blog_id ) : home_url('', 'http');
$user = new WP_User( (int) $user_id );
?>

<h1 class="home-header__title home-header__title_404"><?php pll_e('Your account is now active!'); ?></h1>
<h2 class="home-header__subtitle home-header__subtitle_404"><?php pll_e('You can'); ?> <a class="activation-link" href="#login"><?php pll_e('Login') ?></a> <?php pll_e('with details:')?></h2>
<h2 class="home-header__subtitle home-header__subtitle_404"><?php _e('Username:'); ?> <?php echo $user->user_login; ?></h2>
<h2 class="home-header__subtitle home-header__subtitle_404"><?php _e('Password:'); ?> <?php echo $password; ?></h2>