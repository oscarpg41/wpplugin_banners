<?php
/**
 * Listado de banners en el panel de administración.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banners = bn_get_banners();
?>
<div class="wrap bn-admin-wrap">
	<hr>
	<h2><?php esc_html_e( 'Listado de banners', 'opg-banners' ); ?></h2>

	<?php if ( empty( $banners ) ) : ?>
		<p><?php esc_html_e( 'Todavía no se ha añadido ningún banner.', 'opg-banners' ); ?></p>
	<?php else : ?>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Nombre', 'opg-banners' ); ?></th>
					<th><?php esc_html_e( 'Url', 'opg-banners' ); ?></th>
					<th><?php esc_html_e( 'Imagen', 'opg-banners' ); ?></th>
					<th style="width:220px"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $banners as $banner ) : ?>
				<tr>
					<td><?php echo esc_html( $banner->name ); ?></td>
					<td>
						<a href="<?php echo esc_url( $banner->url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $banner->url ); ?>
						</a>
					</td>
					<td>
						<?php if ( '' !== $banner->image ) : ?>
							<img src="<?php echo esc_url( $banner->image ); ?>" alt="<?php echo esc_attr( $banner->name ); ?>" style="max-width:150px; height:auto;">
						<?php endif; ?>
					</td>
					<td>
						<a class="button button-small" href="<?php echo esc_url( admin_url( 'admin.php?page=opg_banners&task=edit_banners&id=' . $banner->idBanner ) ); ?>">
							<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Modificar', 'opg-banners' ); ?>
						</a>
						<a class="button button-small bn-delete-banner" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=opg_banners&task=remove_banners&id=' . $banner->idBanner ), 'bn_delete_banner_' . $banner->idBanner ) ); ?>">
							<span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Borrar', 'opg-banners' ); ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
