<?php

register_nav_menus( array(
    'primary' => __( 'Primary Navigation', 'soreq' ),
    'secondary' => __('Secondary Navigation', 'soreq'),
    'ternary' => __('Ternary Navigation', 'soreq')
) );

//Primary
class Primary_Menu extends Walker_Nav_Menu {

    // add classes to ul sub-menus
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            ( $display_depth < 2  ? 'submenu__list' : ''),
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'menu-lvl3__list' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }

    // add main/sub classes to li's and links
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        // depth dependent classes
        $menu_item_class = 'home-menu__item';
        $sub_menu_item_class = 'submenu__item';
        $menu_lvl3_item_class = 'menu-lvl3__item';
        $current_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
        $depth_classes = array(
            ( $depth == 0 ? $menu_item_class : '' ),
            ( $depth == 1 ? $sub_menu_item_class : '' ),
            ( $depth >=2 ? $menu_lvl3_item_class : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),

            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        if($depth == 0) {
            $attributes .= ' class="home-menu__link"';
        } else if($depth == 1){
            $attributes .= ' class="submenu__link"';
        } else {
            $attributes .= ' class="menu-lvl3__link"';
        }

        $has_children = strpos($class_names, 'menu-item-has-children');
        if(get_locale() == 'en_GB') {
            $direction = 'right';
        } else {
            $direction = 'left';
        };

        if($depth == 0) {
            if ($has_children) {

                $item_output = sprintf('%1$s<section class="menu__container"><a%2$s>%3$s%4$s%5$s<i class="fa fa-caret-down" aria-hidden="true"></i></a><button class="menu__arrow"></button></section>%6$s',
                    $args->before,
                    $attributes,
                    $args->link_before,
                    apply_filters('the_title', $item->title, $item->ID),
                    $args->link_after,
                    $args->after
                );
            } else {
                $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                    $args->before,
                    $attributes,
                    $args->link_before,
                    apply_filters('the_title', $item->title, $item->ID),
                    $args->link_after,
                    $args->after
                );
            }
        } else if($depth == 1) {
            if ($has_children) {
                $item_output = sprintf('%1$s<section class="submenu__container"><a%2$s>%3$s%4$s%5$s<i class="fa fa-caret-'.$direction.'" aria-hidden="true"></i></a><button class="submenu__arrow"></button></section>%6$s',
                    $args->before,
                    $attributes,
                    $args->link_before,
                    apply_filters('the_title', $item->title, $item->ID),
                    $args->link_after,
                    $args->after
                );
            } else {
                $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                    $args->before,
                    $attributes,
                    $args->link_before,
                    apply_filters('the_title', $item->title, $item->ID),
                    $args->link_after,
                    $args->after
                );
            }
        } else {
            $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                $args->before,
                $attributes,
                $args->link_before,
                apply_filters('the_title', $item->title, $item->ID),
                $args->link_after,
                $args->after
            );
        }
        // build html
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

//secondary
class Second_Menu extends Walker_Nav_Menu {

    // add classes to ul sub-menus
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }

    // add main/sub classes to li's and links
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'home-second-menu__item' : 'home-second-menu__item' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= 'class="home-second-menu__link"';

        $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters('the_title', $item->title, $item->ID),
            $args->link_after,
            $args->after
        );
        // build html
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

//ternary
class Third_Menu extends Walker_Nav_Menu {

    // add classes to ul sub-menus
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );

        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }

    // add main/sub classes to li's and links
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'footer__item' : 'footer__item' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= 'class="footer__link"';

        $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters('the_title', $item->title, $item->ID),
            $args->link_after,
            $args->after
        );
        // build html
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}