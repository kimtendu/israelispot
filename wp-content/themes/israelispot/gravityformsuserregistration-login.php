<?php

extract( $args );

/* Get the login form. */
$form = GF_User_Registration::login_form_object();

/* Set the tab index. */
GFCommon::$tab_index = gf_apply_filters( array( 'gform_tabindex', $form['id'] ), $tabindex, $form );

/* Enqueue needed scripts. */
GFFormDisplay::enqueue_form_scripts( $form, false );

/* Prepare the form wrapper class. */
$wrapper_css_class = GFCommon::get_browser_class() . ' gform_wrapper';

/* Ensure login redirect URL isn't empty. */
if ( rgblank( $login_redirect ) ) {
    $login_redirect = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/* Open Gravity Form wrapper and form tag. */
$html  = "<div class='{$wrapper_css_class} gf_login_form' id='gform_wrapper_{$form['id']}'>";
$html .= "<form method='post' id='gform_{$form['id']}'>";
$html .= "<input type='hidden' name='login_redirect' value='" . esc_attr( sanitize_text_field( $login_redirect ) ) . "' />";

// Convert display title and description to boolean valudes.
$display_title       = filter_var( $display_title, FILTER_VALIDATE_BOOLEAN );
$display_description = filter_var( $display_description, FILTER_VALIDATE_BOOLEAN );

/* Insert form heading if needed. */
if ( $display_title || $display_description ) {
    $html .= "<div class='gform_heading'>";
    $html .= $display_title ? "<h3 class='gform_title'>" . esc_html( $form['title'] ) . "</h3>" : "";
    $html .= $display_description ? "<span class='gform_description'>" . esc_html( $form['description'] ) . "</span>" : "";
    $html .= "</div>";
}

/* Insert form body. */
$html .= "<div class='gform_body'>";
$html .= "<ul id='gform_fields_login' class='gform_fields top_label'>";
echo $html;

foreach ( $form['fields'] as $field ) {
    $field_value = GFFormsModel::get_field_value( $field );
    $field_html  = GFFormDisplay::get_field( $field, $field_value );
    $field_html  = str_replace( "<span class='gfield_required'>*</span>", '', $field_html );
    $field_html  = str_replace( "Username", pll__('Username'), $field_html );
    $field_html  = str_replace( "Password", pll__('Password'), $field_html );
    $field_html  = str_replace( "Remember Me", pll__('Remember Me'), $field_html );
    $html       .= $field_html;
}
$html .= '</ul>';
$html .= GFFormDisplay::gform_footer( $form, 'gform_footer top_label', false, array(), '', false, false, 0 );
$html  = str_replace( "Login", pll__('Login'), $html );
$html .= '</div>';

/* Close Gravity Form wrapper and form tag. */
$html .= '</form>';
$html .= '</div>';

/* Display links. */
if ( ! empty( $logged_out_links ) ) {

    if ( GF_User_Registration::get_plugin_setting( 'custom_registration_page_enable' ) == '1' ) {
        $registration_page = GF_User_Registration::get_plugin_setting( 'custom_registration_page' );
        $register_url      = 'gf_custom' === $registration_page ? GF_User_Registration::get_plugin_setting( 'custom_registration_page_custom' ) : get_permalink( $registration_page );
    } else {
        $register_url = wp_registration_url();
    }

    $html .= '<nav class="gf_login_links">';

    foreach ( $logged_out_links as $link ) {

        $link['url']  = str_replace( '{register_url}', esc_attr( $register_url ), $link['url'] );
        $link['url']  = str_replace( '{password_url}', esc_attr( wp_lostpassword_url() ), $link['url'] );
        $html        .= '<a href="' . esc_attr( $link['url'] ) . '" title="' . esc_attr( $link['text'] ) . '">' . esc_html( $link['text'] ) . '</a><br />';

    }

    $html .= '</nav>';

}

echo $html;