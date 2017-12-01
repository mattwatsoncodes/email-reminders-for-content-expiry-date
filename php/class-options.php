<?php
/**
 * Class Options
 *
 * @package mkdo\email_reminders_for_content_expiry_date
 */

namespace mkdo\email_reminders_for_content_expiry_date;

/**
 * Options
 */
class Options {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_init', array( $this, 'init_options_page' ) );
	}

	/**
	 * Add settings to the options page.
	 */
	function init_options_page() {
		$prefix   = 'mkdo_content_expiry_date_';
		$settings = $prefix . 'settings';

		register_setting( $settings . '_group', $prefix . 'email_configuration' );

		$section = $prefix . 'section_email_configuration';
		add_settings_section( $section, __( 'Email Configuration', 'email-reminders-for-content-expiry-date' ), array( $this, 'render_section_email_configuration' ), $settings );
		add_settings_field( $prefix . 'field_email_configuration', __( 'Email Reminders:', 'email-reminders-for-content-expiry-date' ), array( $this, 'render_field_email_configuration' ), $settings, $section );
	}


	/**
	 * Render the settings heading
	 */
	function render_section_email_configuration() {
		echo '<p>';
		esc_html_e( 'To can add any ammount of email reminders (sent to the content author) that are set to trigger a certain ammount of days before or after the expiry date. ', 'email-reminders-for-content-expiry-date' );
		echo '</p>';
		echo '<p>';
		esc_html_e( 'Remember to Save Changes before you add a new email reminder.', 'email-reminders-for-content-expiry-date' );
		echo '</p>';
	}

	/**
	 * Render the fields.
	 */
	function render_field_email_configuration() {

		$prefix = 'mkdo_content_expiry_date_';

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

		if ( ! array( $email_configuration ) ) {
			$email_configuration = array();
		}

		foreach ( $email_configuration as $i => $config ) {
			if (
				isset( $email_configuration[ $i ] ) &&
				isset( $config['days'] ) && ! empty( $config['days'] ) &&
				isset( $config['before'] ) && ! empty( $config['before'] ) &&
				isset( $config['subject'] ) && ! empty( $config['subject'] ) &&
				isset( $config['message'] ) && ! empty( $config['message'] ) &&
				! isset( $config['delete'] )
			) {
				?>
				<div class="c-email-reminder">
					<div class="field field-input">
						<p>
							<label for="<?php echo esc_attr( $prefix );?>email_configuration_days_<?php echo esc_attr( $i );?>">
								<?php esc_html_e( 'Days:', 'email-reminders-for-content-expiry-date' ); ?>
							</label>
							<br/>
							<input
								type="number"
								id="<?php echo esc_attr( $prefix );?>email_configuration_days_<?php echo esc_attr( $i );?>"
								name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][days]"
								value="<?php echo esc_attr( $config['days'] );?>"
								placeholder="7"
							/>
						</p>
						<p class="description"><?php esc_html_e( 'Number of days before or after the expiry date to report on.', 'email-reminders-for-content-expiry-date' ); ?></p>
					</div>
					<div class="field field-select">
						<p>
							<label for="<?php echo esc_attr( $prefix );?>email_configuration_before_<?php echo esc_attr( $i );?>">
								<?php esc_html_e( 'Days are Before or After Expiry:', 'email-reminders-for-content-expiry-date' ); ?>
							</label>
							<br/>
							<select
								id="<?php echo esc_attr( $prefix );?>email_configuration_before_<?php echo esc_attr( $i );?>"
								name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][before]"
							>
								<option
									value="true"
									<?php selected( 'true', esc_attr( $config['before'] ), true ); ?>
								>
								<?php esc_html_e( 'Days before expiry', 'email-reminders-for-content-expiry-date' ); ?>
								</option>
								<option
									value="false"
									<?php selected( 'false', esc_attr( $config['before'] ), true ); ?>
								>
								<?php esc_html_e( 'Days after expiry', 'email-reminders-for-content-expiry-date' ); ?>
								</option>
							</select>
						</p>
					</div>
					<div class="field field-input">
						<p>
							<label for="<?php echo esc_attr( $prefix );?>email_configuration_subject_<?php echo esc_attr( $i );?>">
								<?php esc_html_e( 'Email Subject:', 'email-reminders-for-content-expiry-date' ); ?>
							</label>
							<br/>
							<input
								type="text"
								id="<?php echo esc_attr( $prefix );?>email_configuration_subject_<?php echo esc_attr( $i );?>"
								name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][subject]"
								value="<?php echo esc_attr( $config['subject'] );?>"
								placeholder="<?php esc_html_e( 'Content due to expire in 1 week', 'email-reminders-for-content-expiry-date' ); ?>"
							/>
						</p>
					</div>
					<div class="field field-textarea">
						<p>
							<label for="<?php echo esc_attr( $prefix );?>email_configuration_message_<?php echo esc_attr( $i );?>">
								<?php esc_html_e( 'Email Message:', 'email-reminders-for-content-expiry-date' ); ?>
							</label>
							<br/>
							<?php
							wp_editor(
								esc_attr( $config['message'] ),
								esc_attr( $prefix ) . 'email_configuration_message_' . esc_attr( $i ),
								array(
									'textarea_name' => esc_attr( $prefix ) . 'email_configuration[' . esc_attr( $i ) . '][message]',
									'textarea_rows' => 5,
								)
							);
							?>
						</p>
						<p class="description"><?php esc_html_e( 'Message to send (shows after name and before expiry report).', 'email-reminders-for-content-expiry-date' ); ?></p>
					</div>
					<div class="field field-checkbox">
						<p>
							<label for="<?php echo esc_attr( $prefix );?>email_configuration_delete_<?php echo esc_attr( $i );?>">
								<input
									id="<?php echo esc_attr( $prefix );?>email_configuration_delete_<?php echo esc_attr( $i );?>"
									type="radio"
									name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][delete]"
									value="true"
								/>
								<?php esc_html_e( 'Remove this setting', 'email-reminders-for-content-expiry-date' ); ?>
							</label>
						</p>
					</div>
				</div>
				<?php
			}
		}
		$i++;
		?>
		<h2 class="c-email-reminder__title"><?php esc_html_e( 'Add New', 'email-reminders-for-content-expiry-date' ); ?></h2>
		<div class="c-email-reminder c-email-reminder--new">

			<div class="field field-input">
				<p>
					<label for="<?php echo esc_attr( $prefix );?>email_configuration_days_<?php echo esc_attr( $i );?>">
						<?php esc_html_e( 'Days:', 'email-reminders-for-content-expiry-date' ); ?>
					</label>
					<br/>
					<input
						type="number"
						id="<?php echo esc_attr( $prefix );?>email_configuration_days_<?php echo esc_attr( $i );?>"
						name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][days]"
						value=""
						placeholder="7"
					/>
				</p>
				<p class="description"><?php esc_html_e( 'Number of days before or after the expiry date to report on.', 'email-reminders-for-content-expiry-date' ); ?></p>
			</div>
			<div class="field field-select">
				<p>
					<label for="<?php echo esc_attr( $prefix );?>email_configuration_before_<?php echo esc_attr( $i );?>">
						<?php esc_html_e( 'Days are Before or After Expiry:', 'email-reminders-for-content-expiry-date' ); ?>
					</label>
					<br/>
					<select
						id="<?php echo esc_attr( $prefix );?>email_configuration_before_<?php echo esc_attr( $i );?>"
						name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][before]"
					>
						<option
							value="true"
						>
						<?php esc_html_e( 'Days before expiry', 'email-reminders-for-content-expiry-date' ); ?>
						</option>
						<option
							value="false"
						>
						<?php esc_html_e( 'Days after expiry', 'email-reminders-for-content-expiry-date' ); ?>
						</option>
					</select>
				</p>
			</div>
			<div class="field field-input">
				<p>
					<label for="<?php echo esc_attr( $prefix );?>email_configuration_subject_<?php echo esc_attr( $i );?>">
						<?php esc_html_e( 'Email Subject:', 'email-reminders-for-content-expiry-date' ); ?>
					</label>
					<br/>
					<input
						type="text"
						id="<?php echo esc_attr( $prefix );?>email_configuration_subject_<?php echo esc_attr( $i );?>"
						name="<?php echo esc_attr( $prefix );?>email_configuration[<?php echo esc_attr( $i );?>][subject]"
						value=""
						placeholder="<?php esc_html_e( 'Content due to expire in 1 week', 'email-reminders-for-content-expiry-date' ); ?>"
					/>
				</p>
			</div>
			<div class="field field-textarea">
				<p>
					<label for="<?php echo esc_attr( $prefix );?>email_configuration_message_<?php echo esc_attr( $i );?>">
						<?php esc_html_e( 'Email Message:', 'email-reminders-for-content-expiry-date' ); ?>
					</label>
					<br/>
					<?php
					wp_editor(
						'',
						esc_attr( $prefix ) . 'email_configuration_message_' . esc_attr( $i ),
						array(
							'textarea_name' => esc_attr( $prefix ) . 'email_configuration[' . esc_attr( $i ) . '][message]',
							'textarea_rows' => 5,
						)
					);
					?>
				</p>
				<p class="description"><?php esc_html_e( 'Message to send (shows after name and before expiry report).', 'email-reminders-for-content-expiry-date' ); ?></p>
			</div>
		</div>
		<?php
	}
}
