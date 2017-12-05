<?php
/**
 * Plugin functions file
 *
 * @package mpp-light-gallery
 */

// If file access directly code will exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if view active on gallery cover
 *
 * @return bool
 */
function mpp_light_gallery_enabled_for_gallery_cover() {

	$is_enabled = mpp_get_option( 'light_gallery_enable_on_cover_click' );

	if ( $is_enabled ) {
		return true;
	}

	return false;
}

