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

		add_action( 'mpp_init', array( $this, 'load_text_domain' ) );
		add_action( 'mpp_enqueue_scripts', array( $this, 'load_assets' ), 999 );
	}

	/**
	 * Load other plugins files
	 */
	public function load() {

		$files = array(
			'core/mpp-light-gallery-actions.php',
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

		wp_register_style( 'jquery-lightgallery', $this->url . 'assets/css/lightgallery.min.css' );
		wp_register_style( 'mpp-lightgallery', $this->url . 'assets/css/mpp-lightgallery.css', array( 'jquery-lightgallery' ) );

		wp_register_script( 'jquery-lightgallery', $this->url . 'assets/js/lightgallery-all.min.js', array( 'jquery' ) );
		wp_register_script( 'jquery-mousewheel', $this->url . 'assets/js/jquery.mousewheel.min.js', array( 'jquery' ) );
		wp_register_script( 'mpp-lightgallery', $this->url . 'assets/js/mpp-lightgallery.js', array( 'jquery-lightgallery', 'jquery-mousewheel' ) );

		$data = array(
			'url'                       => admin_url( 'admin-ajax.php' ),
			'enable_on_cover'          => mpp_get_option( 'light_gallery_on_gallery_cover' ),
			'enable_on_single_gallery' => mpp_get_option( 'light_gallery_on_single_gallery' ),
			'enable_in_activity'       => mpp_get_option( 'light_gallery_in_activity' ),
		);

		wp_localize_script( 'mpp-lightgallery', 'MPP_Light_Gallery', $data );

		wp_enqueue_style( 'mpp-lightgallery' );
		wp_enqueue_script( 'mpp-lightgallery' );
	}

	/**
	 * Load plugin text domain
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'mpp-light-gallery', false, basename( dirname( __FILE__ ) ) . '/languages/' );
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
