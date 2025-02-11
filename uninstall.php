<?php
/**
 * Uninstall UM JobBoardWP
 *
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! defined( 'UM_JOBBOARDWP_PATH' ) ) {
	define( 'UM_JOBBOARDWP_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'UM_JOBBOARDWP_URL' ) ) {
	define( 'UM_JOBBOARDWP_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'UM_JOBBOARDWP_PLUGIN' ) ) {
	define( 'UM_JOBBOARDWP_PLUGIN', plugin_basename( __FILE__ ) );
}

$options = get_option( 'um_options', array() );

if ( ! empty( $options['uninstall_on_delete'] ) ) {
	if ( ! class_exists( 'um_ext\um_jobboardwp\common\Setup' ) ) {
		require_once UM_JOBBOARDWP_PATH . 'includes/common/class-setup.php';
	}

	$jb_setup = new um_ext\um_jobboardwp\common\Setup();

	//remove settings
	foreach ( $jb_setup->settings_defaults as $k => $v ) {
		unset( $options[ $k ] );
	}

	update_option( 'um_options', $options );

	delete_option( 'um_jobboardwp_last_version_upgrade' );
	delete_option( 'um_jobboardwp_version' );
}
