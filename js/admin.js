( function( $ ) {
	$( document ).ready( function() {

		var settings = motpr355_woo_sale_product_date_info_settings;

		var init = function() {
			init_activation();
			init_background_color();
			init_color();
			init_font_weight();
			init_text_transform();
			init_display();
			init_preview();
		};

		var init_activation = function() {
			init_value( 'activation' );
			if ( settings[ 'activation' ] === 'deactived' ) {
				$( '#motpr355_woo_sale_product_date_info #motpr355_woo_sale_product_date_info_activate_arrow' ).show();
			}
		};

		var init_background_color = function() {
			init_value( 'background_color' );
		};

		var init_color = function() {
			init_value( 'color' );
		};

		var init_font_weight = function() {
			init_value( 'font_weight' );
		};

		var init_text_transform = function() {
			init_value( 'text_transform' );
		};

		var init_display = function() {
			init_value( 'display' );
		};

		var init_preview = function() {
			set_preview(
				settings.background_color,
				settings.color,
				settings.font_weight,
				settings.text_transform,
				settings.display
			);
		};

		var get_background_color = function() {
			return get_value( 'background_color' );
		};

		var get_color = function() {
			return get_value( 'color' );
		};

		var get_font_weight = function() {
			return get_value( 'font_weight' );
		};

		var get_text_transform = function() {
			return get_value( 'text_transform' );
		};

		var get_display = function() {
			return get_value( 'display' );
		};

		var refresh_preview = function() {
			set_preview(
				get_background_color(),
				get_color(),
				get_font_weight(),
				get_text_transform(),
				get_display()
			);
		};

		var get_value = function( id ) {
			var text_values = [ 'background_color', 'color' ];
			var radio_values = [ 'font_weight', 'text_transform', 'display' ];
			var element = '#motpr355_woo_sale_product_date_info input[name="' + id + '"]';
			if ( radio_values.indexOf( id ) !== -1 ) {
				element += ':checked';
				return $( element ).val();
			} else if ( text_values.indexOf( id ) !== -1 ) {
				return $( element ).val();
			}
		};

		var init_value = function( id ) {
			var text_values = [ 'background_color', 'color' ];
			var radio_values = [ 'activation', 'font_weight', 'text_transform', 'display' ];
			var element = '#motpr355_woo_sale_product_date_info [name="' + id + '"]';
			if ( radio_values.indexOf( id ) !== -1 ) {
				element += '[value="' + settings[ id ] + '"]';
				$( element ).prop( 'checked', true );
			} else if ( text_values.indexOf( id ) !== -1 ) {
				$( element ).val( settings[ id ] );
			}
		};

		var set_preview = function( background_color, color, font_weight, text_transform, display ) {
			var start = '#motpr355_woo_sale_product_date_info #motpr355_woo_sale_product_date_display_start';
			var end = '#motpr355_woo_sale_product_date_info #motpr355_woo_sale_product_date_display_end';
			var start_end = '#motpr355_woo_sale_product_date_info #motpr355_woo_sale_product_date_display_start_end';
			var show = '';
			var hide = '';
			if (display === 'start_date') {
				show = start;
				hide = end + ',' + start_end;
			} else if (display === 'end_date') {
				show = end;
				hide = start + ',' + start_end;
			} else {
				show = end + ',' + start + ',' + start_end;
				hide = '';
			}
			$( show ).show();
			$( hide ).hide();
			if ( check_color_format( background_color ) && check_color_format( color ) ) {
				var element = '#motpr355_woo_sale_product_date_info .motpr355_woo_sale_product_date_info_classic';
				$( element ).css( {
					backgroundColor: background_color,
					color: color,
					fontWeight: font_weight,
					textTransform: text_transform
				} );
			}
		};

		var check_color_format = function( color ) {
			if ( ( color.length !== 4 && color.length !== 7 ) || color.substring( 0, 1 ) !== '#' ) {
				return false;
			}
			var check1 = ( parseInt( color.substring( 1 ), 16 ).toString( 16 ) ).padStart( color.length - 1, '0' );
			var check2 = color.substring( 1 );
			return check1 === check2;
		};

		init();

		$( '#motpr355_woo_sale_product_date_info .input_color' ).wpColorPicker( {
			change: function () {
				setTimeout( function() {
					refresh_preview();
				} );
			}
		} );

		$( document ).on( 'click', '#motpr355_woo_sale_product_date_info #motpr355_woo_sale_product_date_info_submit', function() {
			$( '#motpr355_woo_sale_product_date_info form' ).submit();
		} );

		$( document ).on( 'change', '#motpr355_woo_sale_product_date_info input', function() {
			refresh_preview();
		} );

	} );
} ) ( jQuery );
