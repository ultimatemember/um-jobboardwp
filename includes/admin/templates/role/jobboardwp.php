<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">
	<?php $role = $object['data'];

	UM()->admin_forms( [
		'class'     => 'um-role-jobboardwp um-half-column',
		'prefix_id' => 'role',
		'fields'    => [
			[
				'id'        => '_um_disable_jobs_tab',
				'type'      => 'checkbox',
				'label'     => __( 'Disable jobs tab?', 'um-jobboardwp' ),
				'tooltip'   => __( 'If you turn this off, this role will not have a jobs tab active in their profile.', 'um-jobboardwp' ),
				'value'     => ! empty( $role['_um_disable_jobs_tab'] ) ? $role['_um_disable_jobs_tab'] : 0,
			],
		],
	] )->render_form(); ?>
</div>