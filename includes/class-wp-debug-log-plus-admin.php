<?php
/**
 * Admin page class
 *
 * @package Wp Debug Log Plus
 * @since   0.0.1
 */

if ( class_exists( 'Wp_Debug_Log_Plus_Admin' ) ) {
	// Make sure that this file is called multiple times.
	return;
}
/**
 * Admin page class
 *
 * @package Wp Debug Log Plus
 * @since   0.0.1
 */
class Wp_Debug_Log_Plus_Admin {


	/**
	 * Instances of this class.
	 *
	 * @since 0.0.1
	 * @access private
	 * @var object
	 */
	private static $object = null;

	/**
	 * Constructor
	 *
	 * @since  0.0.1
	 * @access protected
	 * @return void
	 */
	protected function __construct() {
		$this->load();
	}

	/**
	 * Returns singleton object
	 *
	 * @since  0.0.1
	 * @access public
	 * @return object
	 */
	public static function get_object() {
		if ( is_null( self::$object ) ) {
			self::$object = new Wp_Debug_Log_Plus_Admin();
		}
		return self::$object;
	}

	/**
	 * Loads the object
	 * Define a hook here
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function load() {

		add_action( 'admin_menu', array( $this, 'wpdlp_menu' ) );

	}

	/**
	 * Add link to menu
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function wpdlp_menu() {
		add_submenu_page( 'options-general.php', __( 'Wp Debug Log Plus' ), __( 'Wp Debug Log Plus' ), 'activate_plugins', 'wpdlp_menu', array( $this, 'wpdlp_options_page' ) );
	}

	/**
	 * Drawing screen
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function wpdlp_options_page() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_die( esc_html( __( 'You do not have sufficient permissions to access this page.' ) ) );
		}
		if ( ! is_null( filter_input( INPUT_POST, 'saved' ) ) ) {
			wpdlp_log( filter_input( INPUT_POST, 'start_log_flag' ) );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'start_log_flag', is_null( filter_input( INPUT_POST, 'start_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'start_log_text', wp_unslash( filter_input( INPUT_POST, 'start_log_text' ) ) );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'get_log_flag', is_null( filter_input( INPUT_POST, 'get_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'post_log_flag', is_null( filter_input( INPUT_POST, 'post_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'end_log_flag', is_null( filter_input( INPUT_POST, 'end_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'end_log_text', wp_unslash( filter_input( INPUT_POST, 'end_log_text' ) ) );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'sql_log_flag', is_null( filter_input( INPUT_POST, 'sql_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'backtrace_log_flag', is_null( filter_input( INPUT_POST, 'backtrace_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'mail_log_flag', is_null( filter_input( INPUT_POST, 'mail_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->set_option( 'ajax_log_flag', is_null( filter_input( INPUT_POST, 'ajax_log_flag' ) ) ? 0 : 1 );
			Wp_Debug_Log_Plus_Options::get_object()->update_options_all();
		}
	?>
		<div class="wrap">
			<h2>Wp Debug Log Plus</h2>
				<?php
				if ( ! is_null( filter_input( INPUT_POST, 'saved' ) ) ) {
					echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			     			<p><strong>Your settings have been saved.</strong></p></div>';
				}
				?>
			<form method="post" action="">
				<input name="saved" type="hidden" id="saved" value="1"/>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="start_log_flag">Start log</label></th>
						<td><label><input name="start_log_flag" type="checkbox" id="start_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'start_log_flag' ) ); ?>/></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="start_log_text">Start log text</label></th>
						<td><input name="start_log_text" type="text" id="start_log_text" value="<?php echo esc_attr( Wp_Debug_Log_Plus_Options::get_object()->get_option( 'start_log_text', '=========== Start ===========' ) ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="get_log_flag">Get parameters log</label></th>
						<td><label><input name="get_log_flag" type="checkbox" id="get_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'get_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="post_log_flag">Post parameters log</label></th>
						<td><label><input name="post_log_flag" type="checkbox" id="post_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'post_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="end_log_flag">End log</label></th>
						<td><label><input name="end_log_flag" type="checkbox" id="end_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'end_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="end_log_text">End log text</label></th>
						<td>
							<input name="end_log_text" type="text" id="end_log_text" value="<?php echo esc_attr( Wp_Debug_Log_Plus_Options::get_object()->get_option( 'end_log_text', '====== End (%ssec) ======' ) ); ?>" class="regular-text" /></br>
							"% s" is replaced with the processing time from the start of the request.
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="sql_log_flag">SQL log</label></th>
						<td><label><input name="sql_log_flag" type="checkbox" id="sql_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'sql_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="backtrace_log_flag">Backtrace log at error occurrence</label></th>
						<td><label><input name="backtrace_log_flag" type="checkbox" id="backtrace_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'backtrace_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="mail_log_flag">Log of mail transmission error</label></th>
						<td><label><input name="mail_log_flag" type="checkbox" id="mail_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'mail_log_flag', 1 ) ); ?> /></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="ajax_log_flag">All logs of admin-ajax.php</label></th>
						<td><label><input name="ajax_log_flag" type="checkbox" id="ajax_log_flag" value="1" <?php checked( 1, Wp_Debug_Log_Plus_Options::get_object()->get_option( 'ajax_log_flag', 1 ) ); ?> /></label></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
