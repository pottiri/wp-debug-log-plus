<?php
/**
 * Wp Options class
 *
 * @package Wp Debug Log Plus
 * @since   1.0.0
 */

if ( class_exists( 'Wp_Debug_Log_Plus_Options' ) ) {
	// Make sure that this file is called multiple times.
	return;
}
/**
 * Admin page class
 *
 * @package Wp Debug Log Plus
 * @since   1.0.0
 */
class Wp_Debug_Log_Plus_Options {


	/**
	 * Instances of this class.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object
	 */
	private static $object = null;

	/**
	 * Options for this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Array
	 */
	private $options = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function __construct() {
		$this->load();
		$this->options = get_option( 'wp_debug_log_plus' );
	}

	/**
	 * Returns singleton object
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_object() {
		if ( is_null( self::$object ) ) {
			self::$object = new Wp_Debug_Log_Plus_Options();
		}
		return self::$object;
	}

	/**
	 * Loads the object
	 * Define a hook here
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {
			register_deactivation_hook( WPDLP_MAIN_FILE, array( $this, 'deactivation' ) );
	}

	/**
	 * Get option value
	 *
	 * @since  1.0.0
	 * @access public
	 * @param string $key Optional key.
	 * @param string $default Initial value when there is no value.
	 * @return string
	 */
	public function get_option( $key, $default = null ) {
		if ( ! isset( $this->options[ $key ] ) || '' === $this->options[ $key ] ) {
			return $default;
		}
		return $this->options[ $key ];
	}

	/**
	 * Set option
	 *
	 * @since  1.0.0
	 * @access public
	 * @param string $key Optional key.
	 * @param string $value Optional value.
	 * @return void
	 */
	public function set_option( $key, $value ) {
		if ( is_null( $this->options ) ) {
			$this->options = get_option( 'wp_debug_log_plus' );
		}
		$this->options[ $key ] = $value;
	}

	/**
	 * Update options
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update_options_all() {
		if ( is_null( $this->options ) ) {
			return;
		}
		update_option( 'wp_debug_log_plus', $this->options );
	}

	/**
	 * Processing when stopping the plug-in.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function deactivation() {
		delete_option( 'wp_debug_log_plus' );
	}
}
