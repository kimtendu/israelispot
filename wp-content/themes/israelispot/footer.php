<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Israelispot
 */
if(get_locale() == 'en_GB') {
    $lang = 'en';
} else {
    $lang = 'he';
};
?>
<footer>
	<div class="wrap wrap_footer">
		<section class="copyright-container">
			<span class="copyright"><?php pll_e('All rights reserved 2017 &copy'); ?></span>
			<i class="fa fa-circle" aria-hidden="true"></i>
			<span class="footer-phone"><?php pll_e('Phone')?>: <a href="tel:<?php the_field('phone', 'options'); ?>"><?php the_field('phone', 'options'); ?></a></span>
			<i class="fa fa-circle" aria-hidden="true"></i>
			<a target="_blank" href="<?php the_field('footer_link', 'options'); ?>" class="footer-link"><?php pll_e('Footer link'); ?></a>
		</section>
		<?php  wp_nav_menu( array(
			'theme_location' => 'ternary',
			'container'     => '',
			'container_class'   => '',
			'menu_class'        => 'footer__list',
			'echo'          => true,
			'depth'         => 1,
			'walker'         => new Third_Menu(),
		) );?>
	</div>
</footer>

<div class="fb-like" data-href="https://www.facebook.com/israelispot/" data-layout="box_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>

<?php wp_footer(); ?>

</body>
</html>
