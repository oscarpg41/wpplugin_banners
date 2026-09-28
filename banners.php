<?php
/**
 * Plugin Name: [Óscar Pérez Gómez] Banners
 * Plugin URI: https://github.com/oscarpg41/wpplugin_banners
 * Description: Gestiona y muestra un conjunto de banners (imagen + enlace). Usa el shortcode [banners] para mostrarlos en cualquier página o entrada.
 * Version: 2.0.0
 * Author: Óscar Pérez
 * Author URI: https://www.oscarperez.es/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: opg-banners
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BN_VERSION', '2.0.0' );
define( 'BN_PLUGIN_FILE', __FILE__ );
define( 'BN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once BN_PLUGIN_DIR . 'includes/db.php';
require_once BN_PLUGIN_DIR . 'includes/admin.php';
require_once BN_PLUGIN_DIR . 'includes/public.php';

register_activation_hook( BN_PLUGIN_FILE, 'bn_activate' );

/**
 * Crea o actualiza la tabla de banners al activar el plugin.
 */
function bn_activate() {
	bn_create_table();
}
