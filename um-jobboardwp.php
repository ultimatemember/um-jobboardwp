<?php
/*
Plugin Name: Ultimate Member - JobBoardWP integration
Plugin URI: https://ultimatemember.com/extensions/jobboardwp/
Description: Integrates Ultimate Member with JobBoardWP
Version: 1.0.1
Author: Ultimate Member
Author URI: http://ultimatemember.com/
Text Domain: um-jobboardwp
Domain Path: /languages
UM version: 2.1.7
JobBoardWP version: 1.0.1
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$plugin_data = get_plugin_data( __FILE__ );

define( 'um_jobboardwp_url', plugin_dir_url( __FILE__ ) );
define( 'um_jobboardwp_path', plugin_dir_path( __FILE__ ) );
define( 'um_jobboardwp_plugin', plugin_basename( __FILE__ ) );
define( 'um_jobboardwp_extension', $plugin_data['Name'] );
define( 'um_jobboardwp_version', $plugin_data['Version'] );
define( 'um_jobboardwp_textdomain', 'um-jobboardwp' );

define( 'um_jobboardwp_requires', '2.1.7' );

function um_jobboardwp_plugins_loaded() {
	$locale = ( get_locale() != '' ) ? get_locale() : 'en_US';
	load_textdomain( um_jobboardwp_textdomain, WP_LANG_DIR . '/plugins/' . um_jobboardwp_textdomain . '-' . $locale . '.mo');
	load_plugin_textdomain( um_jobboardwp_textdomain, false, dirname( plugin_basename(  __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'um_jobboardwp_plugins_loaded', 0 );


add_action( 'plugins_loaded', 'um_jobboardwp_check_dependencies', -20 );

if ( ! function_exists( 'um_jobboardwp_check_dependencies' ) ) {
	function um_jobboardwp_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! file_exists( um_path  . 'includes/class-dependencies.php' ) ) {
			//UM is not installed
			function um_jobboardwp_dependencies() {
				echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-jobboardwp' ), um_jobboardwp_extension ) . '</p></div>';
			}

			add_action( 'admin_notices', 'um_jobboardwp_dependencies' );
		} else {

			if ( ! function_exists( 'UM' ) ) {
				require_once um_path . 'includes/class-dependencies.php';
				$is_um_active = um\is_um_active();
			} else {
				$is_um_active = UM()->dependencies()->ultimatemember_active_check();
			}

			if ( ! $is_um_active ) {
				//UM is not active
				function um_jobboardwp_dependencies() {
					echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-jobboardwp' ), um_jobboardwp_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );

			} elseif ( true !== UM()->dependencies()->compare_versions( um_jobboardwp_requires, um_jobboardwp_version, 'jobboardwp', um_jobboardwp_extension ) ) {
				//UM old version is active
				function um_jobboardwp_dependencies() {
					echo '<div class="error"><p>' . UM()->dependencies()->compare_versions( um_jobboardwp_requires, um_jobboardwp_version, 'jobboardwp', um_jobboardwp_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );

			} elseif ( ! UM()->dependencies()->jobboardwp_active_check() ) {
				//UM is not active
				function um_jobboardwp_dependencies() {
					echo '<div class="error"><p>' . sprintf( __( 'Sorry. You must activate the <strong>JobBoardWP</strong> plugin to use the %s.', 'um-jobboardwp' ), um_jobboardwp_extension ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );
			} else {
				require_once um_jobboardwp_path . 'includes/core/um-jobboardwp-init.php';
			}
		}
	}
}


if ( ! function_exists( 'um_jobboardwp_activation_hook' ) ) {
	function um_jobboardwp_activation_hook() {
		//first install
		$version = get_option( 'um_jobboardwp_version' );
		if ( ! $version ) {
			update_option( 'um_jobboardwp_last_version_upgrade', um_jobboardwp_version );
		}

		if ( $version != um_jobboardwp_version ) {
			update_option( 'um_jobboardwp_version', um_jobboardwp_version );
		}

		//run setup
		if ( ! class_exists( 'um_ext\um_jobboardwp\core\Setup' ) ) {
			require_once um_jobboardwp_path . 'includes/core/class-setup.php';
		}

		$fmwp_setup = new um_ext\um_jobboardwp\core\Setup();
		$fmwp_setup->run_setup();
	}
}
register_activation_hook( um_jobboardwp_plugin, 'um_jobboardwp_activation_hook' );