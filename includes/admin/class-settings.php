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
		add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ) );
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
		$settings['']['sections']['account']['form_sections'] = UM()->array_insert_before(
			$settings['']['sections']['account']['form_sections'],
			'delete_tab',
			array(
				'jobboardwp_tab' => array(
					'title'       => __( 'JobBoardWP: Jobs Dashboard tab', 'um-jobboardwp' ),
					'description' => __( 'Enables you to enable or disable the "Jobs Dashboard" tab on the account page. Disable this tab if you wish to prevent users from being able to manage JobBoardWP Jobs on the account page.', 'um-jobboardwp' ),
					'fields'      => array(
						array(
							'id'             => 'account_tab_jobboardwp',
							'type'           => 'checkbox',
							'label'          => __( 'Jobs Dashboard Tab', 'um-jobboardwp' ),
							'checkbox_label' => __( 'Display Jobs Dashboard account tab', 'um-jobboardwp' ),
							'description'    => __( 'Enable or disable the "Jobs Dashboard" tab on the account page.', 'um-jobboardwp' ),
						),
					),
				),
			)
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
