( function( $ ) {
	$( document ).ready( function() {

		var woo_is_active = function() {
			return $( 'table tr.active[data-slug="woocommerce"]' ).length
			|| $( 'table tr.active[data-plugin="woocommerce/woocommerce.php"]' ).length;
		};

		var woo_is_inactive = function() {
			return $( 'table tr.inactive[data-slug="woocommerce"]' ).length
			|| $( 'table tr.inactive[data-plugin="woocommerce/woocommerce.php"]' ).length;
		};

		if ( ! woo_is_active() ) {
			var message = motpr355_woo_sale_product_date_info_translations.woo_not_found;
			if ( woo_is_inactive() ) {
				message = motpr355_woo_sale_product_date_info_translations.woo_inactive;
			}
			$( document ).on( 'click', '#motpr355_woo_sale_product_date_info', function( e ) {
				e.preventDefault();
				alert( message );
			} );
		}

	} );
} ) ( jQuery );
