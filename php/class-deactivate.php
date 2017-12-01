<?php
/**
 * Class Deactivate
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * Deactivate
 */
class Deactivate {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		register_deactivation_hook( MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_ROOT, 'remove_chron_job' );
	}

	/**
	 * Remove Chron Job on Deactivation
	 */
	function remove_chron_job() {
		// Find out when the last event was scheduled.
		$timestamp = wp_next_scheduled( MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX . '_chron' );
		// Unschedule previous event if any.
		wp_unschedule_event( $timestamp, MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX . '_chron' );
	}

}
