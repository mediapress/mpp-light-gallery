<?php

/**
 * View class that actually renders /loads various templates
 *
 * Class MPP_Activity_Light_Gallery_Grid_View
 */
class MPP_Light_Gallery_View extends MPP_Gallery_View {

	private static $instance = null;

	protected function __construct() {

		parent::__construct();
		//unique id of the view
		$this->id   = 'light-gallery';
		$this->name = __( 'Light Gallery Grid', 'mpp-light-gallery' );
		//supported views
		$this->supported_views      = array( 'activity', 'gallery' );
		$this->supported_components = array( 'sitewide', 'groups', 'members' );
	}

	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function activity_display( $media_ids = array() ) {

		if ( empty( $media_ids ) ) {
			return;
		}

		mpp_get_template( 'buddypress/activity/views/light-gallery-photo.php', array(), mpp_light_gallery()->get_path() . 'templates' );
	}

	/**
	 * Single Gallery View
	 *
	 * @param type $gallery
	 */
	public function display( $gallery ) {

		if ( ! $gallery ) {
			return;
		}

		$gallery = mpp_get_gallery( $gallery );

		if ( ! $gallery || empty( $gallery->id ) ) {
			return;
		}

		//overridden template
		mpp_get_template( 'buddypress/gallery/views/light-gallery-photo.php', array(), mpp_light_gallery()->get_path() . 'templates' );
	}

}