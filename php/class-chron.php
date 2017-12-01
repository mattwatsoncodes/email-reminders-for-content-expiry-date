<?php
/**
 * Class Chron
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * Chron
 */
class Chron {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'wp', array( $this, 'activate_chron_job' ) );
		add_action(  MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX . '_chron', array( $this, 'do_chron_job' ) );
	}

	/**
	 * Create Chron Job
	 */
	function activate_chron_job() {
		if ( ! wp_next_scheduled( MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX . '_chron' ) ) {
			wp_schedule_event( time(), 'daily', MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX . '_chron' );
		}
	}

	/**
	 * Chron Job Function.
	 */
	function do_chron_job() {

		// @codingStandardsIgnoreStart
		$content_posts = get_posts(
			array(
				'post_type'      => get_post_types(),
				'posts_per_page' => -1, // Bad practice, but we need them all.
				'post_status'    => array( 'publish', 'expired' ),
				'meta_key'       => 'mkdo_content_expiry_date',
				'meta_value'     => '',
				'meta_compare'   => '!=',
			)
		);
		// @codingStandardsIgnoreEnd

		$current_date   = new \DateTime();
		$expiry_content = array();

		foreach ( $content_posts as $content_post ) {
			$expire_date = get_post_meta( $content_post->ID, 'mkdo_content_expiry_date', true );
			$expire_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $expire_date );
			$interval    = $current_date->diff( $expire_date );

			$expiry_content[ $content_post->post_author ][] = array(
				'days'        => $interval->format( '%a' ),
				'before'      => ( '+' === $interval->format( '%R' ) ? 'false' : 'true' ),
				'post_id'     => $content_post->ID,
			);
		}

		$expired_content = array();
		$message_config  = array();

		$prefix   = 'mkdo_content_expiry_date_';

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

		foreach ( $email_configuration as $i => $config ) {
			if (
				isset( $email_configuration[ $i ] ) &&
				isset( $config['days'] ) && ! empty( $config['days'] ) &&
				isset( $config['before'] ) && ! empty( $config['before'] ) &&
				isset( $config['subject'] ) && ! empty( $config['subject'] ) &&
				isset( $config['message'] ) && ! empty( $config['message'] ) &&
				! isset( $config['delete'] )
			) {
				$message_config[] = $config;
			}
		}

		foreach ( $expiry_content as $user_id => $details ) {
			$user_info  = get_userdata( $user_id );
			$first_name = $user_info->first_name;
			$last_name  = $user_info->last_name;
			$username   = $user_info->user_login;
			$email      = $user_info->user_email;

			if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
				$username = $first_name . ' ' . $last_name;
			}

			foreach ( $message_config as &$config ) {
				foreach ( $details as $detail ) {
					$message_sent_before = get_post_meta( $detail['post_id'], 'mkdo_content_expiry_date_sent_mail_before_' . $config['days'], true );
					$message_sent_after  = get_post_meta( $detail['post_id'], 'mkdo_content_expiry_date_sent_mail_after_' . $config['days'], true );
					// Message has not been sent before, so send it this time.
					if ( empty( $message_sent_before ) ) {
						if ( $detail['before'] === $config['before'] && 'true' === $config['before'] && intval( $detail['days'] ) > 0 && intval( $detail['days'] ) <= intval( $config['days'] ) ) {
							$config['entries'][] = $detail['post_id'];
							update_post_meta( $detail['post_id'], 'mkdo_content_expiry_date_sent_mail_before_' . $config['days'], true );
						}
					}

					if ( empty( $message_sent_after ) ) {
						if ( $detail['before'] === $config['before'] && 'false' === $config['before'] && intval( $detail['days'] ) >= intval( $config['days'] ) ) {
							$config['entries'][] = $detail['post_id'];
							update_post_meta( $detail['post_id'], 'mkdo_content_expiry_date_sent_mail_after_' . $config['days'], true );
						}
					}
				}

				$to       = $email;
				$subject  = $config['subject'];
				$message  = '';
				$message .= '<p>';
				$message .= $username . ',';
				$message .= '</p>';
				$message .= wp_kses_post( apply_filters( 'the_content', $config['message'] ) );
				$message .= '<table>';
				$message .= '<tr>';
				$message .= '<th>' . esc_html__( 'Content Title', 'textdomain' ) . '</th>';
				$message .= '<th>' . esc_html__( 'Expiry Date', 'textdomain' ) . '</th>';
				$message .= '<th>' . esc_html__( 'View', 'textdomain' ) . '</th>';
				$message .= '<th>' . esc_html__( 'Edit', 'textdomain' ) . '</th>';
				$message .= '</tr>';
				foreach ( $config['entries'] as $entry ) {
					$expire_date = get_post_meta( $entry, 'mkdo_content_expiry_date', true );
					$expire_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $expire_date );
					$message .= '<tr>';
					$message .= '<td>' . get_the_title( $entry ) . '</td>';
					$message .= '<td>' . $expire_date->format( 'Y-m-d H:i:s' ) . '</td>';
					$message .= '<td>' . get_the_permalink( $entry ) . '</td>';
					$message .= '<td>' . get_edit_post_link( $entry ) . '</td>';
					$message .= '</tr>';
				}
				$message .= '</table>';

				add_filter( 'wp_mail_content_type', array( $this, 'set_content_type' ) );
				wp_mail( $to, $subject, $message );
				remove_filter( 'wp_mail_content_type', array( $this, 'set_content_type' ) );
			}
		}
	}

	/**
	 * Set email content type
	 */
	function set_content_type() {
	    return 'text/html';
	}

}
