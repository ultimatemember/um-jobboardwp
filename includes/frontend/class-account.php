<?php
namespace um_ext\um_jobboardwp\frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Account
 *
 * @package um_ext\um_jobboardwp\frontend
 */
class Account {

	/**
	 * Account constructor.
	 */
	public function __construct() {
		add_filter( 'um_account_page_default_tabs_hook', array( &$this, 'add_account_tab' ), 10, 1 );
		add_filter( 'um_account_content_hook_jobboardwp', array( &$this, 'account_tab' ), 60, 2 );

		add_filter( 'um_account_scripts_dependencies', array( &$this, 'add_js_scripts' ), 10, 1 );
	}

	/**
	 * @param array $tabs
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function add_account_tab( $tabs ) {
		if ( empty( $tabs[500]['jobboardwp'] ) ) {
			$args = array(
				'icon'        => 'um-faicon-list-alt',
				'title'       => __( 'Jobs Dashboard', 'um-jobboardwp' ),
				'show_button' => false,
			);
			if ( UM()->is_new_ui() ) {
				$args['custom'] = true;
			}

			$tabs[500]['jobboardwp'] = $args;
		}

		return $tabs;
	}

	/**
	 * @param string $output
	 * @param array $shortcode_args
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public function account_tab( $output, $shortcode_args ) {
		if ( version_compare( get_bloginfo( 'version' ), '5.4', '<' ) ) {
			$output .= '<div class="um-clear"></div><br />' . do_shortcode( '[jb_jobs_dashboard /]' );
		} else {
			$output .= '<div class="um-clear"></div><br />' . apply_shortcodes( '[jb_jobs_dashboard /]' );
		}

		return $output;
	}

	/**
	 * @param array $scripts
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function add_js_scripts( $scripts ) {
		wp_register_script( 'um-jb-account', UM_JOBBOARDWP_URL . 'assets/js/account' . UM()->frontend()->enqueue()::get_suffix() . '.js', array( 'wp-hooks' ), UM_JOBBOARDWP_VERSION, true );

		$scripts[] = 'um-jb-account';
		return $scripts;
	}
}
