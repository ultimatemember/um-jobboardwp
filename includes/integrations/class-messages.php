<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Messages
 * @package um_ext\um_jobboardwp\integrations
 */
class Messages {

	/**
	 * Messages constructor.
	 */
	public function __construct() {
		add_filter( 'um_messaging_settings_fields', array( &$this, 'add_messaging_settings' ) );
		add_filter( 'um_settings_map', array( &$this, 'settings_map' ) );
		add_action( 'jb_after_job_apply_block', array( &$this, 'add_private_message_button' ) );
	}

	/**
	 * @param array $settings_fields
	 *
	 * @return array
	 */
	public function add_messaging_settings( $settings_fields ) {
		$settings_fields[] = array(
			'id'             => 'job_show_pm_button',
			'type'           => 'checkbox',
			'label'          => __( 'Messages button in individual job post', 'um-jobboardwp' ),
			'checkbox_label' => __( 'Display messages button in individual job post', 'um-jobboardwp' ),
			'description'    => __( 'Start private messaging with a job author.', 'um-jobboardwp' ),
		);

		return $settings_fields;
	}

	/**
	 * @param array $settings_map
	 *
	 * @return array
	 */
	public function settings_map( $settings_map ) {
		return array_merge(
			$settings_map,
			array(
				'job_show_pm_button' => array(
					'sanitize' => 'bool',
				),
			)
		);
	}

	/**
	 * @param int $job_id
	 */
	public function add_private_message_button( $job_id ) {
		if ( ! UM()->options()->get( 'job_show_pm_button' ) ) {
			return;
		}

		$job = get_post( $job_id );

		if ( empty( $job ) || is_wp_error( $job ) ) {
			return;
		}

		if ( is_user_logged_in() && get_current_user_id() === absint( $job->post_author ) ) {
			return;
		}

		echo wp_kses( apply_shortcodes( '[ultimatemember_message_button user_id="' . absint( $job->post_author ) . '"]' ), UM()->get_allowed_html( 'templates' ) );
	}
}
