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
		$key = ! empty( $settings['extensions']['sections'] ) ? 'jobboardwp' : '';
		$settings['extensions']['sections'][ $key ] = [
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
}