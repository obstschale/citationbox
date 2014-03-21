jQuery(document).ready(function($) {
	$('.colorpicker').hide();
	$('.colorpicker_bg').farbtastic( '#color_bg' );
	$('.colorpicker_border').farbtastic( '#color_border' );
	$('.colorpicker_link').farbtastic( '#color_link' );
	$('.colorpicker_link_hover').farbtastic( '#color_link_hover' );
	$('.colorpicker_text').farbtastic( '#color_text' );

	$('.pickcolor').click( function(e) {
		colorPicker = jQuery(this).next('div');
		input = jQuery(this).prev('input');
		$(colorPicker).farbtastic(input);
		colorPicker.show();
		e.preventDefault();
		$(document).mousedown( function() {
			$(colorPicker).hide();
		});
	});

	$('#color_reset').click( function(e) {
		$('#color_bg').val( '#D6E8F2' );
		$('#color_bg').css( 'background-color', '#D6E8F2' );
		$('#color_bg').css( 'color', '#000000' );

		$('#color_border').val( '#5CACE2' );
		$('#color_border').css( 'background-color', '#5CACE2' );
		$('#color_border').css( 'color', '#000000' );

		$('#color_link').val( '#5CACE2' );
		$('#color_link').css( 'background-color', '#5CACE2' );
		$('#color_link').css( 'color', '#000000' );

		$('#color_link_hover').val( '#006385' );
		$('#color_link_hover').css( 'background-color', '#006385' );
		$('#color_link_hover').css( 'color', '#FFFFFF' );

		$('#color_text').val( '#000000' );
		$('#color_text').css( 'background-color', '#000000' );
		$('#color_text').css( 'color', '#FFFFFF' );
	});
});
