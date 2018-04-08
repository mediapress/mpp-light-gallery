<?php
/**
 * Plugin admin settings
 *
 * @package mpp-light-gallery
 */

// If file access directly code will exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPP_Light_Gallery_Admin
 */
class MPP_Light_Gallery_Admin {

	/**
	 * Class instance
	 *
	 * @var MPP_Light_Gallery_Admin
	 */
	private static $instance = null;

	/**
	 * The constructor.
	 */
	private function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Get the instance
	 *
	 * @return MPP_Light_Gallery_Admin
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Callback to register new settings.
	 */
	private function setup_hooks() {
		add_action( 'mpp_admin_register_settings', array( $this, 'register_settings' ) );
	}

	/**
	 * Register plugin admin settings
	 *
	 * @param MPP_Admin_Settings_Page $page Admin settings page.
	 */
	public function register_settings( $page ) {

		$panel = $page->get_panel( 'theming' );

		$section = $panel->add_section( 'theme-light-gallery-settings', __( 'Light Gallery Settings', 'mpp-light-gallery' ) );

		$fields = array(
			array(
				'name'    => 'light_gallery_on_gallery_cover',
				'label'   => __( 'Enable on Gallery Cover Click?', 'mpp-light-gallery' ),
				'type'    => 'radio',
				'default' => 1,
				'options' => array(
					1 => __( 'Yes', 'mpp-light-gallery' ),
					0 => __( 'No', 'mpp-light-gallery' ),
				),
			),
			array(
				'name'    => 'light_gallery_on_single_gallery',
				'label'   => __( 'Enable on single gallery page', 'mpp-light-gallery' ),
				'type'    => 'radio',
				'default' => 1,
				'options' => array(
					1 => __( 'Yes', 'mpp-light-gallery' ),
					0 => __( 'No', 'mpp-light-gallery' ),
				),
			),
			array(
				'name'    => 'light_gallery_in_activity',
				'label'   => __( 'Enable in activity', 'mpp-light-gallery' ),
				'type'    => 'radio',
				'default' => 1,
				'options' => array(
					1 => __( 'Yes', 'mpp-light-gallery' ),
					0 => __( 'No', 'mpp-light-gallery' ),
				),
			),
		);

		$section->add_fields( $fields );
	}
}

MPP_Light_Gallery_Admin::get_instance();
