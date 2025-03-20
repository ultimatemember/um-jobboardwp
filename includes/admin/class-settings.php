<?php
namespace um_ext\um_jobboardwp\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings
 * @package um_ext\um_jobboardwp\admin
 */
class Settings {

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ), 10, 1 );
		add_filter( 'um_settings_map', array( &$this, 'settings_map' ) );
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
			),
		);

		return $settings;
	}

	/**
	 * @param array $settings_map
	 *
	 * @return array
	 */
	public function settings_map( $settings_map ) {
		return array_merge(
			$settings_map,
			array(
				'account_tab_jobboardwp' => array(
					'sanitize' => 'bool',
				),
			)
		);
	}
}
