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
		add_filter( 'um_messaging_settings_fields', array( &$this, 'add_messaging_settings' ), 10, 1 );
		add_action( 'jb_after_job_apply_block', array( &$this, 'add_private_message_button' ), 10, 1 );
	}

	/**
	 * @param array $settings_fields
	 *
	 * @return array
	 */
	public function add_messaging_settings( $settings_fields ) {
		$settings_fields[] = array(
			'id'      => 'job_show_pm_button',
			'type'    => 'checkbox',
			'label'   => __( 'Show messages button in individual job post', 'um-jobboardwp' ),
			'tooltip' => __( 'Start private messaging with a job author.', 'um-jobboardwp' ),
		);

		return $settings_fields;
	}

	/**
	 * @param int $job_id
	 */
	public function add_private_message_button( $job_id ) {
		if ( empty( UM()->classes['um_messaging_main_api'] ) ) {
			return;
		}

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

		if ( version_compare( get_bloginfo( 'version' ), '5.4', '<' ) ) {
			echo do_shortcode( '[ultimatemember_message_button user_id="' . absint( $job->post_author ) . '"]' );
		} else {
			echo apply_shortcodes( '[ultimatemember_message_button user_id="' . absint( $job->post_author ) . '"]' );
		}
	}
}
