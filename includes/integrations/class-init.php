<?php
namespace um_ext\um_jobboardwp\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Init
 *
 * @package um_ext\um_jobboardwp\integrations
 */
class Init {

	/**
	 * Create classes' instances where __construct isn't empty for hooks init
	 */
	public function includes() {
		$this->activity();
		$this->messages();
		$this->verified();
		$this->notifications();
		$this->bookmarks();
	}

	/**
	 * @return Activity
	 */
	public function activity() {
		if ( ! class_exists( 'UM_Activity_API' ) ) {
			return false;
		}
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\activity'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\activity'] = new Activity();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\activity'];
	}

	/**
	 * @return Verified
	 */
	public function verified() {
		if ( ! class_exists( 'UM_Verified_Users_API' ) ) {
			return false;
		}
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\verified'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\verified'] = new Verified();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\verified'];
	}

	/**
	 * @return Notifications
	 */
	public function notifications() {
		if ( ! class_exists( 'UM_Online' ) ) {
			return false;
		}
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\notifications'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\notifications'] = new Notifications();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\notifications'];
	}

	/**
	 * @return null|Messages
	 */
	public function messages() {
		if ( ! class_exists( 'UM_Messaging' ) ) {
			return null;
		}
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\messages'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\messages'] = new Messages();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\messages'];
	}

	/**
	 * @return Bookmarks
	 */
	public function bookmarks() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\common\bookmarks'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\common\bookmarks'] = new Bookmarks();
		}
		return UM()->classes['um_ext\um_jobboardwp\common\bookmarks'];
	}
}
