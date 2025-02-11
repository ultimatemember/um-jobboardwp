<?php namespace um_ext\um_jobboardwp\common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Profile
 *
 * @package um_ext\um_jobboardwp\common
 */
class Profile {

	/**
	 * Profile constructor.
	 */
	public function __construct() {
		add_filter( 'um_profile_tabs', array( $this, 'add_profile_tab' ), 802 );
		add_filter( 'um_user_profile_tabs', array( $this, 'check_profile_tab_privacy' ), 1000, 1 );

		add_action( 'um_profile_content_jobboardwp', array( &$this, 'profile_tab_content' ) );
		add_action( 'um_profile_content_jobboardwp_dashboard', array( &$this, 'profile_tab_dashboard_content' ) );

		add_action( 'um_pre_profile_shortcode', array( &$this, 'custom_js_template' ) );
		add_action( 'jb_before_jobs_list_shortcode', array( &$this, 'move_template_to_footer' ) );
		add_action( 'jb_before_jobs_dashboard_shortcode', array( &$this, 'move_template_to_footer' ) );
	}

	public function move_template_to_footer() {
		if ( ! UM()->is_new_ui() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( um_is_predefined_page( 'user' ) && array_key_exists( 'profiletab', $_GET ) && ( 'jobboardwp' === $_GET['profiletab'] || 'jobboardwp_dashboard' === $_GET['profiletab'] ) ) {
			add_action( 'jb_change_template_part', array( &$this, 'jb_change_template_part' ) );
			add_action( 'um_profile_footer', array( &$this, 'return_proper_content' ) );
		}
	}

	public function jb_change_template_part( &$template_name ) {
		if ( 'js/jobs-list' === $template_name || 'js/jobs-dashboard' === $template_name ) {
			$template_name = '';
		}
	}

	public function custom_js_template() {
		if ( ! UM()->is_new_ui() ) {
			return;
		}
		if ( ! um_is_predefined_page( 'user' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( array_key_exists( 'profiletab', $_GET ) && 'jobboardwp' === $_GET['profiletab'] ) {
			$jb_jobs_list = array(
				'employer-id'          => um_profile_id(),
				'hide-search'          => true,
				'no-logo'              => true,
				'hide-location-search' => true,
				'hide-filters'         => true,
				'hide-job-types'       => true,
			);
			JB()->get_template_part( 'js/jobs-list', $jb_jobs_list );
		}
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( array_key_exists( 'profiletab', $_GET ) && 'jobboardwp_dashboard' === $_GET['profiletab'] ) {
			JB()->get_template_part( 'js/jobs-dashboard' );
		}
	}

	public function return_proper_content() {
		remove_action( 'jb_change_template_part', array( &$this, 'jb_change_template_part' ) );
	}

	/**
	 * Add profile tab
	 *
	 * @param array $tabs
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function add_profile_tab( $tabs ) {
		$tabs['jobboardwp'] = array(
			'name' => __( 'Jobs', 'um-jobboardwp' ),
			'icon' => 'um-faicon-list-alt',
		);

		$tabs['jobboardwp_dashboard'] = array(
			'name' => __( 'Job dashboard', 'um-jobboardwp' ),
			'icon' => 'um-faicon-list',
		);

		return $tabs;
	}

	/**
	 * Add tabs based on user
	 *
	 * @param array $tabs
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function check_profile_tab_privacy( $tabs ) {
		if ( empty( $tabs['jobboardwp'] ) && empty( $tabs['jobboardwp_dashboard'] ) ) {
			return $tabs;
		}

		$user_id = um_user( 'ID' );
		if ( ! $user_id ) {
			return $tabs;
		}

		if ( um_user( 'disable_jobs_tab' ) ) {
			unset( $tabs['jobboardwp'] );
		}

		if ( um_user( 'disable_job_dashboard_tab' ) || ! um_is_myprofile() ) {
			unset( $tabs['jobboardwp_dashboard'] );
		}

		return $tabs;
	}

	/**
	 *
	 * @since 1.0
	 */
	public function profile_tab_content() {
		echo apply_shortcodes( '[jb_jobs employer-id="' . um_profile_id() . '" hide-search="1" hide-location-search="1" hide-filters="1" hide-job-types="1" /]' );
	}

	/**
	 *
	 * @since 1.0
	 */
	public function profile_tab_dashboard_content() {
		echo apply_shortcodes( '[jb_jobs_dashboard /]' );
	}
}
