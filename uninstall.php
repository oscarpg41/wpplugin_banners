<?php
/**
 * Se ejecuta cuando el usuario borra el plugin desde el administrador de WordPress
 * (no simplemente al desactivarlo). Elimina la tabla de banners.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$table_name = $wpdb->prefix . 'opg_plugin_banners';
$wpdb->query( "DROP TABLE IF EXISTS `{$table_name}`" );
