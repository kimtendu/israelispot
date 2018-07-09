<?php
/* Prepare the logged in message. */
if ( rgblank( $logged_in_message ) ) {
$logged_in_message = sprintf(
esc_html__( pll__('You are currently logged in as').' %s%s%s. %s'.pll__('Log out?').'%s', 'gravityformsuserregistration' ),
'<strong>', $current_user->display_name, '</strong>',
'<a href="' . wp_logout_url( $logout_redirect ) . '">', '</a>'
);
} else {
$logged_in_message = str_replace( '{logout_url}', '<a href="' . esc_attr( wp_logout_url( $logout_redirect ) ) . '" title="' . esc_attr__( 'Logout', 'gravityformsuserregistration' ) . '">' . esc_html__( 'Logout', 'gravityformsuserregistration' ) . '</a>', $logged_in_message );
$logged_in_message = GFCommon::replace_variables( $logged_in_message, array(), array(), false, false, false, 'text' );
}

/* Display the avatar and logged in message. */
$html  = '<p>';
    $html .= filter_var( $logged_in_avatar, FILTER_VALIDATE_BOOLEAN ) ? get_avatar( $current_user->ID ) . '<br />' : null;
    $html .= $logged_in_message;
    $html .= '</p>';

/* Display links. */
if ( ! empty( $logged_in_links ) ) {

foreach ( $logged_in_links as $link ) {

$link['url']  = str_replace( '{logout_url}', esc_attr( wp_logout_url( $logout_redirect ) ), $link['url'] );
$link['url']  = GFCommon::replace_variables( $link['url'], array(), array(), false, false, false, 'text' );
$html        .= '<a href="' . esc_attr( $link['url'] ) . '" title="' . esc_attr( $link['text'] ) . '">' . esc_html( $link['text'] ) . '</a><br />';

}

}

echo $html;