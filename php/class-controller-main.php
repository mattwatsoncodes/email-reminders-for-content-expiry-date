<?php
/**
 * Class Controller_Main
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * The main loader for this plugin
 */
class Controller_Main {

	/**
	 * Object to load the assets
	 *
	 * @var object
	 */
	private $controller_assets;

	/**
	 * Render admin notices
	 *
	 * @var object
	 */
	private $notices_admin;

	/**
	 * Deactivation Hooks
	 *
	 * @var object
	 */
	private $deactivate;

	/**
	 * Register Chron Job
	 *
	 * @var object
	 */
	private $chron;

	/**
	 * Register Options
	 *
	 * @var object
	 */
	private $options;

	/**
	 * Actions that Occur on Save Post
	 *
	 * @var object
	 */
	private $save_post;

	/**
	 * Constructor
	 *
	 * @param Controller_Assets $controller_assets Object to load the assets.
	 * @param Notices_Admin     $notices_admin     Render admin notices.
	 * @param Deactivate        $deactivate        Deactivation Hooks.
	 * @param Chron             $chron             Deactivation Hooks.
	 * @param Options           $options           Register Options.
	 * @param Save_Post         $save_post         Actions that Occur on Save Post.
	 */
	public function __construct(
		Controller_Assets $controller_assets,
		Notices_Admin $notices_admin,
		Deactivate $deactivate,
		Chron $chron,
		Options $options,
		Save_Post $save_post
	) {
		$this->controller_assets = $controller_assets;
		$this->notices_admin     = $notices_admin;
		$this->deactivate        = $deactivate;
		$this->chron             = $chron;
		$this->options           = $options;
		$this->save_post         = $save_post;
	}

	/**
	 * Do Work
	 */
	public function run() {
		load_plugin_textdomain( 'email-reminders-for-content-expiry-date', false, MKDO_EMAIL_REMINDERS_FOR_CONTENT_EXPIRY_DATE_ROOT . '\languages' );

		$this->controller_assets->run();
		$this->notices_admin->run();
		$this->deactivate->run();
		$this->chron->run();
		$this->options->run();
		$this->save_post->run();
	}
}
