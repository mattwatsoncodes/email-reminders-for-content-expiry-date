<?php
/**
 * Class Controller_Assets
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * Sets up the JS and CSS needed for this plugin
 */
class Controller_Assets {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqeue Scripts
	 */
	public function admin_enqueue_scripts() {

		$plugin_css_url = plugins_url( 'css/plugin-admin.css', MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_ROOT );
		wp_enqueue_style( 'email-reminders-for-content-expiry-date', $plugin_css_url, array(), '1.0.12' );
	}
}
