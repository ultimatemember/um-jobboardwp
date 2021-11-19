<?php
namespace um_ext\um_jobboardwp\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Integrations
 *
 * @package um_ext\um_jobboardwp\core
 */
class Integrations {


	/**
	 * Integrations constructor.
	 */
	function __construct() {
		// UM: Social Activity integration
		add_filter( 'um_activity_global_actions', [ &$this, 'social_activity_action' ], 10, 1 );
		add_action( 'jb_job_submission_after_create_account', [ &$this, 'social_activity_new_user' ], 10, 1 );

		// UM: Notifications integration
		add_filter( 'um_notifications_core_log_types', [ &$this, 'add_notifications' ], 300, 1 );

		// UM: Verified Users integration
		add_filter( 'um_verified_users_settings_fields', [ &$this, 'add_verified_users_settings' ], 10, 1 );
		add_filter( 'jb_can_applied_job', [ &$this, 'lock_for_unverified' ], 10, 2 );

		// UM: Messaging integration
		add_filter( 'um_messaging_settings_fields', [ &$this, 'add_messaging_settings' ], 10, 1 );
		add_action( 'jb_after_job_apply_block', [ &$this, 'add_private_message_button' ], 10, 1 );


		// UM: Bookmarks integration
		add_filter( 'jb_jobs_job_data_response', [ &$this, 'add_bookmarks_action' ], 10, 2 );
		add_filter( 'um_bookmarks_add_button_args', [ $this, 'remove_text_ajax' ], 10, 1 );
		add_filter( 'um_bookmarks_remove_button_args', [ $this, 'remove_text_ajax' ], 10, 1 );

		add_filter( 'jb-jobs-scripts-enqueue', [ $this, 'add_js_scripts' ], 10, 1 );
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
	function social_activity_action( $actions ) {
		$actions['new-jobboardwp-job'] = __( 'New job', 'um-jobboardwp' );
		$actions['jobboardwp-job-filled'] = __( 'Job is filled', 'um-jobboardwp' );
		return $actions;
	}

	/**
	 * Add new user activity post
	 *
	 * @param array $user_id
	 */
	function social_activity_new_user( $user_id ) {
		do_action( 'um_after_user_is_approved', $user_id );
	}


	/**
	 * Adds a notification type
	 *
	 * @param array $logs
	 *
	 * @return array
	 */
	function add_notifications( $logs ) {
		$logs['jb_job_approved'] = array(
			'title'         => __( 'Your job is approved', 'um-jobboardwp' ),
			'account_desc'  => __( 'When your job gets approved status', 'um-jobboardwp' ),
		);
		$logs['jb_job_expired'] = array(
			'title'         => __( 'Your job is expired', 'um-jobboardwp' ),
			'account_desc'  => __( 'When your job gets expired status', 'um-jobboardwp' ),
		);
		return $logs;
	}


	/**
	 * @param array $settings_fields
	 *
	 * @return array
	 */
	function add_verified_users_settings( $settings_fields ) {
		$settings_fields[] = array(
			'id'        => 'job_apply_only_verified',
			'type'      => 'checkbox',
			'label'     => __( 'Only verified users can apply for jobs', 'um-jobboardwp' ),
			'tooltip'   => __( 'Unverified users cannot apply the jobs.', 'um-jobboardwp' ),
		);

		return $settings_fields;
	}


	/**
	 * @param bool $can_applied
	 * @param int $job_id
	 *
	 * @return bool
	 */
	function lock_for_unverified( $can_applied, $job_id ) {
		if ( UM()->Verified_Users_API() === false ) {
			return $can_applied;
		}

		if ( ! UM()->options()->get( 'job_apply_only_verified' ) ) {
			return $can_applied;
		}

		if ( is_user_logged_in() && ! UM()->Verified_Users_API()->api()->is_verified( get_current_user_id() ) ) {
			$can_applied = false;
		}

		return $can_applied;
	}


	/**
	 * @param array $settings_fields
	 *
	 * @return array
	 */
	function add_messaging_settings( $settings_fields ) {
		$settings_fields[] = array(
			'id'        => 'job_show_pm_button',
			'type'      => 'checkbox',
			'label'     => __( 'Show messages button in individual job post', 'um-jobboardwp' ),
			'tooltip'   => __( 'Start private messaging with a job author.', 'um-jobboardwp' ),
		);

		return $settings_fields;
	}


	/**
	 * @param int $job_id
	 */
	function add_private_message_button( $job_id ) {
		if ( ! UM()->options()->get( 'job_show_pm_button' ) ) {
			return;
		}

		$job = get_post( $job_id );

		if ( empty( $job ) || is_wp_error( $job ) ) {
			return;
		}

		if ( is_user_logged_in() && get_current_user_id() == $job->post_author ) {
			return;
		}

		if ( version_compare( get_bloginfo( 'version' ),'5.4', '<' ) ) {
			echo do_shortcode( '[ultimatemember_message_button user_id="' . $job->post_author . '"]' );
		} else {
			echo apply_shortcodes( '[ultimatemember_message_button user_id="' . $job->post_author . '"]' );
		}
	}


	/**
	 * @param array $job_data
	 * @param \WP_Post $job_post
	 *
	 * @return mixed
	 */
	function add_bookmarks_action( $job_data, $job_post ) {
		if ( UM()->User_Bookmarks() === false ) {
			return $job_data;
		}

		add_filter( 'um_bookmarks_add_button_args', [ $this, 'remove_text' ], 10, 1 );
		add_filter( 'um_bookmarks_remove_button_args', [ $this, 'remove_text' ], 10, 1 );

		$button = UM()->User_Bookmarks()->common()->get_bookmarks_button( $job_post->ID, false );

		if ( ! empty( $button ) ) {
			$job_data['actions'][] = [
				'html' => $button,
			];
		}

		remove_filter( 'um_bookmarks_add_button_args', [ $this, 'remove_text' ] );
		remove_filter( 'um_bookmarks_remove_button_args', [ $this, 'remove_text' ] );

		return $job_data;
	}


	function remove_text_ajax( $button_args ) {
		if ( ! UM()->is_request( 'ajax' ) ) {
			return $button_args;
		}

		if ( ! empty( $button_args['post_id'] ) && ! empty( $_REQUEST['job_list'] ) ) {
			$post = get_post( $button_args['post_id'] );
			if ( ! empty( $post ) && ! is_wp_error( $post ) ) {
				if ( $post->post_type == 'jb-job' ) {
					$button_args['text'] = '';
				}
			}
		}

		return $button_args;
	}


	function remove_text( $button_args ) {
		if ( ! empty( $button_args['post_id'] ) ) {
			$post = get_post( $button_args['post_id'] );
			if ( ! empty( $post ) && ! is_wp_error( $post ) ) {
				if ( $post->post_type == 'jb-job' ) {
					$button_args['text'] = '';
				}
			}
		}

		return $button_args;
	}


	/**
	 * @param array $scripts
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	function add_js_scripts( $scripts ) {

		$post_types = ( array ) UM()->options()->get( 'um_user_bookmarks_post_types' );
		if ( ! in_array( 'jb-job', $post_types ) ) {
			return $scripts;
		}

		wp_register_script('um-jb-bookmarks', um_jobboardwp_url . 'assets/js/bookmarks' . UM()->enqueue()->suffix . '.js', [ 'wp-hooks' ], um_jobboardwp_version, true );

		$scripts[] = 'um-jb-bookmarks';
		return $scripts;
	}
}