<?php
/**
 * Class Save_Post
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * Save Post
 */
class Save_Post {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'save_post', array( $this, 'save_post' ), -1, 1 );
	}

	/**
	 * Post Updated
	 *
	 * @param  int $post_id The post ID.
	 */
	function save_post( $post_id ) {

		if (
			isset( $_POST['expire-mm'] ) && // Input var okay.
			isset( $_POST['expire-jj'] ) && // Input var okay.
			isset( $_POST['expire-aa'] ) && // Input var okay.
			isset( $_POST['expire-hh'] ) && // Input var okay.
			isset( $_POST['expire-mn'] ) // Input var okay.
		) {
			$year   = $_POST['expire-aa'];
			$month  = $_POST['expire-mm'];
			$day    = $_POST['expire-jj'];
			$hour   = $_POST['expire-hh'];
			$minute = $_POST['expire-mn'];

			$save_date     = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':00';
			$previous_date = get_post_meta( $post_id, 'mkdo_content_expiry_date', true );

			// Clear email notification data.
			if ( $save_date !== $previous_date ) {
				$email_configuration = get_option(
					$prefix . 'mkdo_content_email_configuration',
					array(
						array(
							'days'    => '7',
							'before'  => 'true',
							'subject' => esc_html__( 'Content due to expire in 1 week', 'email-reminders-for-content-expiry-date' ),
							'message' => esc_html__( 'Please find content due to expire in 1 week (or less):', 'email-reminders-for-content-expiry-date' ),
							'entries' => array(),
						),
						array(
							'days'    => '1',
							'before'  => 'true',
							'subject' => esc_html__( 'Content due to expire in 1 day', 'email-reminders-for-content-expiry-date' ),
							'message' => esc_html__( 'Please find content due to expire in 1 day:', 'email-reminders-for-content-expiry-date' ),
							'entries' => array(),
						),
						array(
							'days'    => '1',
							'before'  => 'false',
							'subject' => esc_html__( 'Content expired for 1 day', 'email-reminders-for-content-expiry-date' ),
							'message' => esc_html__( 'Please find content that has been expired for at least 1 day:', 'email-reminders-for-content-expiry-date' ),
							'entries' => array(),
						),
						array(
							'days'    => '7',
							'before'  => 'false',
							'subject' => esc_html__( 'Content expired for 1 week', 'email-reminders-for-content-expiry-date' ),
							'message' => esc_html__( 'Please find content that has been expired for at least 1 week:', 'email-reminders-for-content-expiry-date' ),
							'entries' => array(),
						),
					)
				);

				foreach ( $email_configuration as $config ) {
					delete_post_meta( $post_id, 'mkdo_content_expiry_date_sent_mail_before_' . $config['days'] );
					delete_post_meta( $post_id, 'mkdo_content_expiry_date_sent_mail_after_' . $config['days'] );
				}
			}
		}
	}
}
