<?php
/**
 * Log output class
 *
 * @package Wp Debug Log Plus
 * @since   1.0.0
 */

if ( class_exists( 'Wp_Debug_Log_Plus_Logger' ) ) {
	// Make sure that this file is called multiple times.
	return;
}
/**
 * Log output class
 *
 * @package Wp Debug Log Plus
 * @since   1.0.0
 */
class Wp_Debug_Log_Plus_Logger {


	/**
	 * Instances of this class.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object
	 */
	private static $object = null;

	/**
	 * Whether the start log was output.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	private $startlog_output = false;

	/**
	 * Whether or not you have logged in user.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	private $loaded_current_user = false;

	/**
	 * Die handler before rewriting.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	private $original_die_handler = false;

	/**
	 * Request time..
	 *
	 * @since 1.0.0
	 * @access private
	 * @var float
	 */
	private $request_time = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function __construct() {
		$this->load();
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
			self::$object = new Wp_Debug_Log_Plus_Logger();
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
		// REQUEST_TIME_FLOAT, REQUEST_TIME can not be obtained without directly referring to $_SERVER.
		// phpcs:disable\
		if ( isset( $_SERVER['REQUEST_TIME_FLOAT'] ) ) {
			$this->request_time = wp_unslash( $_SERVER['REQUEST_TIME_FLOAT'] );
		}
		// phpcs:enable
		add_action( 'set_current_user', array( $this, 'check_current_user' ), -1, 0 );

		// Start/End log.
		add_action( 'plugins_loaded', array( $this, 'startlog' ), -1, 0 );
		add_action( 'shutdown', array( $this, 'endlog' ), 9999, 1 );

		// SQL log.
		add_filter( 'query', array( $this, 'sqllog' ), 9999, 1 );

		// wp_die log
		add_filter( 'wp_die_ajax_handler', array( $this, 'die_handler' ) );
		add_filter( 'wp_die_xmlrpc_handler', array( $this, 'die_handler' ) );
		add_filter( 'wp_die_handler', array( $this, 'die_handler' ) );
	}

	/**
	 * Debug log output
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $message String to log.
	 * @return void
	 */
	public static function debug( $message ) {
		self::get_object()->log( $message );
	}

	/**
	 * Log output
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $message String to log.
	 * @return void
	 */
	public function log( $message ) {
		if ( preg_match( '/admin-ajax.php/', filter_input( INPUT_SERVER, 'REQUEST_URI' ) ) &&
		! Wp_Debug_Log_Plus_Options::get_object()->get_option( 'ajax_log_flag', 1 ) ) {
			return;
		}
		if ( is_array( $message ) || is_object( $message ) ) {
			// phpcs:disable
			$message = print_r( $message, true );
			// phpcs:enable
		}

		$info  = '';
		$info .= $this->get_request_time();
		$info .= $this->get_ip();
		$info .= $this->get_user_login();
		// phpcs:disable
		error_log( $info . $message );
		// phpcs:enable
	}

	/**
	 * Check if login user has been loaded
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function check_current_user() {
		$this->loaded_current_user = true;
	}

	/**
	 * Get request start time
	 *
	 * @since  1.0.0
	 * @access private
	 * @return string
	 */
	private function get_request_time() {
		return '[' . $this->request_time . ']';
	}

	/**
	 * Get ip address
	 *
	 * @since  1.0.0
	 * @access private
	 * @return string
	 */
	private function get_ip() {
		// REMOTE_ADDE can not be obtained without directly referring to $_SERVER.
		$ip = '';
		// phpcs:disable
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = wp_unslash( $_SERVER['REMOTE_ADDR'] );
		}
		// phpcs:enable
		return '[' . $ip . ']';
	}

	/**
	 * Surround and return the login ID of the logged-in user
	 *
	 * @since  1.0.0
	 * @access private
	 * @return string
	 */
	private function get_user_login() {
		if ( ! $this->loaded_current_user ) {
			return '[]';
		}
		$current_user = wp_get_current_user();
		if ( ! ( $current_user instanceof WP_User ) ) {
			return '[]';
		}
		return '[' . $current_user->get( 'user_login' ) . ']';
	}

	/**
	 * Log the contents of the request at the start of processing
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function startlog() {

		if ( $this->startlog_output ) {
			return;
		}
		$this->startlog_output = true;

		if ( Wp_Debug_Log_Plus_Options::get_object()->get_option( 'start_log_flag', 1 ) ) {
			$this->log(
				Wp_Debug_Log_Plus_Options::get_object()->get_option(
					'start_log_text',
					'=========== Start ==========='
				)
			);
			$this->log( 'REQUEST URI = ' . filter_input( INPUT_SERVER, 'REQUEST_URI' ) );
			$this->log( 'REQUEST_METHOD = ' . filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) );
			$this->log( 'QUERY_STRING = ' . filter_input( INPUT_SERVER, 'QUERY_STRING' ) );
		}

		if ( Wp_Debug_Log_Plus_Options::get_object()->get_option( 'get_log_flag', 1 ) &&
			'GET' === filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) ) {
			$this->log( '******* GET parameters ******' );
			$this->log( filter_input_array( INPUT_GET ) );
			$this->log( '******* GET parameters end **' );
		}
		if ( Wp_Debug_Log_Plus_Options::get_object()->get_option( 'post_log_flag', 1 ) &&
		'POST' === filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) ) {
			$this->log( '********* POST paramaters *******' );
			$this->log( filter_input_array( INPUT_POST ) );
			$this->log( '********* POST paramaters end ***' );
		}

	}

	/**
	 * Log the contents of the request at the start of processing
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function endlog() {
		if ( ! Wp_Debug_Log_Plus_Options::get_object()->get_option( 'end_log_flag', 1 ) ) {
			return;
		}
		$process_time = round( microtime( true ) - $this->request_time, 2 );
		$this->log(
			sprintf(
				Wp_Debug_Log_Plus_Options::get_object()->get_option(
					'end_log_text',
					'====== End (%ssec) ======'
				), $process_time
			)
		);
	}

	/**
	 * Output SQL log
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $query Execution SQL.
	 * @return string
	 */
	public function sqllog( $query ) {

		if ( ! $this->startlog_output ) {
			$this->startlog();
		}
		if ( ! Wp_Debug_Log_Plus_Options::get_object()->get_option( 'sql_log_flag', 1 ) ) {
			return $query;
		}
		$this->log( 'SQL:' . $query );
		return $query;
	}

	/**
	 * Rewrite die handler
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $return Value passed through for {@see 'wp_die_handler'} filter.
	 * @return mixed Value passed through for {@see 'wp_die_handler'} filter.
	 */
	public function die_handler( $return = null ) {
		$this->original_die_handler = $return;
		return array( $this, 'backtrace' );
	}

	/**
	 * Log backtrace when an exception occurs
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string       $message Error message.
	 * @param  string       $title   Optional. Error title. Default empty.
	 * @param  string|array $args    Optional. Arguments to control behavior. Default empty array.
	 * @return void
	 */
	public function backtrace( $message, $title = '', $args = array() ) {

		if ( ! Wp_Debug_Log_Plus_Options::get_object()->get_option( 'backtrace_log_flag', 1 ) ) {
			return;
		}

		$this->log( '**** wp_die paramters ****' );
		if ( $message ) {
				$this->log( $message );
		}
		if ( $title ) {
				$this->log( $title );
		}
		if ( $args ) {
				$this->log( $args );
		}

		if ( is_wp_error( $message ) ) {
			$this->log( '**** An error occurred ****' );
			// phpcs:disable
			$this->log( debug_backtrace() );
			// phpcs:enable
		}
		if ( isset( $args['response'] )
			&& ( preg_match( '/^4/', $args['response'] )
			|| preg_match( '/^5/', $args['response'] ) )
		) {
			$this->log( '**** An error occurred(' . $args['response'] . ') ****' );
			// phpcs:disable
			$this->log( debug_backtrace() );
			// phpcs:enable
		}
		// Call original die handler.
		if ( $this->original_die_handler ) {
			call_user_func( $this->original_die_handler, $message, $title, $args );
		}
	}

}
