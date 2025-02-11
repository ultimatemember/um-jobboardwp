<?php
namespace um_ext\um_jobboardwp\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Metabox
 * @package um_ext\um_jobboardwp\admin
 */
class Metabox {

	/**
	 * Metabox constructor.
	 */
	public function __construct() {
		add_filter( 'um_admin_role_metaboxes', array( &$this, 'add_role_metabox' ), 10, 1 );
	}

	/**
	 * Creates options in Role page
	 *
	 * @param array $roles_metaboxes
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function add_role_metabox( $roles_metaboxes ) {
		$roles_metaboxes[] = array(
			'id'       => 'um-admin-form-jobboardwp{' . UM_JOBBOARDWP_PATH . '}',
			'title'    => __( 'JobBoardWP', 'um-jobboardwp' ),
			'callback' => array( UM()->metabox(), 'load_metabox_role' ),
			'screen'   => 'um_role_meta',
			'context'  => 'normal',
			'priority' => 'default',
		);

		return $roles_metaboxes;
	}
}
