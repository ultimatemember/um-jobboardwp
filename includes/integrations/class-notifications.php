<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Notifications
 * @package um_ext\um_jobboardwp\integrations
 */
class Notifications {

	/**
	 * Notifications constructor.
	 */
	public function __construct() {
		add_filter( 'um_notifications_core_log_types', array( &$this, 'add_notifications' ), 300, 1 );
	}

	/**
	 * Adds a notification type
	 *
	 * @param array $logs
	 *
	 * @return array
	 */
	public function add_notifications( $logs ) {
		$logs['jb_job_approved'] = array(
			'title'        => __( 'Your job is approved', 'um-jobboardwp' ),
			'account_desc' => __( 'When your job gets approved status', 'um-jobboardwp' ),
		);
		$logs['jb_job_expired']  = array(
			'title'        => __( 'Your job is expired', 'um-jobboardwp' ),
			'account_desc' => __( 'When your job gets expired status', 'um-jobboardwp' ),
		);
		return $logs;
	}
}
