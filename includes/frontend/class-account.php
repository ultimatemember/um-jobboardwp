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
		add_filter( 'um_account_page_default_tabs_hook', array( &$this, 'add_account_tab' ) );
		add_filter( 'um_account_content_hook_jobboardwp', array( &$this, 'account_tab' ), 60 );

		add_filter( 'um_account_scripts_dependencies', array( &$this, 'add_js_scripts' ) );
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
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public function account_tab( $output ) {
		$before_html = '<div class="um-clear"></div><br />';
		if ( UM()->is_new_ui() ) {
			JB()->get_template_part( 'js/jobs-dashboard' );
			$before_html = '';
		}
		$output .= $before_html . apply_shortcodes( '[jb_jobs_dashboard /]' );
		if ( UM()->is_new_ui() ) {
			remove_action( 'jb_change_template_part', array( UM()->JobBoardWP()->common()->profile(), 'jb_change_template_part' ) );
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
		if ( UM()->is_new_ui() ) {
			return $scripts;
		}

		wp_register_script( 'um-jb-account', UM_JOBBOARDWP_URL . 'assets/js/account' . UM()->frontend()->enqueue()::get_suffix() . '.js', array( 'wp-hooks' ), UM_JOBBOARDWP_VERSION, true );

		$scripts[] = 'um-jb-account';
		return $scripts;
	}
}
