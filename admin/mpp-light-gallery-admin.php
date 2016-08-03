<?php

class MPP_Light_Gallery_Admin {

	private static $instance = null;

	private function __construct() {
		//setup hooks
		$this->setup_hooks();
	}


	/**
	 *
	 * @return MPP_Light_Gallery_Admin
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	private function setup_hooks() {
		//register admin settings fields
		add_action( 'mpp_admin_register_settings', array( $this, 'register_settings' ) );
	}

	/**
	 *
	 * @param MPP_Admin_Settings_Page $page
	 */
	public function register_settings( $page ) {

		//$page = mpp_admin()->get_page();

		$panel = $page->get_panel( 'theming' );


		$panel->add_section( 'theme-light-gallery-settings', __( 'Light Gallery Settings', 'mpp-light-gallery' ) )
		      ->add_field( array(
			      'name'    => 'light_gallery_enable_on_cover_click',
			      'label'   => __( 'Enable on Gallery Cover Click?', 'mpp-light-gallery' ),
			      'type'    => 'radio',
			      'default' => 1,
			      'options' => array(
				      1 => __( 'Yes', 'mpp-light-gallery' ),
				      0 => __( 'No', 'mpp-light-gallery' ),
			      )
		      ) );
	}
}

MPP_Light_Gallery_Admin::get_instance();