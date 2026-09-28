<?php
/**
 * Listado público (shortcode [banners]).
 * Variables disponibles: $banners, $columnas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="bn-list" style="--bn-columnas: <?php echo esc_attr( $columnas ); ?>;">
	<?php foreach ( $banners as $banner ) : ?>
		<li class="bn-item">
			<a class="bn-link" href="<?php echo esc_url( $banner->url ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $banner->name ); ?>">
				<img class="bn-image" src="<?php echo esc_url( $banner->image ); ?>" alt="<?php echo esc_attr( $banner->name ); ?>" loading="lazy">
			</a>
		</li>
	<?php endforeach; ?>
</ul>
