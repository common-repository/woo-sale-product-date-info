<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$example_start_date = date_i18n( get_option( 'date_format' ), time() - 432000 );
$example_end_date = date_i18n( get_option( 'date_format' ), time() + 432000 );
?>
<div class="wrap" id="motpr355_woo_sale_product_date_info">

	<h1>
		<?php _e( 'Sale product date info', 'woo-sale-product-date-info' ) ?>
	</h1>

	<h3><?php _e( 'Display WooCommerce sale product date.', 'woo-sale-product-date-info' ) ?></h3>

	<noscript>
		<div class="notice notice-warning is-dismissible">
			<p>
				<?php _e( 'Please activate JavaScript to edit the configuration.', 'woo-sale-product-date-info' ) ?>
			</p>
		</div>
	</noscript>

	<?php if ( $this->configuration_saved ) { ?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php _e( 'Configuration saved.', 'woo-sale-product-date-info' ) ?>
		</p>
	</div>
	<?php } ?>

	<form autocomplete="off" method="POST" action="">
		<p>
			<span>
				<?php _e( 'Activate functionality:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->activation ?>"
						   value="<?php echo $this->activation_enum->actived ?>">
					<span>
						<?php _e( 'Activate', 'woo-sale-product-date-info' ) ?>
					</span>
					<span id="motpr355_woo_sale_product_date_info_activate_arrow">
						<span></span>
						<span></span>
					</span>
				</label>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->activation ?>"
						   value="<?php echo $this->activation_enum->deactived ?>">
					<span>
						<?php _e( 'Deactivate', 'woo-sale-product-date-info' ) ?>
					</span>
				</label>
			</span>
		</p>
		<hr/>
		<p>
			<span>
				<?php _e( 'Background color:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<input type="text"
					   class="input_color"
					   name="<?php echo $this->settings_enum->background_color ?>">
			</span>
		</p>
		<p>
			<span>
				<?php _e( 'Color:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<input type="text"
					   class="input_color"
					   name="<?php echo $this->settings_enum->color ?>">
			</span>
		</p>
		<p>
			<span>
				<?php _e( 'Font weight:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->font_weight ?>"
						   value="<?php echo $this->font_weight_enum->normal ?>">
					<span><?php _e( 'Normal', 'woo-sale-product-date-info' ) ?></span>
				</label>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->font_weight ?>"
						   value="<?php echo $this->font_weight_enum->bold ?>">
					<span><?php _e( 'Bold', 'woo-sale-product-date-info' ) ?></span>
				</label>
			</span>
		</p>
		<p>
			<span>
				<?php _e( 'Text transform:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->text_transform ?>"
						   value="<?php echo $this->text_transform_enum->none ?>">
					<span><?php _e( 'None', 'woo-sale-product-date-info' ) ?></span>
				</label>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->text_transform ?>"
						   value="<?php echo $this->text_transform_enum->lowercase ?>">
					<span><?php _e( 'Lowercase', 'woo-sale-product-date-info' ) ?></span>
				</label>
				<label>
					<input type="radio"
						   name="<?php echo $this->settings_enum->text_transform ?>"
						   value="<?php echo $this->text_transform_enum->uppercase ?>">
					<span><?php _e( 'Uppercase', 'woo-sale-product-date-info' ) ?></span>
				</label>
			</span>
		</p>
		<p>
			<span>
				<?php _e( 'Display:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<label>
					<input type="radio"
						name="<?php echo $this->settings_enum->display ?>"
						value="<?php echo $this->display_enum->start_date ?>">
					<span><?php _e( 'Only sale start date', 'woo-sale-product-date-info' ) ?></span>
				</label>
				<label>
					<input type="radio"
						name="<?php echo $this->settings_enum->display ?>"
						value="<?php echo $this->display_enum->end_date ?>">
					<span><?php _e( 'Only sale end date', 'woo-sale-product-date-info' ) ?></span>
				</label>
				<label>
					<input type="radio"
						name="<?php echo $this->settings_enum->display ?>"
						value="<?php echo $this->display_enum->start_end_date ?>">
					<span><?php _e( 'Sale start and end date', 'woo-sale-product-date-info' ) ?></span>
				</label>
			</span>
		</p>
		<hr/>
		<p>
			<span>
				<?php _e( 'Preview:', 'woo-sale-product-date-info' ) ?>
			</span>
			<span>
				<span class="motpr355_woo_sale_product_date_info_classic" id="motpr355_woo_sale_product_date_display_start">
					<?php printf( __( "On sale from %s", 'woo-sale-product-date-info' ), $example_start_date ) ?>
				</span>
				<span class="motpr355_woo_sale_product_date_info_classic" id="motpr355_woo_sale_product_date_display_end">
					<?php printf( __( "On sale until %s", 'woo-sale-product-date-info' ), $example_end_date ) ?>
				</span>
				<span class="motpr355_woo_sale_product_date_info_classic" id="motpr355_woo_sale_product_date_display_start_end">
					<?php printf( __( "On sale from %s to %s", 'woo-sale-product-date-info' ), $example_start_date, $example_end_date ) ?>
				</span>
			</span>
		</p>
		<hr/>
		<p>
			<?php wp_nonce_field( $this->settings->{$this->settings_enum->nonce}, 'nonce' ) ?>
			<input type="button"
				   class="button button-primary"
				   id="motpr355_woo_sale_product_date_info_submit"
				   value="<?php esc_attr_e( 'Save configuration', 'woo-sale-product-date-info' ) ?>">
		</p>
	</form>

</div>
