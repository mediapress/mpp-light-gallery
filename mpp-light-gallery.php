<?php
/**
 * Plugin Name: MediaPress Light Gallery
 * Version: 1.0.0
 * Author: BuddyDev
 * Author URI: https://buddydev.com/plugins/mediapress-light-gallery
 * Description: An addon for MediaPress to show photo media in full slide view using lightGallery
 * License: GPL2 or Above
 */

// If file access directly code will exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPP_Light_Gallery_Photo_Helper
 */
class MPP_Light_Gallery_Photo_Helper {

	/**
	 * Class instance
	 *
	 * @var MPP_Light_Gallery_Photo_Helper
	 */
	private static $instance = null;

	/**
	 * Plugin directory path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin directory url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * The constructor.
	 */
	private function __construct() {

		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		$this->setup();
	}

	/**
	 * Get instance of class
	 *
	 * @return MPP_Light_Gallery_Photo_Helper
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Callback on various MediaPress hooks
	 */
	public function setup() {

		add_action( 'mpp_loaded', array( $this, 'load' ) );
		add_action( 'mpp_setup_core', array( $this, 'register_views' ) );
		add_action( 'mpp_init', array( $this, 'load_text_domain' ) );
		add_action( 'mpp_enqueue_scripts', array( $this, 'load_assets' ), 999 );
	}

	/**
	 * Load other plugins files
	 */
	public function load() {

		$files = array(
			'core/class-mpp-light-gallery-view.php',
			'core/mpp-light-gallery-actions.php',
			'core/mpp-light-gallery-functions.php',
		);

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$files[] = 'admin/mpp-light-gallery-admin.php';
		}

		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}
	}

	/**
	 * Load plugin scripts and styles
	 */
	public function load_assets() {

		if ( ! $this->maybe_load_assets() ) {
			return;
		}

		wp_register_style( 'jquery-light-gallery', $this->url . 'assets/css/light-gallery.min.css' );
		wp_register_style( 'mpp-light-gallery', $this->url . 'assets/css/mpp-light-gallery.css', array( 'jquery-light-gallery' ) );

		wp_register_script( 'jquery-light-gallery', $this->url . 'assets/js/light-gallery-all.min.js', array( 'jquery' ) );
		wp_register_script( 'jquery-mousewheel', $this->url . 'assets/js/jquery.mousewheel.min.js', array( 'jquery' ) );
		wp_register_script( 'mpp-light-gallery', $this->url . 'assets/js/mpp-light-gallery.js', array( 'jquery-light-gallery', 'jquery-mousewheel' ) );

		$data = array(
			'url'              => admin_url( 'admin-ajax.php' ),
			'enabled_on_cover' => mpp_light_gallery_enabled_for_gallery_cover(),
			'activity_default_view' => mpp_get_option( 'activity_photo_default_view' ),
		);

		wp_localize_script( 'mpp-light-gallery', 'MPP_Light_Gallery', $data );

		wp_enqueue_style( 'mpp-light-gallery' );
		wp_enqueue_script( 'mpp-light-gallery' );
	}

	/**
	 * Register new MediaPress Photo view
	 */
	public function register_views() {
		mpp_register_gallery_view( 'photo', MPP_Light_Gallery_View::get_instance() );
	}

	/**
	 * Load plugin text domain
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'mpp-light-gallery', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Check if plugin should be loaded or not
	 *
	 * @todo check for more
	 *
	 * @return bool
	 */
	public function maybe_load_assets() {

		if ( mpp_light_gallery_enabled_for_gallery_cover() ) {
			return true;
		}

		if ( mpp_is_single_gallery() && mpp_get_gallery_view( mpp_get_current_gallery() )->get_id() == 'light-gallery' ) {
			return true;
		}

		if ( ! function_exists( 'buddypress' ) ) {
			return false;
		}

		if ( ( bp_is_activity_component() || bp_is_group_activity() ) &&
		     mpp_get_activity_view( 'photo' )->get_id() == 'light-gallery' ) {
			return true;
		}

		return false;
	}

	/**
	 * Get plugin directory path
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get plugin directory url
	 *
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}

/**
 * Get instance of class
 *
 * @return MPP_Light_Gallery_Photo_Helper
 */
function mpp_light_gallery() {
	return MPP_Light_Gallery_Photo_Helper::get_instance();
}

mpp_light_gallery();
