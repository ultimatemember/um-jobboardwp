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
		$fields = array(
			'account_tab_jobboardwp' => array(
				'label' => __( 'Account Tab', 'um-jobboardwp' ),
				'value' => UM()->options()->get( 'account_tab_jobboardwp' ) ? __( 'Yes', 'um-jobboardwp' ) : __( 'No', 'um-jobboardwp' ),
			),
		);
		$fields = apply_filters( 'um_debug_information_jobboardwp_fields', $fields );

		$info['um-jobboardwp'] = array(
			'label'       => UM_JOBBOARDWP_EXTENSION,
			// translators: %s is the plugin name.
			'description' => sprintf( __( 'This debug information for your "%s" extension installation can assist you in getting support.', 'um-jobboardwp' ), UM_JOBBOARDWP_EXTENSION ),
			'fields'      => $fields,
		);

		return $info;
	}

	public function role_debug_information( $info, $rolemeta ) {
		$labels = array(
			'yes' => __( 'Yes', 'um-jobboardwp' ),
			'no'  => __( 'No', 'um-jobboardwp' ),
		);

		$info[] = array(
			'disable_jobs_tab'          => array(
				'label' => __( 'Disable jobs tab? ', 'um-jobboardwp' ),
				'value' => ! empty( $rolemeta['_um_disable_jobs_tab'] ) ? $labels['yes'] : $labels['no'],
			),
			'disable_job_dashboard_tab' => array(
				'label' => __( 'Disable jobs tab? ', 'um-jobboardwp' ),
				'value' => ! empty( $rolemeta['_um_disable_job_dashboard_tab'] ) ? $labels['yes'] : $labels['no'],
			),
		);

		return $info;
	}
}
