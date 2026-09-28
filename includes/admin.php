<?php
/**
 * Pantalla de administración: alta, edición, borrado y listado de banners.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'bn_register_admin_menu' );

/**
 * Añade este plugin como submenú "Banners" del menú compartido
 * "Oscar Pérez Plugins". Ese menú lo crea normalmente el plugin maestro
 * (Enlaces de Interés / weblinks); si no está activo, lo crea este plugin para
 * poder funcionar de forma autónoma.
 */
function bn_register_admin_menu() {
	if ( ! function_exists( 'opg_plugin_links_show_form_in_wpadmin' ) ) {
		add_menu_page(
			'Oscar Pérez Plugins',
			'Oscar Pérez Plugins',
			'manage_options',
			'opg_plugins',
			'bn_render_admin_page',
			'dashicons-images-alt2',
			110
		);
		remove_submenu_page( 'opg_plugins', 'opg_plugins' );
	}

	add_submenu_page(
		'opg_plugins',
		__( 'Banners', 'opg-banners' ),
		__( 'Banners', 'opg-banners' ),
		'manage_options',
		'opg_banners',
		'bn_render_admin_page'
	);
}

add_action( 'admin_enqueue_scripts', 'bn_admin_enqueue_scripts' );

/**
 * Carga el cargador de medios de WordPress y el script de administración solo
 * en la pantalla de este plugin.
 */
function bn_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( (string) $hook_suffix, 'opg_banners' ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'bn-admin', BN_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), BN_VERSION, true );
	wp_localize_script(
		'bn-admin',
		'bnAdmin',
		array(
			'mediaTitle'    => __( 'Selecciona o sube la imagen del banner', 'opg-banners' ),
			'mediaButton'   => __( 'Usar esta imagen', 'opg-banners' ),
			'confirmDelete' => __( '¿Está seguro de eliminar este banner?', 'opg-banners' ),
		)
	);
}

/**
 * Procesa las acciones (guardar/editar/borrar) y pinta el formulario y el listado.
 */
function bn_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'No tiene permisos suficientes para acceder a esta página.', 'opg-banners' ) );
	}

	$values = array(
		'id'    => 0,
		'name'  => '',
		'url'   => '',
		'image' => '',
	);

	if ( isset( $_POST['bn_action'] ) && 'save' === $_POST['bn_action'] ) {
		check_admin_referer( 'bn_save_banner', 'bn_nonce' );

		$data = array(
			'name'  => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
			'url'   => isset( $_POST['url'] ) ? bn_normalize_url( wp_unslash( $_POST['url'] ) ) : '',
			'image' => isset( $_POST['upload_image'] ) ? esc_url_raw( wp_unslash( $_POST['upload_image'] ) ) : '',
		);

		$id = isset( $_POST['idBanner'] ) ? absint( $_POST['idBanner'] ) : 0;

		if ( '' === $data['name'] || '' === $data['url'] ) {
			echo '<div class="error notice"><p>' . esc_html__( 'El nombre y la URL son obligatorios.', 'opg-banners' ) . '</p></div>';
		} elseif ( 0 === $id && '' === $data['image'] ) {
			echo '<div class="error notice"><p>' . esc_html__( 'Debe seleccionar una imagen para el banner.', 'opg-banners' ) . '</p></div>';
		} elseif ( $id > 0 ) {
			bn_update_banner( $id, $data );
			echo '<div class="updated notice"><p>' . esc_html__( 'Banner modificado correctamente.', 'opg-banners' ) . '</p></div>';
		} else {
			bn_save_banner( $data );
			echo '<div class="updated notice"><p>' . esc_html__( 'Información del banner guardada correctamente.', 'opg-banners' ) . '</p></div>';
		}
	} elseif ( isset( $_GET['task'], $_GET['id'] ) && 'edit_banners' === $_GET['task'] ) {
		$id  = absint( $_GET['id'] );
		$row = bn_get_banner( $id );

		if ( $row ) {
			$values = array(
				'id'    => $id,
				'name'  => $row->name,
				'url'   => $row->url,
				'image' => $row->image,
			);
		}
	} elseif ( isset( $_GET['task'], $_GET['id'] ) && 'remove_banners' === $_GET['task'] ) {
		$id = absint( $_GET['id'] );
		check_admin_referer( 'bn_delete_banner_' . $id );
		bn_delete_banner( $id );
		echo '<div class="updated notice"><p>' . esc_html__( 'Se ha borrado la información del banner.', 'opg-banners' ) . '</p></div>';
	}

	$title = $values['id'] > 0
		? __( 'Modificar información del banner', 'opg-banners' )
		: __( 'Añadir un nuevo banner', 'opg-banners' );

	include BN_PLUGIN_DIR . 'includes/views/admin-form.php';
	include BN_PLUGIN_DIR . 'includes/views/admin-list.php';
}

/**
 * Normaliza una URL introducida por el usuario: le añade el esquema http://
 * si no lo lleva y la sanea con esc_url_raw.
 */
function bn_normalize_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '';
	}

	if ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
		$url = 'http://' . $url;
	}

	return esc_url_raw( $url );
}
