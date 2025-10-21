<?php namespace um_ext\um_jobboardwp\common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Setup
 *
 * @package um_ext\um_jobboardwp\common
 */
class Setup {

	/**
	 * @var array
	 *
	 * @since 1.0
	 */
	public $settings_defaults;

	/**
	 * Setup constructor.
	 */
	public function __construct() {
		//settings defaults
		$this->settings_defaults = array(
			'profile_tab_jobboardwp'           => 1,
			'profile_tab_jobboardwp_privacy'   => 0,
			'account_tab_jobboardwp'           => 1,
			'profile_tab_jobboardwp_dashboard' => 1,
		);

		$notification_types_templates = array(
			'jb_job_approved' => __( 'Your <a href="{job_uri}">job</a> is now approved.', 'um-jobboardwp' ),
			'jb_job_expired'  => __( 'Your <a href="{job_uri}">job</a> is now expired.', 'um-jobboardwp' ),
		);

		foreach ( $notification_types_templates as $k => $template ) {
			$this->settings_defaults[ 'log_' . $k ]               = 1;
			$this->settings_defaults[ 'log_' . $k . '_template' ] = $template;
		}
	}

	/**
	 * @since 1.0
	 */
	public function set_default_settings() {
		$options = get_option( 'um_options', array() );

		foreach ( $this->settings_defaults as $key => $value ) {
			//set new options to default
			if ( ! isset( $options[ $key ] ) ) {
				$options[ $key ] = $value;
			}
		}

		update_option( 'um_options', $options );
	}

	/**
	 * @since 1.0
	 */
	public function run_setup() {
		$this->set_default_settings();
	}
}
