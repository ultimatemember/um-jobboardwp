<?php if ( ! defined( 'ABSPATH' ) ) exit;


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
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_JobBoardWP constructor.
	 */
	function __construct() {
		add_filter( 'plugins_loaded', [ &$this, 'init' ] );

		add_filter( 'um_call_object_JobBoardWP', [ &$this, 'get_this' ] );
		add_filter( 'um_settings_default_values', [ &$this, 'default_settings' ], 10, 1 );
	}


	/**
	 * @return $this
	 */
	function get_this() {
		return $this;
	}


	/**
	 * @param $defaults
	 *
	 * @return array
	 */
	function default_settings( $defaults ) {
		$defaults = array_merge( $defaults, $this->setup()->settings_defaults );
		return $defaults;
	}


	/**
	 * Init
	 */
	function init() {
		$this->account();
		$this->profile();
		$this->mail();
		$this->integrations();

		if ( is_admin() ) {
			$this->admin();
		}
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Setup()
	 */
	function setup() {
		if ( empty( UM()->classes['um_jobboardwp_setup'] ) ) {
			UM()->classes['um_jobboardwp_setup'] = new um_ext\um_jobboardwp\core\Setup();
		}
		return UM()->classes['um_jobboardwp_setup'];
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Profile()
	 */
	function profile() {
		if ( empty( UM()->classes['um_jobboardwp_profile'] ) ) {
			UM()->classes['um_jobboardwp_profile'] = new um_ext\um_jobboardwp\core\Profile();
		}
		return UM()->classes['um_jobboardwp_profile'];
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Account()
	 */
	function account() {
		if ( empty( UM()->classes['um_jobboardwp_account'] ) ) {
			UM()->classes['um_jobboardwp_account'] = new um_ext\um_jobboardwp\core\Account();
		}
		return UM()->classes['um_jobboardwp_account'];
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Mail()
	 */
	public function mail() {
		if ( empty( UM()->classes['um_jobboardwp_mail'] ) ) {
			UM()->classes['um_jobboardwp_mail'] = new um_ext\um_jobboardwp\core\Mail();
		}
		return UM()->classes['um_jobboardwp_mail'];
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Integrations()
	 */
	function integrations() {
		if ( empty( UM()->classes['um_jobboardwp_integrations'] ) ) {
			UM()->classes['um_jobboardwp_integrations'] = new um_ext\um_jobboardwp\core\Integrations();
		}
		return UM()->classes['um_jobboardwp_integrations'];
	}


	/**
	 * @return um_ext\um_jobboardwp\core\Admin()
	 */
	function admin() {
		if ( empty( UM()->classes['um_jobboardwp_admin'] ) ) {
			UM()->classes['um_jobboardwp_admin'] = new um_ext\um_jobboardwp\core\Admin();
		}
		return UM()->classes['um_jobboardwp_admin'];
	}
}

//create class var
add_action( 'plugins_loaded', 'um_init_jobboardwp', -10, 1 );
function um_init_jobboardwp() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'JobBoardWP', true );
	}
}