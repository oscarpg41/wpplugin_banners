<?php
/**
 * Acceso a base de datos para la tabla de banners.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nombre completo (con prefijo) de la tabla de banners.
 * Se mantiene el nombre histórico `opg_plugin_banners` para no perder los datos
 * de instalaciones que ya usaban versiones anteriores del plugin.
 */
function bn_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'opg_plugin_banners';
}

/**
 * Crea o actualiza la tabla mediante dbDelta (no destruye datos existentes).
 */
function bn_create_table() {
	global $wpdb;

	$table_name      = bn_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table_name} (
		idBanner INT(11) NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		url VARCHAR(140) NOT NULL,
		image VARCHAR(255) NOT NULL,
		target TINYINT(1) NOT NULL DEFAULT 0,
		PRIMARY KEY  (idBanner),
		KEY name (name)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Inserta un nuevo banner. $data debe venir ya saneado.
 */
function bn_save_banner( $data ) {
	global $wpdb;

	return $wpdb->insert(
		bn_table_name(),
		array(
			'name'   => $data['name'],
			'url'    => $data['url'],
			'image'  => $data['image'],
			'target' => $data['target'],
		),
		array( '%s', '%s', '%s', '%d' )
	);
}

/**
 * Actualiza un banner existente. La imagen solo se actualiza si se ha
 * seleccionado una nueva (si $data['image'] viene vacío se conserva la actual).
 */
function bn_update_banner( $id, $data ) {
	global $wpdb;

	$fields  = array(
		'name'   => $data['name'],
		'url'    => $data['url'],
		'target' => $data['target'],
	);
	$formats = array( '%s', '%s', '%d' );

	if ( isset( $data['image'] ) && '' !== $data['image'] ) {
		$fields['image'] = $data['image'];
		$formats[]       = '%s';
	}

	return $wpdb->update(
		bn_table_name(),
		$fields,
		array( 'idBanner' => absint( $id ) ),
		$formats,
		array( '%d' )
	);
}

/**
 * Elimina un banner por id.
 */
function bn_delete_banner( $id ) {
	global $wpdb;
	return $wpdb->delete( bn_table_name(), array( 'idBanner' => absint( $id ) ), array( '%d' ) );
}

/**
 * Recupera un banner por id.
 */
function bn_get_banner( $id ) {
	global $wpdb;
	$table = bn_table_name();

	return $wpdb->get_row(
		$wpdb->prepare(
			"SELECT idBanner, name, url, image, target FROM {$table} WHERE idBanner = %d",
			absint( $id )
		)
	);
}

/**
 * Recupera todos los banners, ordenados por nombre.
 */
function bn_get_banners() {
	global $wpdb;
	$table = bn_table_name();

	return $wpdb->get_results(
		"SELECT idBanner, name, url, image, target FROM {$table} ORDER BY name ASC"
	);
}
