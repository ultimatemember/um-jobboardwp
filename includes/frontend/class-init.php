<?php
namespace um_ext\um_jobboardwp\frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Init
 *
 * @package um_ext\um_jobboardwp\frontend
 */
class Init {

	/**
	 * Create classes' instances where __construct isn't empty for hooks init
	 */
	public function includes() {
		$this->account();
	}

	/**
	 * @return Account
	 */
	public function account() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\frontend\account'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\frontend\account'] = new Account();
		}
		return UM()->classes['um_ext\um_jobboardwp\frontend\account'];
	}
}
