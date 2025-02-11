<?php
namespace um_ext\um_jobboardwp\common;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Init
 *
 * @package um_ext\um_jobboardwp\common
 */
class Init {

	/**
	 * Create classes' instances where __construct isn't empty for hooks init
	 */
	public function includes() {
		$this->profile();
	}

	/**
	 * @return Profile
	 */
	public function profile() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\common\profile'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\common\profile'] = new Profile();
		}
		return UM()->classes['um_ext\um_jobboardwp\common\profile'];
	}
}
