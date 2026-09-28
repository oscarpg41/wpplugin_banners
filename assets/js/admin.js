jQuery( function ( $ ) {
	'use strict';

	var frame;

	// Cargador de medios de WordPress (API wp.media, sustituye a thickbox).
	$( '#bn_upload_image_button' ).on( 'click', function ( event ) {
		event.preventDefault();

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media( {
			title: bnAdmin.mediaTitle,
			button: { text: bnAdmin.mediaButton },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			$( '#bn_upload_image' ).val( attachment.url );
			$( '#bn_image_preview' ).attr( 'src', attachment.url ).show();
		} );

		frame.open();
	} );

	// Confirmación antes de borrar un banner.
	$( document ).on( 'click', '.bn-delete-banner', function ( event ) {
		if ( ! window.confirm( bnAdmin.confirmDelete ) ) {
			event.preventDefault();
		}
	} );
} );
