<?php
/**
 * Formulario de alta/edición de banner.
 * Variables disponibles: $values, $title
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap bn-admin-wrap">
	<h1><?php echo esc_html( $title ); ?></h1>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=opg_banners' ) ); ?>" id="bnAdminForm">
		<?php wp_nonce_field( 'bn_save_banner', 'bn_nonce' ); ?>
		<input type="hidden" name="bn_action" value="save">
		<input type="hidden" name="idBanner" value="<?php echo esc_attr( $values['id'] ); ?>">

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="name"><?php esc_html_e( 'Nombre', 'opg-banners' ); ?></label></th>
					<td>
						<input type="text" name="name" id="name" class="regular-text" required maxlength="100"
							placeholder="<?php esc_attr_e( 'Introduzca el nombre', 'opg-banners' ); ?>"
							value="<?php echo esc_attr( $values['name'] ); ?>">
					</td>
				</tr>
				<tr>
					<th><label for="url"><?php esc_html_e( 'Url', 'opg-banners' ); ?></label></th>
					<td>
						<input type="text" name="url" id="url" class="regular-text" required maxlength="140"
							placeholder="<?php esc_attr_e( 'Introduzca la url', 'opg-banners' ); ?>"
							value="<?php echo esc_attr( $values['url'] ); ?>">
					</td>
				</tr>
				<tr>
					<th><label for="bn_upload_image"><?php esc_html_e( 'Imagen', 'opg-banners' ); ?></label></th>
					<td>
						<img id="bn_image_preview" class="bn-image-preview"
							src="<?php echo esc_url( $values['image'] ); ?>"
							alt="<?php esc_attr_e( 'Vista previa del banner', 'opg-banners' ); ?>"
							<?php echo '' === $values['image'] ? 'style="display:none;"' : ''; ?>>
						<p>
							<input type="text" name="upload_image" id="bn_upload_image" class="regular-text" readonly
								value="" placeholder="<?php esc_attr_e( 'Ninguna imagen nueva seleccionada', 'opg-banners' ); ?>">
							<input type="button" class="button button-secondary" id="bn_upload_image_button"
								value="<?php esc_attr_e( 'Seleccionar imagen', 'opg-banners' ); ?>">
						</p>
						<?php if ( $values['id'] > 0 ) : ?>
							<p class="description">
								<?php esc_html_e( 'Si no selecciona una imagen nueva, se conservará la actual.', 'opg-banners' ); ?>
							</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="bn_target"><?php esc_html_e( 'Abrir enlace', 'opg-banners' ); ?></label></th>
					<td>
						<label>
							<input type="checkbox" name="target" id="bn_target" value="1" <?php checked( 1, (int) $values['target'] ); ?>>
							<?php esc_html_e( 'Abrir en una pestaña nueva', 'opg-banners' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Si se desmarca, el enlace se abre en la misma pestaña.', 'opg-banners' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center; padding-top: 20px;">
						<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e( 'Enviar', 'opg-banners' ); ?>">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
