<?php namespace um_ext\um_jobboardwp\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Admin
 *
 * @package um_ext\um_jobboardwp\core
 */
class Mail {


	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'jb_job_submission_after_create_account', array( &$this, 'send_email' ), 10, 1 );
		add_filter( 'jb_job_email_notify', array( &$this, 'jb_job_email_notify' ), 10, 1 );
	}


	/**
	 * Send email
	 *
	 * @param $user_id
	 *
	 * @since 1.0.8
	 */
	public function send_email( $user_id ) {
		if ( UM()->options()->get( 'welcome_email_jobboardwp' ) ) {
			$user  = get_userdata( $user_id );
			$email = $user->user_email;
			UM()->mail()->send( $email, 'welcome_email' );
		}
	}


	/**
	 * Disable default JobBoard email notification for registered users
	 *
	 * @param string $notify
	 *
	 * @return string
	 *
	 * @since 1.0.8
	 */
	public function jb_job_email_notify( $notify ) {
		if ( UM()->options()->get( 'welcome_email_jobboardwp' ) ) {
			$notify = 'admin';
		}

		return $notify;
	}
}
