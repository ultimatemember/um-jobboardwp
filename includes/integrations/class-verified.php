<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Integration with UM Verified Users
 *
 * Class Verified
 *
 * @package um_ext\um_jobboardwp\integrations
 */
class Verified {

	/**
	 * Verified constructor.
	 */
	public function __construct() {
		add_filter( 'um_verified_users_settings_fields', array( &$this, 'add_verified_users_settings' ), 10, 1 );
		add_filter( 'jb_can_applied_job', array( &$this, 'lock_for_unverified' ), 10, 2 );
	}

	/**
	 * @param array $settings_fields
	 *
	 * @return array
	 */
	public function add_verified_users_settings( $settings_fields ) {
		$settings_fields[] = array(
			'id'      => 'job_apply_only_verified',
			'type'    => 'checkbox',
			'label'   => __( 'Only verified users can apply for jobs', 'um-jobboardwp' ),
			'tooltip' => __( 'Unverified users cannot apply the jobs.', 'um-jobboardwp' ),
		);

		return $settings_fields;
	}

	/**
	 * @param bool $can_applied
	 * @param int $job_id
	 *
	 * @return bool
	 */
	public function lock_for_unverified( $can_applied, $job_id ) {
		if ( UM()->Verified_Users_API() === false ) {
			return $can_applied;
		}

		if ( ! UM()->options()->get( 'job_apply_only_verified' ) ) {
			return $can_applied;
		}

		if ( ! is_user_logged_in() || ( is_user_logged_in() && ! UM()->Verified_Users_API()->common()->verify()->is_verified( get_current_user_id() ) ) ) {
			$can_applied = false;
		}

		return $can_applied;
	}
}
