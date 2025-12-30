<?php namespace um_ext\um_jobboardwp\common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Access
 *
 * @package um_ext\um_jobboardwp\common
 */
class Access {

	/**
	 * Access constructor.
	 */
	public function __construct() {
		add_action( 'jb_before_check_for_reminder_expired_jobs', array( &$this, 'before_check_for_reminder_expired_jobs' ) );
	}

	public function before_check_for_reminder_expired_jobs() {
		add_filter( 'um_ignore_restricted_title', array( &$this, 'ignore_job_restricted_title' ), 10, 2 );
		add_action( 'jb_after_check_for_reminder_expired_jobs', array( &$this, 'after_check_for_reminder_expired_jobs' ) );
	}

	public function ignore_job_restricted_title( $ignore_title, $id ) {
		$maybe_job = get_post( $id );
		if ( 'jb-job' !== $maybe_job->post_type ) {
			return $ignore_title;
		}

		return true;
	}

	public function after_check_for_reminder_expired_jobs() {
		remove_filter( 'um_ignore_restricted_title', array( &$this, 'ignore_job_restricted_title' ) );
	}
}
