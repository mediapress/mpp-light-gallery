<?php
/**
 * Class register new MediaPress photo view
 *
 * @package mpp-light-gallery
 */


// If access directly over web code will exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPP_Light_Gallery_View
 */
class MPP_Light_Gallery_View extends MPP_Gallery_View {

	/**
	 * Class instance
	 *
	 * @var MPP_Light_Gallery_View
	 */
	private static $instance = null;

	/**
	 * MPP_Light_Gallery_View constructor.
	 */
	protected function __construct() {

		parent::__construct();

		$this->id                   = 'light-gallery';
		$this->name                 = __( 'Light Gallery Grid', 'mpp-light-gallery' );
		$this->supported_views      = array( 'activity', 'gallery' );
		$this->supported_components = array( 'sitewide', 'groups', 'members' );
	}

	/**
	 * Get class instance
	 *
	 * @return MPP_Light_Gallery_View
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Activity display
	 *
	 * @param array $media_ids List of media ids.
	 * @param int $activity_id Activity id.
	 */
	public function activity_display( $media_ids = array(), $activity_id = 0 ) {

		if ( empty( $media_ids ) ) {
			return;
		}

		mpp_get_template( 'buddypress/activity/views/light-gallery-photo.php', array(), mpp_light_gallery()->get_path() . 'templates' );
	}

	/**
	 * Single gallery display
	 *
	 * @param MPP_Gallery $gallery Gallery object.
	 */
	public function display( $gallery ) {

		if ( ! $gallery ) {
			return;
		}

		$gallery = mpp_get_gallery( $gallery );

		if ( ! $gallery || empty( $gallery->id ) ) {
			return;
		}

		mpp_get_template( 'buddypress/gallery/views/light-gallery-photo.php', array(), mpp_light_gallery()->get_path() . 'templates' );
	}
}
