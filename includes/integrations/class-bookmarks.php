<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bookmarks
 * @package um_ext\um_jobboardwp\integrations
 */
class Bookmarks {

	/**
	 * Bookmarks constructor.
	 */
	public function __construct() {
		add_filter( 'jb_jobs_job_data_response', array( &$this, 'add_bookmarks_action' ), 10, 2 );
		add_filter( 'um_bookmarks_add_button_args', array( $this, 'remove_text_ajax' ), 10, 1 );
		add_filter( 'um_bookmarks_remove_button_args', array( $this, 'remove_text_ajax' ), 10, 1 );
		add_filter( 'um_user_bookmarks_exclude', array( $this, 'remove_filled_expired_bookmarks' ), 10, 1 );
		add_filter( 'um_user_bookmarks_change_count', array( $this, 'remove_filled_expired_count' ), 10, 2 );
		add_filter( 'jb-jobs-scripts-enqueue', array( $this, 'add_js_scripts' ), 10, 1 );
	}

	/**
	 * @param array $job_data
	 * @param \WP_Post $job_post
	 *
	 * @return mixed
	 */
	public function add_bookmarks_action( $job_data, $job_post ) {
		if ( UM()->User_Bookmarks() === false ) {
			return $job_data;
		}

		if ( ! is_user_logged_in() || ! um_user( 'enable_bookmark' ) ) {
			return $job_data;
		}

		add_filter( 'um_bookmarks_add_button_args', array( $this, 'remove_text' ), 10, 1 );
		add_filter( 'um_bookmarks_remove_button_args', array( $this, 'remove_text' ), 10, 1 );

		$button = UM()->User_Bookmarks()->common()->bookmarks()->get_bookmarks_button( $job_post->ID, false );

		if ( ! empty( $button ) ) {
			$job_data['actions'][] = array(
				'html' => $button,
			);
		}

		remove_filter( 'um_bookmarks_add_button_args', array( $this, 'remove_text' ) );
		remove_filter( 'um_bookmarks_remove_button_args', array( $this, 'remove_text' ) );

		return $job_data;
	}


	public function remove_text_ajax( $button_args ) {
		if ( ! UM()->is_request( 'ajax' ) ) {
			return $button_args;
		}
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $button_args['post_id'] ) && ! empty( $_REQUEST['job_list'] ) ) {
			$post = get_post( $button_args['post_id'] );
			if ( ! empty( $post ) && ! is_wp_error( $post ) ) {
				if ( 'jb-job' === $post->post_type ) {
					$button_args['text'] = '';
				}
			}
		}

		return $button_args;
	}

	public function remove_filled_expired_bookmarks( $bookmarks ) {
		$hide_filled  = JB()->options()->get( 'jobs-list-hide-filled' );
		$hide_expired = JB()->options()->get( 'jobs-list-hide-expired' );

		if ( $hide_filled || $hide_expired ) {
			foreach ( $bookmarks as $key => $id ) {
				if ( 'jb-job' === get_post_type( $id ) ) {
					if ( $hide_filled && JB()->common()->job()->is_filled( $id ) ) {
						unset( $bookmarks[ $key ] );
					}
					if ( $hide_expired && JB()->common()->job()->is_expired( $id ) ) {
						unset( $bookmarks[ $key ] );
					}
				}
			}
		}

		return $bookmarks;
	}

	public function remove_filled_expired_count( $count, $bookmarks ) {
		$hide_filled  = JB()->options()->get( 'jobs-list-hide-filled' );
		$hide_expired = JB()->options()->get( 'jobs-list-hide-expired' );

		if ( $hide_filled || $hide_expired ) {
			$bookmarks = array_keys( $bookmarks );
			foreach ( $bookmarks as $key => $id ) {
				if ( 'jb-job' === get_post_type( $id ) ) {
					if ( $hide_filled && JB()->common()->job()->is_filled( $id ) ) {
						unset( $bookmarks[ $key ] );
					}
					if ( $hide_expired && JB()->common()->job()->is_expired( $id ) ) {
						unset( $bookmarks[ $key ] );
					}
				}
			}
			$count = count( $bookmarks );
		}

		return $count;
	}

	public function remove_text( $button_args ) {
		if ( ! empty( $button_args['post_id'] ) ) {
			$post = get_post( $button_args['post_id'] );
			if ( ! empty( $post ) && ! is_wp_error( $post ) ) {
				if ( 'jb-job' === $post->post_type ) {
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
	public function add_js_scripts( $scripts ) {
		$post_types = (array) UM()->options()->get( 'um_user_bookmarks_post_types' );
		if ( ! in_array( 'jb-job', $post_types, true ) ) {
			return $scripts;
		}

		wp_register_script( 'um-jb-bookmarks', UM_JOBBOARDWP_URL . 'assets/js/bookmarks' . UM()->frontend()->enqueue()::get_suffix() . '.js', array( 'wp-hooks' ), UM_JOBBOARDWP_VERSION, true );

		$scripts[] = 'um-jb-bookmarks';
		return $scripts;
	}
}
