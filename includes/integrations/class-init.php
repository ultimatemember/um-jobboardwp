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
		// We need to use early hook to integrate it with other extensions. And we know they are active and classes exist on `plugins_loaded`.
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded() {
		$this->messages();

		// TODO as soon as reviewed these extensions, check JobBoardWP integration functionality.
		$this->activity();
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
	 * @return Bookmarks
	 */
	public function bookmarks() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\common\bookmarks'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\common\bookmarks'] = new Bookmarks();
		}
		return UM()->classes['um_ext\um_jobboardwp\common\bookmarks'];
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
	 * @return Notifications
	 */
	public function notifications() {
		if ( ! class_exists( 'UM_Notifications_API' ) ) {
			return false;
		}
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\notifications'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\notifications'] = new Notifications();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\notifications'];
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
}
