<?php
/*
 * Plugin Name:       Tob Custom Portfolio Meta
 * Plugin URI:        https://wpdrops.com/
 * Description:       Handle the Portfolio custom metaboxes with this plugin.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            WPDrops
 * Author URI:        https://wpdrops.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://wpdrops.com/
 * Text Domain:       custom-portfolio-meta
 * Domain Path:       /languages
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('init', 'check_plugin_state');
function check_plugin_state() {
	$theme = wp_get_theme(); // gets the current theme
	$parent_theme = $theme->parent(); // gets the current theme parent
	if($parent_theme) {
		$parent_theme_name = $parent_theme->get('Name');
	} else {
		$parent_theme_name = '';
	}
	if('Avada' === $theme->name || 'Avada' === $parent_theme_name) {
		if(is_plugin_active( 'fusion-core/fusion-core.php' )) {
			tob_activate();
		}
		else {
			deactivate_plugins( plugin_basename(__FILE__) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}
	else {
		deactivate_plugins( plugin_basename(__FILE__) );
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

register_deactivation_hook(__FILE__, 'tob_deactivation');

function tob_deactivation() {
	add_action( 'admin_notices', 'tob_deactivation_notice' );
}

function tob_deactivation_notice() {
	$class = 'notice notice-error is-dismissible';
	$message = __( 'Avada theme and Avada Core plugin is required to activate this plugin.', 'tob-custom-plugin' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

function tob_activate() {
	define( 'CUSTOM_PORTFOLIO_VERSION', '1.0.0' );
	define( 'CUSTOM_PORTFOLIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'CUSTOM_PORTFOLIO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'CUSTOM_PORTFOLIO_PLUGIN_FILE', __FILE__ );
	define( 'CUSTOM_PORTFOLIO_PLUGIN_BASE', plugin_basename( __FILE__ ) );
	define( 'CUSTOM_PORTFOLIO_API_NAMESPACE', 'customportfolio' );

	function includes() {
		include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'admin/customize-portfolio-setting.php');
		if(get_theme_mod('portfolio_maintenance_setting')):
			include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'includes/metabox.php');
			include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'includes/getportfolio.php');
		endif;
	}
	
	if ( ! class_exists( 'CustomPortfolio1' ) ) :
		class CustomPortfolio1 {
			public function __construct() {
				add_action( 'admin_notices', 'tob_activation_notice' );
			}
			public static $instance;

			public static function instance() {
				if( ! isset (self::$instance)) {
					self::$instance = new CustomPortfolio1();
					self::$instance->init();
					self::$instance->includes();
				}
				return self::$instance;
			}

			public static function includes() {
				include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'admin/customize-portfolio-setting.php');
				if(get_theme_mod('portfolio_maintenance_setting')):
					include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'includes/metabox.php');
					include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'includes/getportfolio.php');
					include_once(CUSTOM_PORTFOLIO_PLUGIN_DIR.'includes/functions.php');
				endif;
			}
			public static function init(){
			}
		}
		CustomPortfolio1::includes();
		CustomPortfolio1::init();
	endif;
};

