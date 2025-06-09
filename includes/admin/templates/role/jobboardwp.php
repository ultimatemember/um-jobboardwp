<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$role_data = $object['data'];
?>

<div class="um-admin-metabox">
	<?php
	UM()->admin_forms(
		array(
			'class'     => 'um-role-jobboardwp um-half-column',
			'prefix_id' => 'role',
			'fields'    => array(
				array(
					'id'             => '_um_disable_jobs_tab',
					'type'           => 'checkbox',
					'label'          => __( 'Jobs tab', 'um-jobboardwp' ),
					'checkbox_label' => __( 'Disable jobs tab', 'um-jobboardwp' ),
					'description'    => __( 'If you turn this off, this role will not have a jobs tab active in their profile.', 'um-jobboardwp' ),
					'value'          => ! empty( $role_data['_um_disable_jobs_tab'] ) ? $role_data['_um_disable_jobs_tab'] : 0,
				),
				array(
					'id'             => '_um_disable_job_dashboard_tab',
					'type'           => 'checkbox',
					'label'          => __( 'Job dashboard tab', 'um-jobboardwp' ),
					'checkbox_label' => __( 'Disable job dashboard tab', 'um-jobboardwp' ),
					'description'    => __( 'If you turn this off, this role will not have a job dashboard tab active in their profile.', 'um-jobboardwp' ),
					'value'          => ! empty( $role_data['_um_disable_job_dashboard_tab'] ) ? $role_data['_um_disable_job_dashboard_tab'] : 0,
				),
			),
		)
	)->render_form();
	?>
</div>
