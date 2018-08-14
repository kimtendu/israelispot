<?php
if(get_locale() == 'en_GB') {
	$lang = 'en';
} else {
	$lang = 'he';
};
$ID = get_the_ID();

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/he_IL/sdk.js#xfbml=1&version=v3.1&appId=197512843706590&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</head>

<body <?php body_class(); ?>>
<header class="<?php if (is_front_page()) echo 'home'; ?> <?php if (!is_front_page() && !is_404()) echo 'not-home'; ?> <?php if (get_page_template_slug() == 'page-search-js.php') echo 'search'; ?>">
	<div class="wrap wrap_big">
		<section class="s-one">
			<?php
			$page = get_pages(
				array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'page-create-activity.php'
				)
			);
			?>
            <div>
                <button class="menu__mobile-button">
                    <svg viewBox="0 0 17 10" class="menu_mobile-icon">
                        <title><?php pll_e('Mobile menu switcher'); ?></title>
                        <path class="st0" d="M17,2H0V0h17V2z"/>
                        <path class="st0" d="M17,6H0V4h17V6z"/>
                        <path class="st0" d="M17,10H0V8h17V10z"/>
                    </svg>
                </button>
                <a href="<?php echo $page[0]->guid; ?>" title="<?php pll_e('Add activity'); ?>" class="add-activity"><span>+</span><?php pll_e('Add activity'); ?></a>
                <?php  wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'     => '',
                    'container_class'   => '',
                    'menu_class'        => 'home-menu__list',
                    'echo'          => true,
                    'depth'         => 2,
                    'walker'         => new Primary_Menu(),
                ) );?>
                <button class="menu__black-button">
                    <svg viewBox="0 0 17 10" class="menu_mobile-icon">
                        <title><?php pll_e('Mobile menu switcher'); ?></title>
                        <path class="st0" d="M17,2H0V0h17V2z"/>
                        <path class="st0" d="M17,6H0V4h17V6z"/>
                        <path class="st0" d="M17,10H0V8h17V10z"/>
                    </svg>
                </button>
            </div>
            <?php if(get_page_template_slug($ID) != 'page-search-js.php') : ?>
                <section class="home-second-menu">
                    <ul class="home-second-menu__list">
                        <li class="home-second-menu__item home-second-menu__item_title">
                            <span class="home-second-menu__link"><?php pll_e('Our Partners'); ?></span>
                        </li>
                        <?php
                        $page = get_pages(
                            array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'page-search-js.php'
                            )
                        );
						
                        foreach (get_field('cooperations_'.$lang, 'options') as $term): ?>							
                            <li class="home-second-menu__item home-second-menu__item_cop">
                                <a href="<?php echo get_permalink($page[0]).'?cooperation='.$term->name; ?>" class="home-second-menu__link"><?php echo $term->name; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <span class="home-second-menu__star"><i class="fa fa-star" aria-hidden="true"></i></span>
                </section>
            <?php endif; ?>
		</section>
		<section class="s-two">
			<button class="search-button" title="<?php pll_e('Open search'); ?>">
				<i class="fa fa-search" aria-hidden="true"></i>
				<?php pll_e('What are you looking for?'); ?>
			</button>

			<a href="/" title="<?php pll_e('Link to homepage'); ?>" class="logo-link">
				<?php if (!is_front_page() && !is_404()) : ?>
					<img src="<?php echo get_template_directory_uri().'/img/logo_black_'.$lang.'.png'; ?>"
						 title="<?php pll_e('Site logotype'); ?>"
						 alt="<?php pll_e('Site logotype'); ?>">
				<?php else : ?>
					<img src="<?php echo get_template_directory_uri().'/img/logo_'.$lang.'.png'; ?>"
						 title="<?php pll_e('Site logotype'); ?>"
						 alt="<?php pll_e('Site logotype'); ?>">
				<?php endif; ?>
			</a>
		</section>
	</div>
</header>
<section id="search-modal" class="modal search-modal">
	<button type="button"
			tabindex="-1"
			title="<?php pll_e('Close search modal window'); ?>"
			alt="<?php pll_e('Close search modal window'); ?>"
			class="search-modal__close">
		<svg viewBox="0 0 17 10">
			<path class="st0" d="M17,2H0V0h17V2z"/>
			<path class="st0" d="M17,10H0V8h17V10z"/>
		</svg>
	</button>
	<form action="<?php echo esc_url( home_url( '/'.$lang ) ); ?>" method="get">
		<input name="s"
			   tabindex="-1"
			   title="<?php pll_e('Search input'); ?>"
			   alt="<?php pll_e('Search input'); ?>"
			   class="search-modal__input"
			   type="search"
			   value="<?php if(!empty($_GET['s'])){echo $_GET['s'];} ?>"
			   placeholder="<?php pll_e('Search title');?>" />
		<button type="submit"
				tabindex="-1"
				title="<?php pll_e('Search submit'); ?>"
				alt="<?php pll_e('Search submit'); ?>"
				class="search-modal__submit">
			<?php pll_e('Search');?>
		</button>
	</form>
</section>
<div id="login-modal" class="modal login-modal">
    <section class="login-modal__container">
        <div class="login-modal__control">
            <h1 class="login-modal__title"><?php pll_e('Sign In'); ?></h1>
            <button id="login-modal__close" class="login-modal__close"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <section class="login-modal__main">
            <div class="login-modal__tabs">
                <button class="login-modal__button login-modal__button_login active"><?php pll_e('Log In'); ?></button>
                <?php if(!is_user_logged_in()) : ?>
                <button class="login-modal__button login-modal__button_register"><?php pll_e('Register'); ?></button>
                <?php endif; ?>
            </div>
            <section class="login-modal__login active">
                <?php
                if($lang == 'he') {
                    echo do_shortcode('[do_widget id=gform_login_widget-4 lang="he"]');
                }else {
                    echo do_shortcode('[do_widget id=gform_login_widget-2 lang="en"]');
                }
                ?>
            </section>
            <?php if(!is_user_logged_in()) : ?>
            <section class="login-modal__register">
                <?php
                if($lang == 'he') {
                    echo do_shortcode('[gravityform id="7" title="false" description="false" ajax="true" tabindex="0"]');
                }else {
                    echo do_shortcode('[gravityform id="6" title="false" description="false" ajax="true" tabindex="0"]');
                }
                ?>
            </section>
            <?php endif; ?>
        </section>
    </section>
</div>