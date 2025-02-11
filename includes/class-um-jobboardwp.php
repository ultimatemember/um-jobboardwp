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
		}
		return self::$instance;
	}

	/**
	 * UM_JobBoardWP constructor.
	 */
	public function __construct() {
		add_filter( 'um_call_object_JobBoardWP', array( &$this, 'get_this' ) );
		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );

		$this->includes();
	}

	/**
	 * @return $this
	 */
	public function get_this() {
		return $this;
	}

	/**
	 * @param $defaults
	 *
	 * @return array
	 */
	public function default_settings( $defaults ) {
		$defaults = array_merge( $defaults, $this->setup()->settings_defaults );
		return $defaults;
	}

	public function includes() {
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

//create class var
add_action( 'plugins_loaded', 'um_init_jobboardwp', -10, 1 );
function um_init_jobboardwp() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'JobBoardWP', true );
	}
}
