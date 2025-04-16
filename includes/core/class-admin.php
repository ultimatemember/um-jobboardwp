<?php
namespace um_ext\um_jobboardwp\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Admin
 *
 * @package um_ext\um_jobboardwp\core
 */
class Admin {


	/**
	 * Admin constructor.
	 */
	function __construct() {
		add_filter( 'um_admin_role_metaboxes', [ &$this, 'add_role_metabox' ], 10, 1 );

		add_filter( 'um_settings_structure', [ &$this, 'extend_settings' ], 10, 1 );

		add_filter( 'debug_information', array( $this, 'debug_information' ), 20 );
		add_filter( 'um_debug_information_user_role', array( $this, 'role_debug_information' ), 10, 2 );
	}


	/**
	 * Creates options in Role page
	 *
	 * @param array $roles_metaboxes
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	function add_role_metabox( $roles_metaboxes ) {
		$roles_metaboxes[] = [
			'id'        => 'um-admin-form-jobboardwp{' . um_jobboardwp_path . '}',
			'title'     => __( 'JobBoardWP', 'um-jobboardwp' ),
			'callback'  => [ UM()->metabox(), 'load_metabox_role' ],
			'screen'    => 'um_role_meta',
			'context'   => 'normal',
			'priority'  => 'default'
		];

		return $roles_metaboxes;
	}


	/**
	 * Extend settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	function extend_settings( $settings ) {
		$settings['extensions']['sections']['jobboardwp'] = [
			'title'     => __( 'JobBoardWP', 'um-jobboardwp' ),
			'fields'    => [
				[
					'id'        => 'account_tab_jobboardwp',
					'type'      => 'checkbox',
					'label'     => __( 'Account Tab', 'um-jobboardwp' ),
					'tooltip'   => __( 'Show or hide an account tab that shows the jobs dashboard.', 'um-jobboardwp' ),
				],
			],
		];

		return $settings;
	}

	public function debug_information( $info ) {
		$info['um-jobboardwp'] = array(
			'label'       => __( 'UM JobBoardwp', 'um-jobboardwp' ),
			'description' => __( 'This debug information for your UM JobBoardwp extension installation can assist you in getting support.', 'um-jobboardwp' ),
			'fields'      => array(
				'account_tab_jobboardwp' => array(
					'label' => __( 'Account Tab', 'um-jobboardwp' ),
					'value' => UM()->options()->get( 'account_tab_jobboardwp' ) ? __( 'Yes', 'um-jobboardwp' ) : __( 'No', 'um-jobboardwp' ),
				),
			),
		);

		return $info;
	}

	public function role_debug_information( $info, $key ) {
		$rolemeta = get_option( "um_role_{$key}_meta", false );

		$labels = array(
			'yes' => __( 'Yes', 'um-jobboardwp' ),
			'no'  => __( 'No', 'um-jobboardwp' ),
		);

		$ext_info = array(
			'um_disable_jobs_tab'          => array(
				'label' => __( 'Disable jobs tab? ', 'um-jobboardwp' ),
				'value' => ! empty( $rolemeta['_um_disable_jobs_tab'] ) ? $labels['yes'] : $labels['no'],
			),
			'um_disable_job_dashboard_tab' => array(
				'label' => __( 'Disable jobs tab? ', 'um-jobboardwp' ),
				'value' => ! empty( $rolemeta['_um_disable_job_dashboard_tab'] ) ? $labels['yes'] : $labels['no'],
			),
		);

		$info[ 'ultimate-member-' . $key ]['fields'] = array_merge(
			$info[ 'ultimate-member-' . $key ]['fields'],
			$ext_info
		);
		return $info;
	}
}
