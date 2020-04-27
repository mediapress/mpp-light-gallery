<?php
/**
 * Filters for plugin
 *
 * @package mpp-light-gallery
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Override shortcode template
 *
 * @param string $template Template path.
 * @param array  $atts Shortcode attributes.
 * @param string $view View for shortcode.
 *
 * @return string
 */
function mpp_light_gallery_override_shortcode_template( $template, $atts, $view ) {

	if ( 'light-gallery' != $view ) {
		return $template;
	}

	return mpp_locate_template( array( 'shortcodes/grid-photo.php' ), false, mpp_light_gallery()->get_path() . 'templates' );
}
add_filter( 'mpp_shortcode_list_media_located_template', 'mpp_light_gallery_override_shortcode_template', 10, 3 );
