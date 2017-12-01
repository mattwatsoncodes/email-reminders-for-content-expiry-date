<?php
/**
 * Email Reminders for Content Expiry Date
 *
 * @link              https://github.com/mwtsn/email-reminders-for-content-expiry-date
 * @package           mkdo\email_reminders_for_content_expiry_date
 *
 * Plugin Name:       Email Reminders for Content Expiry Date
 * Plugin URI:        https://github.com/mkdo/email-reminders-for-content-expiry-date
 * Description:       Remove content from WordPress on a certain date
 * Version:           1.0.0
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        http://www.makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       email-reminders-for-content-expiry-date
 * Domain Path:       /languages
 */

// Constants.
define( 'MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_ROOT', __FILE__ );
define( 'MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_NAME', 'Email Reminders for Email Reminders for Content Expiry Date' );
define( 'MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_PREFIX', 'mkdo_email_reminders_for_content_expiry_date' );

// Load Classes.
require_once 'php/class-helper.php';
require_once 'php/class-controller-assets.php';
require_once 'php/class-controller-main.php';
require_once 'php/class-notices-admin.php';
require_once 'php/class-deactivate.php';
require_once 'php/class-chron.php';
require_once 'php/class-options.php';
require_once 'php/class-save-post.php';


// Use Namespaces.
use mkdo\email_reminders_for_content_expiry_date\Helper;
use mkdo\email_reminders_for_content_expiry_date\Controller_Assets;
use mkdo\email_reminders_for_content_expiry_date\Controller_Main;
use mkdo\email_reminders_for_content_expiry_date\Notices_Admin;
use mkdo\email_reminders_for_content_expiry_date\Deactivate;
use mkdo\email_reminders_for_content_expiry_date\Chron;
use mkdo\email_reminders_for_content_expiry_date\Options;
use mkdo\email_reminders_for_content_expiry_date\Save_Post;


// Initialize Classes.
$controller_assets = new Controller_Assets();
$notices_admin     = new Notices_Admin();
$deactivate        = new Deactivate();
$chron             = new Chron();
$options           = new Options();
$save_post         = new Save_Post();
$controller        = new Controller_Main(
	$controller_assets,
	$notices_admin,
	$deactivate,
	$chron,
	$options,
	$save_post
);

// Run the Plugin.
$controller->run();
