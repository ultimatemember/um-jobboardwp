<?php
/**
 * Plugin Name: Ultimate Member - JobBoardWP integration
 * Plugin URI: https://ultimatemember.com/extensions/jobboardwp/
 * Description: Integrates Ultimate Member with JobBoardWP
 * Version: 1.1.0
 * Author: Ultimate Member
 * Author URI: http://ultimatemember.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: um-jobboardwp
 * Domain Path: /languages
 * Requires at least: 6.2
 * Requires PHP: 7.0
 * UM version: 2.9.2
 * JobBoardWP version: 1.3.2
 * Requires Plugins: ultimate-member, jobboardwp
 *
 * @package UM_JobBoardWP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin_data = get_plugin_data( __FILE__, true, false );

// phpcs:disable Generic.NamingConventions.UpperCaseConstantName
define( 'um_jobboardwp_url', plugin_dir_url( __FILE__ ) );
define( 'um_jobboardwp_path', plugin_dir_path( __FILE__ ) );
define( 'um_jobboardwp_plugin', plugin_basename( __FILE__ ) );
define( 'um_jobboardwp_extension', $plugin_data['Name'] );
define( 'um_jobboardwp_version', $plugin_data['Version'] );
define( 'um_jobboardwp_textdomain', 'um-jobboardwp' );
define( 'um_jobboardwp_requires', '2.9.2' );
// phpcs:enable Generic.NamingConventions.UpperCaseConstantName

define( 'UM_JOBBOARDWP_URL', plugin_dir_url( __FILE__ ) );
define( 'UM_JOBBOARDWP_PATH', plugin_dir_path( __FILE__ ) );
define( 'UM_JOBBOARDWP_PLUGIN', plugin_basename( __FILE__ ) );
define( 'UM_JOBBOARDWP_EXTENSION', $plugin_data['Name'] );
define( 'UM_JOBBOARDWP_VERSION', $plugin_data['Version'] );
define( 'UM_JOBBOARDWP_TEXTDOMAIN', 'um-jobboardwp' );
define( 'UM_JOBBOARDWP_REQUIRES', '2.9.2' );
define( 'UM_JOBBOARDWP_REQUIRES_NEW_UI', '3.0.0-alpha-20250528' );

function um_jobboardwp_plugins_loaded() {
	$locale = ( get_locale() !== '' ) ? get_locale() : 'en_US';
	load_textdomain( UM_JOBBOARDWP_TEXTDOMAIN, WP_LANG_DIR . '/plugins/' . UM_JOBBOARDWP_TEXTDOMAIN . '-' . $locale . '.mo' );
	load_plugin_textdomain( UM_JOBBOARDWP_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'um_jobboardwp_plugins_loaded', 0 );


add_action( 'plugins_loaded', 'um_jobboardwp_check_dependencies', -20 );

if ( ! function_exists( 'um_jobboardwp_check_dependencies' ) ) {
	function um_jobboardwp_check_dependencies() {
		if ( ! defined( 'um_path' ) || ! file_exists( UM_PATH . 'includes/class-dependencies.php' ) ) {
			//UM is not installed
			function um_jobboardwp_dependencies() {
				$allowed_html = array(
					'a'      => array(
						'href'   => array(),
						'target' => true,
					),
					'strong' => array(),
					'br'     => array(),
				);
				// translators: %s is the JobBoardWP extension name.
				echo '<div class="error"><p>' . wp_kses( sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-jobboardwp' ), UM_JOBBOARDWP_EXTENSION ), $allowed_html ) . '</p></div>';
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
					$allowed_html = array(
						'a'      => array(
							'href'   => array(),
							'target' => true,
						),
						'strong' => array(),
						'br'     => array(),
					);
					// translators: %s is the JobBoardWP extension name.
					echo '<div class="error"><p>' . wp_kses( sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-jobboardwp' ), UM_JOBBOARDWP_EXTENSION ), $allowed_html ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );

			} elseif ( ! UM()->is_new_ui() && true !== UM()->dependencies()->compare_versions( UM_JOBBOARDWP_REQUIRES, UM_JOBBOARDWP_VERSION, 'jobboardwp', UM_JOBBOARDWP_EXTENSION ) ) {
				// UM old version is active
				function um_jobboardwp_dependencies() {
					$allowed_html = array(
						'a'      => array(
							'href'   => array(),
							'target' => true,
						),
						'strong' => array(),
						'br'     => array(),
					);
					echo '<div class="error"><p>' . wp_kses( UM()->dependencies()->compare_versions( UM_JOBBOARDWP_REQUIRES, UM_JOBBOARDWP_VERSION, 'jobboardwp', UM_JOBBOARDWP_EXTENSION ), $allowed_html ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );
			} elseif ( UM()->is_new_ui() && true !== UM()->dependencies()->compare_versions( UM_JOBBOARDWP_REQUIRES_NEW_UI, UM_JOBBOARDWP_VERSION, 'jobboardwp', UM_JOBBOARDWP_EXTENSION ) ) {
				// UM old version is active
				function um_jobboardwp_dependencies() {
					$allowed_html = array(
						'a'      => array(
							'href'   => array(),
							'target' => true,
						),
						'strong' => array(),
						'br'     => array(),
					);
					echo '<div class="error"><p>' . wp_kses( UM()->dependencies()->compare_versions( UM_JOBBOARDWP_REQUIRES_NEW_UI, UM_JOBBOARDWP_VERSION, 'jobboardwp', UM_JOBBOARDWP_EXTENSION ), $allowed_html ) . '</p></div>';
				}

				add_action( 'admin_notices', 'um_jobboardwp_dependencies' );
			} else {
				require_once UM_JOBBOARDWP_PATH . 'includes/class-um-jobboardwp.php';
				UM()->set_class( 'JobBoardWP', true );
			}
		}
	}
}


if ( ! function_exists( 'um_jobboardwp_activation_hook' ) ) {
	function um_jobboardwp_activation_hook() {
		//first install
		$version = get_option( 'um_jobboardwp_version' );
		if ( ! $version ) {
			update_option( 'um_jobboardwp_last_version_upgrade', UM_JOBBOARDWP_VERSION );
		}

		if ( UM_JOBBOARDWP_VERSION !== $version ) {
			update_option( 'um_jobboardwp_version', UM_JOBBOARDWP_VERSION );
		}

		//run setup
		if ( ! class_exists( 'um_ext\um_jobboardwp\common\Setup' ) ) {
			require_once UM_JOBBOARDWP_PATH . 'includes/common/class-setup.php';
		}

		$fmwp_setup = new um_ext\um_jobboardwp\common\Setup();
		$fmwp_setup->run_setup();
	}
}
register_activation_hook( UM_JOBBOARDWP_PLUGIN, 'um_jobboardwp_activation_hook' );
