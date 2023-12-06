<?php namespace um_ext\um_jobboardwp\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Admin
 *
 * @package um_ext\um_jobboardwp\core
 */
class Admin {


	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_filter( 'um_admin_role_metaboxes', array( &$this, 'add_role_metabox' ), 10, 1 );
		add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ), 10, 1 );
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
	public function add_role_metabox( $roles_metaboxes ) {
		$roles_metaboxes[] = array(
			'id'       => 'um-admin-form-jobboardwp{' . um_jobboardwp_path . '}',
			'title'    => __( 'JobBoardWP', 'um-jobboardwp' ),
			'callback' => array( UM()->metabox(), 'load_metabox_role' ),
			'screen'   => 'um_role_meta',
			'context'  => 'normal',
			'priority' => 'default',
		);

		return $roles_metaboxes;
	}


	/**
	 * Extend settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function extend_settings( $settings ) {
		$settings['extensions']['sections']['jobboardwp'] = array(
			'title'  => __( 'JobBoardWP', 'um-jobboardwp' ),
			'fields' => array(
				array(
					'id'      => 'account_tab_jobboardwp',
					'type'    => 'checkbox',
					'label'   => __( 'Account Tab', 'um-jobboardwp' ),
					'tooltip' => __( 'Show or hide an account tab that shows the jobs dashboard.', 'um-jobboardwp' ),
				),
				array(
					'id'      => 'welcome_email_jobboardwp',
					'type'    => 'checkbox',
					'label'   => __( 'Welcome email', 'um-jobboardwp' ),
					'tooltip' => __( 'Use the "Welcome email" Ultimate Member template for a new registered user', 'um-jobboardwp' ),
				),
			),
		);

		return $settings;
	}
}
