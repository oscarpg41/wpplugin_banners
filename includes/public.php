<?php
/**
 * Shortcode y renderizado público del listado de banners.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'banners', 'bn_render_shortcode' );

/**
 * [banners columnas="2"]
 */
function bn_render_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'columnas' => 1,
		),
		$atts,
		'banners'
	);

	$columnas = max( 1, min( 4, absint( $atts['columnas'] ) ) );
	$banners  = bn_get_banners();

	if ( empty( $banners ) ) {
		return '<p class="bn-empty">' . esc_html__( 'Todavía no se ha publicado ningún banner.', 'opg-banners' ) . '</p>';
	}

	bn_enqueue_public_assets();

	ob_start();
	include BN_PLUGIN_DIR . 'includes/views/public-list.php';
	return ob_get_clean();
}

/**
 * Carga los estilos públicos solo cuando se usa el shortcode.
 */
function bn_enqueue_public_assets() {
	wp_enqueue_style( 'bn-public', BN_PLUGIN_URL . 'assets/css/public.css', array(), BN_VERSION );
}
