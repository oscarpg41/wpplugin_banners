<?php
/**
 * Listado público (shortcode [banners]).
 * Variables disponibles: $banners, $columnas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="banners" style="--bn-columnas: <?php echo esc_attr( $columnas ); ?>;">
	<?php foreach ( $banners as $banner ) : ?>
		<li class="banner">
			<a href="<?php echo esc_url( $banner->url ); ?>"<?php echo ( 1 === (int) $banner->target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> title="<?php echo esc_attr( $banner->name ); ?>">
				<img src="<?php echo esc_url( $banner->image ); ?>" alt="<?php echo esc_attr( $banner->name ); ?>" loading="lazy">
			</a>
		</li>
	<?php endforeach; ?>
</ul>
