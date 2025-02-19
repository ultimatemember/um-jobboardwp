<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UM_JobBoardWP
 */
class UM_JobBoardWP {

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * @return UM_JobBoardWP
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->um_jobboardwp_construct();
		}

		return self::$instance;
	}

	/**
	 * UM_JobBoardWP constructor.
	 */
	public function um_jobboardwp_construct() {
		$this->common()->includes();
		if ( UM()->is_request( 'admin' ) ) {
			$this->admin()->includes();
		} elseif ( UM()->is_request( 'frontend' ) ) {
			$this->frontend()->includes();
		}
		$this->integrations()->includes();
	}

	/**
	 * @return um_ext\um_jobboardwp\common\Setup()
	 */
	public function setup() {
		if ( empty( UM()->classes['um_jobboardwp_setup'] ) ) {
			UM()->classes['um_jobboardwp_setup'] = new um_ext\um_jobboardwp\common\Setup();
		}
		return UM()->classes['um_jobboardwp_setup'];
	}

	/**
	 * @return um_ext\um_friends\admin\Init
	 */
	public function admin() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\admin\init'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\admin\init'] = new um_ext\um_jobboardwp\admin\Init();
		}
		return UM()->classes['um_ext\um_jobboardwp\admin\init'];
	}

	/**
	 * @return um_ext\um_jobboardwp\common\Init
	 */
	public function common() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\common\init'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\common\init'] = new um_ext\um_jobboardwp\common\Init();
		}
		return UM()->classes['um_ext\um_jobboardwp\common\init'];
	}

	/**
	 * @return um_ext\um_jobboardwp\frontend\Init
	 */
	public function frontend() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\frontend\init'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\frontend\init'] = new um_ext\um_jobboardwp\frontend\Init();
		}
		return UM()->classes['um_ext\um_jobboardwp\frontend\init'];
	}

	/**
	 * @return um_ext\um_jobboardwp\integrations\Init
	 */
	public function integrations() {
		if ( empty( UM()->classes['um_ext\um_jobboardwp\integrations\init'] ) ) {
			UM()->classes['um_ext\um_jobboardwp\integrations\init'] = new um_ext\um_jobboardwp\integrations\Init();
		}
		return UM()->classes['um_ext\um_jobboardwp\integrations\init'];
	}
}
