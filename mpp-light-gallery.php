<?php

/**
 * Plugin Name: MediaPress - Light Gallery View
 * Version: 1.0.0
 * Author: Ravi Sharma( BuddyDev )
 * Author URI: http://buddydev.com
 * Description: List Photo Media with Light Gallery View
 * License: GPL2 or Above
 */
class MPP_Light_Gallery_Photo_Helper {

	/**
	 * @var MPP_Light_Gallery_Photo_Helper
	 */
	private static $instance = null;

	/**
	 * @var string plugin directory path
	 */
	private $path;

	/**
	 * @var string plugin directory url
	 */
	private $url;

	private function __construct() {

		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		$this->setup();

	}

	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup Hooks
	 */
	public function setup() {

		//load require plugins files
		add_action( 'mpp_loaded', array( $this, 'load' ) );
		// register custom light gallery view
		add_action( 'mpp_setup_core', array( $this, 'register_views' ) );
		// load translations at initialization time
		add_action( 'mpp_init', array( $this, 'load_text_domain' ) );
		// load assets
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Load require files
	 */
	public function load() {

		$files = array(
			'core/class-mpp-light-gallery-view.php',
			'core/mpp-light-gallery-actions.php',
			'core/mpp-light-gallery-functions.php'
		);

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$files[] = 'admin/mpp-light-gallery-admin.php';
		}

		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}
	}

	/**
	 * Load plugin script and style
	 */
	public function load_assets() {

		if ( ! $this->maybe_load_assets() ) {
			return;
		}

		//register required css for the gallery
		wp_register_style( 'lightgallery-style', $this->url . 'assets/css/lightgallery.min.css' );
		wp_register_style( 'mpp-light-gallery-style', $this->url . 'assets/css/mpp-light-gallery-style.css', array( 'lightgallery-style' ) );

		//register js files
		wp_register_script( 'lightgallery', $this->url . 'assets/js/lightgallery-all.min.js', array( 'jquery' ) );
		wp_register_script( 'lg-mousewheel', $this->url . 'assets/js/jquery.mousewheel.min.js', array( 'lightgallery' ) );
		wp_register_script( 'mpp-light-gallery-script', $this->url . 'assets/js/mpp-light-gallery.js', array( 'lg-mousewheel' ) );

		//We need the url for handling ajax requests
		$data = array( 'url' => admin_url( 'admin-ajax.php' ), );

		wp_localize_script( 'mpp-light-gallery-script', 'MPP_Light_Gallery', $data );

		wp_enqueue_style( 'mpp-light-gallery-style' );

		wp_enqueue_script( 'mpp-light-gallery-script' );
	}

	public function register_views() {
		mpp_register_gallery_view( 'photo', MPP_Light_Gallery_View::get_instance() );
	}

	public function load_text_domain() {
		load_plugin_textdomain( 'mpp-light-gallery', false, $this->path . 'languages/' );
	}

	/**
	 *
	 * @todo improve it
	 * @return boolean true to load false to not
	 */
	public function maybe_load_assets() {
		//if MediaPress is not active, do not proceed
		if ( ! function_exists( 'mediapress' ) ) {
			return false;
		}

		if ( mpp_light_gallery_enabled_for_gallery_cover() ) {
			return true;
		}

		if ( mpp_is_single_gallery() && mpp_get_gallery_view( mpp_get_current_gallery() )->get_id() == 'light-gallery' ) {
			return true;
		}

		//if BuddyPress is enabled and we are on activity(directory, single activity, user activity, group activity)
		if ( ! function_exists( 'buddypress' ) ) {
			return false;
		}

		if ( ( bp_is_activity_component() || bp_is_group_activity() ) && mpp_get_activity_view( 'photo' )->get_id() == 'light-gallery' ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the absolute path to this plugin directory
	 * @return string plugin directory path
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * @return string plugin directory url
	 */
	public function get_url() {
		return $this->url;
	}

}

//initialize helper
mpp_light_gallery();

/**
 * A shortcut function to allow access to the singleton instance of the Addon
 *
 * @return MPP_Light_Gallery_Photo_Grid_Helper
 */
function mpp_light_gallery() {
	return MPP_Light_Gallery_Photo_Helper::get_instance();
}
