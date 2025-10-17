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

		// New UI only for proper displaying JS templates on the User Profile page
		add_action( 'um_pre_profile_shortcode', array( &$this, 'custom_js_template_profile' ) );
		add_action( 'um_pre_account_shortcode', array( &$this, 'custom_js_template_account' ) );
		add_action( 'jb_before_jobs_list_shortcode', array( &$this, 'move_template_to_footer' ) );
		add_action( 'jb_before_jobs_dashboard_shortcode', array( &$this, 'move_template_to_footer' ) );
	}

	public function custom_js_template_profile() {
		if ( ! um_is_predefined_page( 'user' ) ) {
			return;
		}

		$active_tab = UM()->profile()->active_tab();
		if ( 'jobboardwp' === $active_tab ) {
			$jb_jobs_list = array(
				'employer-id'          => um_profile_id(),
				'hide-search'          => true,
				'no-logo'              => true,
				'hide-location-search' => true,
				'hide-filters'         => true,
				'hide-job-types'       => true,
			);
			JB()->get_template_part( 'js/jobs-list', $jb_jobs_list );
		} elseif ( 'jobboardwp_dashboard' === $active_tab ) {
			JB()->get_template_part( 'js/jobs-dashboard' );
		}
	}

	public function custom_js_template_account() {
		if ( ! UM()->is_new_ui() ) {
			return;
		}

		if ( ! um_is_predefined_page( 'account' ) ) {
			return;
		}

		$account_tab = get_query_var( 'um_tab' );
		if ( 'jobboardwp' === $account_tab ) {
			JB()->get_template_part( 'js/jobs-dashboard' );
		}
	}

	public function move_template_to_footer() {
		if ( um_is_predefined_page( 'account' ) && UM()->is_new_ui() ) {
			add_action( 'jb_change_template_part', array( &$this, 'jb_change_template_part' ) );
			add_action( 'um_after_account_page_load', array( &$this, 'return_proper_content' ) );
		} elseif ( um_is_predefined_page( 'user' ) ) {
			$active_tab = UM()->profile()->active_tab();
			if ( 'jobboardwp' === $active_tab || 'jobboardwp_dashboard' === $active_tab ) {
				add_action( 'jb_change_template_part', array( &$this, 'jb_change_template_part' ) );
				add_action( 'um_profile_footer', array( &$this, 'return_proper_content' ) );
			}
		}
	}

	public function jb_change_template_part( &$template_name ) {
		if ( 'js/jobs-list' === $template_name || 'js/jobs-dashboard' === $template_name ) {
			$template_name = '';
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
		echo wp_kses( apply_shortcodes( '[jb_jobs employer-id="' . um_profile_id() . '" hide-search="1" hide-location-search="1" hide-filters="1" hide-job-types="1" /]' ), UM()->get_allowed_html( 'templates' ) );
	}

	/**
	 *
	 * @since 1.0
	 */
	public function profile_tab_dashboard_content() {
		echo wp_kses( apply_shortcodes( '[jb_jobs_dashboard /]' ), UM()->get_allowed_html( 'templates' ) );
	}
}
