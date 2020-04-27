<?php
/**
 * File contains actions functions
 *
 * @package mpp-light-gallery
 */

// If file access directly code will exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Light gallery view on gallery
 */
function mpp_light_gallery_ajax_handler() {

	$gallery_id = absint( $_GET['gallery_id'] );

	$gallery = mpp_get_gallery( $gallery_id );

	if ( is_null( $gallery_id ) || ! mpp_is_valid_gallery( $gallery_id ) ) {
		exit( 0 );
	}

	$media_ids = mpp_get_all_media_ids( array(
		'gallery_id'   => $gallery->id,
		'component'    => $gallery->component,
		'component_id' => $gallery->component_id,
	) );

	$statuses = (array) mpp_get_accessible_statuses( $gallery->component, $gallery->component_id, get_current_user_id() );

	$data    = array();
	$user_id = get_current_user_id();

	foreach ( $media_ids as $media_id ) {

		if ( ! in_array( mpp_get_media_status( $media_id ), $statuses ) || ! mpp_user_can_view_media( $media_id, $user_id ) ) {
			continue;
		}

		$info = array(
			'id'      => $media_id,
			'src'     => mpp_get_media_src( 'photo', $media_id ),
			'thumb'   => mpp_get_media_src( 'thumbnail', $media_id ),
			'subHtml' => '<h4>' . mpp_get_media_title( $media_id ) . '</h4><p>' . mpp_get_media_description( $media_id ) . '</p>',
		);

		$data[] = $info;

	}

	echo json_encode( $data );

	exit;
}

add_action( 'wp_ajax_mpp_light_gallery_get_media', 'mpp_light_gallery_ajax_handler' );
add_action( 'wp_ajax_nopriv_mpp_light_gallery_get_media', 'mpp_light_gallery_ajax_handler' );

/**
 * Gallery view on activity.
 */
function mpp_light_activity_ajax_handler() {

	$activity_id = absint( $_GET['activity_id'] );

	if ( ! $activity_id ) {
		exit( 0 );
	}

	$media_ids = mpp_activity_get_attached_media_ids( $activity_id );

	$data    = array();
	$user_id = get_current_user_id();

	foreach ( $media_ids as $media_id ) {

		if ( ! mpp_user_can_view_media( $media_id, $user_id ) ) {
			continue;
		}

		$info = array(
			'src'     => mpp_get_media_src( 'photo', $media_id ),
			'thumb'   => mpp_get_media_src( 'thumbnail', $media_id ),
			'subHtml' => '<h4>' . mpp_get_media_title( $media_id ) . '</h4><p>' . mpp_get_media_description( $media_id ) . '</p>',
		);

		$data[] = $info;

	}

	echo json_encode( $data );

	exit;
}

add_action( 'wp_ajax_mpp_light_activity_get_media', 'mpp_light_activity_ajax_handler' );
add_action( 'wp_ajax_nopriv_mpp_light_activity_get_media', 'mpp_light_activity_ajax_handler' );


/**
 * Gallery view on activity.
 */
function mpp_light_gallery_shortcode_ajax_handler() {
	$gallery_ids = array_map( 'absint', explode( ',', $_GET['galleries'] ) );

	if ( ! $gallery_ids ) {
		exit( 0 );
	}

	$media_ids = array();

	foreach ( $gallery_ids as $gallery_id ) {
		$gallery = mpp_get_gallery( $gallery_id );
		$media_ids = array_merge( $media_ids, mpp_get_all_media_ids( array( 'gallery_id' => $gallery_id, 'component' => $gallery->component, 'component_id' => $gallery->component_id ) ) );
	}

	$data    = array();
	$user_id = get_current_user_id();

	array_unique( $media_ids );
	foreach ( $media_ids as $media_id ) {

		if ( ! mpp_user_can_view_media( $media_id, $user_id ) ) {
			continue;
		}

		$info = array(
			'src'     => mpp_get_media_src( 'photo', $media_id ),
			'thumb'   => mpp_get_media_src( 'thumbnail', $media_id ),
			'subHtml' => '<h4>' . mpp_get_media_title( $media_id ) . '</h4><p>' . mpp_get_media_description( $media_id ) . '</p>',
		);

		$data[] = $info;

	}

	echo json_encode( $data );

	exit;
}

add_action( 'wp_ajax_mpp_light_gallery_shortcode_get_media', 'mpp_light_gallery_shortcode_ajax_handler' );
add_action( 'wp_ajax_nopriv_mpp_light_gallery_shortcode_get_media', 'mpp_light_gallery_shortcode_ajax_handler' );
