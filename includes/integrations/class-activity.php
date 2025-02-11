<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Activity
 * @package um_ext\um_jobboardwp\integrations
 */
class Activity {

	/**
	 * Activity constructor.
	 */
	public function __construct() {
		add_filter( 'um_activity_global_actions', array( &$this, 'social_activity_action' ), 10, 1 );
		add_action( 'jb_job_submission_after_create_account', array( &$this, 'social_activity_new_user' ), 10, 1 );
		add_action( 'jb_job_submission_after_create_account', array( &$this, 'maybe_verify' ), 11, 1 );
	}

	/**
	 * Add new activity action
	 *
	 * @param array $actions
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function social_activity_action( $actions ) {
		$actions['new-jobboardwp-job']    = __( 'New job', 'um-jobboardwp' );
		$actions['jobboardwp-job-filled'] = __( 'Job is filled', 'um-jobboardwp' );
		return $actions;
	}

	/**
	 * Add new user activity post
	 *
	 * @param array $user_id
	 */
	public function social_activity_new_user( $user_id ) {
		do_action( 'um_after_user_is_approved', $user_id );
	}


	/**
	 * Maybe auto-verify user after registration on posting job
	 * based on UM role settings
	 *
	 * @param $user_id
	 */
	public function maybe_verify( $user_id ) {
		if ( function_exists( 'um_verified_registration_complete' ) ) {
			um_verified_registration_complete( $user_id );
		}
	}
}
