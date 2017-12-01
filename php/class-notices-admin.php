<?php
/**
 * Class Notices_Admin
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * If the plugin needs attention, here is where the notices are set.
 *
 * You should place warnings such as plugin dependancies here.
 */
class Notices_Admin {

	/**
	 * Constructor
	 */
	function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function admin_notices() {

		// Notify if the content expiry date plugin is not enabled.
		if ( ! class_exists( 'mkdo\email_reminders_for_content_expiry_date\Controller_Main' ) ) {
			$url     = 'https://github.com/mwtsn/email-reminders-for-content-expiry-date';
			$warning = sprintf( __( 'The %1$sEmail Reminders for Email Reminders for Content Expiry Date%2$s plugin works much better when you %3$sinstall and activate the Email Reminders for Content Expiry Date plugin%4$s.', 'email-reminders-for-content-expiry-date' ), '<strong>', '</strong>', '<a href="' . $url . '" target="_blank">', '</a>' );
			?>
			<div class="notice notice-warning is-dismissible">
			<p>
			<?php
				echo wp_kses(
					$warning,
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
						'strong'   => array(),
						'em' => array(),
					)
				);
			?>
			</p>
			</div>
			<?php
		}
	}
}
