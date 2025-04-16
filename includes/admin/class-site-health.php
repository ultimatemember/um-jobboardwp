<?php
namespace um_ext\um_jobboardwp\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Site_Health
 *
 * @package um_ext\um_jobboardwp\admin
 */
class Site_Health {

	/**
	 * Site_Health constructor.
	 */
	public function __construct() {
		add_filter( 'debug_information', array( $this, 'debug_information' ), 20 );
		add_filter( 'um_debug_information_user_role', array( $this, 'role_debug_information' ), 10, 2 );
	}

	public function debug_information( $info ) {
		$info['um-jobboardwp'] = array(
			'label'       => __( 'UM JobBoardWP', 'um-jobboardwp' ),
			'description' => __( 'This debug information for your UM JobBoardWP extension installation can assist you in getting support.', 'um-jobboardwp' ),
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
